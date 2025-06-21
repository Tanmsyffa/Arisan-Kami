@extends('layouts.app')

@section('title', 'Pembayaran Saya')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Daftar Pembayaran</h2>

    @if($payments->isEmpty())
        <div class="alert alert-info">Belum ada data pembayaran.</div>
    @else
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Arisan</th>
                    <th>Periode</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Dibuat</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $payment)
                <tr>
                    <td>{{ $payment->group->name ?? '-' }}</td>
                    <td>{{ $payment->period }}</td>
                    <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge bg-{{ 
                            $payment->payment_status == 'paid' ? 'success' :
                            ($payment->payment_status == 'pending' ? 'warning' : 'danger')
                        }}">
                            {{ ucfirst($payment->payment_status) }}
                        </span>
                    </td>
                    <td>{{ $payment->created_at->format('d M Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
