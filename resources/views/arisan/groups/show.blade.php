@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">{{ $group->name }}</h3>
        </div>
        
        <div class="card-body">
            <!-- Informasi Grup -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Detail Grup</h5>
                    <ul class="list-group">
                        <li class="list-group-item">
                            <strong>Jumlah Iuran:</strong> Rp {{ number_format($group->amount, 0, ',', '.') }}
                        </li>
                        <li class="list-group-item">
                            <strong>Status:</strong> 
                            <span class="badge bg-{{ $group->isActive() ? 'success' : 'secondary' }}">
                                {{ $group->status }}
                            </span>
                        </li>
                        <li class="list-group-item">
                            <strong>Pemilik:</strong> {{ $group->owner->name }}
                        </li>
                    </ul>
                </div>
                
                <div class="col-md-6">
                    <h5>Status Pembayaran Anda</h5>
                    @php
                        $status = $group->userPaymentStatus(auth()->id());
                        $badgeColor = [
                            'paid' => 'success',
                            'pending' => 'warning',
                            'unpaid' => 'danger'
                        ][$status];
                    @endphp
                    
                    <div class="alert alert-{{ $badgeColor }}">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>
                                @if($status === 'paid')
                                    Anda telah membayar iuran arisan ini
                                @elseif($status === 'pending')
                                    Pembayaran Anda sedang diproses
                                @else
                                    Anda belum membayar iuran arisan ini
                                @endif
                            </span>
                            <span class="badge bg-{{ $badgeColor }}">
                                {{ $status === 'paid' ? 'Lunas' : ($status === 'pending' ? 'Pending' : 'Belum Bayar') }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Tombol Bayar -->
                    @if($group->isActive() && $group->hasMember(auth()->id()) && $status !== 'paid')
                        <form method="POST" action="{{ route('payment.create', $group) }}">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                @if($status === 'pending')
                                    Lanjutkan Pembayaran
                                @else
                                    Bayar Iuran Sekarang
                                @endif
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            
            <!-- Daftar Anggota -->
            <div class="mb-4">
                <h5>Daftar Anggota ({{ $group->members->count() }})</h5>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Status Pembayaran</th>
                                <th>Peran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($group->members as $member)
                                <tr>
                                    <td>
                                        {{ $member->name }}
                                        @if($group->isOwner($member->id))
                                            <span class="badge bg-primary ms-2">Pemilik</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $memberStatus = $group->userPaymentStatus($member->id);
                                            $memberBadge = [
                                                'paid' => 'success',
                                                'pending' => 'warning',
                                                'unpaid' => 'danger'
                                            ][$memberStatus];
                                        @endphp
                                        <span class="badge bg-{{ $memberBadge }}">
                                            {{ $memberStatus === 'paid' ? 'Lunas' : ($memberStatus === 'pending' ? 'Pending' : 'Belum Bayar') }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $member->role }} <!-- Asumsi ada kolom role di users -->
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Riwayat Pembayaran -->
            <div>
                <h5>Riwayat Pembayaran</h5>
                @if($group->payments->isEmpty())
                    <div class="alert alert-info">
                        Belum ada riwayat pembayaran
                    </div>
                @else
                    <div class="list-group">
                        @foreach($group->payments->sortByDesc('created_at') as $payment)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-1">{{ $payment->user->name }}</h6>
                                        <small class="text-muted">
                                            {{ $payment->created_at->format('d M Y H:i') }}
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-{{ $payment->isPaid() ? 'success' : 'warning' }}">
                                            {{ $payment->isPaid() ? 'Berhasil' : 'Pending' }}
                                        </span>
                                        <div class="fw-bold mt-1">
                                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Footer Card untuk Admin -->
        @if($group->isOwner(auth()->id()))
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.groups.edit', $group) }}" class="btn btn-outline-primary">
                        <i class="fas fa-edit me-1"></i> Edit Grup
                    </a>
                    
                    @if($group->isActive())
                        <form method="POST" action="{{ route('groups.complete', $group) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check-circle me-1"></i> Tandai Selesai
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection