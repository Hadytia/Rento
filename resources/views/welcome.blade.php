<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rento — Premium Rental Management</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #2D4DA3;
            --primary-dark: #1E3A8A;
            --primary-light: #E0E7FF;
            --secondary: #6366F1;
            --accent: #00D2FF;
            --bg: #F8FAFC;
            --text-main: #0F172A;
            --text-muted: #64748B;
            --glass: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(255, 255, 255, 0.3);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        /* ── Navbar ── */
        nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 5vw;
            height: 80px;
            background: var(--glass);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--glass-border);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            transition: transform 0.2s;
        }
        .nav-brand:hover { transform: scale(1.02); }

        .nav-logo {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            box-shadow: 0 4px 12px rgba(45, 77, 163, 0.2);
        }

        .nav-brand-name {
            font-family: 'Outfit', sans-serif;
            font-size: 22px;
            font-weight: 700;
            color: var(--text-main);
            letter-spacing: -0.5px;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-login {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-main);
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 10px;
            transition: all 0.2s;
        }
        .btn-login:hover { background: rgba(0,0,0,0.04); }

        .btn-dashboard-nav {
            background: var(--primary);
            color: white;
            text-decoration: none;
            font-size: 14px;
            font-weight: 700;
            padding: 12px 24px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(45, 77, 163, 0.25);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .btn-dashboard-nav:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(45, 77, 163, 0.35);
        }

        /* ── Hero Section ── */
        .hero {
            position: relative;
            padding: 100px 5vw;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            background: radial-gradient(circle at top right, #EEF2FF 0%, transparent 50%),
                        radial-gradient(circle at bottom left, #F0FDFA 0%, transparent 50%);
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: white;
            border: 1px solid var(--primary-light);
            padding: 6px 16px;
            border-radius: 100px;
            font-size: 13px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 32px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            animation: fadeInDown 0.8s ease-out;
        }

        .hero h1 {
            font-family: 'Outfit', sans-serif;
            font-size: clamp(42px, 6vw, 72px);
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -2px;
            max-width: 900px;
            margin-bottom: 24px;
            animation: fadeInUp 0.8s ease-out;
        }

        .hero h1 span {
            background: linear-gradient(to right, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .hero-sub {
            font-size: 18px;
            color: var(--text-muted);
            max-width: 580px;
            line-height: 1.6;
            margin-bottom: 48px;
            animation: fadeInUp 0.8s ease-out 0.2s backwards;
        }

        .hero-actions {
            display: flex;
            gap: 16px;
            animation: fadeInUp 0.8s ease-out 0.4s backwards;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            padding: 18px 40px;
            border-radius: 16px;
            font-size: 16px;
            font-weight: 700;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 10px 25px rgba(45, 77, 163, 0.2);
            transition: all 0.3s;
        }
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(45, 77, 163, 0.3);
        }

        .btn-secondary {
            background: white;
            color: var(--text-main);
            padding: 18px 36px;
            border-radius: 16px;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            border: 1px solid var(--primary-light);
            transition: all 0.3s;
        }
        .btn-secondary:hover { background: #F1F5F9; }

        /* ── Stats ── */
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 24px;
            padding: 60px 5vw;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        .stat-card {
            background: white;
            padding: 32px;
            border-radius: 24px;
            border: 1px solid rgba(0,0,0,0.05);
            text-align: center;
            transition: all 0.3s;
        }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.05); }

        .stat-val { font-size: 32px; font-weight: 800; color: var(--primary); margin-bottom: 8px; }
        .stat-label { font-size: 14px; font-weight: 600; color: var(--text-muted); }

        /* ── Features ── */
        .features {
            padding: 100px 5vw;
            background: white;
        }

        .section-header { text-align: center; margin-bottom: 64px; }
        .section-header h2 { font-family: 'Outfit', sans-serif; font-size: 36px; font-weight: 800; margin-bottom: 16px; }
        .section-header p { color: var(--text-muted); font-size: 18px; }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 24px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .feature-card {
            background: var(--bg);
            padding: 40px;
            border-radius: 28px;
            border: 1px solid transparent;
            transition: all 0.4s ease;
        }
        .feature-card:hover {
            background: white;
            border-color: var(--primary-light);
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.05);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 24px;
        }
        .feature-icon.blue { background: #EEF2FF; color: #4338CA; }
        .feature-icon.green { background: #F0FDF4; color: #15803D; }
        .feature-icon.red { background: #FEF2F2; color: #B91C1C; }
        .feature-icon.purple { background: #F5F3FF; color: #6D28D9; }

        .feature-card h3 { font-size: 20px; font-weight: 700; margin-bottom: 12px; }
        .feature-card p { font-size: 15px; color: var(--text-muted); line-height: 1.6; }

        /* ── Footer ── */
        footer {
            margin-top: auto;
            padding: 40px 5vw;
            background: white;
            border-top: 1px solid var(--primary-light);
            text-align: center;
            font-size: 14px;
            color: var(--text-muted);
        }

        footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 700;
            transition: color 0.2s;
        }
        footer a:hover { color: var(--secondary); text-decoration: underline; }

        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            nav { padding: 0 24px; }
            .hero h1 { font-size: 42px; }
            .hero-actions { flex-direction: column; width: 100%; }
            .btn-primary, .btn-secondary { justify-content: center; }
        }
    </style>
</head>
<body>

    <nav>
        <a href="/" class="nav-brand">
            <div class="nav-logo">📦</div>
            <span class="nav-brand-name">Rento</span>
        </a>
        <div class="nav-links">
            @auth
                <a href="{{ url('/dashboard') }}" class="btn-dashboard-nav">
                    Dashboard →
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-login">Log In</a>
                <a href="{{ route('login') }}" class="btn-dashboard-nav">Get Started</a>
            @endauth
        </div>
    </nav>

    <div class="hero">
        <div class="hero-badge">
            ✨ Intelligent Rental Management System
        </div>
        <h1>Kelola Bisnis Rental<br>Anda dengan <span>Keunggulan</span></h1>
        <p class="hero-sub">
            Pantau stok produk, otomasi denda, dan kelola laporan transaksi dalam satu sistem terintegrasi yang modern.
        </p>
        <div class="hero-actions">
            @auth
                <a href="{{ url('/dashboard') }}" class="btn-primary">
                    Buka Dashboard Pintar
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-primary">
                    Mulai Sekarang
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
                <a href="#" class="btn-secondary">Lihat Demo Produk</a>
            @endauth
        </div>
    </div>

    <div class="stats">
        <div class="stat-card">
            <div class="stat-val">100%</div>
            <div class="stat-label">Cloud-based & Secure</div>
        </div>
        <div class="stat-card">
            <div class="stat-val">Real-time</div>
            <div class="stat-label">Auto-sync MySQL Database</div>
        </div>
        <div class="stat-card">
            <div class="stat-val">24/7</div>
            <div class="stat-label">Akses Kapan Pun & Di Mana Pun</div>
        </div>
        <div class="stat-card">
            <div class="stat-val">Fast</div>
            <div class="stat-label">Export Laporan & Email OTP</div>
        </div>
    </div>

    <div class="features">
        <div class="section-header">
            <h2>Kekuatan Rento Untuk Bisnis Anda</h2>
            <p>Fitur cerdas yang dirancang untuk mempercepat operasional rental Anda.</p>
        </div>
        <div class="feature-grid">
            <div class="feature-card">
                <div class="feature-icon blue">📦</div>
                <h3>Manajemen Inventaris</h3>
                <p>Kelola stok produk secara presisi. Ketahui barang mana yang sedang keluar dan mana yang siap disewa.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon green">📊</div>
                <h3>Laporan Otomatis</h3>
                <p>Dapatkan insight bisnis harian, bulanan, dan tahunan tanpa harus menghitung manual satu per satu.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon red">⚠️</div>
                <h3>Sistem Denda Cerdas</h3>
                <p>Kalkulasi denda keterlambatan secara otomatis berdasarkan durasi sewa yang terlewat.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon purple">🔐</div>
                <h3>Keamanan Berlapis</h3>
                <p>Dilengkapi dengan fitur 2FA (Two-Factor Authentication) untuk melindungi data transaksi sensitif Anda.</p>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; {{ date('Y') }} <strong>Rento</strong> · Powered by <a href="https://sekawanputrapratama.com" target="_blank">Sekawan Putra Pratama</a></p>
    </footer>

</body>
</html>