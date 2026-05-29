<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi{{ $status !== 'all' ? ' - ' . $statusLabel : '' }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica Neue', Arial, sans-serif; font-size: 12px; color: #1e293b; }
        .page { padding: 36px 44px; }

        .header-bg { background-color: #1e3a5f; border-radius: 8px; padding: 18px 22px; margin-bottom: 4px; }
        .header-bg table { width: 100%; border-collapse: collapse; }
        .brand-name { font-size: 22px; font-weight: 900; color: #fff; letter-spacing: 3px; }
        .brand-tagline { font-size: 9px; color: rgba(255,255,255,0.55); margin-top: 3px; letter-spacing: 1px; text-transform: uppercase; }
        .report-label { font-size: 15px; font-weight: 800; color: #fff; letter-spacing: 2px; text-align: right; }
        .report-sub { font-size: 10px; color: rgba(255,255,255,0.65); text-align: right; margin-top: 4px; }
        .accent { height: 3px; background: #2563eb; margin-bottom: 20px; }

        .info-row { display: table; width: 100%; margin-bottom: 16px; }
        .info-chip {
            display: inline-block; padding: 4px 12px; border-radius: 20px;
            font-size: 11px; font-weight: 700; margin-right: 8px;
        }
        .chip-all      { background: #eff6ff; color: #2563eb; }
        .chip-active   { background: #dbeafe; color: #1d4ed8; }
        .chip-completed{ background: #dcfce7; color: #15803d; }
        .chip-overdue  { background: #ffedd5; color: #c2410c; }
        .chip-cancelled{ background: #fee2e2; color: #b91c1c; }

        .section-title { font-size: 9px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; }

        .trx-table { width: 100%; border-collapse: collapse; border: 1px solid #e2e8f0; }
        .trx-table thead td { background: #1e3a5f; padding: 8px 10px; font-size: 9px; font-weight: 700; color: #fff; text-transform: uppercase; letter-spacing: .5px; }
        .trx-table tbody td { padding: 8px 10px; font-size: 10px; border-bottom: 1px solid #f1f5f9; color: #374151; vertical-align: middle; }
        .trx-table tbody tr:last-child td { border-bottom: none; }
        .trx-table tbody tr:nth-child(even) td { background: #f8fafc; }

        .badge { display: inline-block; padding: 2px 7px; border-radius: 10px; font-size: 9px; font-weight: 700; }
        .b-lunas   { background: #dcfce7; color: #15803d; }
        .b-pending { background: #fef9c3; color: #a16207; }
        .b-cancel  { background: #fee2e2; color: #b91c1c; }
        .b-active  { background: #dbeafe; color: #1d4ed8; }
        .b-overdue { background: #ffedd5; color: #c2410c; }
        .b-none    { background: #f1f5f9; color: #64748b; }

        .footer { border-top: 1px solid #e2e8f0; padding-top: 12px; margin-top: 20px; }
        .footer table { width: 100%; border-collapse: collapse; }
        .footer-brand { font-size: 12px; font-weight: 900; color: #1e3a5f; letter-spacing: 2px; }
        .footer-sub { font-size: 9px; color: #94a3b8; margin-top: 1px; }
    </style>
</head>
<body>
<div class="page">

    <div class="header-bg">
        <table><tr>
            <td>
                <div class="brand-name">RENTO</div>
                <div class="brand-tagline">Sistem Manajemen Rental</div>
            </td>
            <td>
                <div class="report-label">LAPORAN TRANSAKSI</div>
                <div class="report-sub">
                    Filter: {{ $statusLabel }}
                    @if($search) · Pencarian: "{{ $search }}" @endif
                </div>
                <div class="report-sub">Dicetak: {{ now()->format('d F Y, H:i') }} WIB</div>
            </td>
        </tr></table>
    </div>
    <div class="accent"></div>

    {{-- Info filter --}}
    <div style="margin-bottom:16px;">
        <span style="font-size:12px;color:#64748b;">
            Total: <strong style="color:#0f172a;">{{ $transactions->count() }} transaksi</strong>
        </span>
        @php
            $chipClass = match(strtolower($status)) {
                'active'    => 'chip-active',
                'completed' => 'chip-completed',
                'overdue'   => 'chip-overdue',
                'cancelled' => 'chip-cancelled',
                default     => 'chip-all',
            };
        @endphp
        <span class="info-chip {{ $chipClass }}" style="margin-left:10px;">{{ $statusLabel }}</span>
    </div>

    <div class="section-title">Detail Transaksi</div>
    <table class="trx-table">
        <thead><tr>
            <td style="width:15%;">ID Transaksi</td>
            <td style="width:16%;">Customer</td>
            <td style="width:18%;">Produk</td>
            <td style="width:18%;">Periode</td>
            <td style="width:12%;text-align:right;">Total</td>
            <td style="width:10%;text-align:center;">Status</td>
            <td style="width:11%;text-align:center;">Payment</td>
        </tr></thead>
        <tbody>
            @forelse($transactions as $trx)
            <tr>
                <td style="font-family:monospace;font-size:9px;font-weight:700;">{{ $trx->trx_code }}</td>
                <td>{{ $trx->user->name ?? '-' }}</td>
                <td>{{ $trx->product->product_name ?? '-' }}</td>
                <td style="font-size:9px;color:#64748b;">
                    {{ \Carbon\Carbon::parse($trx->rental_start)->format('d M Y') }} -
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
            <tr><td colspan="7" style="text-align:center;padding:20px;color:#94a3b8;">Tidak ada transaksi.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <table><tr>
            <td>
                <div class="footer-brand">RENTO</div>
                <div class="footer-sub">Dokumen ini digenerate otomatis oleh sistem</div>
            </td>
            <td style="text-align:right;font-size:9px;color:#94a3b8;vertical-align:bottom;">
                {{ now()->format('d F Y, H:i') }} WIB
            </td>
        </tr></table>
    </div>

</div>
</body>
</html>