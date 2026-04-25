@extends('layouts.app')

@section('page_title', 'Dashboard')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;1,400&family=Inter:wght@400;500;600;700&display=swap');

    .welcome-block { margin-bottom: 28px; }
    .welcome-block h1 { font-family: 'Inter', sans-serif; font-size: 22px; font-weight: 700; color: #1E1E1E; margin: 0 0 6px 0; }
    .welcome-block p { font-family: 'Playfair Display', Georgia, serif; font-style: italic; font-size: 15px; color: #6B6B6B; margin: 0; line-height: 1.7; max-width: 620px; }

    .stat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 20px; }

    .stat-card { background: white; border-radius: 12px; padding: 20px; border: 1.5px solid #E5E5E5; position: relative; transition: box-shadow 0.2s; }
    .stat-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.08); }
    .stat-card.blue   { border-color: #2D4DA3; }
    .stat-card.green  { border-color: #22C55E; }
    .stat-card.orange { border-color: #F97316; }
    .stat-card.red    { border-color: #EF4444; }

    .stat-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }
    .stat-label { font-family: Inter, sans-serif; font-size: 13px; color: #6B6B6B; font-weight: 500; }
    .stat-icon { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; }
    .stat-icon.blue   { background: #EEF2FF; color: #2D4DA3; }
    .stat-icon.green  { background: #F0FDF4; color: #22C55E; }
    .stat-icon.orange { background: #FFF7ED; color: #F97316; }
    .stat-icon.red    { background: #FEF2F2; color: #EF4444; }
    .stat-icon svg { width: 16px; height: 16px; }

    .stat-value { font-family: Inter, sans-serif; font-size: 26px; font-weight: 700; color: #1E1E1E; margin-bottom: 4px; letter-spacing: -0.5px; }
    .stat-sub { font-family: Inter, sans-serif; font-size: 12px; color: #9E9E9E; }
    .stat-sub.positive { color: #22C55E; }
    .stat-sub.warning  { color: #EF4444; }

    /* ── Chart Section ── */
    .chart-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 16px; margin-bottom: 20px; }
    .chart-panel {
        background: white; border-radius: 14px; padding: 22px;
        box-shadow: 0 2px 12px rgba(0,0,0,.07); border: 1px solid #F1F5F9;
    }
    .chart-panel-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 18px; }
    .chart-panel-title { font-family: Inter, sans-serif; font-size: 14px; font-weight: 700; color: #0F172A; margin: 0; }
    .chart-panel-sub   { font-family: Inter, sans-serif; font-size: 12px; color: #94A3B8; margin: 3px 0 0 0; }
    .chart-wrap { position: relative; }

    /* Pie legend */
    .pie-legend { display: flex; flex-direction: column; gap: 10px; margin-top: 18px; }
    .legend-item { display: flex; align-items: center; justify-content: space-between; }
    .legend-left { display: flex; align-items: center; gap: 8px; }
    .legend-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
    .legend-label { font-family: Inter, sans-serif; font-size: 12px; color: #64748B; }
    .legend-val { font-family: Inter, sans-serif; font-size: 12px; font-weight: 700; color: #0F172A; }

    /* ── Bottom Grid ── */
    .bottom-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

    .panel { background: white; border-radius: 12px; padding: 20px; border: 1px solid #E5E5E5; }
    .panel-title { font-family: Inter, sans-serif; font-size: 15px; font-weight: 700; color: #1E1E1E; margin-bottom: 18px; }

    .trx-item { display: flex; align-items: center; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #F5F5F5; }
    .trx-item:last-child { border-bottom: none; }
    .trx-left .trx-name { font-family: Inter, sans-serif; font-size: 13px; font-weight: 600; color: #1E1E1E; }
    .trx-left .trx-item-name { font-family: Inter, sans-serif; font-size: 12px; color: #9E9E9E; margin-top: 2px; }
    .trx-right { text-align: right; }
    .trx-amount { font-family: Inter, sans-serif; font-size: 13px; font-weight: 700; color: #2D4DA3; margin-bottom: 4px; }
    .trx-badge { display: inline-block; border-radius: 20px; padding: 2px 10px; font-size: 11px; font-weight: 600; font-family: Inter, sans-serif; }
    .trx-active    { background: #EFF6FF; color: #2D4DA3; }
    .trx-completed { background: #ECFDF5; color: #059669; }
    .trx-overdue   { background: #FFF7ED; color: #EA580C; }
    .trx-cancelled { background: #FEF2F2; color: #DC2626; }

    .action-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .action-card { border: 1px solid #E5E5E5; border-radius: 10px; padding: 20px 16px; display: flex; flex-direction: column; align-items: center; gap: 10px; cursor: pointer; transition: background 0.15s, border-color 0.15s; text-decoration: none; }
    .action-card:hover { background: #F5F7FF; border-color: #C7D2FE; }
    .action-icon { width: 44px; height: 44px; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
    .action-icon.blue   { background: #EEF2FF; color: #2D4DA3; }
    .action-icon.orange { background: #FFF7ED; color: #F97316; }
    .action-icon svg { width: 20px; height: 20px; }
    .action-label { font-family: Inter, sans-serif; font-size: 13px; font-weight: 600; color: #1E1E1E; }
</style>

{{-- Welcome --}}
<div class="welcome-block">
    <h1>Selamat Datang, {{ Auth::user()->name }} 👋</h1>
    <p>Anda telah berhasil masuk ke sistem. Silakan gunakan menu yang tersedia untuk mengelola data dan memantau aktivitas.</p>
</div>

{{-- Stat Cards --}}
<div class="stat-grid">
    <div class="stat-card blue">
        <div class="stat-header">
            <span class="stat-label">Total Revenue</span>
            <div class="stat-icon blue">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="12" y1="1" x2="12" y2="23"/>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                </svg>
            </div>
        </div>
        <div class="stat-value">Rp {{ number_format($totalRevenue / 1000000, 1) }}M</div>
        <div class="stat-sub">From completed rentals</div>
    </div>
    <div class="stat-card green">
        <div class="stat-header">
            <span class="stat-label">Active Rentals</span>
            <div class="stat-icon green">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                    <circle cx="12" cy="10" r="3"/>
                </svg>
            </div>
        </div>
        <div class="stat-value">{{ $activeRentals }}</div>
        <div class="stat-sub">Currently out</div>
    </div>
    <div class="stat-card orange">
        <div class="stat-header">
            <span class="stat-label">Total Customers</span>
            <div class="stat-icon orange">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
        </div>
        <div class="stat-value">{{ number_format($totalCustomers) }}</div>
        <div class="stat-sub positive">Active customers</div>
    </div>
    <div class="stat-card red">
        <div class="stat-header">
            <span class="stat-label">Pending Penalties</span>
            <div class="stat-icon red">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
            </div>
        </div>
        <div class="stat-value">{{ $pendingPenalties }}</div>
        <div class="stat-sub {{ $pendingPenalties > 0 ? 'warning' : '' }}">
            {{ $pendingPenalties > 0 ? 'Require attention' : 'All clear' }}
        </div>
    </div>
</div>

{{-- ── CHART SECTION ── --}}
<div class="chart-grid">

    {{-- Line Chart: Pendapatan per Bulan --}}
    <div class="chart-panel">
        <div class="chart-panel-header">
            <div>
                <div class="chart-panel-title">Pendapatan Bulanan</div>
                <div class="chart-panel-sub">Revenue dari transaksi Completed — 12 bulan terakhir</div>
            </div>
        </div>
        <div class="chart-wrap" style="height:220px;">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    {{-- Pie Chart: Status Transaksi --}}
    <div class="chart-panel">
        <div class="chart-panel-header">
            <div>
                <div class="chart-panel-title">Status Transaksi</div>
                <div class="chart-panel-sub">Distribusi status semua transaksi</div>
            </div>
        </div>
        <div class="chart-wrap" style="height:160px;display:flex;justify-content:center;">
            <canvas id="statusChart"></canvas>
        </div>
        <div class="pie-legend">
            @php
                $legendItems = [
                    ['Active',    '#2D4DA3', $statusData['Active']],
                    ['Completed', '#22C55E', $statusData['Completed']],
                    ['Overdue',   '#F97316', $statusData['Overdue']],
                    ['Cancelled', '#EF4444', $statusData['Cancelled']],
                ];
                $totalTrx = array_sum(array_column($legendItems, 2));
            @endphp
            @foreach($legendItems as [$label, $color, $val])
            <div class="legend-item">
                <div class="legend-left">
                    <div class="legend-dot" style="background:{{ $color }}"></div>
                    <span class="legend-label">{{ $label }}</span>
                </div>
                <span class="legend-val">{{ $val }} <span style="color:#94A3B8;font-weight:400;">({{ $totalTrx > 0 ? round($val/$totalTrx*100) : 0 }}%)</span></span>
            </div>
            @endforeach
        </div>
    </div>

</div>

{{-- Bottom Grid --}}
<div class="bottom-grid">
    <div class="panel">
        <div class="panel-title">Recent Transactions</div>
        @forelse ($recentTransactions as $t)
        <div class="trx-item">
            <div class="trx-left">
                <div class="trx-name">{{ $t->customer_name }}</div>
                <div class="trx-item-name">{{ $t->product_name }}</div>
            </div>
            <div class="trx-right">
                <div class="trx-amount">Rp {{ number_format($t->total_amount, 0, ',', '.') }}</div>
                <span class="trx-badge trx-{{ strtolower($t->trx_status) }}">{{ $t->trx_status }}</span>
            </div>
        </div>
        @empty
        <p style="font-size:13px;color:#9E9E9E;text-align:center;padding:20px 0;">Belum ada transaksi.</p>
        @endforelse
    </div>

    <div class="panel">
        <div class="panel-title">Quick Actions</div>
        <div class="action-grid">
            <a href="{{ route('produks.create') }}" class="action-card">
                <div class="action-icon blue">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <path d="M16 10a4 4 0 0 1-8 0"/>
                    </svg>
                </div>
                <span class="action-label">Add Product</span>
            </a>
            <a href="{{ route('penalties.index') }}" class="action-card">
                <div class="action-icon orange">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                </div>
                <span class="action-label">View Penalties</span>
            </a>
        </div>
    </div>
</div>

{{-- Chart.js CDN --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
    // ── Data dari Laravel ──
    const revenueLabels = @json($revenueLabels);
    const revenueData   = @json($revenueData);
    const statusData    = @json($statusData);

    // ── Line Chart: Pendapatan Bulanan ──
    const ctxRevenue = document.getElementById('revenueChart').getContext('2d');

    const gradient = ctxRevenue.createLinearGradient(0, 0, 0, 220);
    gradient.addColorStop(0, 'rgba(45, 77, 163, 0.15)');
    gradient.addColorStop(1, 'rgba(45, 77, 163, 0.0)');

    new Chart(ctxRevenue, {
        type: 'line',
        data: {
            labels: revenueLabels,
            datasets: [{
                label: 'Pendapatan',
                data: revenueData,
                borderColor: '#2D4DA3',
                backgroundColor: gradient,
                borderWidth: 2.5,
                pointBackgroundColor: '#2D4DA3',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0F172A',
                    titleFont: { family: 'Inter', size: 12 },
                    bodyFont:  { family: 'Inter', size: 12 },
                    padding: 10,
                    callbacks: {
                        label: ctx => ' Rp ' + ctx.parsed.y.toLocaleString('id-ID'),
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: {
                        font: { family: 'Inter', size: 11 },
                        color: '#94A3B8',
                        maxRotation: 0,
                        maxTicksLimit: 6,
                    },
                    border: { display: false },
                },
                y: {
                    grid: { color: '#F1F5F9', drawBorder: false },
                    ticks: {
                        font: { family: 'Inter', size: 11 },
                        color: '#94A3B8',
                        callback: v => v >= 1000000
                            ? 'Rp ' + (v/1000000).toFixed(1) + 'M'
                            : v >= 1000 ? 'Rp ' + (v/1000).toFixed(0) + 'K' : 'Rp ' + v,
                    },
                    border: { display: false },
                }
            }
        }
    });

    // ── Pie Chart: Status Transaksi ──
    const ctxStatus = document.getElementById('statusChart').getContext('2d');
    new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: ['Active', 'Completed', 'Overdue', 'Cancelled'],
            datasets: [{
                data: [
                    statusData.Active,
                    statusData.Completed,
                    statusData.Overdue,
                    statusData.Cancelled,
                ],
                backgroundColor: ['#2D4DA3', '#22C55E', '#F97316', '#EF4444'],
                borderColor: ['#fff','#fff','#fff','#fff'],
                borderWidth: 3,
                hoverOffset: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '68%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0F172A',
                    titleFont: { family: 'Inter', size: 12 },
                    bodyFont:  { family: 'Inter', size: 12 },
                    padding: 10,
                    callbacks: {
                        label: ctx => ' ' + ctx.label + ': ' + ctx.parsed + ' transaksi',
                    }
                }
            }
        }
    });
</script>

@endsection