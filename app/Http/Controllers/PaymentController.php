<?php

namespace App\Http\Controllers;

use App\Models\{Payment, ArisanGroup, User};
use Illuminate\Http\Request;
use Midtrans\{Snap, Config, Notification};
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel; // jika memakai Laravel‑Excel
use Barryvdh\DomPDF\Facade\Pdf;      // jika memakai barryvdh/laravel-dompdf

class PaymentController extends Controller
{
    public function __construct()
    {
        // Midtrans config
        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    /* -------------------------------------------------------------------------
     |  1. MIDTRANS SNAP PAYMENT (Member-side)
     * -------------------------------------------------------------------------*/
    public function pay(ArisanGroup $group)
    {
        $period = now()->format('Y-m');

        // Cegah double payment pada periode yang sama
        $exists = Payment::where([
            'user_id'          => auth()->id(),
            'arisan_group_id'  => $group->id,
            'period'           => $period,
            'payment_status'   => 'paid',
        ])->exists();

        if ($exists) {
            return back()->with('error', 'Anda sudah membayar untuk periode ini.');
        }

        $orderId = 'ARISAN-' . $group->id . '-' . auth()->id() . '-' . now()->timestamp;

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => (int) $group->amount,
            ],
            'customer_details' => [
                'first_name' => auth()->user()->name,
                'email'      => auth()->user()->email,
                'phone'      => auth()->user()->phone ?? '087721669168',
            ],
            'item_details'      => [[
                'id'       => 'arisan_' . $group->id,
                'price'    => (int) $group->amount,
                'quantity' => 1,
                'name'     => 'Arisan ' . $group->name . ' - ' . $period,
            ]],
            'callbacks' => [
                'finish'   => route('payment.finish'),
                'unfinish' => route('payment.unfinish'),
                'error'    => route('payment.error'),
            ],
            'enabled_payments' => [
                'credit_card', 'gopay', 'bank_transfer', 'echannel',
                'bca_va', 'bni_va', 'bri_va', 'other_va',
            ],
        ];

