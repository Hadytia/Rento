<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/favicon.png">
    <title>Verifikasi Email OTP – Rento</title>
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
        .card {
            width: 400px;
            background: #fff;
            border-radius: 12px;
            padding: 32px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            text-align: center;
        }
        .logo { font-size: 22px; font-weight: 700; margin-bottom: 16px; }
        .logo span.ren { color: #2D4DA3; }
        .logo span.to  { color: #FF7A00; }
        .icon { font-size: 40px; margin-bottom: 12px; }
        h1 { font-size: 18px; font-weight: 600; color: #1E1E1E; margin-bottom: 8px; }
        p  { font-size: 13px; color: #6B6B6B; margin-bottom: 24px; }
        .otp-input {
            width: 100%;
            height: 52px;
            border: 2px solid #E5E5E5;
            border-radius: 8px;
            text-align: center;
            font-size: 24px;
            font-weight: 600;
            letter-spacing: 8px;
            color: #1E1E1E;
            outline: none;
            transition: border-color 0.2s;
            margin-bottom: 8px;
        }
        .otp-input:focus { border-color: #2D4DA3; }
        .otp-input.is-invalid { border-color: #e53e3e; }
        .invalid-feedback { font-size: 12px; color: #e53e3e; margin-bottom: 14px; }
        .btn {
            width: 100%;
            height: 44px;
            background: #2D4DA3;
            border: none;
            border-radius: 8px;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            font-weight: 500;
            color: #fff;
            cursor: pointer;
            transition: background 0.2s;
            margin-bottom: 12px;
        }
        .btn:hover { background: #253f8a; }
        .resend { font-size: 13px; color: #6B6B6B; margin-bottom: 8px; }
        .resend a { color: #2D4DA3; text-decoration: none; }
        .resend a:hover { text-decoration: underline; }
        .back-link { font-size: 13px; color: #6B6B6B; }
        .back-link a { color: #2D4DA3; text-decoration: none; }
        .back-link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo"><span class="ren">Ren</span><span class="to">to</span></div>
        <div class="icon">📧</div>
        <h1>Verifikasi Email OTP</h1>
        <p>Masukkan 6 digit kode yang sudah dikirim ke email kamu.</p>

        @if($errors->any())
            <p class="invalid-feedback">{{ $errors->first() }}</p>
        @endif

        @if(session('success'))
            <p style="font-size:12px;color:#38a169;margin-bottom:14px;">{{ session('success') }}</p>
        @endif

        <form method="POST" action="{{ route('2fa.email.verify.post') }}">
            @csrf
            <input
                type="text"
                name="otp_code"
                class="otp-input {{ $errors->any() ? 'is-invalid' : '' }}"
                placeholder="000000"
                maxlength="6"
                inputmode="numeric"
                autocomplete="off"
                autofocus
            >
            <button type="submit" class="btn">Verifikasi</button>
        </form>

        <p class="resend">Tidak dapat email? <a href="{{ route('2fa.email.send') }}">Kirim ulang</a></p>
        <p class="back-link"><a href="{{ route('2fa.choose') }}">← Pilih metode lain</a></p>
    </div>
</body>
</html>