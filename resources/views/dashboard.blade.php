<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Arisan Kami</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #7e22ce;
            --primary-light: #a855f7;
            --primary-dark: #6b21a8;
            --secondary: #f0abfc;
            --accent: #e879f9;
            --light: #f5f3ff;
            --dark: #1e1b4b;
            --gray: #64748b;
            --success: #10b981;
            --error: #ef4444;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #fdf4ff 0%, #f0f9ff 100%);
            color: var(--dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            line-height: 1.6;
        }
        
        /* Navigation */
        nav {
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 4px 20px rgba(126, 34, 206, 0.1);
            padding: 1.2rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(10px);
        }
        
        .logo {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .logo:hover {
            text-decoration: none;
        }
        
        .logo::after {
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }
        
        .nav-links a {
            text-decoration: none;
            color: var(--dark);
            font-weight: 500;
            position: relative;
            padding: 0.5rem 0;
            transition: all 0.3s ease;
        }
        
        .nav-links a:hover {
            color: var(--primary);
        }
        
        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 3px;
            background: var(--primary);
            border-radius: 10px;
            transition: width 0.3s ease;
        }
        
        .nav-links a:hover::after {
            width: 100%;
        }
        
        .nav-links button {
            background: transparent;
            border: none;
            color: var(--error);
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
        }
        
        .nav-links button:hover {
            color: #dc2626;
        }
        
        /* Main Content */
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
            width: 100%;
        }
        
        .dashboard-header {
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .dashboard-title {
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--primary-dark);
            margin-bottom: 1rem;
            position: relative;
            display: inline-block;
        }
        
        .dashboard-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--accent);
            border-radius: 2px;
        }
        
        .dashboard-subtitle {
            font-size: 1.1rem;
            color: var(--gray);
            max-width: 700px;
            margin: 0 auto;
        }
        
        /* Groups Grid */
        .groups-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .group-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(126, 34, 206, 0.1);
            transition: all 0.3s ease;
            padding: 1.8rem;
            position: relative;
        }
        
        .group-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(126, 34, 206, 0.2);
        }
        
        .group-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.2rem;
        }
        
        .group-name {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--primary-dark);
        }
        
        .group-admin {
            background: rgba(168, 85, 247, 0.1);
            color: var(--primary);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .group-details {
            margin-bottom: 1.5rem;
        }
        
        .group-detail {
            display: flex;
            align-items: center;
            margin-bottom: 0.8rem;
            font-size: 1rem;
        }
        
        .group-detail i {
            width: 24px;
            color: var(--primary);
            margin-right: 0.8rem;
        }
        
        .group-amount {
            font-weight: 600;
            color: var(--success);
        }
        
        .group-status {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        .status-pending {
            background: rgba(239, 68, 68, 0.1);
            color: var(--error);
        }
        
        .status-paid {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }
        
        .btn-pay {
            background: linear-gradient(90deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: block;
            width: 100%;
            font-family: 'Poppins', sans-serif;
            margin-top: 1rem;
        }
        
        .btn-pay:hover {
            transform: translateY(-3px);
            box-shadow: 0 7px 15px rgba(126, 34, 206, 0.3);
        }
        
        /* Footer */
        footer {
            background: var(--primary-dark);
            color: white;
            padding: 2rem 1rem;
            text-align: center;
            margin-top: auto;
        }
        
        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .footer-logo {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.5rem;
            font-weight: 800;
        }
        
        .social-links {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 0.5rem;
        }
        
        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            color: white;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .social-links a:hover {
            background: var(--accent);
            transform: translateY(-3px);
        }
        
        .copyright {
            margin-top: 1rem;
            font-size: 0.8rem;
            opacity: 0.7;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            nav {
                padding: 1rem;
                flex-direction: column;
            }
            
            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
                margin-top: 1rem;
            }
            
            .groups-grid {
                grid-template-columns: 1fr;
            }
            
            .dashboard-title {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav>
        <a href="{{ url('/') }}" class="logo">Arisan Kami</a>
        <div class="nav-links">
            <a href="{{ url('/#about') }}">About</a>
            <a href="{{ url('/#features') }}">Features</a>
            <a href="{{ url('/#contact') }}">Contact</a>
            
            @auth
                @if(auth()->user()->hasRole('admin'))
                    <a href="{{ route('groups.index') }}">Admin Dashboard</a>
                @endif
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Register</a>
            @endauth
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <div class="dashboard-header">
            <h1 class="dashboard-title">Dashboard</h1>
            <p class="dashboard-subtitle">Kelola grup arisan Anda dan lakukan pembayaran dengan mudah</p>
        </div>
        
        @if($groups->count() > 0)
            <div class="groups-grid">
                @foreach ($groups as $group)
                    <div class="group-card">
                        <div class="group-header">
                            <h3 class="group-name">{{ $group->name }}</h3>
                            @if($group->admin_id == auth()->id())
                                <span class="group-admin">Admin</span>
                            @endif
                        </div>
                        
                        <div class="group-details">
                            <div class="group-detail">
                                <i class="fas fa-users"></i>
                                <span>Anggota: {{ $group->members_count }}</span>
                            </div>
                            
                            <div class="group-detail">
                                <i class="fas fa-money-bill-wave"></i>
                                <span>Iuran: <span class="group-amount">Rp{{ number_format($group->amount, 0, ',', '.') }}</span></span>
                            </div>
                            
                            <div class="group-detail">
                                <i class="fas fa-calendar-alt"></i>
                                <span>Periode: {{ $group->schedule }}</span>
                            </div>
                            
                            <div class="group-detail">
                                <i class="fas fa-info-circle"></i>
                                <span>Status: 
                                    @if($group->paymentStatus(auth()->id()))
                                        <span class="group-status status-paid">Lunas</span>
                                    @else
                                        <span class="group-status status-pending">Belum Bayar</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                        
                        @if(!$group->paymentStatus(auth()->id()))
                            <form method="POST" action="{{ route('pay', $group->id) }}">
                                @csrf
                                <button type="submit" class="btn-pay">
                                    <i class="fas fa-credit-card mr-2"></i>Bayar Sekarang
                                </button>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <i class="fas fa-users-slash text-5xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Anda belum tergabung dalam grup arisan</h3>
                <p class="text-gray-500 mb-4">Bergabunglah dengan grup arisan untuk mulai berpartisipasi</p>
                <a href="{{ route('groups.index') }}" class="btn-pay inline-block w-auto px-6">
                    <i class="fas fa-search mr-2"></i>Cari Grup Arisan
                </a>
            </div>
        @endif
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-logo">Arisan Kami</div>
            <p>Platform modern untuk mengelola arisan dengan mudah dan transparan</p>
            <div class="copyright">
                &copy; {{ date('Y') }} Arisan Kami. Hak Cipta Dilindungi.
            </div>
        </div>
    </footer>
</body>
</html>