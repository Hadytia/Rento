<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/favicon.png">
    <title>Pilih Metode 2FA – Rento</title>
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
            width: 420px;
            background: #fff;
            border-radius: 12px;
            padding: 32px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            text-align: center;
        }
        .logo { font-size: 22px; font-weight: 700; margin-bottom: 16px; }
        .logo span.ren { color: #2D4DA3; }
        .logo span.to  { color: #FF7A00; }
        h1 { font-size: 18px; font-weight: 600; color: #1E1E1E; margin-bottom: 8px; }
        p  { font-size: 13px; color: #6B6B6B; margin-bottom: 24px; }
        .options { display: flex; flex-direction: column; gap: 12px; }
        .option-btn {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px;
            border-radius: 10px;
            border: 2px solid #E5E5E5;
            background: #fff;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            text-align: left;
        }
        .option-btn:hover { border-color: #2D4DA3; background: #f0f4ff; }
        .option-icon { font-size: 28px; flex-shrink: 0; }
        .option-text .title { font-size: 14px; font-weight: 600; color: #1E1E1E; }
        .option-text .desc  { font-size: 12px; color: #6B6B6B; margin-top: 2px; }
        .back-link { margin-top: 20px; font-size: 13px; color: #6B6B6B; }
        .back-link a { color: #2D4DA3; text-decoration: none; }
        .back-link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo"><span class="ren">Ren</span><span class="to">to</span></div>
        <h1>Two-Factor Authentication</h1>
        <p>Pilih metode verifikasi yang ingin kamu gunakan.</p>

        <div class="options">
            {{-- Google Authenticator → ke halaman setup QR dulu --}}
            <a href="{{ route('2fa.setup') }}" class="option-btn">
                <div class="option-icon">🔐</div>
                <div class="option-text">
                    <div class="title">Google Authenticator</div>
                    <div class="desc">Scan QR Code lalu masukkan kode dari app Google Authenticator</div>
                </div>
            </a>

            {{-- Email OTP → langsung kirim OTP ke email --}}
            <a href="{{ route('2fa.email.send') }}" class="option-btn">
                <div class="option-icon">📧</div>
                <div class="option-text">
                    <div class="title">Email OTP</div>
                    <div class="desc">Kirim kode OTP ke email kamu, berlaku 5 menit</div>
                </div>
            </a>
        </div>

        <div class="back-link"><a href="/login">← Kembali ke Login</a></div>
    </div>
</body>
</html>