        try {
            Log::info('Midtrans Payment Request', [
                'order_id' => $orderId,
                'amount'   => $group->amount,
                'user_id'  => auth()->id(),
            ]);

            $snapToken = Snap::getSnapToken($params);

            // Buat / update record pembayaran
            Payment::updateOrCreate([
                'user_id'         => auth()->id(),
                'arisan_group_id' => $group->id,
                'period'          => $period,
            ], [
                'amount'           => $group->amount,
                'payment_status'   => 'pending',
                'midtrans_order_id'=> $orderId,
            ]);

            return view('pay', compact('snapToken', 'group'));
        } catch (\Exception $e) {
            Log::error('Midtrans Error', [
                'error'    => $e->getMessage(),
                'order_id' => $orderId,
            ]);
            return back()->with('error', 'Gagal membuat pembayaran: ' . $e->getMessage());
        }
    }

    /* -------------------------------------------------------------------------
     |  2. ADMIN – CRUD & LISTING
     * -------------------------------------------------------------------------*/
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $payments = Payment::with(['group', 'user'])
            ->when($search, function ($q) use ($search) {
                $q->where('midtrans_order_id', 'like', "%$search%")
                  ->orWhereHas('user',   fn ($u) => $u->where('name',  'like', "%$search%"))
                  ->orWhereHas('group',  fn ($g) => $g->where('name', 'like', "%$search%"));
            })
            ->when($status, fn ($q) => $q->where('payment_status', $status))
            ->latest()
            ->paginate(10)
            ->appends($request->query());

        $groups = ArisanGroup::pluck('name', 'id');

        return view('admin.payments.index', compact('payments', 'groups'));
    }

    /**
     * Show manual payment creation form (admin)
     */
    public function create()
    {
        $groups = ArisanGroup::all();
        $users  = User::select('id', 'name', 'email')->get();
        return view('admin.payments.create', compact('groups', 'users'));
    }

    /**
     * Store manual payment (admin)
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'group_id'       => 'required|exists:arisan_groups,id',
            'user_id'        => 'required|exists:users,id',
            'period'         => 'required|date_format:Y-m',
            'amount'         => 'required|numeric|min:1000',
            'payment_status' => 'required|in:pending,paid,failed,challenge',
        ]);

        Payment::create([
            'arisan_group_id' => $data['group_id'],
            'user_id'         => $data['user_id'],
            'period'          => $data['period'],
            'amount'          => $data['amount'],
            'payment_status'  => $data['payment_status'],
            'midtrans_order_id' => null,
        ]);

        return redirect()->route('admin.payments.index')->with('success', 'Pembayaran manual berhasil ditambahkan.');
    }

    public function show(Payment $payment)
    {
        return view('admin.payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        return view('admin.payments.edit', compact('payment'));
    }

    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'amount'         => 'required|numeric|min:1000',
            'payment_status' => 'required|in:pending,paid,failed,challenge,refunded',
            'period'         => 'required|date_format:Y-m',
        ]);

        $payment->update($validated);

        return redirect()->route('admin.payments.index')->with('success', 'Pembayaran berhasil diperbarui!');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('admin.payments.index')->with('success', 'Pembayaran berhasil dihapus!');
    }

    /* -------------------------------------------------------------------------
     |  3. ADMIN – ACTIONS
     * -------------------------------------------------------------------------*/
    public function verify(Payment $payment)
    {
        $payment->update([
            'payment_status'     => 'paid',
            'transaction_status' => 'settlement',
            'verified_at'        => now(),
        ]);

        return redirect()->route('admin.payments.index')->with('success', 'Pembayaran berhasil diverifikasi!');
    }

    /**
     * Polling endpoint for JS auto‑refresh pending payments
     */
    public function checkPendingStatus()
    {
        $updated = 0;

        $pendingPayments = Payment::where('payment_status', 'pending')->get();

        foreach ($pendingPayments as $payment) {
            try {
                $status = Snap::status($payment->midtrans_order_id);

                if ($status && $status->transaction_status !== 'pending') {
                    $payment->payment_status   = match ($status->transaction_status) {
                        'settlement', 'capture' => 'paid',
                        'deny', 'cancel', 'expire' => 'failed',
                        default => 'pending',
                    };
                    $payment->transaction_status = $status->transaction_status;
                    $payment->save();
                    $updated++;
                }
            } catch (\Exception $e) {
                Log::error('Midtrans status error', ['order_id' => $payment->midtrans_order_id, 'msg' => $e->getMessage()]);
            }
        }

        return response()->json(['updated' => $updated]);
    }

    /* -------------------------------------------------------------------------
     |  4. EXPORT (PDF / EXCEL)
     * -------------------------------------------------------------------------*/
    public function export(Request $request, string $format)
    {
        $payments = Payment::with(['group', 'user'])
            ->latest()
            ->get();

        return match ($format) {
            'excel' => Excel::download(new \App\Exports\PaymentsExport($payments), 'payments.xlsx'),
            'pdf'   => Pdf::loadView('admin.payments.export-pdf', compact('payments'))->download('payments.pdf'),
            default => abort(404),
        };
    }

    /* -------------------------------------------------------------------------
     |  5. MIDTRANS CALLBACKS
     * -------------------------------------------------------------------------*/
    public function callback(Request $request)
    {
        try {
            Log::info('Midtrans Notification', $request->all());

            $notif = new Notification();

            $orderId      = $notif->order_id;
            $statusCode   = $notif->status_code;
            $grossAmount  = $notif->gross_amount;
            $signatureKey = $notif->signature_key;
            $hash         = hash('sha512', $orderId . $statusCode . $grossAmount . config('midtrans.server_key'));

            if ($hash !== $signatureKey) {
                Log::error('Invalid signature', ['order_id' => $orderId]);
                return response()->json(['status' => 'invalid signature'], 400);
            }

            $payment = Payment::where('midtrans_order_id', $orderId)->first();

            if (!$payment) {
                Log::error('Payment not found', ['order_id' => $orderId]);
                return response()->json(['status' => 'payment not found'], 404);
            }

            $transactionStatus = $notif->transaction_status;
            $fraudStatus       = $notif->fraud_status ?? null;

            $status = match ($transactionStatus) {
                'capture'    => $fraudStatus === 'challenge' ? 'challenge' : 'paid',
                'settlement' => 'paid',
                'pending'    => 'pending',
                'deny', 'cancel', 'expire' => 'failed',
                'refund'     => 'refunded',
                default      => 'pending',
            };

            $payment->update([
                'payment_status'     => $status,
                'transaction_status' => $transactionStatus,
                'fraud_status'       => $fraudStatus,
            ]);

            Log::info('Payment Updated', ['order_id' => $orderId, 'status' => $status]);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Callback Error', ['error' => $e->getMessage()]);
            return response()->json(['status' => 'error'], 500);
        }
    }

    /* finish, unfinish, error tetap seperti sebelumnya (dipangkas untuk singkat) */

    public function finish(Request $request)
    {
        $payment = Payment::where('midtrans_order_id', $request->order_id)->first();

        return redirect()->route('dashboard')->with(
            $payment && $payment->payment_status === 'paid' ? 'success' : 'info',
            $payment ? 'Pembayaran berhasil!' : 'Menunggu konfirmasi pembayaran'
        );
    }

    public function unfinish() { return redirect()->route('dashboard')->with('warning', 'Pembayaran belum selesai'); }
    public function error()    { return redirect()->route('dashboard')->with('error', 'Terjadi kesalahan pembayaran'); }

    /* -------------------------------------------------------------------------
     |  6. MEMBER – LIST
     * -------------------------------------------------------------------------*/
    public function memberPayments()
    {
        $payments = Payment::with('group')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('member.payments.index', compact('payments'));
    }
}
