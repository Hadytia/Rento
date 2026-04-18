<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="/favicon.png">
    <title>Admin Portal – Rento</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0B1A2B 0%, #142A4A 100%);
        }
        .login-card {
            width: 400px;
            background: #FFFFFF;
            border-radius: 12px;
            padding: 32px 32px 0 32px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.2);
        }
        .logo { text-align: center; font-size: 22px; font-weight: 700; margin-bottom: 10px; }
        .logo span.ren { color: #2D4DA3; }
        .logo span.to  { color: #FF7A00; }
        .portal-title    { font-size: 20px; font-weight: 600; color: #1E1E1E; text-align: center; }
        .portal-subtitle { font-size: 13px; color: #6B6B6B; text-align: center; margin-bottom: 20px; }
        .form-group { margin-bottom: 14px; }
        .form-group label { display: block; font-size: 13px; color: #1E1E1E; margin-bottom: 6px; }
        .form-group input {
            width: 100%; height: 42px; border: 1px solid #E5E5E5; border-radius: 8px;
            padding: 0 12px; font-family: 'Inter', sans-serif; font-size: 13px;
            color: #1E1E1E; outline: none; transition: border-color 0.2s;
        }
        .form-group input:focus { border-color: #2D4DA3; }
        .form-group input.is-invalid { border-color: #e53e3e; }
        .invalid-feedback { font-size: 12px; color: #e53e3e; margin-top: 4px; }
        .alert-error {
            background: #fff5f5; border: 1px solid #fed7d7; border-radius: 8px;
            padding: 10px 14px; font-size: 13px; color: #c53030; margin-bottom: 14px;
        }
        .btn-login {
            display: block; width: 100%; height: 44px; background: #2D4DA3;
            border: none; border-radius: 8px; font-family: 'Inter', sans-serif;
            font-size: 14px; font-weight: 500; color: #FFFFFF; cursor: pointer;
            text-align: center; transition: background 0.2s, transform 0.1s; margin-bottom: 0;
        }
        .btn-login:hover  { background: #253f8a; }
        .btn-login:active { transform: scale(0.98); }
        .divider {
            display: flex; align-items: center; gap: 10px; margin: 14px 0;
        }
        .divider hr { flex: 1; border: none; border-top: 1px solid #E5E5E5; }
        .divider span { font-size: 12px; color: #6B6B6B; line-height: 1; }
        .btn-google {
            display: flex; align-items: center; justify-content: center; gap: 10px;
            width: 100%; height: 44px; background: #F5F5F5; border: 1px solid #E5E5E5;
            border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 13px;
            color: #1E1E1E; cursor: pointer; text-decoration: none; transition: background 0.2s;
            margin-bottom: 16px;
        }
        .btn-google:hover { background: #ececec; }
        .btn-google svg { width: 18px; height: 18px; flex-shrink: 0; }

        /* ✅ Info pending section */
        .pending-info {
            background: #F0F4FF;
            border: 1px solid #C7D2FE;
            border-radius: 8px;
            padding: 12px 14px;
            margin-bottom: 16px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        .pending-info .icon { font-size: 16px; flex-shrink: 0; margin-top: 1px; }
        .pending-info .text { font-size: 12px; color: #3730A3; line-height: 1.6; }
        .pending-info .text strong { color: #1E1E1E; }

        .card-footer {
            margin: 0 -32px; background: #F5F5F5; height: 50px; border-radius: 0 0 12px 12px;
            display: flex; align-items: center; justify-content: center; padding: 0 12px;
        }
        .card-footer p { font-size: 12px; color: #6B6B6B; text-align: center; }
    </style>
</head>
<body>
    <div class="login-card">

        <div class="logo"><span class="ren">Ren</span><span class="to">to</span></div>
        <h1 class="portal-title">Admin Portal</h1>
        <p class="portal-subtitle">Enter your credentials to access the dashboard</p>

        @if (session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="email">Username</label>
                <input type="text" id="email" name="email" placeholder="admin"
                    value="{{ old('email') }}" autocomplete="email"
                    class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
                @error('email')
                    <p class="invalid-feedback">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="password"
                    autocomplete="current-password"
                    class="{{ $errors->has('password') ? 'is-invalid' : '' }}">
                @error('password')
                    <p class="invalid-feedback">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="btn-login">Login</button>
        </form>

        <div class="divider"><hr><span>or</span><hr></div>

        <a href="{{ route('login.google') }}" class="btn-google">
            <svg viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.18 1.48-4.97 2.36-8.16 2.36-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                <path fill="none" d="M0 0h48v48H0z"/>
            </svg>
            Login with Google
        </a>

        {{-- ✅ Info untuk dosen/tamu --}}
        <div class="pending-info">
            <div class="icon">ℹ️</div>
            <div class="text">
                <strong>Dosen atau tamu?</strong><br>
                Klik <strong>Login with Google</strong> menggunakan akun Google kamu.
                Jika belum terdaftar, akun akan otomatis didaftarkan dan menunggu persetujuan Admin.
            </div>
        </div>

        <div class="card-footer">
            <p>System Administration Only</p>
        </div>
    </div>
</body>
</html>