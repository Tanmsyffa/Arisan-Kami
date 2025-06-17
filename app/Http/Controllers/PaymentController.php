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
        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function pay(ArisanGroup $group)
    {
        $period = now()->format('Y-m');

        // Cek apakah sudah bayar untuk periode ini
        $exists = Payment::where([
            'user_id' => auth()->id(),
            'arisan_group_id' => $group->id,
            'period' => $period,
            'status' => 'paid'
        ])->exists();

        if ($exists) {
            return back()->with('error', 'Anda sudah membayar untuk periode ini.');
        }

        // Generate unique order ID dengan format yang lebih baik
        $orderId = 'ARISAN-' . $group->id . '-' . auth()->id() . '-' . now()->timestamp;

        // Parameter untuk Midtrans dengan lebih lengkap
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $group->amount, // Pastikan integer
            ],
            'customer_details' => [
                'first_name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'phone' => auth()->user()->phone ?? '087721669168', // Fallback jika phone kosong
            ],
            'item_details' => [
                [
                    'id' => 'arisan_' . $group->id,
                    'price' => (int) $group->amount,
                    'quantity' => 1,
                    'name' => 'Arisan ' . $group->name . ' - ' . $period,
                ]
            ],
            // Tambahkan callback URLs
            'callbacks' => [
                'finish' => route('payment.finish'),
                'unfinish' => route('payment.unfinish'),
                'error' => route('payment.error'),
            ],
            // Gunakan semua payment methods untuk testing
            'enabled_payments' => [
                'credit_card', 'gopay', 'bank_transfer', 'echannel', 
                'bca_va', 'bni_va', 'bri_va', 'other_va'
            ],
        ];

        try {
            // Log request untuk debugging
            Log::info('Midtrans Payment Request', [
                'order_id' => $orderId,
                'amount' => $group->amount,
                'user_id' => auth()->id()
            ]);

            $snapToken = Snap::getSnapToken($params);

            // Simpan payment pending
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

            Log::info('Snap Token Generated', ['token' => $snapToken]);

            return view('pay', compact('snapToken', 'group'));

        } catch (\Exception $e) {
            Log::error('Midtrans Error', [
                'error' => $e->getMessage(),
                'order_id' => $orderId
            ]);
            
            return back()->with('error', 'Gagal membuat pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan semua pembayaran untuk admin
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        
        $payments = Payment::with(['group', 'user'])
            ->when($search, function ($query) use ($search) {
                $query->where('midtrans_order_id', 'like', "%$search%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    })
                    ->orWhereHas('group', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    });
            })
            ->when($status, function ($query) use ($status) {
                $query->where('payment_status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Menampilkan detail pembayaran
     */
    public function show(Payment $payment)
    {
        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Form edit pembayaran
     */
    public function edit(Payment $payment)
    {
        return view('admin.payments.edit', compact('payment'));
    }

    /**
     * Update pembayaran
     */
    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1000',
            'payment_status' => 'required|in:pending,paid,failed,challenge,refunded',
            'period' => 'required|date_format:Y-m',
        ]);

        $payment->update($validated);

        return redirect()->route('admin.payments.index')
            ->with('success', 'Pembayaran berhasil diperbarui!');
    }

    /**
     * Hapus pembayaran
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('admin.payments.index')
            ->with('success', 'Pembayaran berhasil dihapus!');
    }

    /**
     * Verifikasi manual pembayaran oleh admin
     */
    public function verify(Payment $payment)
    {
        $payment->update([
            'payment_status' => 'paid',
            'transaction_status' => 'settlement',
            'verified_at' => now()
        ]);

        return redirect()->route('admin.payments.index')
            ->with('success', 'Pembayaran berhasil diverifikasi secara manual!');
    }

    public function callback(Request $request)
    {
        try {
            // Log incoming notification
            Log::info('Midtrans Notification', $request->all());

            // Ambil notifikasi dari Midtrans
            $notif = new Notification();

            // Verify signature
            $serverKey = config('midtrans.server_key');
            $orderId = $notif->order_id;
            $statusCode = $notif->status_code;
            $grossAmount = $notif->gross_amount;
            $signatureKey = $notif->signature_key;

            $hash = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

            if ($hash !== $signatureKey) {
                Log::error('Invalid signature', [
                    'order_id' => $orderId,
                    'expected' => $hash,
                    'received' => $signatureKey
                ]);
                return response()->json(['status' => 'invalid signature'], 400);
            }

            // Ambil payment berdasarkan order_id
            $transactionStatus = $notif->transaction_status;
            $fraudStatus = $notif->fraud_status ?? null;

            $payment = Payment::where('midtrans_order_id', $orderId)->first();

            if (!$payment) {
                Log::error('Payment not found', ['order_id' => $orderId]);
                return response()->json(['status' => 'payment not found'], 404);
            }

            // Update status berdasarkan notifikasi Midtrans
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

            Log::info('Payment Updated', [
                'order_id' => $orderId,
                'status' => $status,
                'transaction_status' => $transactionStatus
            ]);

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Callback Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['status' => 'error'], 500);
        }
    }

    // Tambahkan method untuk handle finish/unfinish/error
    public function finish(Request $request)
    {
        $orderId = $request->order_id;
        $payment = Payment::where('midtrans_order_id', $orderId)->first();
        
        if (!$payment) {
            return redirect()->route('dashboard')->with('error', 'Pembayaran tidak ditemukan');
        }

        if ($payment->status === 'paid') {
            return redirect()->route('dashboard')->with('success', 'Pembayaran berhasil!');
        }

        return redirect()->route('dashboard')->with('info', 'Menunggu konfirmasi pembayaran');
    }

    public function unfinish(Request $request)
    {
        return redirect()->route('dashboard')->with('warning', 'Pembayaran belum selesai');
    }

    public function error(Request $request)
    {
        return redirect()->route('dashboard')->with('error', 'Terjadi kesalahan dalam pembayaran');
    }
}