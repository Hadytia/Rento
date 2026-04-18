<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/favicon.png">
    <title>Menunggu Persetujuan – Rento</title>
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
            width: 440px;
            background: #fff;
            border-radius: 12px;
            padding: 40px 32px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            text-align: center;
        }
        .logo { font-size: 22px; font-weight: 700; margin-bottom: 24px; }
        .logo span.ren { color: #2D4DA3; }
        .logo span.to  { color: #FF7A00; }
        .icon {
            width: 72px;
            height: 72px;
            background: #FFF7ED;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin: 0 auto 20px;
        }
        h1 {
            font-size: 18px;
            font-weight: 600;
            color: #1E1E1E;
            margin-bottom: 10px;
        }
        p {
            font-size: 13px;
            color: #6B6B6B;
            line-height: 1.7;
            margin-bottom: 8px;
        }
        .info-box {
            background: #F0F4FF;
            border: 1px solid #C7D2FE;
            border-radius: 8px;
            padding: 14px 16px;
            margin: 20px 0;
            text-align: left;
        }
        .info-box p {
            font-size: 13px;
            color: #3730A3;
            margin: 0;
            line-height: 1.6;
        }
        .info-box p strong { color: #1E1E1E; }
        .divider {
            border: none;
            border-top: 1px solid #E5E5E5;
            margin: 20px 0;
        }
        .btn-back {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 42px;
            padding: 0 24px;
            background: #F5F5F5;
            border: 1px solid #E5E5E5;
            border-radius: 8px;
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            color: #1E1E1E;
            text-decoration: none;
            transition: background 0.2s;
        }
        .btn-back:hover { background: #ececec; }
        .status-badge {
            display: inline-block;
            background: #FFF7ED;
            border: 1px solid #FED7AA;
            color: #EA580C;
            font-size: 12px;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 20px;
            margin-bottom: 16px;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo"><span class="ren">Ren</span><span class="to">to</span></div>

        <div class="icon">⏳</div>

        <span class="status-badge">Menunggu Persetujuan</span>

        <h1>Akun Kamu Sedang Ditinjau</h1>
        <p>Pendaftaran kamu sebagai <strong>Dosen</strong> telah berhasil diterima.</p>
        <p>Silakan tunggu hingga Admin atau Superadmin menyetujui akun kamu.</p>

        <div class="info-box">
            <p>📋 <strong>Informasi:</strong></p>
            <p>Setelah disetujui, kamu dapat login kembali menggunakan akun Google yang sama dan mengakses sistem sebagai <strong>Dosen (View Only)</strong>.</p>
        </div>

        <hr class="divider">

        <p style="font-size:12px; color:#9E9E9E; margin-bottom:16px;">
            Jika sudah lama menunggu, hubungi administrator sistem.
        </p>

        <a href="/login" class="btn-back">← Kembali ke Login</a>
    </div>
</body>
</html>