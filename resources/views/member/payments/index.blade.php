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
                    <td>{{ $payment->arisanGroup->name ?? '-' }}</td>
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
                    <td>
                        <span class="badge bg-{{ 
                            $payment->payment_status == 'paid' ? 'success' :
                            ($payment->payment_status == 'pending' ? 'warning' : 'danger')
                        }}">
                            {{ ucfirst($payment->payment_status) }}
                        </span>

                        @if($payment->payment_status == 'pending')
                            <form method="POST" action="{{ route('member.groups.join', ['group' => $payment->arisanGroup->id]) }}" class="d-inline">
                                @csrf
                                <a href="{{ route('member.payments.pay', $payment->arisanGroup->id) }}" class="btn btn-sm btn-primary mt-1">
                                    Bayar Sekarang
                                </a>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
<script type="text/javascript"
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}">
</script>
@endsection
