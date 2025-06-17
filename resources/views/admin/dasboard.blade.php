@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('icon', 'fas fa-tachometer-alt')

@section('content')
    <!-- Statistik -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-info">
                <h3>Total Grup Arisan</h3>
                <p>{{ $stats['total_groups'] }}</p>
            </div>
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-info">
                <h3>Total Anggota</h3>
                <p>{{ $stats['total_members'] }}</p>
            </div>
            <div class="stat-icon">
                <i class="fas fa-user-friends"></i>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-info">
                <h3>Total Pembayaran</h3>
                <p>{{ $stats['total_payments'] }}</p>
            </div>
            <div class="stat-icon">
                <i class="fas fa-money-bill-wave"></i>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-info">
                <h3>Pendapatan</h3>
                <p>Rp {{ number_format($stats['total_income'], 0, ',', '.') }}</p>
            </div>
            <div class="stat-icon">
                <i class="fas fa-wallet"></i>
            </div>
        </div>
    </div>

    <!-- Dua kolom: Pembayaran Terbaru dan Pembayaran Tertunda -->
    <div class="row" style="display: flex; flex-wrap: wrap; gap: 1.5rem; margin-bottom: 2rem;">
        <!-- Pembayaran Terbaru -->
        <div class="card" style="flex: 1; min-width: 300px;">
            <div class="card-header" style="padding: 1rem 1.5rem; background: white; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 1.2rem; font-weight: 600; margin: 0;">Pembayaran Terbaru</h3>
                <a href="{{ route('admin.payments.index') }}" style="font-size: 0.9rem; color: var(--primary);">Lihat Semua</a>
            </div>
            <div class="card-body" style="padding: 1.5rem;">
                @if($stats['recent_payments']->count() > 0)
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        @foreach($stats['recent_payments'] as $payment)
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.8rem 0; border-bottom: 1px solid #f1f5f9;">
                                <div>
                                    <div style="font-weight: 500; margin-bottom: 0.2rem;">{{ $payment->user->name }}</div>
                                    <div style="font-size: 0.8rem; color: var(--gray);">
                                        {{ $payment->group->name }} · {{ $payment->period }}
                                    </div>
                                </div>
                                <div style="text-align: right;">
                                    <div style="font-weight: 600;">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
                                    <div>
                                        @switch($payment->payment_status)
                                            @case('paid')
                                                <span style="font-size: 0.8rem; background: rgba(16, 185, 129, 0.15); color: var(--success); padding: 0.2rem 0.5rem; border-radius: 20px;">Lunas</span>
                                                @break
                                            @case('pending')
                                                <span style="font-size: 0.8rem; background: rgba(245, 158, 11, 0.15); color: var(--warning); padding: 0.2rem 0.5rem; border-radius: 20px;">Menunggu</span>
                                                @break
                                            @case('failed')
                                                <span style="font-size: 0.8rem; background: rgba(239, 68, 68, 0.15); color: var(--error); padding: 0.2rem 0.5rem; border-radius: 20px;">Gagal</span>
                                                @break
                                            @default
                                                <span style="font-size: 0.8rem; background: #e2e8f0; color: var(--gray); padding: 0.2rem 0.5rem; border-radius: 20px;">{{ $payment->payment_status }}</span>
                                        @endswitch
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align: center; padding: 2rem; color: var(--gray);">
                        <i class="fas fa-money-bill-wave" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                        <p>Belum ada pembayaran</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Pembayaran Tertunda -->
        <div class="card" style="flex: 1; min-width: 300px;">
            <div class="card-header" style="padding: 1rem 1.5rem; background: white; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 1.2rem; font-weight: 600; margin: 0;">Pembayaran Tertunda</h3>
                <span style="font-size: 0.9rem; color: var(--primary);">{{ $stats['pending_payments'] }} Pembayaran</span>
            </div>
            <div class="card-body" style="padding: 1.5rem;">
                @if($stats['pending_payments'] > 0)
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        @foreach(Payment::where('payment_status', 'pending')->with('user', 'group')->latest()->take(5)->get() as $payment)
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.8rem 0; border-bottom: 1px solid #f1f5f9;">
                                <div>
                                    <div style="font-weight: 500; margin-bottom: 0.2rem;">{{ $payment->user->name }}</div>
                                    <div style="font-size: 0.8rem; color: var(--gray);">
                                        {{ $payment->group->name }} · {{ $payment->period }}
                                    </div>
                                </div>
                                <div style="text-align: right;">
                                    <div style="font-weight: 600;">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
                                    <a href="{{ route('admin.payments.verify', $payment) }}" style="font-size: 0.8rem; background: var(--success); color: white; padding: 0.2rem 0.8rem; border-radius: 20px; display: inline-block; margin-top: 0.5rem;">
                                        Verifikasi
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align: center; padding: 2rem; color: var(--gray);">
                        <i class="fas fa-check-circle" style="font-size: 3rem; color: var(--success); margin-bottom: 1rem;"></i>
                        <p>Tidak ada pembayaran tertunda</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Grafik Pembayaran (Placeholder) -->
    <div class="card" style="margin-bottom: 2rem;">
        <div class="card-header" style="padding: 1rem 1.5rem; background: white; border-bottom: 1px solid #e2e8f0;">
            <h3 style="font-size: 1.2rem; font-weight: 600; margin: 0;">Statistik Pembayaran Bulan Ini</h3>
        </div>
        <div class="card-body" style="padding: 1.5rem; min-height: 300px;">
            <div style="text-align: center; padding: 4rem 0; color: var(--gray);">
                <i class="fas fa-chart-bar" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                <p>Grafik statistik pembayaran akan ditampilkan di sini</p>
                <small>Integrasi dengan library chart seperti Chart.js atau ApexCharts</small>
            </div>
        </div>
    </div>
@endsection