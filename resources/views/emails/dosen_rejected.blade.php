<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Tidak Disetujui - Rento</title>
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
                                        <div style="width:72px; height:72px; background:#EF4444; border-radius:50%; text-align:center; line-height:72px; font-size:36px; color:white; margin:0 auto;">
                                            ✕
                                        </div>
                                    </td>
                                </tr>

                                <!-- Title -->
                                <tr>
                                    <td align="center" style="padding-bottom:12px;">
                                        <div style="font-size:22px; font-weight:700; color:#0B1A2B;">Pendaftaran Tidak Disetujui</div>
                                    </td>
                                </tr>

                                <!-- Subtitle -->
                                <tr>
                                    <td align="center" style="padding-bottom:32px;">
                                        <div style="font-size:14px; color:#6B7280; line-height:1.9;">
                                            Halo, <strong style="color:#0B1A2B;">{{ $dosenName }}</strong>!<br>
                                            Kami mohon maaf, pendaftaran akun Anda di Rento<br>
                                            tidak dapat disetujui oleh administrator.<br>
                                            Silakan hubungi admin jika Anda merasa ini adalah kesalahan.
                                        </div>
                                    </td>
                                </tr>

                                <!-- Info Card -->
                                <tr>
                                    <td style="padding-bottom:28px;">
                                        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#FFF5F5; border:1px solid #FECACA; border-radius:12px; overflow:hidden;">

                                            <tr>
                                                <td style="padding:16px 24px; border-bottom:1px solid #FEE2E2;">
                                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td style="font-size:12px; color:#6B7280; font-weight:500;">Nama</td>
                                                            <td align="right" style="font-size:13px; color:#0B1A2B; font-weight:700;">{{ $dosenName }}</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="padding:16px 24px; border-bottom:1px solid #FEE2E2;">
                                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td style="font-size:12px; color:#6B7280; font-weight:500;">Email</td>
                                                            <td align="right" style="font-size:13px; color:#DC2626; font-weight:700;">{{ $dosenEmail }}</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="padding:16px 24px; border-bottom:1px solid #FEE2E2;">
                                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td style="font-size:12px; color:#6B7280; font-weight:500;">Status</td>
                                                            <td align="right">
                                                                <span style="display:inline-block; background:#FEE2E2; color:#DC2626; font-size:11px; font-weight:700; padding:4px 14px; border-radius:20px; border:1px solid #FECACA;">
                                                                    ✕ &nbsp;Ditolak
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="padding:16px 24px; border-bottom:1px solid #FEE2E2;">
                                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td style="font-size:12px; color:#6B7280; font-weight:500;">Ditolak oleh</td>
                                                            <td align="right" style="font-size:13px; color:#0B1A2B; font-weight:700;">{{ $rejectedBy }}</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="padding:16px 24px;">
                                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td style="font-size:12px; color:#6B7280; font-weight:500;">Tanggal</td>
                                                            <td align="right" style="font-size:13px; color:#0B1A2B; font-weight:700;">{{ $rejectedAt }}</td>
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
                                            Jika Anda merasa ini adalah kesalahan atau ingin<br>
                                            mengajukan pertanyaan, silakan hubungi administrator<br>
                                            melalui email di bawah ini.
                                        </div>
                                    </td>
                                </tr>

                                <!-- CTA Button -->
                                <tr>
                                    <td align="center" style="padding-bottom:28px;">
                                        <a href="mailto:{{ $adminEmail }}"
                                           style="display:inline-block; background:#374151; color:#ffffff; text-decoration:none; font-size:15px; font-weight:700; padding:16px 48px; border-radius:10px;">
                                            ✉️ &nbsp; Hubungi Administrator
                                        </a>
                                    </td>
                                </tr>

                                <!-- Info box -->
                                <tr>
                                    <td style="padding-bottom:28px;">
                                        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#F9FAFB; border:1px solid #E5E7EB; border-radius:10px;">
                                            <tr>
                                                <td style="padding:16px 20px; font-size:12px; color:#6B7280; line-height:1.9; text-align:center;">
                                                    Atau kirim email langsung ke:<br>
                                                    <a href="mailto:{{ $adminEmail }}" style="color:#2D4DA3; text-decoration:none; font-weight:700;">{{ $adminEmail }}</a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <!-- Ignore text -->
                                <tr>
                                    <td align="center">
                                        <div style="font-size:11px; color:#9CA3AF; line-height:1.6;">
                                            Email ini dikirim otomatis oleh sistem Rento. Mohon tidak membalas email ini.
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