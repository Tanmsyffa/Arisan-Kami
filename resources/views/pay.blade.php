@extends('layouts.app')

@section('content')
<h2 class="text-xl font-bold mb-4">Pembayaran QRIS Sandbox</h2>

<button id="pay-button" class="bg-indigo-600 text-white px-4 py-2 rounded">Bayar Sekarang</button>

<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
    snap.pay('{{ $snapToken }}', {
    onSuccess: function(result){
        alert("✅ Pembayaran berhasil (simulasi)");
        window.location.href = "/dashboard";
    },
    onPending: function(result){
        alert("🕒 Pembayaran tertunda");
    },
    onError: function(result){
        alert("❌ Pembayaran gagal");
    },
    onClose: function() {
        alert("❌ Transaksi dibatalkan.");
    }
    });

</script>
@endsection
