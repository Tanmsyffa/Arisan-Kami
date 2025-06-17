@extends('layouts.app')

@section('title', 'Edit Pembayaran')
@section('icon', 'fas fa-edit')

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.payments.update', $payment) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">ID Transaksi</label>
                            <input type="text" class="form-control" value="{{ $payment->midtrans_order_id }}" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Grup Arisan</label>
                            <input type="text" class="form-control" value="{{ $payment->group->name }}" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Peserta</label>
                            <input type="text" class="form-control" value="{{ $payment->user->name }}" readonly>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Jumlah (Rp)</label>
                            <input type="number" class="form-control" name="amount" value="{{ $payment->amount }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Status Pembayaran</label>
                            <select class="form-select" name="payment_status" required>
                                <option value="pending" {{ $payment->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ $payment->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="failed" {{ $payment->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                                <option value="challenge" {{ $payment->payment_status === 'challenge' ? 'selected' : '' }}>Challenge</option>
                                <option value="refunded" {{ $payment->payment_status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Periode (YYYY-MM)</label>
                            <input type="text" class="form-control" name="period" value="{{ $payment->period }}" required>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection