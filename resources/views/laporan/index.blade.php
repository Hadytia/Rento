@extends('layouts.app')

@section('page_title', 'Laporan Owner')

@section('content')

<style>
    /* ── FILTER BAR ── */
    .filter-bar {
        display: flex; align-items: center; gap: 12px;
        margin-bottom: 24px; flex-wrap: wrap;
    }
    .filter-select {
        height: 42px; padding: 0 14px;
        background: white; border: 1px solid #e2e8f0;
        border-radius: 10px; font-family: Inter, sans-serif;
        font-size: 13px; color: #1e293b; cursor: pointer;
        outline: none; transition: border-color .2s;
    }
    .filter-select:focus { border-color: #4F46E5; }
    .btn-filter {
        height: 42px; padding: 0 20px;
        background: linear-gradient(135deg, #4F46E5, #6366F1);
        border: none; border-radius: 10px; color: white;
        font-family: Inter, sans-serif; font-size: 13px;
        font-weight: 600; cursor: pointer;
    }
    .btn-pdf {
        height: 42px; padding: 0 20px;
        background: linear-gradient(135deg, #dc2626, #ef4444);
        border: none; border-radius: 10px; color: white;
        font-family: Inter, sans-serif; font-size: 13px;
        font-weight: 600; cursor: pointer; text-decoration: none;
        display: inline-flex; align-items: center; gap: 8px;
        margin-left: auto;
    }

    /* ── SUMMARY CARDS ── */
    .cards-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px; margin-bottom: 24px;
    }
    .card {
        background: white; border-radius: 14px;
        padding: 20px 22px; border: 1px solid #e2e8f0;
        box-shadow: 0 1px 4px rgba(0,0,0,.05);
    }
    .card-label {
        font-size: 11px; font-weight: 700; color: #94a3b8;
        text-transform: uppercase; letter-spacing: .8px; margin-bottom: 10px;
    }
    .card-value {
        font-size: 22px; font-weight: 800; color: #0f172a;
        letter-spacing: -0.5px; margin-bottom: 6px;
    }
    .card-sub { font-size: 12px; color: #64748b; }
    .card-icon {
        width: 40px; height: 40px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 14px;
    }
    .card-icon svg { width: 20px; height: 20px; }
    .icon-blue   { background: #eff6ff; color: #2563eb; }
    .icon-green  { background: #f0fdf4; color: #16a34a; }
    .icon-yellow { background: #fefce8; color: #ca8a04; }
    .icon-purple { background: #f5f3ff; color: #7c3aed; }

    /* ── GRID 2 COL ── */
    .grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px; margin-bottom: 24px;
    }

    /* ── CHART BOX ── */
    .box {
        background: white; border-radius: 14px;
        padding: 22px 24px; border: 1px solid #e2e8f0;
        box-shadow: 0 1px 4px rgba(0,0,0,.05);
    }
    .box-title {
        font-size: 14px; font-weight: 700; color: #0f172a;
        margin-bottom: 18px; letter-spacing: -0.2px;
    }

    /* ── TOP PRODUK ── */
    .produk-item {
        display: flex; align-items: center; gap: 12px;
        padding: 10px 0; border-bottom: 1px solid #f1f5f9;
    }
    .produk-item:last-child { border-bottom: none; }
    .produk-rank {
        width: 26px; height: 26px; border-radius: 8px;
        background: #f1f5f9; display: flex; align-items: center;
        justify-content: center; font-size: 11px; font-weight: 800;
        color: #64748b; flex-shrink: 0;
    }
    .produk-rank.rank-1 { background: #fef9c3; color: #a16207; }
    .produk-rank.rank-2 { background: #f1f5f9; color: #475569; }
    .produk-rank.rank-3 { background: #fff7ed; color: #c2410c; }
    .produk-name { font-size: 13px; font-weight: 600; color: #1e293b; flex: 1; }
    .produk-count { font-size: 12px; color: #64748b; }
    .produk-revenue { font-size: 12px; font-weight: 700; color: #2563eb; }

    /* ── STATUS PIE LEGEND ── */
    .legend-item {
        display: flex; align-items: center; gap: 10px;
        padding: 8px 0; border-bottom: 1px solid #f1f5f9;
        font-size: 13px;
    }
    .legend-item:last-child { border-bottom: none; }
    .legend-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
    .legend-label { flex: 1; color: #374151; font-weight: 500; }
    .legend-val { font-weight: 700; color: #0f172a; }

    /* ── TABLE ── */
    .table-box {
        background: white; border-radius: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 4px rgba(0,0,0,.05);
        overflow: hidden; margin-bottom: 24px;
    }
    .table-box-header {
        padding: 18px 22px; border-bottom: 1px solid #e2e8f0;
        display: flex; align-items: center; justify-content: space-between;
    }
    .table-scroll { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; }
    thead th {
        background: #f8fafc; padding: 11px 16px;
        font-size: 11px; font-weight: 700; color: #64748b;
        text-transform: uppercase; letter-spacing: .5px;
        text-align: left; border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }
    tbody td {
        padding: 13px 16px; font-size: 13px; color: #374151;
        border-bottom: 1px solid #f1f5f9; vertical-align: middle;
    }
    tbody tr:last-child td { border-bottom: none; }
    tbody tr:hover td { background: #fafbff; }
    .badge-sm {
        display: inline-block; padding: 3px 10px;
        border-radius: 20px; font-size: 11px; font-weight: 600;
    }
    .b-lunas   { background: #dcfce7; color: #15803d; }
    .b-pending { background: #fef9c3; color: #a16207; }
    .b-cancel  { background: #fee2e2; color: #b91c1c; }
    .b-none    { background: #f1f5f9; color: #64748b; }
</style>

{{-- FILTER BAR --}}
<form method="GET" action="{{ route('laporan.index') }}">
    <div class="filter-bar">
        <select name="bulan" class="filter-select">
            @foreach(range(1,12) as $m)
                <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                </option>
            @endforeach
        </select>

        <select name="tahun" class="filter-select">
            @foreach($tahunList as $y)
                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
        </select>

        <button type="submit" class="btn-filter">Tampilkan</button>

        <a href="{{ route('laporan.exportPdf', ['bulan' => $bulan, 'tahun' => $tahun]) }}"
           class="btn-pdf">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14,2 14,8 20,8"/>
            </svg>
            Download PDF
        </a>
    </div>
</form>

{{-- SUMMARY CARDS --}}
<div class="cards-grid">
    <div class="card">
        <div class="card-icon icon-green">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <line x1="12" y1="1" x2="12" y2="23"/>
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
            </svg>
        </div>
        <div class="card-label">Total Revenue</div>
        <div class="card-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
        <div class="card-sub">Dari transaksi lunas</div>
    </div>
    <div class="card">
        <div class="card-icon icon-blue">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14,2 14,8 20,8"/>
            </svg>
        </div>
        <div class="card-label">Total Transaksi</div>
        <div class="card-value">{{ $totalTransaksi }}</div>
        <div class="card-sub">Semua status</div>
    </div>
    <div class="card">
        <div class="card-icon icon-green">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <polyline points="20,6 9,17 4,12"/>
            </svg>
        </div>
        <div class="card-label">Transaksi Lunas</div>
        <div class="card-value">{{ $transaksiLunas }}</div>
        <div class="card-sub">Payment settlement</div>
    </div>
    <div class="card">
        <div class="card-icon icon-yellow">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/>
                <polyline points="12,6 12,12 16,14"/>
            </svg>
        </div>
        <div class="card-label">Belum Lunas</div>
        <div class="card-value">{{ $transaksiPending }}</div>
        <div class="card-sub">Perlu follow up</div>
    </div>
</div>

{{-- CHART + TOP PRODUK --}}
<div class="grid-2">

    {{-- CHART REVENUE --}}
    <div class="box">
        <div class="box-title">Revenue 12 Bulan Terakhir</div>
        <<canvas id="revenueChart" height="200" style="max-height:220px;"></canvas>
    </div>

    {{-- TOP PRODUK --}}
    <div class="box">
        <div class="box-title">Top Produk Bulan Ini</div>
        @forelse($topProduk as $i => $item)
        <div class="produk-item">
            <div class="produk-rank rank-{{ $i+1 }}">{{ $i+1 }}</div>
            <div class="produk-name">{{ $item->product->product_name ?? '-' }}</div>
            <div class="produk-count">{{ $item->total_sewa }}x</div>
            <div class="produk-revenue">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</div>
        </div>
        @empty
        <p style="color:#94a3b8;font-size:13px;text-align:center;padding:20px 0;">Belum ada data</p>
        @endforelse
    </div>

</div>

{{-- STATUS BREAKDOWN + PIE --}}
<div class="grid-2" style="margin-bottom:24px;">

    {{-- PIE CHART --}}
    <div class="box">
        <div class="box-title">Status Transaksi</div>
        <canvas id="statusChart" height="200" style="max-height:220px;"></canvas>
    </div>

    {{-- LEGEND --}}
    <div class="box">
        <div class="box-title">Breakdown Status</div>
        @php
            $statusConfig = [
                'Active'    => ['Aktif',      '#3b82f6'],
                'Completed' => ['Selesai',    '#22c55e'],
                'Overdue'   => ['Terlambat',  '#f97316'],
                'Cancelled' => ['Dibatalkan', '#ef4444'],
            ];
        @endphp
        @foreach($statusConfig as $key => [$label, $color])
        @php $count = $statusBreakdown[$key] ?? 0; @endphp
        <div class="legend-item">
            <div class="legend-dot" style="background:{{ $color }};"></div>
            <div class="legend-label">{{ $label }}</div>
            <div class="legend-val">{{ $count }}</div>
        </div>
        @endforeach
    </div>

</div>

{{-- TABEL TRANSAKSI --}}
<div class="table-box">
    <div class="table-box-header">
        <span style="font-size:14px;font-weight:700;color:#0f172a;">
            Transaksi —
            {{ \Carbon\Carbon::create()->month((int)$bulan)->format('F') }} {{ $tahun }}
            <span style="background:#eff6ff;color:#2563eb;font-size:12px;font-weight:700;padding:3px 10px;border-radius:8px;margin-left:8px;">
                {{ $transaksi->count() }}
            </span>
        </span>
    </div>
    <div class="table-scroll">
        <table>
            <thead>
                <tr>
                    <th>ID Transaksi</th>
                    <th>Customer</th>
                    <th>Produk</th>
                    <th>Periode</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Payment</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksi as $trx)
                <tr>
                    <td style="font-family:monospace;font-weight:700;color:#0f172a;font-size:12px;">
                        {{ $trx->trx_code }}
                    </td>
                    <td>{{ $trx->user->name ?? '-' }}</td>
                    <td>{{ $trx->product->product_name ?? '-' }}</td>
                    <td style="font-size:12px;color:#64748b;white-space:nowrap;">
                        {{ \Carbon\Carbon::parse($trx->rental_start)->format('d M') }}
                        -
                        {{ \Carbon\Carbon::parse($trx->rental_end)->format('d M Y') }}
                    </td>
                    <td style="font-weight:700;white-space:nowrap;">
                        Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                    </td>
                    <td>
                        @php
                            $sc = match(strtolower($trx->trx_status)) {
                                'active'    => ['Aktif',      'b-lunas'],
                                'completed' => ['Selesai',    'b-lunas'],
                                'overdue'   => ['Terlambat',  'b-pending'],
                                'cancelled' => ['Dibatalkan', 'b-cancel'],
                                default     => [$trx->trx_status, 'b-none'],
                            };
                        @endphp
                        <span class="badge-sm {{ $sc[1] }}">{{ $sc[0] }}</span>
                    </td>
                    <td>
                        @php $pay = $trx->payment; @endphp
                        @if($pay?->transaction_status === 'settlement')
                            <span class="badge-sm b-lunas">Lunas</span>
                        @elseif($pay?->transaction_status === 'pending')
                            <span class="badge-sm b-pending">Pending</span>
                        @elseif($pay)
                            <span class="badge-sm b-cancel">Batal</span>
                        @else
                            <span class="badge-sm b-none">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;padding:40px;color:#94a3b8;">
                    Tidak ada transaksi di periode ini.
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- CHART JS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Revenue Chart
    const revenueData = @json($revenuePerBulan);
    new Chart(document.getElementById('revenueChart'), {
        type: 'bar',
        data: {
            labels: revenueData.map(d => d.bulan),
            datasets: [{
                label: 'Revenue',
                data: revenueData.map(d => d.total),
                backgroundColor: 'rgba(79, 70, 229, 0.15)',
                borderColor: '#4F46E5',
                borderWidth: 2,
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: v => 'Rp ' + (v/1000000).toFixed(1) + 'jt',
                        font: { size: 11 }
                    },
                    grid: { color: '#f1f5f9' }
                },
                x: { ticks: { font: { size: 10 } }, grid: { display: false } }
            }
        }
    });

    // Status Chart
    const statusData = @json($statusBreakdown);
    const statusLabels = { Active: 'Aktif', Completed: 'Selesai', Overdue: 'Terlambat', Cancelled: 'Dibatalkan' };
    const statusColors = { Active: '#3b82f6', Completed: '#22c55e', Overdue: '#f97316', Cancelled: '#ef4444' };
    const keys = Object.keys(statusData);

    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: keys.map(k => statusLabels[k] || k),
            datasets: [{
                data: keys.map(k => statusData[k]),
                backgroundColor: keys.map(k => statusColors[k] || '#94a3b8'),
                borderWidth: 0,
                hoverOffset: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { font: { size: 11 }, padding: 16 }
                }
            }
        }
    });
</script>

@endsection