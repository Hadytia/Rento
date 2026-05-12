@extends('layouts.app')

@section('page_title', 'Dashboard')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    :root {
        --blue:   #2D4DA3;
        --blue-l: #EFF6FF;
        --green:  #059669;
        --green-l:#ECFDF5;
        --amber:  #D97706;
        --amber-l:#FFFBEB;
        --red:    #DC2626;
        --red-l:  #FEF2F2;
        --gray-50:#FAFBFC;
        --gray-100:#F4F6F8;
        --gray-200:#E5E7EB;
        --gray-400:#9CA3AF;
        --gray-500:#6B7280;
        --gray-700:#374151;
        --gray-900:#111827;
    }

    * { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* ── Welcome ── */
    .welcome {
        display:flex; align-items:center; justify-content:space-between;
        margin-bottom:28px;
    }
    .welcome h1 {
        font-size:24px; font-weight:800; color:var(--gray-900);
        margin:0 0 5px 0; letter-spacing:-0.5px;
    }
    .welcome p { font-size:13px; color:var(--gray-500); margin:0; }
    .welcome-date {
        font-size:12px; font-weight:600; color:var(--blue);
        background:var(--blue-l); padding:6px 14px; border-radius:20px;
        border:1px solid #BFDBFE;
    }

    /* ── Stat Cards ── */
    .stat-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:20px; }
    .stat-card {
        background:#FFF; border-radius:16px; padding:20px 22px;
        border:1px solid var(--gray-200);
        box-shadow:0 1px 2px rgba(15,23,42,.04);
        position:relative; overflow:hidden;
        transition:all .25s ease;
    }
    .stat-card::after {
        content:''; position:absolute; top:0; left:0; right:0; height:3px;
        border-radius:16px 16px 0 0;
    }
    .stat-card:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(15,23,42,.1); }
    .stat-card.blue::after  { background:linear-gradient(90deg,#2D4DA3,#4F6FCA); }
    .stat-card.green::after { background:linear-gradient(90deg,#059669,#34D399); }
    .stat-card.amber::after { background:linear-gradient(90deg,#D97706,#FBBF24); }
    .stat-card.red::after   { background:linear-gradient(90deg,#DC2626,#F87171); }

    .stat-top { display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:14px; }
    .stat-icon-wrap { width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; }
    .stat-icon-wrap svg { width:18px; height:18px; }
    .stat-icon-wrap.blue  { background:var(--blue-l);  color:var(--blue); }
    .stat-icon-wrap.green { background:var(--green-l); color:var(--green); }
    .stat-icon-wrap.amber { background:var(--amber-l); color:var(--amber); }
    .stat-icon-wrap.red   { background:var(--red-l);   color:var(--red); }

    .stat-badge {
        font-size:10px; font-weight:700; padding:3px 8px; border-radius:20px;
        display:inline-flex; align-items:center; gap:3px;
    }
    .stat-badge.up   { background:#ECFDF5; color:#059669; }
    .stat-badge.down { background:#FEF2F2; color:#DC2626; }
    .stat-badge.flat { background:var(--gray-100); color:var(--gray-500); }

    .stat-value {
        font-size:28px; font-weight:800; color:var(--gray-900);
        letter-spacing:-1px; line-height:1; margin-bottom:5px;
    }
    .stat-label { font-size:12px; font-weight:600; color:var(--gray-500); }
    .stat-sub   { font-size:11px; color:var(--gray-400); margin-top:3px; }

    /* ── Row: Grafik Harian + Pie ── */
    .row-2 { display:grid; grid-template-columns:2fr 1fr; gap:16px; margin-bottom:16px; }
    .row-3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:16px; margin-bottom:16px; }

    /* ── Panel ── */
    .panel {
        background:#FFF; border-radius:16px; padding:22px 24px;
        border:1px solid var(--gray-200);
        box-shadow:0 1px 2px rgba(15,23,42,.04);
    }
    .panel-head { display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:18px; }
    .panel-title { font-size:14px; font-weight:700; color:var(--gray-900); margin:0; letter-spacing:-0.2px; }
    .panel-sub   { font-size:11px; color:var(--gray-400); margin:3px 0 0 0; }

    /* ── Tab Toggle ── */
    .tab-group { display:flex; gap:4px; background:var(--gray-100); border-radius:8px; padding:3px; }
    .tab-btn {
        font-size:11px; font-weight:600; padding:5px 12px; border-radius:6px;
        border:none; background:transparent; color:var(--gray-500);
        cursor:pointer; transition:all .15s ease;
    }
    .tab-btn.active { background:#FFF; color:var(--blue); box-shadow:0 1px 3px rgba(15,23,42,.1); }

    /* ── Pie legend ── */
    .pie-legend { display:flex; flex-direction:column; gap:9px; margin-top:16px; }
    .legend-row { display:flex; align-items:center; justify-content:space-between; }
    .legend-l   { display:flex; align-items:center; gap:8px; }
    .legend-dot { width:8px; height:8px; border-radius:50%; flex-shrink:0; }
    .legend-name { font-size:12px; color:var(--gray-500); font-weight:500; }
    .legend-num  { font-size:12px; font-weight:700; color:var(--gray-900); }
    .legend-pct  { font-size:10px; color:var(--gray-400); margin-left:4px; }

    /* ── Recent Trx ── */
    .trx-list { display:flex; flex-direction:column; gap:0; }
    .trx-row {
        display:flex; align-items:center; justify-content:space-between;
        padding:11px 0; border-bottom:1px solid var(--gray-100);
    }
    .trx-row:last-child { border-bottom:none; }
    .trx-avatar {
        width:34px; height:34px; border-radius:50%; flex-shrink:0;
        display:flex; align-items:center; justify-content:center;
        font-size:11px; font-weight:700; color:#FFF;
        margin-right:10px;
    }
    .trx-info { flex:1; min-width:0; }
    .trx-name { font-size:13px; font-weight:600; color:var(--gray-900); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .trx-product { font-size:11px; color:var(--gray-400); margin-top:1px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .trx-right { text-align:right; flex-shrink:0; padding-left:10px; }
    .trx-amount { font-size:12px; font-weight:700; color:var(--blue); }
    .trx-badge {
        display:inline-block; border-radius:20px; padding:2px 8px;
        font-size:10px; font-weight:700; margin-top:3px;
    }
    .badge-active    { background:#EFF6FF; color:#2D4DA3; }
    .badge-completed { background:#ECFDF5; color:#059669; }
    .badge-overdue   { background:#FFF7ED; color:#EA580C; }
    .badge-cancelled { background:#FEF2F2; color:#DC2626; }

    /* ── Top Products ── */
    .product-row {
        display:flex; align-items:center; gap:12px;
        padding:10px 0; border-bottom:1px solid var(--gray-100);
    }
    .product-row:last-child { border-bottom:none; }
    .product-rank {
        width:24px; height:24px; border-radius:8px; flex-shrink:0;
        display:flex; align-items:center; justify-content:center;
        font-size:11px; font-weight:800;
    }
    .rank-1 { background:linear-gradient(135deg,#FCD34D,#F59E0B); color:#92400E; }
    .rank-2 { background:linear-gradient(135deg,#E2E8F0,#CBD5E1); color:#475569; }
    .rank-3 { background:linear-gradient(135deg,#FCA5A5,#F87171); color:#991B1B; }
    .rank-n { background:var(--gray-100); color:var(--gray-500); }
    .product-info { flex:1; min-width:0; }
    .product-name { font-size:12px; font-weight:600; color:var(--gray-900); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .product-trx  { font-size:11px; color:var(--gray-400); margin-top:1px; }
    .product-rev  { font-size:12px; font-weight:700; color:var(--blue); flex-shrink:0; }

    /* ── Monthly Summary ── */
    .month-compare {
        display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-top:4px;
    }
    .month-box {
        background:var(--gray-50); border-radius:12px; padding:14px 16px;
        border:1px solid var(--gray-100);
    }
    .month-box-label { font-size:11px; font-weight:600; color:var(--gray-400); text-transform:uppercase; letter-spacing:.06em; margin-bottom:6px; }
    .month-box-val   { font-size:18px; font-weight:800; color:var(--gray-900); letter-spacing:-0.5px; }
    .month-box-sub   { font-size:11px; color:var(--gray-400); margin-top:3px; }

    .growth-badge {
        display:inline-flex; align-items:center; gap:4px;
        font-size:12px; font-weight:700; padding:4px 10px;
        border-radius:20px; margin-top:12px;
    }
    .growth-up   { background:#ECFDF5; color:#059669; }
    .growth-down { background:#FEF2F2; color:#DC2626; }
    .growth-flat { background:var(--gray-100); color:var(--gray-500); }

    /* ── Quick Actions ── */
    .action-grid { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
    .action-card {
        border:1.5px solid var(--gray-200); border-radius:12px; padding:16px;
        display:flex; flex-direction:column; align-items:center; gap:8px;
        text-decoration:none; transition:all .2s ease;
        background:#FFF;
    }
    .action-card:hover { background:var(--blue-l); border-color:#BFDBFE; transform:translateY(-1px); box-shadow:0 4px 12px rgba(45,77,163,.12); }
    .action-icon-wrap { width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; }
    .action-icon-wrap svg { width:18px; height:18px; }
    .action-label { font-size:12px; font-weight:700; color:var(--gray-700); text-align:center; }
</style>

{{-- Welcome --}}
<div class="welcome">
    <div>
        <h1>Selamat Datang, {{ explode(' ', Auth::user()->name)[0] }} 👋</h1>
        <p>Pantau performa rental Anda hari ini.</p>
    </div>
    <div class="welcome-date">
        {{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
    </div>
</div>

{{-- Stat Cards --}}
@php
    $growthRevClass = $revenueGrowth > 0 ? 'up' : ($revenueGrowth < 0 ? 'down' : 'flat');
    $growthRevIcon  = $revenueGrowth > 0 ? '↑' : ($revenueGrowth < 0 ? '↓' : '→');
    $growthTrxClass = $trxGrowth > 0 ? 'up' : ($trxGrowth < 0 ? 'down' : 'flat');
    $growthTrxIcon  = $trxGrowth > 0 ? '↑' : ($trxGrowth < 0 ? '↓' : '→');
@endphp

<div class="stat-grid">
    {{-- Total Revenue --}}
    <div class="stat-card blue">
        <div class="stat-top">
            <div class="stat-icon-wrap blue">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="12" y1="1" x2="12" y2="23"/>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                </svg>
            </div>
            <span class="stat-badge {{ $growthRevClass }}">{{ $growthRevIcon }} {{ abs($revenueGrowth) }}%</span>
        </div>
        <div class="stat-value">
            @if($totalRevenue >= 1000000000)
                Rp {{ number_format($totalRevenue/1000000000, 1) }}B
            @else
                Rp {{ number_format($totalRevenue/1000000, 1) }}M
            @endif
        </div>
        <div class="stat-label">Total Revenue</div>
        <div class="stat-sub">vs bulan lalu: Rp {{ number_format($revenueLastMonth/1000000, 1) }}M</div>
    </div>

    {{-- Active Rentals --}}
    <div class="stat-card green">
        <div class="stat-top">
            <div class="stat-icon-wrap green">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                    <circle cx="12" cy="10" r="3"/>
                </svg>
            </div>
            <span class="stat-badge flat">Live</span>
        </div>
        <div class="stat-value">{{ $activeRentals }}</div>
        <div class="stat-label">Active Rentals</div>
        <div class="stat-sub">Sedang berjalan</div>
    </div>

    {{-- Transaksi Bulan Ini --}}
    <div class="stat-card amber">
        <div class="stat-top">
            <div class="stat-icon-wrap amber">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14,2 14,8 20,8"/>
                </svg>
            </div>
            <span class="stat-badge {{ $growthTrxClass }}">{{ $growthTrxIcon }} {{ abs($trxGrowth) }}%</span>
        </div>
        <div class="stat-value">{{ $trxThisMonth }}</div>
        <div class="stat-label">Transaksi Bulan Ini</div>
        <div class="stat-sub">Bulan lalu: {{ $trxLastMonth }}</div>
    </div>

    {{-- Pending Penalties --}}
    <div class="stat-card red">
        <div class="stat-top">
            <div class="stat-icon-wrap red">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
            </div>
            @if($pendingPenalties > 0)
                <span class="stat-badge down">Perlu Aksi</span>
            @else
                <span class="stat-badge up">All Clear</span>
            @endif
        </div>
        <div class="stat-value">{{ $pendingPenalties }}</div>
        <div class="stat-label">Pending Penalties</div>
        <div class="stat-sub">{{ $totalCustomers }} total pelanggan</div>
    </div>
</div>

{{-- Row 1: Chart Harian + Pie Status --}}
<div class="row-2">

    {{-- Chart Harian / Bulanan (switchable) --}}
    <div class="panel">
        <div class="panel-head">
            <div>
                <div class="panel-title">Grafik Transaksi</div>
                <div class="panel-sub" id="chartSubtitle">Pendapatan 7 hari terakhir</div>
            </div>
            <div class="tab-group">
                <button class="tab-btn active" onclick="switchChart('daily')">Harian</button>
                <button class="tab-btn" onclick="switchChart('monthly')">Bulanan</button>
            </div>
        </div>
        <div style="height:220px; position:relative;">
            <canvas id="mainChart"></canvas>
        </div>
    </div>

    {{-- Pie: Status Transaksi --}}
    <div class="panel">
        <div class="panel-head">
            <div>
                <div class="panel-title">Status Transaksi</div>
                <div class="panel-sub">Distribusi semua transaksi</div>
            </div>
        </div>
        <div style="height:150px; display:flex; justify-content:center;">
            <canvas id="statusChart"></canvas>
        </div>
        <div class="pie-legend">
            @php
                $legendItems = [
                    ['Active',    '#2D4DA3', $statusData['Active']],
                    ['Completed', '#059669', $statusData['Completed']],
                    ['Overdue',   '#D97706', $statusData['Overdue']],
                    ['Cancelled', '#DC2626', $statusData['Cancelled']],
                ];
                $totalTrx = array_sum(array_column($legendItems, 2));
            @endphp
            @foreach($legendItems as [$label, $color, $val])
            <div class="legend-row">
                <div class="legend-l">
                    <div class="legend-dot" style="background:{{ $color }}"></div>
                    <span class="legend-name">{{ $label }}</span>
                </div>
                <div>
                    <span class="legend-num">{{ $val }}</span>
                    <span class="legend-pct">({{ $totalTrx > 0 ? round($val/$totalTrx*100) : 0 }}%)</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</div>

{{-- Row 2: Recent Trx + Top Produk + Monthly Summary --}}
<div class="row-3">

    {{-- Recent Transactions --}}
    <div class="panel">
        <div class="panel-head">
            <div>
                <div class="panel-title">Transaksi Terbaru</div>
                <div class="panel-sub">5 transaksi terakhir</div>
            </div>
            <a href="{{ route('reports.index') }}" style="
                display:inline-flex; align-items:center; gap:5px;
                font-size:11px; font-weight:700; color:var(--blue);
                text-decoration:none; padding:6px 12px;
                background:var(--blue-l); border-radius:20px;
                border:1px solid #BFDBFE;
                transition:all .2s ease;
            " onmouseover="this.style.background='#2D4DA3';this.style.color='#fff';this.style.borderColor='#2D4DA3';"
            onmouseout="this.style.background='var(--blue-l)';this.style.color='var(--blue)';this.style.borderColor='#BFDBFE';">
                Lihat Semua
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <line x1="5" y1="12" x2="19" y2="12"/>
                    <polyline points="12,5 19,12 12,19"/>
                </svg>
            </a>
        </div>
        <div class="trx-list">
            @php
                $avatarColors = ['#2D4DA3','#7C3AED','#059669','#D97706','#DC2626','#0891B2'];
            @endphp
            @forelse ($recentTransactions as $i => $t)
            @php
                $initials = strtoupper(substr($t->customer_name, 0, 1)) . strtoupper(substr(strstr($t->customer_name, ' ') ?: ' ', 1, 1));
                $avColor  = $avatarColors[$i % count($avatarColors)];
            @endphp
            <div class="trx-row">
                <div class="trx-avatar" style="background:{{ $avColor }}">{{ $initials }}</div>
                <div class="trx-info">
                    <div class="trx-name">{{ $t->customer_name }}</div>
                    <div class="trx-product">{{ $t->product_name }}</div>
                </div>
                <div class="trx-right">
                    <div class="trx-amount">Rp {{ number_format($t->total_amount/1000, 0) }}K</div>
                    <div class="trx-badge badge-{{ strtolower($t->trx_status) }}">{{ $t->trx_status }}</div>
                </div>
            </div>
            @empty
            <p style="font-size:12px;color:var(--gray-400);text-align:center;padding:20px 0;">Belum ada transaksi.</p>
            @endforelse
        </div>
    </div>

    {{-- Top Produk --}}
    <div class="panel">
        <div class="panel-head">
            <div>
                <div class="panel-title">Top Produk Terlaris</div>
                <div class="panel-sub">Berdasarkan jumlah transaksi</div>
            </div>
        </div>
        @forelse ($topProducts as $i => $p)
        @php
            $rankClass = match($i) { 0 => 'rank-1', 1 => 'rank-2', 2 => 'rank-3', default => 'rank-n' };
        @endphp
        <div class="product-row">
            <div class="product-rank {{ $rankClass }}">#{{ $i+1 }}</div>
            <div class="product-info">
                <div class="product-name">{{ $p->product_name }}</div>
                <div class="product-trx">{{ $p->total_trx }} transaksi</div>
            </div>
            <div class="product-rev">
                Rp {{ number_format($p->total_revenue/1000000, 1) }}M
            </div>
        </div>
        @empty
        <p style="font-size:12px;color:var(--gray-400);text-align:center;padding:20px 0;">Belum ada data.</p>
        @endforelse
    </div>

    {{-- Monthly Summary + Quick Actions --}}
    <div style="display:flex; flex-direction:column; gap:16px;">

        {{-- Monthly Comparison --}}
        <div class="panel" style="flex:1;">
            <div class="panel-head">
                <div>
                    <div class="panel-title">Ringkasan Bulan Ini</div>
                    <div class="panel-sub">{{ now()->format('F Y') }}</div>
                </div>
            </div>
            <div class="month-compare">
                <div class="month-box">
                    <div class="month-box-label">Revenue</div>
                    <div class="month-box-val">
                        Rp {{ number_format($revenueThisMonth/1000000, 1) }}M
                    </div>
                    <div class="month-box-sub">bulan ini</div>
                </div>
                <div class="month-box">
                    <div class="month-box-label">Transaksi</div>
                    <div class="month-box-val">{{ $trxThisMonth }}</div>
                    <div class="month-box-sub">bulan ini</div>
                </div>
            </div>
            @if($revenueGrowth != 0)
            <div class="growth-badge {{ $revenueGrowth > 0 ? 'growth-up' : 'growth-down' }}">
                {{ $revenueGrowth > 0 ? '↑' : '↓' }} {{ abs($revenueGrowth) }}% vs bulan lalu
            </div>
            @else
            <div class="growth-badge growth-flat">→ Sama dengan bulan lalu</div>
            @endif
        </div>

        {{-- Quick Actions --}}
        <div class="panel">
            <div class="panel-title" style="margin-bottom:12px;">Quick Actions</div>
            <div class="action-grid">
                <a href="{{ route('transaksi.create') }}" class="action-card">
                    <div class="action-icon-wrap" style="background:#EFF6FF;color:#2D4DA3;">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                    </div>
                    <span class="action-label">Buat Transaksi</span>
                </a>
                <a href="{{ route('reports.index') }}" class="action-card">
                    <div class="action-icon-wrap" style="background:#ECFDF5;color:#059669;">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14,2 14,8 20,8"/>
                        </svg>
                    </div>
                    <span class="action-label">Laporan</span>
                </a>
                <a href="{{ route('produks.index') }}" class="action-card">
                    <div class="action-icon-wrap" style="background:#FFF7ED;color:#D97706;">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                            <line x1="3" y1="6" x2="21" y2="6"/>
                        </svg>
                    </div>
                    <span class="action-label">Produk</span>
                </a>
                <a href="{{ route('penalties.index') }}" class="action-card">
                    <div class="action-icon-wrap" style="background:#FEF2F2;color:#DC2626;">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                    </div>
                    <span class="action-label">Penalties</span>
                </a>
            </div>
        </div>

    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
    const dailyLabels   = @json($dailyLabels);
    const dailyData     = @json($dailyData);
    const dailyCount    = @json($dailyCount);
    const monthlyLabels = @json($revenueLabels);
    const monthlyData   = @json($revenueData);
    const statusData    = @json($statusData);

    // ── Main Chart (switchable) ──
    const ctxMain = document.getElementById('mainChart').getContext('2d');

    function makeGradient(ctx, color1, color2) {
        const g = ctx.createLinearGradient(0, 0, 0, 220);
        g.addColorStop(0, color1);
        g.addColorStop(1, color2);
        return g;
    }

    let mainChart = new Chart(ctxMain, {
        type: 'bar',
        data: {
            labels: dailyLabels,
            datasets: [{
                label: 'Pendapatan',
                data: dailyData,
                backgroundColor: makeGradient(ctxMain, 'rgba(45,77,163,0.8)', 'rgba(45,77,163,0.3)'),
                borderColor: '#2D4DA3',
                borderWidth: 0,
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0F172A',
                    titleFont: { family: 'Plus Jakarta Sans', size: 12 },
                    bodyFont:  { family: 'Plus Jakarta Sans', size: 12 },
                    padding: 10,
                    callbacks: {
                        label: ctx => ' Rp ' + ctx.parsed.y.toLocaleString('id-ID'),
                        afterLabel: (ctx) => {
                            const cnt = dailyCount[ctx.dataIndex];
                            return cnt !== undefined ? ' ' + cnt + ' transaksi' : '';
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { family: 'Plus Jakarta Sans', size: 11 }, color: '#94A3B8' },
                    border: { display: false },
                },
                y: {
                    grid: { color: '#F1F5F9' },
                    ticks: {
                        font: { family: 'Plus Jakarta Sans', size: 11 }, color: '#94A3B8',
                        callback: v => v >= 1000000 ? 'Rp ' + (v/1000000).toFixed(1) + 'M'
                                     : v >= 1000    ? 'Rp ' + (v/1000).toFixed(0) + 'K'
                                     : 'Rp ' + v,
                    },
                    border: { display: false },
                }
            }
        }
    });

    function switchChart(type) {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        event.target.classList.add('active');

        if (type === 'daily') {
            mainChart.data.labels   = dailyLabels;
            mainChart.data.datasets[0].data = dailyData;
            mainChart.config.type   = 'bar';
            document.getElementById('chartSubtitle').textContent = 'Pendapatan 7 hari terakhir';
        } else {
            mainChart.data.labels   = monthlyLabels;
            mainChart.data.datasets[0].data = monthlyData;
            mainChart.config.type   = 'line';
            mainChart.data.datasets[0].backgroundColor = makeGradient(ctxMain, 'rgba(45,77,163,0.15)', 'rgba(45,77,163,0)');
            mainChart.data.datasets[0].borderColor = '#2D4DA3';
            mainChart.data.datasets[0].borderWidth = 2.5;
            mainChart.data.datasets[0].pointBackgroundColor = '#2D4DA3';
            mainChart.data.datasets[0].pointBorderColor = '#fff';
            mainChart.data.datasets[0].pointBorderWidth = 2;
            mainChart.data.datasets[0].pointRadius = 4;
            mainChart.data.datasets[0].tension = 0.4;
            mainChart.data.datasets[0].fill = true;
            document.getElementById('chartSubtitle').textContent = 'Pendapatan 12 bulan terakhir';
        }
        mainChart.update();
    }

    // ── Doughnut: Status ──
    const ctxStatus = document.getElementById('statusChart').getContext('2d');
    new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: ['Active','Completed','Overdue','Cancelled'],
            datasets: [{
                data: [statusData.Active, statusData.Completed, statusData.Overdue, statusData.Cancelled],
                backgroundColor: ['#2D4DA3','#059669','#D97706','#DC2626'],
                borderColor: ['#fff','#fff','#fff','#fff'],
                borderWidth: 3,
                hoverOffset: 6,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0F172A',
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