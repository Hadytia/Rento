<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $trx->trx_code }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #1e293b;
            background: #ffffff;
        }
        .page { padding: 44px 48px; }

        /* HEADER */
        .header-table { width: 100%; border-collapse: collapse; }
        .header-td {
            background-color: #1e3a5f;
            border-radius: 10px;
            padding: 26px 30px;
        }
        .brand-name {
            font-size: 28px; font-weight: 900;
            color: #ffffff; letter-spacing: 3px;
        }
        .brand-tagline {
            font-size: 10px; color: rgba(255,255,255,0.55);
            margin-top: 4px; letter-spacing: 1.5px; text-transform: uppercase;
        }
        .invoice-label {
            font-size: 20px; font-weight: 800;
            color: #ffffff; letter-spacing: 3px; text-align: right;
        }
        .invoice-number {
            font-size: 11px; color: rgba(255,255,255,0.65);
            margin-top: 5px; text-align: right;
        }
        .invoice-date {
            font-size: 10px; color: rgba(255,255,255,0.45);
            margin-top: 2px; text-align: right;
        }

        /* ACCENT */
        .accent { height: 3px; background-color: #2563eb; border-radius: 2px; margin: 22px 0; }

        /* STATUS ROW */
        .status-table {
            width: 100%; border-collapse: collapse;
            background: #f8fafc; border: 1px solid #e2e8f0;
            border-radius: 8px; margin-bottom: 20px;
        }
        .status-table td { padding: 12px 18px; }
        .badge {
            display: inline-block; padding: 4px 14px;
            border-radius: 20px; font-size: 10px; font-weight: 700;
        }
        .badge-paid    { background: #dcfce7; color: #15803d; }
        .badge-pending { background: #fef9c3; color: #a16207; }
        .badge-cancel  { background: #fee2e2; color: #b91c1c; }

        /* INFO BOXES */
        .info-section { width: 100%; border-collapse: separate; border-spacing: 12px 0; margin-bottom: 20px; }
        .info-box {
            width: 50%; vertical-align: top;
            background: #f8fafc; border: 1px solid #e2e8f0;
            border-radius: 8px; padding: 16px 18px;
        }
        .info-box-label {
            font-size: 9px; font-weight: 800; color: #94a3b8;
            text-transform: uppercase; letter-spacing: 1px;
            padding-bottom: 8px; border-bottom: 1px solid #e2e8f0;
            margin-bottom: 12px;
        }
        .info-key {
            font-size: 9px; color: #94a3b8;
            text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 2px;
        }
        .info-val { font-size: 12px; color: #1e293b; font-weight: 500; margin-bottom: 9px; }
        .info-val-lg { font-size: 13px; color: #0f172a; font-weight: 700; margin-bottom: 9px; }

        /* SECTION LABEL */
        .section-label {
            font-size: 9px; font-weight: 800; color: #94a3b8;
            text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;
        }

        /* ITEMS TABLE */
        .items-wrap {
            border: 1px solid #e2e8f0; border-radius: 8px;
            overflow: hidden; margin-bottom: 20px;
        }
        .items-table { width: 100%; border-collapse: collapse; }
        .items-table thead td {
            background-color: #1e3a5f;
            padding: 10px 14px; font-size: 10px; font-weight: 700;
            color: #ffffff; text-transform: uppercase; letter-spacing: 0.5px;
        }
        .items-table tbody td {
            padding: 12px 14px; font-size: 12px; color: #374151;
            border-bottom: 1px solid #f1f5f9;
        }
        .items-table tbody tr:last-child td { border-bottom: none; }
        .item-main { font-weight: 700; color: #0f172a; }
        .item-sub  { font-size: 10px; color: #94a3b8; margin-top: 2px; }

        /* TOTAL */
        .total-outer { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .total-box {
            width: 42%; vertical-align: top;
            border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;
        }
        .total-inner { width: 100%; border-collapse: collapse; }
        .total-inner td { padding: 9px 16px; font-size: 11px; border-bottom: 1px solid #f1f5f9; }
        .total-inner tr:last-child td { border-bottom: none; }
        .total-inner .t-key { color: #64748b; }
        .total-inner .t-val { text-align: right; font-weight: 600; color: #1e293b; }
        .total-final { background-color: #1e3a5f; }
        .total-final .t-key { color: #ffffff; font-size: 13px; font-weight: 800; }
        .total-final .t-val { color: #ffffff; font-size: 14px; font-weight: 800; }

        /* NOTES */
        .notes-box {
            background: #fffbeb; border: 1px solid #fde68a;
            border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;
        }
        .notes-label {
            font-size: 9px; font-weight: 800; color: #a16207;
            text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px;
        }
        .notes-text { font-size: 11px; color: #78350f; }

        /* FOOTER */
        .footer-table { width: 100%; border-collapse: collapse; border-top: 1px solid #e2e8f0; padding-top: 14px; }
        .footer-table td { padding-top: 14px; font-size: 10px; color: #94a3b8; vertical-align: bottom; }
        .footer-brand { font-size: 14px; font-weight: 900; color: #1e3a5f; letter-spacing: 2px; margin-bottom: 3px; }
    </style>
</head>
<body>
<div class="page">

    {{-- HEADER --}}
    <table class="header-table"><tr>
        <td class="header-td">
            <table style="width:100%;border-collapse:collapse;"><tr>
                <td>
                    <div class="brand-name">RENTO</div>
                    <div class="brand-tagline">Sistem Manajemen Rental</div>
                </td>
                <td style="text-align:right;">
                    <div class="invoice-label">INVOICE</div>
                    <div class="invoice-number">#{{ $trx->trx_code }}</div>
                    <div class="invoice-date">{{ \Carbon\Carbon::parse($trx->created_date)->format('d F Y') }}</div>
                </td>
            </tr></table>
        </td>
    </tr></table>

    {{-- ACCENT --}}
    <div class="accent"></div>

    {{-- STATUS --}}
    <table class="status-table"><tr>
        <td style="font-size:12px;color:#64748b;">
            Status Transaksi: <strong style="color:#1e293b;">{{ $trx->trx_status }}</strong>
        </td>
        <td style="text-align:right;">
            @if($trx->payment)
                @if($trx->payment->transaction_status === 'settlement')
                    <span class="badge badge-paid">&#10003; Lunas</span>
                @elseif($trx->payment->transaction_status === 'pending')
                    <span class="badge badge-pending">Menunggu Pembayaran</span>
                @else
                    <span class="badge badge-cancel">Dibatalkan</span>
                @endif
            @else
                <span class="badge badge-pending">Belum Dibayar</span>
            @endif
        </td>
    </tr></table>

    {{-- INFO 2 KOLOM --}}
    <table class="info-section"><tr>
        <td class="info-box">
            <div class="info-box-label">Data Pelanggan</div>
            <div class="info-val-lg">{{ $trx->user->name ?? '-' }}</div>
            <div class="info-key">Email</div>
            <div class="info-val">{{ $trx->user->email ?? '-' }}</div>
            <div class="info-key">Telepon</div>
            <div class="info-val" style="margin-bottom:0;">{{ $trx->user->phone ?? '-' }}</div>
        </td>
        <td class="info-box">
            <div class="info-box-label">Detail Pembayaran</div>
            <div class="info-key">Metode</div>
            <div class="info-val">{{ $trx->payment->payment_type ?? $trx->payment_method ?? '-' }}</div>
            @if($trx->payment && $trx->payment->bank)
            <div class="info-key">Bank</div>
            <div class="info-val">{{ strtoupper($trx->payment->bank) }}</div>
            @endif
            @if($trx->payment && $trx->payment->va_number)
            <div class="info-key">Virtual Account</div>
            <div class="info-val">{{ $trx->payment->va_number }}</div>
            @endif
            @if($trx->payment && $trx->payment->settlement_time)
            <div class="info-key">Tanggal Bayar</div>
            <div class="info-val" style="margin-bottom:0;">{{ \Carbon\Carbon::parse($trx->payment->settlement_time)->format('d M Y, H:i') }}</div>
            @endif
        </td>
    </tr></table>

    {{-- DETAIL SEWA --}}
    <div class="section-label">Detail Sewa</div>
    <div class="items-wrap">
        <table class="items-table">
            <thead><tr>
                <td style="width:35%;">Barang</td>
                <td style="width:28%;">Periode Sewa</td>
                <td style="width:10%;text-align:center;">Durasi</td>
                <td style="width:13%;text-align:right;">Harga/Hari</td>
                <td style="width:14%;text-align:right;">Subtotal</td>
            </tr></thead>
            <tbody><tr>
                <td>
                    <div class="item-main">{{ $trx->product->product_name ?? '-' }}</div>
                    @if($trx->delivery_method)
                    <div class="item-sub">Pengiriman: {{ $trx->delivery_method }}</div>
                    @endif
                </td>
                <td>
                    {{ \Carbon\Carbon::parse($trx->rental_start)->format('d M Y') }}
                    -
                    {{ \Carbon\Carbon::parse($trx->rental_end)->format('d M Y') }}
                </td>
                <td style="text-align:center;">{{ $trx->total_days }} hari</td>
                <td style="text-align:right;">Rp {{ number_format($trx->product->rental_price ?? 0, 0, ',', '.') }}</td>
                <td style="text-align:right;">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
            </tr></tbody>
        </table>
    </div>

    {{-- TOTAL --}}
    <table class="total-outer"><tr>
        <td style="width:58%;"></td>
        <td class="total-box">
            <table class="total-inner">
                <tr>
                    <td class="t-key">Subtotal</td>
                    <td class="t-val">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="t-key">Sudah Dibayar</td>
                    <td class="t-val">Rp {{ number_format($trx->paid_amount, 0, ',', '.') }}</td>
                </tr>
                <tr class="total-final">
                    <td class="t-key">TOTAL</td>
                    <td class="t-val">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                </tr>
            </table>
        </td>
    </tr></table>

    {{-- NOTES --}}
    @if($trx->notes)
    <div class="notes-box">
        <div class="notes-label">Catatan</div>
        <div class="notes-text">{{ $trx->notes }}</div>
    </div>
    @endif

    {{-- FOOTER --}}
    <table class="footer-table"><tr>
        <td>
            <div class="footer-brand">RENTO</div>
            <div>Dokumen ini digenerate otomatis oleh sistem</div>
        </td>
        <td style="text-align:right;">
            <div>{{ now()->format('d F Y') }}</div>
            <div style="margin-top:2px;">{{ now()->format('H:i') }} WIB</div>
        </td>
    </tr></table>

</div>
</body>
</html>