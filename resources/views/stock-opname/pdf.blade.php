<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Stock Opname - Rento</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #1e293b;
            background: #fff;
        }

        /* ── HEADER ── */
        .header {
            background: #1e3a5f;
            padding: 18px 24px;
            position: relative;
            overflow: hidden;
        }
        .header::after {
            content: '';
            position: absolute;
            top: 0; right: 0;
            width: 200px; height: 100%;
            background: linear-gradient(135deg, transparent 40%, rgba(59,130,246,0.15) 100%);
        }
        .header-accent {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 3px;
            background: #2563eb;
        }
        .header-title {
            color: #fff;
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        .header-sub {
            color: #93c5fd;
            font-size: 9px;
            margin-top: 3px;
        }
        .header-meta {
            color: #cbd5e1;
            font-size: 8px;
            margin-top: 6px;
        }

        /* ── SUMMARY CARDS ── */
        .summary-section {
            padding: 10px 24px 6px;
        }
        .summary-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 5px 0;
        }
        .summary-table td {
            width: 16.66%;
            border-radius: 6px;
            padding: 8px 10px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }
        .summary-card .val {
            font-size: 15px;
            font-weight: bold;
            line-height: 1;
        }
        .summary-card .lbl {
            font-size: 7px;
            color: #64748b;
            margin-top: 3px;
        }

        /* ── TABLE ── */
        .table-wrap {
            padding: 0 24px 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5px;
        }
        thead tr {
            background: #1e3a5f;
            color: #fff;
        }
        thead th {
            padding: 7px 6px;
            text-align: center;
            font-weight: 600;
            font-size: 7.5px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            white-space: nowrap;
        }
        thead th:nth-child(2),
        thead th:nth-child(3) {
            text-align: left;
        }
        tbody tr:nth-child(even) { background: #f8fafc; }
        tbody tr:nth-child(odd)  { background: #fff; }
        tbody td {
            padding: 5.5px 6px;
            border-bottom: 1px solid #f1f5f9;
            text-align: center;
            vertical-align: middle;
        }
        tbody td:nth-child(2) { text-align: left; font-weight: 600; color: #1e293b; }
        tbody td:nth-child(3) { text-align: left; color: #64748b; }

        .badge {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 20px;
            font-size: 7.5px;
            font-weight: 600;
        }
        .badge-normal  { background: #dcfce7; color: #16a34a; }
        .badge-kurang  { background: #fef9c3; color: #ca8a04; }
        .badge-kritis  { background: #fee2e2; color: #dc2626; }
        .badge-habis   { background: #f1f5f9; color: #64748b; }
        .badge-baik    { background: #dcfce7; color: #16a34a; }
        .badge-rusak   { background: #fee2e2; color: #dc2626; }

        .mono { font-family: 'DejaVu Sans Mono', monospace; }

        /* ── FOOTER ── */
        .footer {
            position: fixed;
            bottom: 0; left: 0; right: 0;
            padding: 6px 24px;
            font-size: 7.5px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
        }

        /* ── LEGEND ── */
        .legend {
            padding: 8px 24px 12px;
            font-size: 7.5px;
            color: #64748b;
        }
        .legend strong { color: #1e293b; }
    </style>
</head>
<body>

    {{-- HEADER --}}
    <div class="header">
        <div class="header-accent"></div>
        <div style="display:flex; justify-content:space-between; align-items:flex-start;">
            <div>
                <div class="header-title">📦 LAPORAN STOCK OPNAME</div>
                <div class="header-sub">Sistem Manajemen Rental — Rento</div>
                <div class="header-meta">
                    Dicetak: {{ $tanggal }} WIB
                    @if($search) &nbsp;|&nbsp; Pencarian: {{ $search }} @endif
                    @if($statusFilter) &nbsp;|&nbsp; Status: {{ $statusFilter }} @endif
                </div>
            </div>
            <div style="text-align:right;">
                <div style="color:#93c5fd; font-size:8px;">Total Produk</div>
                <div style="color:#fff; font-size:22px; font-weight:bold; line-height:1;">{{ $summary['total_produk'] }}</div>
                <div style="color:#93c5fd; font-size:7px;">item terdaftar</div>
            </div>
        </div>
    </div>

    {{-- SUMMARY CARDS --}}
    <div class="summary-section">
        <table class="summary-table">
            <tr>
                <td class="summary-card" style="background:#eff6ff; border-color:#bfdbfe;">
                    <div class="val" style="color:#2563eb;">{{ $summary['total_stok'] }}</div>
                    <div class="lbl">Total Stok</div>
                </td>
                <td class="summary-card" style="background:#f5f3ff; border-color:#ddd6fe;">
                    <div class="val" style="color:#7c3aed;">{{ $summary['total_on_rent'] }}</div>
                    <div class="lbl">Sedang Disewa</div>
                </td>
                <td class="summary-card" style="background:#f0fdf4; border-color:#bbf7d0;">
                    <div class="val" style="color:#16a34a;">{{ $summary['total_available'] }}</div>
                    <div class="lbl">Tersedia</div>
                </td>
                <td class="summary-card" style="background:#fef2f2; border-color:#fecaca;">
                    <div class="val" style="color:#dc2626;">{{ $summary['total_penalty'] }}</div>
                    <div class="lbl">Dalam Penalty</div>
                </td>
                <td class="summary-card" style="background:#fff7ed; border-color:#fed7aa;">
                    <div class="val" style="color:#ea580c;">{{ $summary['produk_kritis'] }}</div>
                    <div class="lbl">Produk Kritis</div>
                </td>
                <td class="summary-card" style="background:#fefce8; border-color:#fde68a;">
                    <div class="val" style="color:#ca8a04;">{{ $summary['produk_kurang'] }}</div>
                    <div class="lbl">Produk Kurang</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- TABEL --}}
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width:28px;">No</th>
                    <th style="min-width:120px;">Nama Produk</th>
                    <th style="min-width:80px;">Kategori</th>
                    <th>Kondisi</th>
                    <th>Stok Awal</th>
                    <th>Stok Saat Ini</th>
                    <th>Disewa</th>
                    <th>Tersedia</th>
                    <th>Penalty</th>
                    <th>Selisih</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $i => $row)
                @php
                $badgeStatus = match($row->stock_status) {
                    'Normal' => 'badge-normal',
                    'Kurang' => 'badge-kurang',
                    'Kritis' => 'badge-kritis',
                    default  => 'badge-habis',
                };
                $badgeKondisi = in_array(strtolower($row->condition ?? ''), ['baik','good'])
                    ? 'badge-baik' : (in_array(strtolower($row->condition ?? ''), ['rusak','damaged']) ? 'badge-rusak' : 'badge-habis');
                @endphp
                <tr>
                    <td style="color:#94a3b8;">{{ $i + 1 }}</td>
                    <td>{{ $row->product_name }}</td>
                    <td>{{ $row->category_name ?? '-' }}</td>
                    <td><span class="badge {{ $badgeKondisi }}">{{ ucfirst($row->condition ?? '-') }}</span></td>
                    <td class="mono" style="color:#64748b;">{{ $row->stock_initial }}</td>
                    <td class="mono" style="font-weight:bold;">{{ $row->stock }}</td>
                    <td class="mono" style="color:#7c3aed;">{{ $row->on_rent > 0 ? $row->on_rent : '—' }}</td>
                    <td class="mono" style="color:{{ $row->available > 0 ? '#16a34a' : '#94a3b8' }}; font-weight:600;">{{ $row->available }}</td>
                    <td class="mono" style="color:{{ $row->in_penalty > 0 ? '#dc2626' : '#94a3b8' }};">{{ $row->in_penalty > 0 ? $row->in_penalty : '—' }}</td>
                    <td class="mono" style="color:{{ $row->selisih != 0 ? '#ea580c' : '#94a3b8' }}; font-weight:{{ $row->selisih != 0 ? 'bold' : 'normal' }};">
                        {{ $row->selisih != 0 ? ($row->selisih > 0 ? '+' : '') . $row->selisih : '0' }}
                    </td>
                    <td><span class="badge {{ $badgeStatus }}">{{ $row->stock_status }}</span></td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" style="padding:20px; text-align:center; color:#94a3b8;">
                        Tidak ada data produk
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- LEGEND --}}
    <div class="legend">
        <strong>Keterangan:</strong>
        &nbsp; <strong style="color:#16a34a;">Normal</strong> = tersedia &gt;20% stok &nbsp;|&nbsp;
        <strong style="color:#ca8a04;">Kurang</strong> = tersedia ≤20% stok &nbsp;|&nbsp;
        <strong style="color:#dc2626;">Kritis</strong> = tersedia = 0 &nbsp;|&nbsp;
        <strong style="color:#64748b;">Habis</strong> = stok total = 0 &nbsp;|&nbsp;
        <em>Stok Awal = Stok saat ini + Disewa + Penalty (estimasi)</em>
    </div>

    {{-- FOOTER --}}
    <div class="footer">
        <span>Rento — Sistem Manajemen Rental</span>
        <span>Dicetak: {{ $tanggal }} WIB</span>
        <span>Halaman <span class="pagenum"></span></span>
    </div>

</body>
</html>