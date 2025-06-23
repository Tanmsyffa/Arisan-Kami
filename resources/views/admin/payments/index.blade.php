@extends('layouts.app')

@section('title', 'Manajemen Pembayaran')
@section('icon')
    <i class="fas fa-money-bill-wave"></i>
@endsection
@section('actions')
    <div class="btn-group">
        <a href="{{ route('admin.payments.create') }}" class="btn btn-success">
            <i class="fas fa-users"></i> Tambah Iuran
        </a>
        <div class="btn-group ms-2">
            <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-file-export"></i> Export
            </button>
            <ul class="dropdown-menu">
                <li>
                    <a class="dropdown-item" href="{{ route('admin.payments.export', 'pdf') . '?' . http_build_query(request()->query()) }}" target="_blank">
                        <i class="fas fa-file-pdf"></i> PDF
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('admin.payments.export', 'excel') . '?' . http_build_query(request()->query()) }}">
                        <i class="fas fa-file-excel"></i> Excel
                    </a>
                </li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Statistics Cards --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Total Pembayaran</h5>
                            <h3 class="mb-0">{{ $stats['total_payments'] ?? 0 }}</h3>
                        </div>
                        <i class="fas fa-money-bill-wave fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Lunas</h5>
                            <h3 class="mb-0">{{ $stats['paid_payments'] ?? 0 }}</h3>
                        </div>
                        <i class="fas fa-check-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Pending</h5>
                            <h3 class="mb-0">{{ $stats['pending_payments'] ?? 0 }}</h3>
                        </div>
                        <i class="fas fa-hourglass-half fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Total Pendapatan</h5>
                            <h3 class="mb-0">Rp {{ number_format($stats['total_income'] ?? 0, 0, ',', '.') }}</h3>
                        </div>
                        <i class="fas fa-wallet fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.payments.partials.table', ['payments' => $payments])
    @include('admin.payments.partials.filters', ['groups' => $groups ?? []])
@endsection

@push('scripts')
<script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
function openMidtransPayment(snapToken) {
    snap.pay(snapToken, {
        onSuccess: function(result) {
            window.location.reload();
        },
        onPending: function(result) {
            alert('Pembayaran dalam proses, silakan tunggu konfirmasi.');
        },
        onError: function(result) {
            alert('Terjadi kesalahan dalam pembayaran.');
        },
        onClose: function() {
            console.log('Payment popup closed');
        }
    });
}

$(document).ready(function() {
    @if($payments->where('payment_status', 'pending')->count() > 0)
        setInterval(function() {
            checkPendingPayments();
        }, 30000);
    @endif
});

function checkPendingPayments() {
    $.get('{{ route("admin.payments.check-status") }}', function(data) {
        if (data.updated > 0) {
            window.location.reload();
        }
    });
}
</script>
@endpush
