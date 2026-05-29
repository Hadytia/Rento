<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Revenue per Kategori - Rento</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'DejaVu Sans',sans-serif; font-size:10px; color:#1e293b; background:#fff; }

        .header { background:#1e3a5f; padding:16px 24px; position:relative; overflow:hidden; }
        .header-accent { position:absolute; bottom:0; left:0; right:0; height:3px; background:#2563eb; }
        .header::after { content:''; position:absolute; top:0; right:0; width:200px; height:100%; background:linear-gradient(135deg,transparent 40%,rgba(59,130,246,.15) 100%); }
        .header-title { color:#fff; font-size:16px; font-weight:bold; }
        .header-sub { color:#93c5fd; font-size:8px; margin-top:2px; }
        .header-meta { color:#cbd5e1; font-size:8px; margin-top:5px; }

        /* SUMMARY TABLE */
        .summary-wrap { padding:10px 24px 6px; }
        .summary-table { width:100%; border-collapse:separate; border-spacing:5px 0; }
        .summary-table td { border-radius:6px; padding:8px 10px; border:1px solid #e2e8f0; vertical-align:top; }
        .s-val { font-size:14px; font-weight:bold; line-height:1; }
        .s-lbl { font-size:7px; color:#64748b; margin-top:3px; }

        /* CHART BARS */
        .chart-wrap { padding:8px 24px; }
        .chart-title { font-size:9px; font-weight:bold; color:#374151; margin-bottom:6px; text-transform:uppercase; letter-spacing:.5px; }
        .bar-row { display:block; margin-bottom:4px; }
        .bar-label-text { font-size:8px; color:#64748b; margin-bottom:2px; }
        .bar-track { width:100%; height:14px; background:#f1f5f9; border-radius:99px; overflow:hidden; }
        .bar-fill { height:100%; border-radius:99px; }

        /* TABLE */
        .table-wrap { padding:0 24px 12px; }
        table.main { width:100%; border-collapse:collapse; font-size:8.5px; }
        thead tr { background:#1e3a5f; color:#fff; }
        thead th { padding:7px 8px; text-align:left; font-weight:600; font-size:7.5px; text-transform:uppercase; letter-spacing:.3px; }
        thead th.right { text-align:right; }
        thead th.center { text-align:center; }
        tbody tr:nth-child(even) { background:#f8fafc; }
        tbody tr:nth-child(odd)  { background:#fff; }
        tbody td { padding:6px 8px; border-bottom:1px solid #f1f5f9; vertical-align:middle; }
        tbody td.right { text-align:right; }
        tbody td.center { text-align:center; }
        tfoot td { padding:7px 8px; border-top:2px solid #e2e8f0; background:#f8fafc; font-weight:bold; }
        tfoot td.right { text-align:right; }

        .badge { display:inline-block; padding:2px 6px; border-radius:99px; font-size:7px; font-weight:600; }
        .badge-ok { background:#dcfce7; color:#16a34a; }
        .badge-active { background:#eff6ff; color:#2563eb; }
        .badge-over { background:#fff7ed; color:#ea580c; }
        .badge-cancel { background:#f1f5f9; color:#64748b; }

        .pct-bar-wrap { display:block; }
        .pct-text { font-size:8px; font-weight:600; }

        .footer { position:fixed; bottom:0; left:0; right:0; padding:5px 24px; font-size:7.5px; color:#94a3b8; border-top:1px solid #e2e8f0; display:flex; justify-content:space-between; }
        .legend { padding:6px 24px 10px; font-size:7.5px; color:#64748b; }
    </style>
</head>
<body>

    <div class="header">
        <div class="header-accent"></div>
        <div style="display:flex; justify-content:space-between; align-items:flex-start;">
            <div>
                <div class="header-title">💰 LAPORAN REVENUE PER KATEGORI</div>
                <div class="header-sub">Sistem Manajemen Rental — Rento</div>
                <div class="header-meta">
                    Dicetak: {{ $tanggal }} WIB &nbsp;|&nbsp;
                    Tahun: {{ $year }}
                    @if($month) &nbsp;|&nbsp; Bulan: {{ $bulanName }} @endif
                </div>
            </div>
            <div style="text-align:right;">
                <div style="color:#93c5fd; font-size:8px;">Total Revenue</div>
                <div style="color:#fff; font-size:18px; font-weight:bold; line-height:1;">
                    Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}
                </div>
                <div style="color:#93c5fd; font-size:7px;">{{ $data->count() }} kategori</div>
            </div>
        </div>
    </div>

    {{-- SUMMARY --}}
    <div class="summary-wrap">
        <table class="summary-table">
            <tr>
                <td style="background:#eff6ff; border-color:#bfdbfe;">
                    <div class="s-val" style="color:#2563eb;">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</div>
                    <div class="s-lbl">Total Revenue</div>
                </td>
                <td style="background:#f0fdf4; border-color:#bbf7d0;">
                    <div class="s-val" style="color:#16a34a;">{{ number_format($summary['total_transaksi']) }}</div>
                    <div class="s-lbl">Total Transaksi</div>
                </td>
                <td style="background:#dcfce7; border-color:#bbf7d0;">
                    <div class="s-val" style="color:#16a34a;">{{ number_format($summary['total_completed']) }}</div>
                    <div class="s-lbl">Completed</div>
                </td>
                <td style="background:#eff6ff; border-color:#bfdbfe;">
                    <div class="s-val" style="color:#2563eb;">{{ number_format($summary['total_active']) }}</div>
                    <div class="s-lbl">Active/Overdue</div>
                </td>
                <td style="background:#f5f3ff; border-color:#ddd6fe;">
                    <div class="s-val" style="color:#7c3aed;">{{ $data->count() }}</div>
                    <div class="s-lbl">Jumlah Kategori</div>
                </td>
                <td style="background:#fefce8; border-color:#fde68a;">
                    <div class="s-val" style="color:#ca8a04; font-size:10px;">
                        {{ $summary['kategori_teratas'] ? $summary['kategori_teratas']->category_name : '-' }}
                    </div>
                    <div class="s-lbl">Kategori Teratas</div>
                </td>
            </tr>
        </table>
    </div>

    @php
    $barColors = ['#2563eb','#7c3aed','#16a34a','#ea580c','#ca8a04','#a21caf','#0891b2','#dc2626'];
    $grandTotal = $data->sum('total_revenue');
    $maxRev = $data->max('total_revenue');
    @endphp

    {{-- CHART --}}
    @if($data->count() > 0)
    <div class="chart-wrap">
        <div class="chart-title">Distribusi Revenue</div>
        <table style="width:100%; border-collapse:collapse;">
            @foreach($data->take(8) as $idx => $row)
            @php
                $pct   = $maxRev > 0 ? ($row->total_revenue / $maxRev * 100) : 0;
                $color = $barColors[$idx % count($barColors)];
                $share = $grandTotal > 0 ? round($row->total_revenue / $grandTotal * 100, 1) : 0;
            @endphp
            <tr>
                <td style="width:130px; padding:2px 6px 2px 0; font-size:7.5px; color:#374151; text-align:right;">{{ $row->category_name }}</td>
                <td style="padding:2px 6px 2px 0;">
                    <div style="width:100%; height:12px; background:#f1f5f9; border-radius:99px; overflow:hidden;">
                        <div style="width:{{ max($pct,4) }}%; height:100%; background:{{ $color }}; border-radius:99px;"></div>
                    </div>
                </td>
                <td style="width:80px; padding:2px 0; font-size:7.5px; color:{{ $color }}; font-weight:bold;">
                    Rp {{ number_format($row->total_revenue/1000,0,',','.') }}k ({{ $share }}%)
                </td>
            </tr>
            @endforeach
        </table>
    </div>
    @endif

    {{-- TABLE --}}
    <div class="table-wrap">
        <table class="main">
            <thead>
                <tr>
                    <th style="width:24px;">No</th>
                    <th style="min-width:100px;">Kategori</th>
                    <th class="right" style="width:60px;">Transaksi</th>
                    <th class="center" style="width:120px;">Status</th>
                    <th class="right" style="min-width:110px;">Total Revenue</th>
                    <th class="right" style="min-width:110px;">Rev. Completed</th>
                    <th class="right" style="width:70px;">Kontribusi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $i => $row)
                @php $kontribusi = $grandTotal > 0 ? round($row->total_revenue / $grandTotal * 100, 1) : 0; @endphp
                <tr>
                    <td style="color:#94a3b8;">{{ $i+1 }}</td>
                    <td style="font-weight:600;">{{ $row->category_name }}</td>
                    <td class="right" style="font-weight:600;">{{ number_format($row->total_transaksi) }}</td>
                    <td class="center">
                        @if($row->completed > 0)<span class="badge badge-ok">✓{{ $row->completed }}</span> @endif
                        @if($row->active > 0)<span class="badge badge-active">●{{ $row->active }}</span> @endif
                        @if($row->overdue > 0)<span class="badge badge-over">!{{ $row->overdue }}</span> @endif
                        @if($row->cancelled > 0)<span class="badge badge-cancel">✕{{ $row->cancelled }}</span> @endif
                    </td>
                    <td class="right" style="font-weight:700;">Rp {{ number_format($row->total_revenue,0,',','.') }}</td>
                    <td class="right" style="color:#16a34a;">Rp {{ number_format($row->revenue_completed,0,',','.') }}</td>
                    <td class="right" style="font-weight:600; color:#2563eb;">{{ $kontribusi }}%</td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center; padding:20px; color:#94a3b8;">Tidak ada data</td></tr>
                @endforelse
            </tbody>
            @if($data->count() > 0)
            <tfoot>
                <tr>
                    <td colspan="2" style="font-size:9px; font-weight:700;">TOTAL</td>
                    <td class="right" style="font-weight:700;">{{ number_format($summary['total_transaksi']) }}</td>
                    <td class="center" style="font-size:7.5px; color:#64748b;">✓{{ $summary['total_completed'] }} ●{{ $summary['total_active'] }}</td>
                    <td class="right" style="font-weight:800; font-size:11px; color:#2563eb;">Rp {{ number_format($summary['total_revenue'],0,',','.') }}</td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>

    <div class="legend">
        <strong>Keterangan:</strong>
        ✓ Completed &nbsp;|&nbsp; ● Active &nbsp;|&nbsp; ! Overdue &nbsp;|&nbsp; ✕ Cancelled &nbsp;|&nbsp;
        <em>Revenue = Completed (total_amount) + Active/Overdue (paid_amount)</em>
    </div>

    <div class="footer">
        <span>Rento — Sistem Manajemen Rental</span>
        <span>{{ $tanggal }} WIB</span>
    </div>
</body>
</html>