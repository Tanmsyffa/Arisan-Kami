<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Arisan Kami</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            --warning: #f59e0b;
            --sidebar-width: 280px;
            --header-height: 70px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
            color: #334155;
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }
        
        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--primary-dark), var(--primary));
            color: white;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
            transition: var(--transition);
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            transform: translateX(0);
        }
        
        .logo-container {
            padding: 1.5rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            gap: 0.8rem;
            position: sticky;
            top: 0;
            background: var(--primary-dark);
            z-index: 10;
        }
        
        .logo {
            background: white;
            color: var(--primary);
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            transition: var(--transition);
        }
        
        .app-name {
            font-size: 1.4rem;
            font-weight: 700;
            transition: var(--transition);
        }
        
        .admin-section {
            padding: 1.5rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            background: rgba(0, 0, 0, 0.1);
            margin: 1.5rem;
            border-radius: 12px;
            transition: var(--transition);
        }
        
        .admin-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-light), var(--accent));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.2rem;
            flex-shrink: 0;
        }
        
        .admin-info {
            flex: 1;
            min-width: 0;
            overflow: hidden;
        }
        
        .admin-name {
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 0.2rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .admin-role {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            padding: 0.2rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            display: inline-block;
            white-space: nowrap;
        }
        
        .nav-menu {
            padding: 1.5rem;
        }
        
        .menu-title {
            text-transform: uppercase;
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.6);
            letter-spacing: 1px;
            margin-bottom: 1rem;
            padding-left: 0.5rem;
            transition: var(--transition);
        }
        
        .menu-items {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .menu-item {
            display: flex;
            align-items: center;
            padding: 0.8rem 1rem;
            border-radius: 8px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: var(--transition);
            gap: 0.8rem;
            position: relative;
        }
        
        .menu-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        .menu-item.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            font-weight: 500;
        }
        
        .menu-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: white;
            border-radius: 0 4px 4px 0;
        }
        
        .menu-item i {
            width: 24px;
            text-align: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }
        
        .menu-item span {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            transition: var(--transition);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            width: calc(100% - var(--sidebar-width));
        }
        
        .top-header {
            height: var(--header-height);
            background: white;
            display: flex;
            align-items: center;
            padding: 0 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 900;
            gap: 1.5rem;
        }
        
        .menu-toggle {
            display: none;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: var(--primary);
            color: white;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            border: none;
        }
        
        .menu-toggle:hover {
            background: var(--primary-dark);
        }
        
        .search-container {
            flex: 1;
            max-width: 500px;
            position: relative;
        }
        
        .search-input {
            width: 100%;
            padding: 0.8rem 1.2rem 0.8rem 3rem;
            border-radius: 30px;
            border: 1px solid #e2e8f0;
            font-size: 1rem;
            transition: var(--transition);
            background: #f1f5f9;
        }
        
        .search-input:focus {
            outline: none;
            border-color: var(--primary-light);
            box-shadow: 0 0 0 4px rgba(168, 85, 247, 0.1);
        }
        
        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
        }
        
        .header-actions {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        
        .notification-btn, .message-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f1f5f9;
            color: var(--dark);
            position: relative;
            transition: var(--transition);
            border: none;
            cursor: pointer;
        }
        
        .notification-btn:hover, .message-btn:hover {
            background: var(--primary-light);
            color: white;
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--error);
            color: white;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: 600;
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            cursor: pointer;
            position: relative;
        }
        
        .user-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-light), var(--accent));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
            flex-shrink: 0;
        }
        
        .user-name {
            font-weight: 500;
            white-space: nowrap;
        }
        
        /* Dashboard Content */
        .content {
            padding: 2rem;
            flex: 1;
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .page-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }
        
        .page-title i {
            font-size: 1.5rem;
            color: var(--primary);
        }
        
        /* Ubah nama kelas untuk menghindari konflik */
        .page-actions {
            display: flex;
            gap: 0.8rem;
        }
        
        .btn {
            padding: 0.7rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: var(--transition);
            border: none;
        }
        
        .btn:focus {
            outline: 2px solid var(--primary-light);
            outline-offset: 2px;
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
        }
        
        .btn-secondary {
            background: #e0e7ff;
            color: var(--primary);
        }
        
        .btn-secondary:hover {
            background: #d1d9ff;
        }
        
        /* Alert messages */
        .alert-container {
            margin-bottom: 2rem;
        }
        
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            animation: fadeIn 0.3s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .alert-success {
            background: rgba(16, 185, 129, 0.15);
            color: var(--success);
            border-left: 4px solid var(--success);
        }
        
        .alert-error {
            background: rgba(239, 68, 68, 0.15);
            color: var(--error);
            border-left: 4px solid var(--error);
        }
        
        .alert-warning {
            background: rgba(245, 158, 11, 0.15);
            color: var(--warning);
            border-left: 4px solid var(--warning);
        }
        
        .alert-info {
            background: rgba(59, 130, 246, 0.15);
            color: #3b82f6;
            border-left: 4px solid #3b82f6;
        }
        
        .alert i {
            font-size: 1.2rem;
        }
        
        /* Stats cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }
        
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: var(--transition);
            border-left: 4px solid var(--primary);
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(126, 34, 206, 0.05), transparent);
            z-index: 0;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(126, 34, 206, 0.15);
        }
        
        .stat-info {
            position: relative;
            z-index: 1;
        }
        
        .stat-info h3 {
            font-size: 0.9rem;
            color: var(--gray);
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        
        .stat-info p {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-dark);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            background: rgba(126, 34, 206, 0.1);
            color: var(--primary);
            position: relative;
            z-index: 1;
            flex-shrink: 0;
        }
        
        /* Sidebar overlay untuk mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.active {
                transform: translateX(0);
                box-shadow: 0 0 50px rgba(0, 0, 0, 0.2);
            }
            
            .main-content {
                margin-left: 0;
                width: 100%;
            }
            
            .menu-toggle {
                display: flex;
            }
            
            .sidebar-overlay.active {
                display: block;
            }
        }
        
        @media (max-width: 768px) {
            .top-header {
                padding: 0 1rem;
            }
            
            .search-container {
                max-width: 200px;
            }
            
            .user-name {
                display: none;
            }
            
            .content {
                padding: 1.5rem 1rem;
            }
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .page-actions {
                width: 100%;
                justify-content: flex-end;
            }
        }
        
        @media (max-width: 576px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .search-container {
                max-width: 150px;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Sidebar Navigation -->
    <div class="sidebar">
        <div class="logo-container">
            <div class="logo">
                <i class="fas fa-users"></i>
            </div>
            <div class="app-name">Arisan Kami</div>
        </div>
        
        <div class="admin-section">
            <div class="admin-avatar">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="admin-info">
                <div class="admin-name">{{ Auth::user()->name }}</div>
                <div class="admin-role">{{ Auth::user()->role }}</div>
            </div>
        </div>
        
        <div class="nav-menu">
            <div class="menu-title">Menu Utama</div>
            <div class="menu-items">
                <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.groups.index') }}" class="menu-item {{ request()->routeIs('admin.groups.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Kelola Grup</span>
                </a>
                <a href="{{ route('admin.payments.index') }}" class="menu-item {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                    <i class="fas fa-money-bill-wave"></i>
                    <span>Pembayaran</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-user-friends"></i>
                    <span>Manajemen User</span>
                </a>
            </div>
            
            <div class="menu-title" style="margin-top: 2rem;">Akun</div>
            <div class="menu-items">
                <a href="{{ route('profile.edit') }}" class="menu-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                    <i class="fas fa-user-cog"></i>
                    <span>Pengaturan Profil</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                    @csrf
                    <a href="#" class="menu-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Overlay untuk sidebar mobile -->
    <div class="sidebar-overlay"></div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Header -->
        <div class="top-header">
            <button class="menu-toggle" aria-label="Toggle menu">
                <i class="fas fa-bars"></i>
            </button>
            
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" placeholder="Cari grup, anggota, atau pembayaran...">
            </div>
            
            <div class="header-actions">
                <button class="notification-btn" aria-label="Notifications">
                    <i class="fas fa-bell"></i>
                    <div class="notification-badge">3</div>
                </button>
                
                <div class="user-profile">
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="user-name">{{ Auth::user()->name }}</div>
                </div>
            </div>
        </div>
        
        <!-- Dashboard Content -->
        <div class="content">
            <!-- Flash Messages -->
            <div class="alert-container">
                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ session('error') }}
                    </div>
                @endif
                
                @if(session('warning'))
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        {{ session('warning') }}
                    </div>
                @endif
                
                @if(session('info'))
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        {{ session('info') }}
                    </div>
                @endif
            </div>
            
            <!-- Page Title and Actions -->
            <div class="page-header">
                <h1 class="page-title">
                    @yield('icon', '')
                    @yield('title', '')
                </h1>
                <div class="page-actions">
                    @yield('actions')
                </div>
            </div>
            
            <!-- Main Content Area -->
            @yield('content')
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elemen DOM
            const menuToggle = document.querySelector('.menu-toggle');
            const sidebar = document.querySelector('.sidebar');
            const sidebarOverlay = document.querySelector('.sidebar-overlay');
            const menuItems = document.querySelectorAll('.menu-item');
            
            // Toggle sidebar
            function toggleSidebar() {
                sidebar.classList.toggle('active');
                sidebarOverlay.classList.toggle('active');
                
                if (sidebar.classList.contains('active')) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
            }
            
            // Event listeners
            if (menuToggle) {
                menuToggle.addEventListener('click', toggleSidebar);
            }
            
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', toggleSidebar);
            }
            
            // Tutup sidebar saat memilih menu di mobile
            menuItems.forEach(item => {
                item.addEventListener('click', function() {
                    if (window.innerWidth <= 992) {
                        toggleSidebar();
                    }
                });
            });
            
            // Auto close alerts
            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(alert => {
                    alert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-10px)';
                    setTimeout(() => {
                        if (alert.parentNode) alert.parentNode.removeChild(alert);
                    }, 500);
                });
            }, 5000);
            
            // Handle resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 992 && sidebar.classList.contains('active')) {
                    toggleSidebar();
                }
            });
        });
    </script>
    
    @yield('scripts')
</body>
</html>