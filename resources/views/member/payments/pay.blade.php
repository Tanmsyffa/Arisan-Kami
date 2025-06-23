@extends('layouts.app')

@section('title', 'Pembayaran Arisan')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Pembayaran Arisan: <strong>{{ $group->name }}</strong></h2>

    <div class="card">
        <div class="card-body">
            <p>Silakan selesaikan pembayaran sebesar:</p>
            <h3 class="text-success">Rp {{ number_format($group->amount, 0, ',', '.') }}</h3>
            <p>Periode: <strong>{{ now()->format('F Y') }}</strong></p>

            <button id="pay-button" class="btn btn-primary mt-3">
                <i class="fas fa-money-bill-wave"></i> Bayar Sekarang
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
document.getElementById('pay-button').addEventListener('click', function () {
    snap.pay('{{ $snapToken }}', {
        onSuccess: function(result) {
            window.location.href = "{{ route('dashboard') }}";
        },
        onPending: function(result) {
            alert('Pembayaran sedang diproses. Mohon tunggu konfirmasi.');
        },
        onError: function(result) {
            alert('Terjadi kesalahan saat memproses pembayaran.');
        },
        onClose: function() {
            console.log('Popup pembayaran ditutup');
        }
    });
});
</script>
@endpush
