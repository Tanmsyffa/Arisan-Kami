@extends('layouts.app')

@section('title', 'Manajemen Pembayaran')
@section('icon', 'fas fa-money-bill-wave')

@section('actions')
    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
        <i class="fas fa-filter"></i> Filter
    </a>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Grup Arisan</th>
                            <th>Peserta</th>
                            <th>Periode</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                        <tr>
                            <td>{{ $payment->midtrans_order_id }}</td>
                            <td>{{ $payment->group->name }}</td>
                            <td>{{ $payment->user->name }}</td>
                            <td>{{ $payment->period }}</td>
                            <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td>
                                @switch($payment->payment_status)
                                    @case('paid')
                                        <span class="badge bg-success">Lunas</span>
                                        @break
                                    @case('pending')
                                        <span class="badge bg-warning">Menunggu</span>
                                        @break
                                    @case('failed')
                                        <span class="badge bg-danger">Gagal</span>
                                        @break
                                    @case('challenge')
                                        <span class="badge bg-info">Challenge</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ $payment->payment_status }}</span>
                                @endswitch
                            </td>
                            <td>{{ $payment->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.payments.show', $payment) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.payments.edit', $payment) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.payments.destroy', $payment) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus pembayaran ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @if($payment->payment_status === 'pending')
                                <form action="{{ route('admin.payments.verify', $payment) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Verifikasi pembayaran ini?')">
                                        <i class="fas fa-check"></i> Verifikasi
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data pembayaran.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $payments->links() }}
        </div>
    </div>

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.payments.index') }}" method="GET">
                    <div class="modal-header">
                        <h5 class="modal-title">Filter Pembayaran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Status Pembayaran</label>
                            <select class="form-select" name="status">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                                <option value="challenge" {{ request('status') === 'challenge' ? 'selected' : '' }}>Challenge</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Pencarian</label>
                            <input type="text" class="form-control" name="search" placeholder="ID, Nama, Grup..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection