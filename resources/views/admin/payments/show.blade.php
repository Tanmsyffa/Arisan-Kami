@extends('layouts.app')

@section('title', 'Detail Pembayaran')
@section('icon', 'fas fa-info-circle')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <dl class="row">
                        <dt class="col-sm-4">ID Transaksi</dt>
                        <dd class="col-sm-8">{{ $payment->midtrans_order_id }}</dd>

                        <dt class="col-sm-4">Grup Arisan</dt>
                        <dd class="col-sm-8">{{ $payment->group->name }}</dd>

                        <dt class="col-sm-4">Peserta</dt>
                        <dd class="col-sm-8">{{ $payment->user->name }}</dd>

                        <dt class="col-sm-4">Periode</dt>
                        <dd class="col-sm-8">{{ $payment->period }}</dd>
                    </dl>
                </div>
                <div class="col-md-6">
                    <dl class="row">
                        <dt class="col-sm-4">Jumlah</dt>
                        <dd class="col-sm-8">Rp {{ number_format($payment->amount, 0, ',', '.') }}</dd>

                        <dt class="col-sm-4">Status</dt>
                        <dd class="col-sm-8">
                            @switch($payment->payment_status)
                                @case('paid')<span class="badge bg-success">Lunas</span>@break
                                @case('pending')<span class="badge bg-warning">Menunggu</span>@break
                                @case('failed')<span class="badge bg-danger">Gagal</span>@break
                                @case('challenge')<span class="badge bg-info">Challenge</span>@break
                                @default<span class="badge bg-secondary">{{ $payment->payment_status }}</span>
                            @endswitch
                        </dd>

                        <dt class="col-sm-4">Status Transaksi</dt>
                        <dd class="col-sm-8">{{ $payment->transaction_status ?? '-' }}</dd>

                        <dt class="col-sm-4">Status Fraud</dt>
                        <dd class="col-sm-8">{{ $payment->fraud_status ?? '-' }}</dd>
                    </dl>
                </div>
            </div>

            <div class="mt-4">
                <h5>Riwayat Status</h5>
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Status</th>
                            <th>Status Transaksi</th>
                            <th>Status Fraud</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $payment->created_at->format('d M Y H:i') }}</td>
                            <td>Created</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>{{ $payment->updated_at->format('d M Y H:i') }}</td>
                            <td>{{ $payment->payment_status }}</td>
                            <td>{{ $payment->transaction_status ?? '-' }}</td>
                            <td>{{ $payment->fraud_status ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
@endsection