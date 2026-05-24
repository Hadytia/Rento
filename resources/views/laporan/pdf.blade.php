<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan {{ \Carbon\Carbon::create()->month((int)$bulan)->format('F') }} {{ $tahun }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
            font-size: 12px; color: #1e293b; background: #fff;
        }
        .page { padding: 36px 44px; }

        /* HEADER */
        .header-bg {
            background-color: #1e3a5f;
            border-radius: 8px;
            padding: 18px 22px;
            margin-bottom: 4px;
        }
        .header-bg table { width: 100%; border-collapse: collapse; }
        .brand-name { font-size: 24px; font-weight: 900; color: #fff; letter-spacing: 3px; }
        .brand-tagline { font-size: 9px; color: rgba(255,255,255,0.55); margin-top: 3px; letter-spacing: 1px; text-transform: uppercase; }
        .report-label { font-size: 16px; font-weight: 800; color: #fff; letter-spacing: 2px; text-align: right; }
        .report-period { font-size: 11px; color: rgba(255,255,255,0.65); margin-top: 4px; text-align: right; }
        .report-date { font-size: 10px; color: rgba(255,255,255,0.45); margin-top: 2px; text-align: right; }
        .accent { height: 3px; background-color: #2563eb; margin-bottom: 20px; }

        /* SECTION TITLE */
        .section-title { font-size: 9px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; }

        /* SUMMARY CARDS */
        .cards-table { width: 100%; border-collapse: separate; border-spacing: 8px 0; margin-bottom: 20px; }
        .card { width: 25%; vertical-align: top; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 14px; }
        .card-label { font-size: 9px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: .8px; margin-bottom: 6px; }
        .card-value { font-size: 16px; font-weight: 800; color: #0f172a; margin-bottom: 3px; }
        .card-sub { font-size: 10px; color: #64748b; }

        /* TOP PRODUK */
        .top-produk-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; border: 1px solid #e2e8f0; }
        .top-produk-table thead td { background: #1e3a5f; padding: 8px 12px; font-size: 10px; font-weight: 700; color: #fff; text-transform: uppercase; letter-spacing: .5px; }
        .top-produk-table tbody td { padding: 9px 12px; font-size: 11px; border-bottom: 1px solid #f1f5f9; }
        .top-produk-table tbody tr:last-child td { border-bottom: none; }
        .top-produk-table tbody tr:nth-child(even) td { background: #f8fafc; }
        .rank-badge { display: inline-block; width: 18px; height: 18px; border-radius: 5px; background: #f1f5f9; text-align: center; line-height: 18px; font-size: 10px; font-weight: 800; color: #64748b; }
        .rank-1 { background: #fef9c3; color: #a16207; }
        .rank-2 { background: #f1f5f9; color: #475569; }
        .rank-3 { background: #fff7ed; color: #c2410c; }

        /* TRANSAKSI TABLE */
        .trx-table { width: 100%; border-collapse: collapse; border: 1px solid #e2e8f0; margin-bottom: 20px; }
        .trx-table thead { display: table-header-group; } /* repeat thead setiap halaman */
        .trx-table thead td { background: #1e3a5f; padding: 8px 10px; font-size: 9px; font-weight: 700; color: #fff; text-transform: uppercase; letter-spacing: .5px; }
        .trx-table tbody td { padding: 8px 10px; font-size: 10px; border-bottom: 1px solid #f1f5f9; color: #374151; vertical-align: middle; }
        .trx-table tbody tr:last-child td { border-bottom: none; }
        .trx-table tbody tr:nth-child(even) td { background: #f8fafc; }

        /* BADGE */
        .badge { display: inline-block; padding: 2px 7px; border-radius: 10px; font-size: 9px; font-weight: 700; }
        .b-lunas   { background: #dcfce7; color: #15803d; }
        .b-pending { background: #fef9c3; color: #a16207; }
        .b-cancel  { background: #fee2e2; color: #b91c1c; }
        .b-none    { background: #f1f5f9; color: #64748b; }
        .b-active  { background: #dbeafe; color: #1d4ed8; }
        .b-overdue { background: #ffedd5; color: #c2410c; }

        /* FOOTER */
        .footer { border-top: 1px solid #e2e8f0; padding-top: 12px; margin-top: 20px; }
        .footer table { width: 100%; border-collapse: collapse; }
        .footer-brand { font-size: 12px; font-weight: 900; color: #1e3a5f; letter-spacing: 2px; }
        .footer-sub { font-size: 9px; color: #94a3b8; margin-top: 1px; }
    </style>
</head>
<body>
<div class="page">

    {{-- HEADER --}}
    <div class="header-bg">
        <table>
            <tr>
                <td>
                    <div class="brand-name">RENTO</div>
                    <div class="brand-tagline">Sistem Manajemen Rental</div>
                </td>
                <td>
                    <div class="report-label">LAPORAN BULANAN</div>
                    <div class="report-period">
                        {{ \Carbon\Carbon::create()->month((int)$bulan)->format('F') }} {{ $tahun }}
                    </div>
                    <div class="report-date">Dicetak: {{ now()->format('d F Y, H:i') }} WIB</div>
                </td>
            </tr>
        </table>
    </div>
    <div class="accent"></div>

    {{-- SUMMARY CARDS --}}
    <div class="section-title">Ringkasan</div>
    <table class="cards-table">
        <tr>
            <td class="card">
                <div class="card-label">Total Revenue</div>
                <div class="card-value" style="font-size:13px;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                <div class="card-sub">Dari transaksi lunas</div>
            </td>
            <td class="card">
                <div class="card-label">Total Transaksi</div>
                <div class="card-value">{{ $totalTransaksi }}</div>
                <div class="card-sub">Semua status</div>
            </td>
            <td class="card">
                <div class="card-label">Transaksi Lunas</div>
                <div class="card-value" style="color:#15803d;">{{ $transaksiLunas }}</div>
                <div class="card-sub">Payment settlement</div>
            </td>
            <td class="card">
                <div class="card-label">Belum Lunas</div>
                <div class="card-value" style="color:#a16207;">{{ $totalTransaksi - $transaksiLunas }}</div>
                <div class="card-sub">Perlu follow up</div>
            </td>
        </tr>
    </table>

    {{-- TOP PRODUK --}}
    <div class="section-title">Top Produk</div>
    <table class="top-produk-table">
        <thead>
            <tr>
                <td style="width:5%;">#</td>
                <td style="width:50%;">Nama Produk</td>
                <td style="width:20%;text-align:center;">Total Disewa</td>
                <td style="width:25%;text-align:right;">Total Revenue</td>
            </tr>
        </thead>
        <tbody>
            @forelse($topProduk as $i => $item)
            <tr>
                <td><span class="rank-badge rank-{{ $i+1 }}">{{ $i+1 }}</span></td>
                <td style="font-weight:600;color:#0f172a;">{{ $item->product->product_name ?? '-' }}</td>
                <td style="text-align:center;">{{ $item->total_sewa }}x</td>
                <td style="text-align:right;font-weight:700;color:#2563eb;">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align:center;padding:14px;color:#94a3b8;">Tidak ada data</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- TABEL TRANSAKSI --}}
    <div class="section-title">Detail Transaksi</div>
    <table class="trx-table">
        <thead>
            <tr>
                <td style="width:16%;">ID Transaksi</td>
                <td style="width:16%;">Customer</td>
                <td style="width:19%;">Produk</td>
                <td style="width:18%;">Periode</td>
                <td style="width:13%;text-align:right;">Total</td>
                <td style="width:9%;text-align:center;">Status</td>
                <td style="width:9%;text-align:center;">Payment</td>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksi as $trx)
            <tr>
                <td style="font-family:monospace;font-size:9px;font-weight:700;">{{ $trx->trx_code }}</td>
                <td>{{ $trx->user->name ?? '-' }}</td>
                <td>{{ $trx->product->product_name ?? '-' }}</td>
                <td style="font-size:9px;color:#64748b;white-space:nowrap;">
                    {{ \Carbon\Carbon::parse($trx->rental_start)->format('d M Y') }}
                    -
                    {{ \Carbon\Carbon::parse($trx->rental_end)->format('d M Y') }}
                </td>
                <td style="text-align:right;font-weight:700;white-space:nowrap;">
                    Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                </td>
                <td style="text-align:center;">
                    @php
                        $sc = match(strtolower($trx->trx_status)) {
                            'active'    => ['Aktif',      'b-active'],
                            'completed' => ['Selesai',    'b-lunas'],
                            'overdue'   => ['Terlambat',  'b-overdue'],
                            'cancelled' => ['Dibatalkan', 'b-cancel'],
                            default     => [$trx->trx_status, 'b-none'],
                        };
                    @endphp
                    <span class="badge {{ $sc[1] }}">{{ $sc[0] }}</span>
                </td>
                <td style="text-align:center;">
                    @if($trx->payment?->transaction_status === 'settlement')
                        <span class="badge b-lunas">Lunas</span>
                    @elseif($trx->payment?->transaction_status === 'pending')
                        <span class="badge b-pending">Pending</span>
                    @elseif($trx->payment)
                        <span class="badge b-cancel">Batal</span>
                    @else
                        <span class="badge b-none">-</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center;padding:20px;color:#94a3b8;">
                    Tidak ada transaksi di periode ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- FOOTER --}}
    <div class="footer">
        <table>
            <tr>
                <td>
                    <div class="footer-brand">RENTO</div>
                    <div class="footer-sub">Laporan ini digenerate otomatis oleh sistem</div>
                </td>
                <td style="text-align:right;font-size:9px;color:#94a3b8;vertical-align:bottom;">
                    {{ now()->format('d F Y, H:i') }} WIB
                </td>
            </tr>
        </table>
    </div>

</div>
</body>
</html>