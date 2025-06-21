<?php

namespace App\Http\Controllers;

use App\Models\{Payment, ArisanGroup};
use Illuminate\Http\Request;
use Midtrans\{Snap, Config, Notification};
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function pay(ArisanGroup $group)
    {
        $period = now()->format('Y-m');

        $exists = Payment::where([
            'user_id' => auth()->id(),
            'arisan_group_id' => $group->id,
            'period' => $period,
            'status' => 'paid'
        ])->exists();

        if ($exists) {
            return back()->with('error', 'Anda sudah membayar untuk periode ini.');
        }

        $orderId = 'ARISAN-' . $group->id . '-' . auth()->id() . '-' . now()->timestamp;

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $group->amount,
            ],
            'customer_details' => [
                'first_name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'phone' => auth()->user()->phone ?? '087721669168',
            ],
            'item_details' => [
                [
                    'id' => 'arisan_' . $group->id,
                    'price' => (int) $group->amount,
                    'quantity' => 1,
                    'name' => 'Arisan ' . $group->name . ' - ' . $period,
                ]
            ],
            'callbacks' => [
                'finish' => route('payment.finish'),
                'unfinish' => route('payment.unfinish'),
                'error' => route('payment.error'),
            ],
            'enabled_payments' => [
                'credit_card', 'gopay', 'bank_transfer', 'echannel',
                'bca_va', 'bni_va', 'bri_va', 'other_va'
            ],
        ];

        try {
            Log::info('Midtrans Payment Request', [
                'order_id' => $orderId,
                'amount' => $group->amount,
                'user_id' => auth()->id()
            ]);

            $snapToken = Snap::getSnapToken($params);

            Payment::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'arisan_group_id' => $group->id,
                    'period' => $period,
                ],
                [
                    'amount' => $group->amount,
                    'payment_status' => 'pending',
                    'midtrans_order_id' => $orderId,
                ]
            );

            return view('pay', compact('snapToken', 'group'));

        } catch (\Exception $e) {
            Log::error('Midtrans Error', [
                'error' => $e->getMessage(),
                'order_id' => $orderId
            ]);
            return back()->with('error', 'Gagal membuat pembayaran: ' . $e->getMessage());
        }
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $payments = Payment::with(['group', 'user'])
            ->when($search, function ($query) use ($search) {
                $query->where('midtrans_order_id', 'like', "%$search%")
                    ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%$search%"))
                    ->orWhereHas('group', fn($q) => $q->where('name', 'like', "%$search%"));
            })
            ->when($status, fn($query) => $query->where('payment_status', $status))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.payments.index', compact('payments'));
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
            'amount' => 'required|numeric|min:1000',
            'payment_status' => 'required|in:pending,paid,failed,challenge,refunded',
            'period' => 'required|date_format:Y-m',
        ]);

        $payment->update($validated);

        return redirect()->route('admin.payments.index')->with('success', 'Pembayaran berhasil diperbarui!');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('admin.payments.index')->with('success', 'Pembayaran berhasil dihapus!');
    }

    public function verify(Payment $payment)
    {
        $payment->update([
            'payment_status' => 'paid',
            'transaction_status' => 'settlement',
            'verified_at' => now()
        ]);

        return redirect()->route('admin.payments.index')->with('success', 'Pembayaran berhasil diverifikasi secara manual!');
    }

    public function callback(Request $request)
    {
        try {
            Log::info('Midtrans Notification', $request->all());

            $notif = new Notification();

            $serverKey = config('midtrans.server_key');
            $orderId = $notif->order_id;
            $statusCode = $notif->status_code;
            $grossAmount = $notif->gross_amount;
            $signatureKey = $notif->signature_key;

            $hash = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

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
            $fraudStatus = $notif->fraud_status ?? null;

            $status = match ($transactionStatus) {
                'capture' => $fraudStatus == 'challenge' ? 'challenge' : 'paid',
                'settlement' => 'paid',
                'pending' => 'pending',
                'deny', 'cancel', 'expire' => 'failed',
                'refund' => 'refunded',
                default => 'pending'
            };

            $payment->update([
                'payment_status' => $status,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
            ]);

            Log::info('Payment Updated', ['order_id' => $orderId, 'status' => $status]);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Callback Error', ['error' => $e->getMessage()]);
            return response()->json(['status' => 'error'], 500);
        }
    }

    public function finish(Request $request)
    {
        $payment = Payment::where('midtrans_order_id', $request->order_id)->first();

        return redirect()->route('dashboard')->with(
            $payment && $payment->status === 'paid' ? 'success' : 'info',
            $payment ? 'Pembayaran berhasil!' : 'Menunggu konfirmasi pembayaran'
        );
    }

    public function unfinish()
    {
        return redirect()->route('dashboard')->with('warning', 'Pembayaran belum selesai');
    }

    public function error()
    {
        return redirect()->route('dashboard')->with('error', 'Terjadi kesalahan dalam pembayaran');
    }

    /**
     * ✅ Tambahan untuk member melihat daftar pembayaran
     */
    public function memberPayments()
    {
        $user = auth()->user();

        $payments = Payment::with('group')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('member.payments.index', compact('payments'));
    }
}
