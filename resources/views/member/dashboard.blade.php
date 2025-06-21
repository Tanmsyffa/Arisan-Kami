<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Member</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .dashboard-card {
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            transition: transform 0.3s, box-shadow 0.3s;
            border: none;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        .card-header {
            border-top-left-radius: 12px !important;
            border-top-right-radius: 12px !important;
            font-weight: 600;
        }
        .activity-item {
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }
        .activity-item:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">Arisan Kami</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('member.dashboard') }}">
                            <i class="fas fa-home me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('member.groups.index') }}">
                            <i class="fas fa-users me-1"></i> Arisan Saya
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('member.payments.index') }}">
                            <i class="fas fa-money-bill-wave me-1"></i> Pembayaran
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <div class="bg-light rounded-circle me-2" style="width: 30px; height: 30px;"></div>
                            <span>{{ auth()->user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-user me-2"></i> Profil
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.change-password') }}">
                                    <i class="fas fa-key me-2"></i> Ganti Password
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('member.payments.history') }}">
                                    <i class="fas fa-clock me-2"></i> Riwayat Pembayaran
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="container py-4">
        <div class="mb-4">
            <h2 class="mb-0">Dashboard Member</h2>
            <p class="text-muted">Selamat datang kembali, {{ auth()->user()->name }}</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-8">
                <div class="card dashboard-card">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">Ringkasan Akun</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <div class="card border-primary">
                                    <div class="card-body text-center">
                                        <h2 class="text-primary mb-0">{{ $activeGroups }}</h2>
                                        <p class="text-muted mb-0">Arisan Aktif</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3 mb-md-0">
                                <div class="card border-success">
                                    <div class="card-body text-center">
                                        <h2 class="text-success mb-0">{{ $completedPayments }}</h2>
                                        <p class="text-muted mb-0">Pembayaran Selesai</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-warning">
                                    <div class="card-body text-center">
                                        <h2 class="text-warning mb-0">{{ $pendingPayments }}</h2>
                                        <p class="text-muted mb-0">Pembayaran Pending</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <h5>Arisan Terbaru</h5>
                            <div class="list-group">
                                <a href="#" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Arisan Keluarga Besar</h6>
                                        <small>3 hari lalu</small>
                                    </div>
                                    <p class="mb-1">Jumlah peserta: 12 orang</p>
                                    <small>Pemenang berikutnya: 2 hari lagi</small>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Arisan Rekan Kerja</h6>
                                        <small>1 minggu lalu</small>
                                    </div>
                                    <p class="mb-1">Jumlah peserta: 8 orang</p>
                                    <small>Pemenang berikutnya: 5 hari lagi</small>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card dashboard-card">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">Aktivitas Terbaru</h5>
                    </div>
                    <div class="card-body">
                        <p>Test</p>
                    </div>
                </div>
                
                <div class="card dashboard-card mt-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">Pembayaran Tertunda</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle me-3 fa-2x"></i>
                            <div>
                                Anda memiliki {{ $pendingPayments }} pembayaran yang belum diselesaikan
                            </div>
                        </div>
                        <a href="{{ route('member.payments.index') }}" class="btn btn-primary w-100">
                            <i class="fas fa-money-bill-wave me-2"></i> Bayar Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3 mb-md-0">
                    <h5>Arisan Kami</h5>
                    <p class="text-muted">Platform arisan digital untuk memudahkan pengelolaan arisan Anda.</p>
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                    <h5>Kontak</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-envelope me-2"></i> arisankami@gmail.com</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <p class="text-center mb-0">&copy; {{ date('Y') }} Arisan Kami.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
