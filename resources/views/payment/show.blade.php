@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card">
        <div class="card-header">
            <h3>Pembayaran Iuran Arisan</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>Detail Pembayaran</h5>
                    <ul class="list-group">
                        <li class="list-group-item">
                            <strong>Grup Arisan:</strong> {{ $payment->arisanGroup->name }}
                        </li>
                        <li class="list-group-item">
                            <strong>Jumlah:</strong> Rp {{ number_format($payment->amount, 0, ',', '.') }}
                        </li>
                        <li class="list-group-item">
                            <strong>Status:</strong>
                            <span class="badge bg-{{ $payment->status == 'pending' ? 'warning' : ($payment->status == 'success' ? 'success' : 'danger') }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h5>Metode Pembayaran</h5>
                    <div id="snap-container" class="mt-4"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" 
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const snapToken = "{{ $snapToken }}";
        
        snap.pay(snapToken, {
            onSuccess: function(result) {
                window.location.href = "{{ route('payment.finish') }}?order_id=" + result.order_id;
            },
            onPending: function(result) {
                window.location.href = "{{ route('payment.finish') }}?order_id=" + result.order_id;
            },
            onError: function(result) {
                window.location.href = "{{ route('payment.finish') }}?order_id=" + result.order_id;
            }
        });
    });
</script>
@endsection