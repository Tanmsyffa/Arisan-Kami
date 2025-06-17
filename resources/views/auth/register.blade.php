<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar - Arisan Kami</title>
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
        
        /* Auth Container */
        .auth-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 100px);
            padding: 2rem;
        }
        
        .auth-form {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(126, 34, 206, 0.1);
            padding: 2.5rem;
            width: 100%;
            max-width: 500px;
            position: relative;
        }
        
        .auth-form::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--primary-light), var(--accent));
        }
        
        .form-title {
            font-size: 1.8rem;
            color: var(--primary-dark);
            margin-bottom: 2rem;
            text-align: center;
            font-weight: 700;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--dark);
        }
        
        .form-input {
            width: 100%;
            padding: 0.8rem 1.2rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
        }
        
        .form-input:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 4px rgba(168, 85, 247, 0.2);
            outline: none;
        }
        
        .btn-primary {
            background: linear-gradient(90deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            padding: 0.9rem 1.5rem;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: block;
            width: 100%;
            margin-top: 1.5rem;
            font-family: 'Poppins', sans-serif;
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 7px 15px rgba(126, 34, 206, 0.3);
        }
        
        .form-footer {
            margin-top: 1.5rem;
            text-align: center;
        }
        
        .form-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }
        
        .form-footer a:hover {
            text-decoration: underline;
        }
        
        /* Error messages */
        .error-message {
            color: var(--error);
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        
        .session-status {
            color: var(--success);
            margin-bottom: 1rem;
            text-align: center;
        }
        
        /* Register prompt */
        .register-prompt {
            margin-top: 1.5rem;
            text-align: center;
            color: var(--gray);
        }
        
        .register-prompt a {
            color: var(--primary);
            font-weight: 500;
            text-decoration: none;
        }
        
        .register-prompt a:hover {
            text-decoration: underline;
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
            
            .auth-container {
                padding: 1rem;
            }
            
            .auth-form {
                padding: 1.5rem;
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
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            @endauth
        </div>
    </nav>

    <!-- Auth Container -->
    <div class="auth-container">
        <div class="auth-form">
            <h2 class="form-title">Daftar Arisan Kami</h2>
            
            <!-- Session Status -->
            @if (session('status'))
                <div class="session-status">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="form-group">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input id="name" class="form-input" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
                    
                    @error('name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email Address -->
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" class="form-input" type="email" name="email" value="{{ old('email') }}" required autocomplete="email">
                    
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" class="form-input" type="password" name="password" required autocomplete="new-password">
                    
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <input id="password_confirmation" class="form-input" type="password" name="password_confirmation" required autocomplete="new-password">
                    
                    @error('password_confirmation')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-footer">
                    <button type="submit" class="btn-primary">Daftar</button>
                </div>
            </form>
            
            <!-- Tambahkan link untuk login -->
            <div class="register-prompt">
                Sudah punya akun? <a href="{{ route('login') }}">Masuk disini</a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-logo">Arisan Kami</div>
            <p>Platform modern untuk mengelola arisan dengan mudah dan transparan</p>
            <div class="copyright">
                &copy; {{ date('Y') }} Arisan Kami.
            </div>
        </div>
    </footer>
</body>
</html>