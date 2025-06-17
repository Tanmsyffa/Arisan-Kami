<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Arisan Kami - Platform Arisan Modern</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.4/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.4/ScrollTrigger.min.js"></script>
    
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
            overflow-x: hidden;
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
            transition: all 0.3s ease;
        }
        
        nav.scrolled {
            padding: 0.8rem 2rem;
            box-shadow: 0 4px 15px rgba(126, 34, 206, 0.15);
        }
        
        .logo {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
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
        
        /* Hero Section */
        .hero {
            position: relative;
            overflow: hidden;
            padding: 8rem 1rem 6rem;
            text-align: center;
            background: linear-gradient(120deg, var(--primary-light) 0%, var(--primary) 100%);
            color: white;
            min-height: 80vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        
        .hero::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%23ffffff' fill-opacity='0.1' d='M0,96L48,112C96,128,192,160,288,186.7C384,213,480,235,576,213.3C672,192,768,128,864,128C960,128,1056,192,1152,208C1248,224,1344,192,1392,176L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z'%3E%3C/path%3E%3C/svg%3E");
            background-size: cover;
            background-position: center bottom;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 800px;
        }
        
        .hero h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            text-shadow: 0 2px 10px rgba(0,0,0,0.1);
            line-height: 1.2;
        }
        
        .hero p {
            font-size: 1.25rem;
            max-width: 700px;
            margin: 0 auto 2rem;
            opacity: 0.9;
        }
        
        .cta-button {
            display: inline-block;
            background: white;
            color: var(--primary-dark);
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            margin-top: 1.5rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
            position: relative;
            z-index: 10;
            animation: pulse 2s infinite;
            transform: translateY(0);
            opacity: 1;
        }
        
        .cta-button:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
            animation: none;
        }
        
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.7);
            }
            70% {
                box-shadow: 0 0 0 15px rgba(255, 255, 255, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0);
            }
        }
        
        /* Sections */
        section {
            padding: 6rem 1rem;
        }
        
        .section-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary-dark);
            margin-bottom: 1rem;
            text-align: center;
            position: relative;
            display: block;
            width: 100%;
        }
        
        .section-title::after {
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
        
        .section-subtitle {
            font-size: 1.1rem;
            max-width: 700px;
            margin: 0 auto 3rem;
            text-align: center;
            color: var(--gray);
        }
        
        /* Features - Card with Animation */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2.5rem;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        
        .feature-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(126, 34, 206, 0.1);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0, 1);
            position: relative;
            z-index: 1;
            transform: translateY(50px);
            opacity: 0;
            padding: 2.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        
        .feature-card.animate {
            transform: translateY(0);
            opacity: 1;
        }
        
        .feature-card:hover {
            transform: translateY(-15px) scale(1.03);
            box-shadow: 0 25px 50px rgba(126, 34, 206, 0.2);
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-light), var(--accent));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            font-size: 2rem;
            color: white;
            box-shadow: 0 10px 20px rgba(168, 85, 247, 0.3);
        }
        
        .feature-card h3 {
            font-size: 1.5rem;
            color: var(--primary-dark);
            margin-bottom: 1rem;
        }
        
        .feature-card p {
            color: var(--gray);
            margin-bottom: 1.5rem;
        }
        
        .feature-highlight {
            display: inline-block;
            background: rgba(168, 85, 247, 0.1);
            color: var(--primary);
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-top: auto;
        }

        .feature-center {
            display: flex;
            justify-content: center;
            margin-top: 2.5rem;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }
        
        /* About Section with Animation */
        .about-section {
            background: linear-gradient(135deg, #f0f9ff 0%, #fdf4ff 100%);
            border-radius: 30px;
            max-width: 1000px;
            margin: 0 auto;
            padding: 4rem;
            display: flex;
            align-items: center;
            gap: 3rem;
            box-shadow: 0 20px 40px rgba(126, 34, 206, 0.1);
            transform: scale(0.95);
            opacity: 0;
            transition: all 0.8s ease;
        }
        
        .about-section.animate {
            transform: scale(1);
            opacity: 1;
        }
        
        .about-content {
            flex: 1;
        }
        
        .about-image {
            flex: 1;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            height: 350px;
            background: linear-gradient(45deg, var(--primary-light), var(--accent));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 4rem;
        }
        
        /* Contact Section */
        .contact-section {
            text-align: center;
            background: linear-gradient(135deg, #f0f9ff 0%, #fdf4ff 100%);
            border-radius: 30px;
            max-width: 800px;
            margin: 0 auto;
            padding: 4rem 3rem;
            box-shadow: 0 20px 40px rgba(126, 34, 206, 0.1);
            position: relative;
            overflow: hidden;
        }
        
        .contact-section::before {
            content: "";
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(168,85,247,0.05) 0%, rgba(168,85,247,0) 70%);
            z-index: 0;
        }
        
        .contact-content {
            position: relative;
            z-index: 1;
        }
        
        .contact-email {
            font-size: 1.5rem;
            color: var(--primary);
            font-weight: 700;
            text-decoration: none;
            display: inline-block;
            margin-top: 1.5rem;
            transition: all 0.3s ease;
            background: white;
            padding: 0.8rem 2rem;
            border-radius: 50px;
            box-shadow: 0 10px 20px rgba(126, 34, 206, 0.1);
        }
        
        .contact-email:hover {
            color: var(--primary-dark);
            transform: scale(1.05);
            box-shadow: 0 15px 30px rgba(126, 34, 206, 0.2);
        }
        
        /* Auth Forms */
        .auth-container {
            display: grid;
            grid-template-columns: 1fr;
            gap: 3rem;
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        @media (min-width: 768px) {
            .auth-container {
                grid-template-columns: 1fr 1fr;
            }
        }
        
        .auth-form {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(126, 34, 206, 0.1);
            padding: 2.5rem;
            position: relative;
            overflow: hidden;
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
        .text-red-600 {
            color: var(--error);
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        
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
        
        /* Validation Errors */
        .validation-errors {
            background: #fef2f2;
            border-left: 4px solid var(--error);
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 0 8px 8px 0;
        }
        
        .validation-errors ul {
            list-style: none;
            padding-left: 0;
        }
        
        .validation-errors li {
            color: var(--error);
            margin-bottom: 0.5rem;
        }
        
        /* Footer */
        footer {
            background: var(--primary-dark);
            color: white;
            padding: 3rem 1rem;
            text-align: center;
            margin-top: auto;
        }
        
        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }
        
        .footer-logo {
            font-family: 'Montserrat', sans-serif;
            font-size: 2rem;
            font-weight: 800;
        }
        
        .social-links {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin-top: 1rem;
        }
        
        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            color: white;
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }
        
        .social-links a:hover {
            background: var(--accent);
            transform: translateY(-5px);
        }
        
        .copyright {
            margin-top: 2rem;
            font-size: 0.9rem;
            opacity: 0.7;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            nav {
                flex-direction: column;
                padding: 1rem;
            }
            
            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
                margin-top: 1rem;
            }
            
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .about-section {
                flex-direction: column;
                padding: 2rem;
            }
            
            .about-image {
                width: 100%;
                height: 250px;
            }
            
            .feature-card {
                padding: 2rem;
            }
            
            .cta-button {
                padding: 10px 20px;
                font-size: 1rem;
            }
            
            .auth-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav>
        <div class="logo">Arisan Kami</div>
        <div class="nav-links">
            <a href="#about">About</a>
            <a href="#features">Features</a>
            <a href="#contact">Contact</a>
            
            @auth
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            @endauth
        </div>
    </nav>

    <!-- Hero -->
    <section class="hero">
        <div class="hero-content">
            <h1>Selamat Datang di Platform Arisan Modern</h1>
            <p>Kelola arisan dengan mudah, pembayaran otomatis menggunakan Midtrans, dan pengalaman yang menyenangkan</p>
            <a href="{{ route('login') }}" class="cta-button">Mulai Sekarang</a>
        </div>
    </section>

    <!-- About Section -->
    <section id="about">
        <div class="about-section">
            <div class="about-content">
                <h2 class="section-title">Tentang Kami</h2>
                <div class="max-w-2xl mx-auto mt-8 text-gray-700 text-lg leading-relaxed px-6 md:px-12 max-w-prose">
                    <p style="text-align: justify;" class="mb-4">
                        <strong class="text-purple-700">Arisan Kami</strong> adalah platform digital yang memudahkan pengelolaan grup arisan, mulai dari pencatatan anggota, pemilihan pemenang, hingga pembayaran online secara otomatis dan efisien.
                    </p><br>
                    <p style="text-align: justify;">
                        Kami menghadirkan solusi modern untuk tradisi arisan yang telah berlangsung turun-temurun. Dengan teknologi terkini, kami memastikan proses arisan berjalan transparan, adil, dan menyenangkan bagi seluruh anggota.
                    </p>
                </div>
            </div>
            <div class="about-image">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section id="features">
        <h2 class="section-title">Fitur Unggulan</h2>
        <p class="section-subtitle">Nikmati kemudahan mengelola arisan dengan fitur-fitur modern kami</p>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-users-cog"></i>
                </div>
                <h3>Manajemen Grup</h3>
                <p>Mudah membuat, mengelola, dan memantau grup arisan dengan fitur lengkap dan intuitif.</p>
                <span class="feature-highlight">Smart Group Management</span>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-credit-card"></i>
                </div>
                <h3>Pembayaran Midtrans</h3>
                <p>Terintegrasi dengan Midtrans untuk pembayaran otomatis, aman, dan terpercaya.</p>
                <span class="feature-highlight">Secure Payment</span>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3>Dashboard Real-Time</h3>
                <p>Pantau status anggota, pembayaran, dan histori pemenang secara real-time.</p>
                <span class="feature-highlight">Live Updates</span>
            </div>
        </div>
        
        <div class="feature-center">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-random"></i>
                </div>
                <h3>Undian Adil</h3>
                <p>Algoritma khusus yang memastikan setiap anggota memiliki kesempatan yang sama.</p>
                <span class="feature-highlight">Fair Algorithm</span>
            </div>
        </div>
    </section>

    <!-- Contact -->
    <section id="contact">
        <div class="contact-section">
            <div class="contact-content">
                <h2 class="section-title">Kontak Kami</h2>
                <p class="section-subtitle">Punya pertanyaan atau masukan? Hubungi tim dukungan kami</p>
                <a href="mailto:arisan-kami@gmail.com" class="contact-email">arisan-kami@gmail.com</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-logo">Arisan Kami</div>
            <p>Platform modern untuk mengelola arisan dengan mudah dan transparan</p>
            <div class="copyright">
                &copy; {{ date('Y') }} Arisan Kami.
        </div>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Navigation scroll effect
            const nav = document.querySelector('nav');
            window.addEventListener('scroll', () => {
                if (window.scrollY > 50) {
                    nav.classList.add('scrolled');
                } else {
                    nav.classList.remove('scrolled');
                }
            });
            
            // Animate about section
            const aboutSection = document.querySelector('.about-section');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        aboutSection.classList.add('animate');
                    }
                });
            }, { threshold: 0.1 });
            
            observer.observe(aboutSection);
            
            // Animate feature cards
            const featureCards = document.querySelectorAll('.feature-card');
            featureCards.forEach((card, index) => {
                setTimeout(() => {
                    card.classList.add('animate');
                }, index * 200);
            });
            
            // Initialize GSAP animations
            gsap.registerPlugin(ScrollTrigger);
            
            // Animate hero text
            gsap.from('.hero h1', {
                duration: 1,
                y: 50,
                opacity: 0,
                ease: "power3.out"
            });
            
            gsap.from('.hero p', {
                duration: 1,
                y: 30,
                opacity: 0,
                delay: 0.3,
                ease: "power3.out"
            })
        });
    </script>
</body>
</html>