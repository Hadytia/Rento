<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Anda Telah Disetujui - Rento</title>
</head>
<body style="margin:0; padding:0; background:#F0F2F5; font-family:Arial, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#F0F2F5; padding:40px 16px;">
        <tr>
            <td align="center">
                <table width="520" cellpadding="0" cellspacing="0" border="0" style="max-width:520px; width:100%;">

                    <!-- HEADER -->
                    <tr>
                        <td style="background:linear-gradient(135deg, #0B1A2B 0%, #1E3A5F 100%); border-radius:16px 16px 0 0; padding:32px 40px; text-align:center;">
                            <div style="font-size:30px; font-weight:800; letter-spacing:-1px;">
                                <span style="color:#5B8AF5;">Ren</span><span style="color:#FF7A00;">to</span>
                            </div>
                            <div style="font-size:11px; color:#90A1B9; margin-top:6px; letter-spacing:2px;">RENTAL MANAGEMENT SYSTEM</div>
                        </td>
                    </tr>

                    <!-- BODY -->
                    <tr>
                        <td style="background:#ffffff; padding:40px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">

                                <!-- Icon -->
                                <tr>
                                    <td align="center" style="padding-bottom:24px;">
                                        <div style="width:72px; height:72px; background:#22C55E; border-radius:50%; text-align:center; line-height:72px; font-size:36px; color:white; margin:0 auto;">
                                            ✓
                                        </div>
                                    </td>
                                </tr>

                                <!-- Title -->
                                <tr>
                                    <td align="center" style="padding-bottom:12px;">
                                        <div style="font-size:22px; font-weight:700; color:#0B1A2B;">Akun Anda Telah Disetujui! 🎉</div>
                                    </td>
                                </tr>

                                <!-- Subtitle -->
                                <tr>
                                    <td align="center" style="padding-bottom:32px;">
                                        <div style="font-size:14px; color:#6B7280; line-height:1.9;">
                                            Halo, <strong style="color:#0B1A2B;">{{ $dosenName }}</strong>!<br>
                                            Selamat, akun Anda di Rento telah diverifikasi<br>
                                            dan disetujui oleh administrator.<br>
                                            Anda sekarang dapat mengakses sistem.
                                        </div>
                                    </td>
                                </tr>

                                <!-- Info Card -->
                                <tr>
                                    <td style="padding-bottom:28px;">
                                        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#F8FAFF; border:1px solid #BFDBFE; border-radius:12px; overflow:hidden;">

                                            <tr>
                                                <td style="padding:16px 24px; border-bottom:1px solid #EFF6FF;">
                                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td style="font-size:12px; color:#6B7280; font-weight:500;">Nama</td>
                                                            <td align="right" style="font-size:13px; color:#0B1A2B; font-weight:700;">{{ $dosenName }}</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="padding:16px 24px; border-bottom:1px solid #EFF6FF;">
                                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td style="font-size:12px; color:#6B7280; font-weight:500;">Email</td>
                                                            <td align="right" style="font-size:13px; color:#2D4DA3; font-weight:700;">{{ $dosenEmail }}</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="padding:16px 24px; border-bottom:1px solid #EFF6FF;">
                                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td style="font-size:12px; color:#6B7280; font-weight:500;">Role</td>
                                                            <td align="right" style="font-size:13px; color:#0B1A2B; font-weight:700;">Dosen</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="padding:16px 24px; border-bottom:1px solid #EFF6FF;">
                                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td style="font-size:12px; color:#6B7280; font-weight:500;">Status</td>
                                                            <td align="right">
                                                                <span style="display:inline-block; background:#DCFCE7; color:#15803D; font-size:11px; font-weight:700; padding:4px 14px; border-radius:20px; border:1px solid #86EFAC;">
                                                                    ✓ &nbsp;Aktif
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="padding:16px 24px; border-bottom:1px solid #EFF6FF;">
                                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td style="font-size:12px; color:#6B7280; font-weight:500;">Disetujui oleh</td>
                                                            <td align="right" style="font-size:13px; color:#0B1A2B; font-weight:700;">{{ $approvedBy }}</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="padding:16px 24px;">
                                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td style="font-size:12px; color:#6B7280; font-weight:500;">Tanggal</td>
                                                            <td align="right" style="font-size:13px; color:#0B1A2B; font-weight:700;">{{ $approvedAt }}</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>

                                        </table>
                                    </td>
                                </tr>

                                <!-- Divider -->
                                <tr><td style="border-top:1px solid #F3F4F6; padding-bottom:28px;"></td></tr>

                                <!-- Info text -->
                                <tr>
                                    <td align="center" style="padding-bottom:24px;">
                                        <div style="font-size:14px; color:#374151; line-height:1.9; text-align:center;">
                                            Klik tombol di bawah untuk langsung masuk ke<br>
                                            <strong style="color:#0B1A2B;">Rento Admin Panel</strong>.<br>
                                            Login menggunakan akun Google yang telah Anda daftarkan.
                                        </div>
                                    </td>
                                </tr>

                                <!-- CTA Button -->
                                <tr>
                                    <td align="center" style="padding-bottom:28px;">
                                        <a href="{{ $loginUrl }}"
                                           style="display:inline-block; background:#2D4DA3; color:#ffffff; text-decoration:none; font-size:15px; font-weight:700; padding:16px 48px; border-radius:10px;">
                                            🚀 &nbsp; Masuk ke Rento Sekarang
                                        </a>
                                    </td>
                                </tr>

                                <!-- URL Fallback -->
                                <tr>
                                    <td style="padding-bottom:24px;">
                                        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#F9FAFB; border:1px solid #E5E7EB; border-radius:8px;">
                                            <tr>
                                                <td style="padding:14px 18px; text-align:center; font-size:11px; color:#6B7280; line-height:1.8;">
                                                    Jika tombol tidak berfungsi, copy link berikut ke browser Anda:<br>
                                                    <a href="{{ $loginUrl }}" style="color:#2D4DA3; text-decoration:none; word-break:break-all; font-weight:600;">{{ $loginUrl }}</a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <!-- Warning -->
                                <tr>
                                    <td style="padding-bottom:28px;">
                                        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#FFFBEB; border:1px solid #FDE68A; border-radius:10px;">
                                            <tr>
                                                <td style="padding:16px 20px; font-size:12px; color:#92400E; line-height:1.9;">
                                                    <strong style="color:#78350F;">⚠️ Penting:</strong> Gunakan akun Google yang sama saat mendaftar
                                                    (<strong>{{ $dosenEmail }}</strong>) untuk login ke sistem Rento.
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <!-- Ignore text -->
                                <tr>
                                    <td align="center">
                                        <div style="font-size:11px; color:#9CA3AF; line-height:1.6;">
                                            Jika Anda merasa tidak mendaftar ke sistem Rento, abaikan email ini.
                                        </div>
                                    </td>
                                </tr>

                            </table>
                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td style="background:#0B1A2B; border-radius:0 0 16px 16px; padding:24px 40px; text-align:center;">
                            <div style="font-size:18px; font-weight:800; margin-bottom:8px;">
                                <span style="color:#5B8AF5;">Ren</span><span style="color:#FF7A00;">to</span>
                            </div>
                            <div style="font-size:11px; color:#4B5563; line-height:1.6;">
                                &copy; {{ date('Y') }} Rento Rental Management System.<br>All rights reserved.
                            </div>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>