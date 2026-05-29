@extends('layouts.app')

@section('title', 'Revenue per Kategori')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    :root {
        --primary:#2563eb; --gray-50:#FAFBFC; --gray-100:#F4F6F8; --gray-200:#E5E7EB;
        --gray-300:#D1D5DB; --gray-400:#9CA3AF; --gray-500:#6B7280;
        --gray-600:#4B5563; --gray-700:#374151; --gray-800:#1F2937; --gray-900:#111827;
    }

    /* PAGE HEADER */
    .rk-header { display:flex; align-items:flex-end; justify-content:space-between; margin-bottom:28px; gap:16px; }
    .rk-title h1 { font-family:'Plus Jakarta Sans',sans-serif; font-size:24px; font-weight:700; color:var(--gray-900); margin:0; }
    .rk-title p  { font-family:'Plus Jakarta Sans',sans-serif; font-size:13px; color:var(--gray-500); margin:5px 0 0; }
    .rk-timestamp { display:flex; align-items:center; gap:6px; font-family:'Plus Jakarta Sans',sans-serif; font-size:12px; color:var(--gray-500); background:#fff; border:1px solid var(--gray-200); border-radius:10px; padding:6px 12px; white-space:nowrap; }

    /* SUMMARY CARDS */
    .rk-cards { display:grid; grid-template-columns:repeat(3,1fr); gap:14px; margin-bottom:20px; }
    .rk-cards-2 { display:grid; grid-template-columns:repeat(3,1fr); gap:14px; margin-bottom:24px; }
    .rk-card { background:#fff; border-radius:14px; padding:18px 20px; border:1px solid var(--gray-200); position:relative; overflow:hidden; transition:all .2s; box-shadow:0 1px 2px rgba(15,23,42,.04); }
    .rk-card::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; }
    .rk-card:hover { transform:translateY(-2px); box-shadow:0 8px 20px -6px rgba(15,23,42,.1); }
    .rk-card-icon { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; margin-bottom:10px; }
    .rk-card-icon svg { width:18px; height:18px; }
    .rk-card-val { font-family:'Plus Jakarta Sans',sans-serif; font-size:22px; font-weight:700; line-height:1; margin-bottom:4px; }
    .rk-card-lbl { font-family:'Plus Jakarta Sans',sans-serif; font-size:11px; color:var(--gray-500); }
    .rk-card-sub { font-family:'Plus Jakarta Sans',sans-serif; font-size:10px; color:var(--gray-400); margin-top:2px; }

    /* TABLE CONTAINER */
    .rk-container { background:#fff; border-radius:16px; padding:22px; border:1px solid var(--gray-200); box-shadow:0 1px 2px rgba(15,23,42,.04), 0 4px 12px rgba(15,23,42,.04); margin-bottom:20px; }

    /* TOOLBAR */
    .rk-toolbar { display:flex; align-items:center; gap:10px; margin-bottom:18px; flex-wrap:wrap; }
    .rk-toolbar-right { margin-left:auto; display:flex; gap:8px; }
    .rk-select { height:40px; background:var(--gray-50); border:1px solid var(--gray-200); border-radius:10px; padding:0 30px 0 10px; font-family:'Plus Jakarta Sans',sans-serif; font-size:13px; color:var(--gray-700); font-weight:500; cursor:pointer; outline:none; appearance:none; min-width:130px; background-image:url("data:image/svg+xml,%3Csvg width='12' height='8' viewBox='0 0 12 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1.5L6 6.5L11 1.5' stroke='%239CA3AF' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 10px center; transition:all .2s; }
    .rk-select:focus { border-color:#2563eb; background-color:#fff; box-shadow:0 0 0 3px rgba(37,99,235,.08); }
    .rk-btn { height:40px; padding:0 16px; display:inline-flex; align-items:center; gap:6px; font-family:'Plus Jakarta Sans',sans-serif; font-size:12px; font-weight:600; border:none; border-radius:10px; cursor:pointer; transition:all .2s; white-space:nowrap; text-decoration:none; }
    .rk-btn-primary { color:#fff; background:linear-gradient(135deg,#1e3a5f 0%,#2563eb 60%,#3b82f6 100%); box-shadow:0 4px 12px rgba(37,99,235,.25); }
    .rk-btn-primary:hover { transform:translateY(-1px); box-shadow:0 6px 16px rgba(37,99,235,.35); }
    .rk-btn-pdf { color:#fff; background:linear-gradient(135deg,#7f1d1d 0%,#dc2626 60%,#ef4444 100%); box-shadow:0 3px 10px rgba(220,38,38,.25); }
    .rk-btn-pdf:hover { transform:translateY(-1px); }
    .rk-btn-csv { color:#fff; background:linear-gradient(135deg,#14532d 0%,#16a34a 60%,#22c55e 100%); box-shadow:0 3px 10px rgba(22,163,74,.25); }
    .rk-btn-csv:hover { transform:translateY(-1px); }
    .rk-btn-reset { color:var(--gray-600); background:#f1f5f9; border:1.5px solid var(--gray-200); }
    .rk-btn svg { width:14px; height:14px; flex-shrink:0; }

    /* CHART */
    .rk-chart-wrap { background:#fff; border-radius:16px; padding:20px 22px; border:1px solid var(--gray-200); box-shadow:0 1px 2px rgba(15,23,42,.04); margin-bottom:20px; }
    .rk-chart-title { font-family:'Plus Jakarta Sans',sans-serif; font-size:14px; font-weight:600; color:var(--gray-800); margin-bottom:16px; }
    .rk-bar-row { display:flex; align-items:center; gap:10px; margin-bottom:10px; }
    .rk-bar-label { font-family:'Plus Jakarta Sans',sans-serif; font-size:12px; color:var(--gray-600); font-weight:500; min-width:140px; max-width:140px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; text-align:right; }
    .rk-bar-track { flex:1; background:var(--gray-100); border-radius:99px; height:22px; overflow:hidden; position:relative; }
    .rk-bar-fill { height:100%; border-radius:99px; display:flex; align-items:center; justify-content:flex-end; padding-right:8px; transition:width .6s ease; min-width:32px; }
    .rk-bar-val { font-family:'Plus Jakarta Sans',sans-serif; font-size:10px; font-weight:700; color:#fff; white-space:nowrap; }
    .rk-bar-pct { font-family:'Plus Jakarta Sans',sans-serif; font-size:11px; color:var(--gray-500); min-width:40px; text-align:right; }

    /* TABLE */
    .rk-table-scroll { overflow-x:auto; border-radius:12px; border:1px solid var(--gray-100); }
    table { width:100%; border-collapse:separate; border-spacing:0; }
    thead tr { background:var(--gray-50); }
    thead th { font-family:'Plus Jakarta Sans',sans-serif; font-size:11px; font-weight:600; color:var(--gray-500); letter-spacing:.07em; text-transform:uppercase; padding:13px 16px; text-align:left; white-space:nowrap; background:var(--gray-50); border-bottom:1px solid var(--gray-200); }
    thead th.right { text-align:right; }
    thead th.center { text-align:center; }
    tbody td { font-family:'Plus Jakarta Sans',sans-serif; font-size:13px; color:var(--gray-800); padding:14px 16px; vertical-align:middle; background:#fff; border-bottom:1px solid var(--gray-100); }
    tbody td.right { text-align:right; }
    tbody td.center { text-align:center; }
    tbody tr:last-child td { border-bottom:none; }
    tbody tr:hover td { background:#FAFBFF; }

    /* SORT */
    .sortable { cursor:pointer; user-select:none; transition:all .15s; }
    .sortable:hover { color:#2563eb !important; background:#eff6ff !important; }
    .th-inner { display:inline-flex; align-items:center; gap:7px; }
    .sort-icon { display:inline-flex; flex-direction:column; align-items:center; gap:2px; flex-shrink:0; }
    .sort-icon svg { width:9px; height:6px; display:block; transition:fill .15s; }
    .sortable:not(.sort-active) .tri-up,
    .sortable:not(.sort-active) .tri-down { fill:var(--gray-300); }
    th.sort-active { color:#2563eb !important; background:#eff6ff !important; }
    th.sort-active.asc .tri-up   { fill:#2563eb; }
    th.sort-active.asc .tri-down { fill:#bfdbfe; }
    th.sort-active.desc .tri-up  { fill:#bfdbfe; }
    th.sort-active.desc .tri-down{ fill:#2563eb; }
    .sort-badge { display:inline-flex; align-items:center; background:linear-gradient(135deg,#2563eb,#3b82f6); color:white; font-size:9px; font-weight:700; padding:2px 6px; border-radius:5px; letter-spacing:.4px; margin-left:3px; opacity:0; transition:opacity .15s; }
    th.sort-active .sort-badge { opacity:1; }

    /* PILLS */
    .pill { display:inline-flex; align-items:center; gap:4px; padding:3px 9px; border-radius:99px; font-size:11px; font-weight:600; font-family:'Plus Jakarta Sans',sans-serif; }
    .pill-dot { width:5px; height:5px; border-radius:50%; display:inline-block; }
    .pill-1 { background:#eff6ff; color:#2563eb; }
    .pill-2 { background:#f5f3ff; color:#7c3aed; }
    .pill-3 { background:#f0fdf4; color:#16a34a; }
    .pill-4 { background:#fff7ed; color:#ea580c; }
    .pill-5 { background:#fefce8; color:#ca8a04; }
    .pill-6 { background:#fdf4ff; color:#a21caf; }
    .pill-7 { background:#ecfeff; color:#0891b2; }
    .pill-8 { background:#fef2f2; color:#dc2626; }

    /* PROGRESS BAR di tabel */
    .kontribusi-wrap { display:flex; align-items:center; gap:8px; }
    .kontribusi-bar { flex:1; height:6px; background:var(--gray-100); border-radius:99px; overflow:hidden; min-width:60px; }
    .kontribusi-fill { height:100%; border-radius:99px; }
    .kontribusi-pct { font-family:'Plus Jakarta Sans',sans-serif; font-size:11px; font-weight:600; min-width:36px; text-align:right; }

    /* MODAL */
    .modal-overlay { display:none; position:fixed; inset:0; z-index:999; align-items:center; justify-content:center; padding:20px; }
    .modal-overlay.show { display:flex; animation:rkFadeIn .2s ease; }
    @keyframes rkFadeIn { from{opacity:0;} to{opacity:1;} }
    .modal-backdrop { position:fixed; inset:0; background:rgba(15,23,42,.55); backdrop-filter:blur(6px); }
    .modal-box { position:relative; z-index:1; background:#fff; border-radius:18px; width:min(920px,95vw); height:min(88vh,720px); overflow:hidden; display:flex; flex-direction:column; box-shadow:0 25px 60px rgba(0,0,0,.25); animation:rkSlideUp .28s cubic-bezier(0.22,1,0.36,1); }
    @keyframes rkSlideUp { from{transform:translateY(24px);opacity:0;} to{transform:translateY(0);opacity:1;} }
    .modal-header { position:relative; padding:24px 28px; flex-shrink:0; overflow:hidden; background:linear-gradient(135deg,#1e3a5f 0%,#2563eb 60%,#3b82f6 100%); }
    .modal-header::before { content:''; position:absolute; top:-40px; right:-40px; width:180px; height:180px; border-radius:50%; background:rgba(255,255,255,0.06); pointer-events:none; }
    .modal-hc { position:relative; z-index:1; display:flex; align-items:center; justify-content:space-between; gap:16px; }
    .modal-icon { width:46px; height:46px; border-radius:12px; background:rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.2); display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .modal-icon svg { width:22px; height:22px; color:#fff; }
    .modal-title h2 { font-family:'Plus Jakarta Sans',sans-serif; font-size:18px; font-weight:800; color:#fff; margin:0 0 3px; }
    .modal-title p  { font-family:'Plus Jakarta Sans',sans-serif; font-size:12px; color:rgba(255,255,255,0.7); margin:0; }
    .modal-actions { display:flex; align-items:center; gap:10px; flex-shrink:0; }
    .btn-dl { display:inline-flex; align-items:center; gap:6px; height:38px; padding:0 16px; font-family:'Plus Jakarta Sans',sans-serif; font-size:12px; font-weight:700; color:#fff; background:rgba(255,255,255,0.2); border:1px solid rgba(255,255,255,0.3); border-radius:10px; cursor:pointer; text-decoration:none; transition:all .2s; }
    .btn-dl:hover { background:rgba(255,255,255,0.3); }
    .btn-dl svg { width:15px; height:15px; }
    .modal-close { width:36px; height:36px; border-radius:10px; background:rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.2); display:flex; align-items:center; justify-content:center; cursor:pointer; transition:all .2s; flex-shrink:0; }
    .modal-close:hover { background:rgba(255,255,255,0.25); transform:rotate(90deg); }
    .modal-close svg { width:17px; height:17px; color:#fff; }
    .pdf-loading { flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:12px; color:var(--gray-400); font-family:'Plus Jakarta Sans',sans-serif; font-size:13px; }
    .spin { width:36px; height:36px; border:3px solid #e0e7ff; border-top-color:#2563eb; border-radius:50%; animation:rkSpin .8s linear infinite; }
    @keyframes rkSpin { to{transform:rotate(360deg);} }

    /* EMPTY */
    .empty-state { text-align:center; padding:56px 0; color:var(--gray-400); }
    .empty-state svg { width:44px; height:44px; margin-bottom:12px; opacity:.35; }
    .empty-state p { font-size:13px; margin:0; font-family:'Plus Jakarta Sans',sans-serif; }
</style>

@php
$barColors = [
    '#2563eb','#7c3aed','#16a34a','#ea580c','#ca8a04',
    '#a21caf','#0891b2','#dc2626','#0d9488','#d97706',
];
$pillClasses = ['pill-1','pill-2','pill-3','pill-4','pill-5','pill-6','pill-7','pill-8'];
$grandTotal  = $data->sum('total_revenue');
@endphp

<div style="padding:24px 0 0;">

    {{-- PAGE HEADER --}}
    <div class="rk-header">
        <div class="rk-title">
            <h1>💰 Revenue per Kategori</h1>
            <p>Analisis pendapatan rental berdasarkan kategori produk</p>
        </div>
        <div class="rk-timestamp">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:14px;height:14px;">
                <circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/>
            </svg>
            {{ now()->format('d F Y, H:i') }} WIB
        </div>
    </div>

    {{-- SUMMARY CARDS --}}
    <div class="rk-cards">
        <div class="rk-card" style="border-color:#2563eb22;">
            <style>.rk-card:nth-child(1)::before{background:#2563eb;}</style>
            <div class="rk-card-icon" style="background:#eff6ff;color:#2563eb;">
                <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                </svg>
            </div>
            <div class="rk-card-val" style="color:#2563eb;">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</div>
            <div class="rk-card-lbl">Total Revenue</div>
        </div>
        <div class="rk-card" style="border-color:#16a34a22;">
            <style>.rk-card:nth-child(2)::before{background:#16a34a;}</style>
            <div class="rk-card-icon" style="background:#f0fdf4;color:#16a34a;">
                <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                </svg>
            </div>
            <div class="rk-card-val" style="color:#16a34a;">{{ number_format($summary['total_transaksi']) }}</div>
            <div class="rk-card-lbl">Total Transaksi</div>
        </div>
        <div class="rk-card" style="border-color:#7c3aed22;">
            <style>.rk-card:nth-child(3)::before{background:#7c3aed;}</style>
            <div class="rk-card-icon" style="background:#f5f3ff;color:#7c3aed;">
                <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9,22 9,12 15,12 15,22"/>
                </svg>
            </div>
            <div class="rk-card-val" style="color:#7c3aed;">{{ $data->count() }}</div>
            <div class="rk-card-lbl">Jumlah Kategori</div>
            @if($summary['kategori_teratas'])
            <div class="rk-card-sub">Teratas: {{ $summary['kategori_teratas']->category_name }}</div>
            @endif
        </div>
    </div>

    {{-- TOOLBAR --}}
    <div class="rk-container">
        <form method="GET" action="{{ route('revenue-kategori.index') }}" id="rkForm" style="display:contents;">
            <div class="rk-toolbar">
                {{-- Tahun --}}
                <select name="year" class="rk-select" onchange="document.getElementById('rkForm').submit()">
                    @foreach($years as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                    @endforeach
                </select>

                {{-- Bulan --}}
                <select name="month" class="rk-select" onchange="document.getElementById('rkForm').submit()">
                    <option value="">Semua Bulan</option>
                    @foreach(range(1,12) as $m)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                        </option>
                    @endforeach
                </select>

                {{-- Kategori --}}
                <select name="category" class="rk-select" onchange="document.getElementById('rkForm').submit()">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $category == $cat->id ? 'selected' : '' }}>
                            {{ $cat->category_name }}
                        </option>
                    @endforeach
                </select>

                @if($month || $category)
                <a href="{{ route('revenue-kategori.index', ['year' => $year]) }}" class="rk-btn rk-btn-reset">Reset</a>
                @endif

                <div style="font-family:'Plus Jakarta Sans',sans-serif; font-size:13px; color:var(--gray-500);">
                    <strong style="color:var(--gray-800);">{{ $data->count() }}</strong> kategori
                </div>

                <div class="rk-toolbar-right">
                    <button type="button" class="rk-btn rk-btn-pdf" onclick="rkOpenPdf()">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14,2 14,8 20,8"/>
                        </svg>
                        Preview PDF
                    </button>
                    <a href="{{ route('revenue-kategori.exportCsv', request()->query()) }}" class="rk-btn rk-btn-csv">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414A1 1 0 0 1 19 9.414V19a2 2 0 0 1-2 2z"/>
                        </svg>
                        Export CSV
                    </a>
                </div>
            </div>
        </form>

        {{-- CHART BAR HORIZONTAL --}}
        @if($chartData->count() > 0)
        <div style="margin-bottom:20px; padding:18px; background:var(--gray-50); border-radius:12px; border:1px solid var(--gray-100);">
            <div class="rk-chart-title">📊 Distribusi Revenue per Kategori</div>
            @php $maxRev = $chartData->max('total_revenue'); @endphp
            @foreach($chartData as $idx => $cd)
            @php
                $pct    = $maxRev > 0 ? ($cd->total_revenue / $maxRev * 100) : 0;
                $color  = $barColors[$idx % count($barColors)];
                $sharePct = $grandTotal > 0 ? round($cd->total_revenue / $grandTotal * 100, 1) : 0;
            @endphp
            <div class="rk-bar-row">
                <div class="rk-bar-label">{{ $cd->category_name }}</div>
                <div class="rk-bar-track">
                    <div class="rk-bar-fill" style="width:{{ max($pct, 8) }}%; background:{{ $color }};">
                        <span class="rk-bar-val">Rp {{ number_format($cd->total_revenue/1000, 0, ',', '.') }}k</span>
                    </div>
                </div>
                <div class="rk-bar-pct">{{ $sharePct }}%</div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- TABLE --}}
        <div class="rk-table-scroll">
            <table id="rkTable">
                <thead>
                    <tr>
                        <th style="width:36px; padding-left:18px;">No</th>
                        <th class="sortable" data-col="1" data-type="text" style="min-width:140px;">
                            <span class="th-inner">Kategori
                                <span class="sort-icon">
                                    <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                                    <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                                </span>
                                <span class="sort-badge" id="badge-1"></span>
                            </span>
                        </th>
                        <th class="sortable right" data-col="2" data-type="number" style="min-width:90px;">
                            <span class="th-inner">Transaksi
                                <span class="sort-icon">
                                    <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                                    <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                                </span>
                                <span class="sort-badge" id="badge-2"></span>
                            </span>
                        </th>
                        <th class="center" style="min-width:220px;">Status Breakdown</th>
                        <th class="sortable right" data-col="5" data-type="number" style="min-width:160px;">
                            <span class="th-inner">Total Revenue
                                <span class="sort-icon">
                                    <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                                    <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                                </span>
                                <span class="sort-badge" id="badge-5"></span>
                            </span>
                        </th>
                        <th class="sortable right" data-col="6" data-type="number" style="min-width:160px;">
                            <span class="th-inner">Rev. Completed
                                <span class="sort-icon">
                                    <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                                    <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                                </span>
                                <span class="sort-badge" id="badge-6"></span>
                            </span>
                        </th>
                        <th class="sortable right" data-col="7" data-type="number" style="min-width:130px;">
                            <span class="th-inner">Kontribusi
                                <span class="sort-icon">
                                    <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                                    <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                                </span>
                                <span class="sort-badge" id="badge-7"></span>
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $i => $row)
                    @php
                        $kontribusi = $grandTotal > 0 ? round($row->total_revenue / $grandTotal * 100, 1) : 0;
                        $pillClass  = $pillClasses[$i % count($pillClasses)];
                        $barColor   = $barColors[$i % count($barColors)];
                    @endphp
                    <tr>
                        <td style="color:var(--gray-400); font-size:12px; padding-left:18px;">{{ $i + 1 }}</td>
                        <td>
                            <span class="pill {{ $pillClass }}">{{ $row->category_name }}</span>
                        </td>
                        <td class="right" style="font-weight:600;">{{ number_format($row->total_transaksi) }}</td>
                        <td class="center">
                            <div style="display:flex; align-items:center; justify-content:center; gap:5px; flex-wrap:wrap;">
                                @if($row->completed > 0)
                                <span class="pill" style="background:#dcfce7;color:#16a34a;">✓ {{ $row->completed }}</span>
                                @endif
                                @if($row->active > 0)
                                <span class="pill" style="background:#eff6ff;color:#2563eb;">● {{ $row->active }}</span>
                                @endif
                                @if($row->overdue > 0)
                                <span class="pill" style="background:#fff7ed;color:#ea580c;">! {{ $row->overdue }}</span>
                                @endif
                                @if($row->cancelled > 0)
                                <span class="pill" style="background:#f1f5f9;color:#64748b;">✕ {{ $row->cancelled }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="right" style="font-weight:700; color:var(--gray-900);">
                            Rp {{ number_format($row->total_revenue, 0, ',', '.') }}
                        </td>
                        <td class="right" style="color:#16a34a; font-weight:600;">
                            Rp {{ number_format($row->revenue_completed, 0, ',', '.') }}
                        </td>
                        <td class="right">
                            <div class="kontribusi-wrap">
                                <div class="kontribusi-bar">
                                    <div class="kontribusi-fill" style="width:{{ $kontribusi }}%; background:{{ $barColor }};"></div>
                                </div>
                                <span class="kontribusi-pct" style="color:{{ $barColor }};">{{ $kontribusi }}%</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                                </svg>
                                <p>Tidak ada data revenue ditemukan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($data->count() > 0)
                <tfoot>
                    <tr style="background:var(--gray-50);">
                        <td colspan="2" style="padding:13px 16px; font-family:'Plus Jakarta Sans',sans-serif; font-size:12px; font-weight:700; color:var(--gray-700); border-top:2px solid var(--gray-200);">TOTAL</td>
                        <td class="right" style="font-weight:700; padding:13px 16px; border-top:2px solid var(--gray-200);">{{ number_format($summary['total_transaksi']) }}</td>
                        <td class="center" style="padding:13px 16px; font-size:11px; color:var(--gray-500); border-top:2px solid var(--gray-200);">
                            ✓ {{ $summary['total_completed'] }} &nbsp; ● {{ $summary['total_active'] }}
                        </td>
                        <td class="right" style="font-weight:800; font-size:14px; color:#2563eb; padding:13px 16px; border-top:2px solid var(--gray-200);">
                            Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}
                        </td>
                        <td colspan="2" style="padding:13px 16px; border-top:2px solid var(--gray-200);"></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>

{{-- MODAL PDF --}}
<div class="modal-overlay" id="rkPdfModal">
    <div class="modal-backdrop" onclick="rkClosePdf()"></div>
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-hc">
                <div style="display:flex;align-items:center;gap:14px;">
                    <div class="modal-icon">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14,2 14,8 20,8"/>
                        </svg>
                    </div>
                    <div class="modal-title">
                        <h2>Preview Laporan PDF</h2>
                        <p id="rkPdfLabel">Revenue per Kategori</p>
                    </div>
                </div>
                <div class="modal-actions">
                    <a id="rkPdfDlBtn" href="#" class="btn-dl">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="7,10 12,15 17,10"/>
                            <line x1="12" y1="15" x2="12" y2="3"/>
                        </svg>
                        Download PDF
                    </a>
                    <button class="modal-close" onclick="rkClosePdf()">
                        <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <div class="pdf-loading" id="rkPdfLoading">
            <div class="spin"></div>
            <span>Memuat preview PDF...</span>
        </div>
        <iframe id="rkPdfIframe" style="display:none;flex:1;border:none;width:100%;" onload="rkPdfLoaded()"></iframe>
    </div>
</div>

<script>
    // ── SORT ──
    let rkSortCol = -1, rkSortDir = 'asc';
    document.querySelectorAll('#rkTable th.sortable').forEach(th => {
        th.addEventListener('click', () => {
            const col = parseInt(th.dataset.col);
            rkSortDir = (rkSortCol === col && rkSortDir === 'asc') ? 'desc' : 'asc';
            rkSortCol = col;
            document.querySelectorAll('#rkTable th.sortable').forEach(t => {
                const c = parseInt(t.dataset.col), b = document.getElementById('badge-'+c);
                if (c === rkSortCol) {
                    t.classList.add('sort-active'); t.classList.remove('asc','desc'); t.classList.add(rkSortDir);
                    if (b) b.textContent = t.dataset.type === 'number'
                        ? (rkSortDir === 'asc' ? 'Kecil→Besar' : 'Besar→Kecil')
                        : (rkSortDir === 'asc' ? 'A-Z' : 'Z-A');
                } else {
                    t.classList.remove('sort-active','asc','desc');
                    if (b) b.textContent = '';
                }
            });
            const tbody = document.querySelector('#rkTable tbody');
            const rows  = Array.from(tbody.querySelectorAll('tr')).filter(r => r.cells.length > 1);
            rows.sort((a, b) => {
                const aT = a.cells[rkSortCol]?.innerText.trim() ?? '';
                const bT = b.cells[rkSortCol]?.innerText.trim() ?? '';
                if (th.dataset.type === 'number') {
                    const aN = parseFloat(aT.replace(/[^\d.-]/g,'')) || 0;
                    const bN = parseFloat(bT.replace(/[^\d.-]/g,'')) || 0;
                    return rkSortDir === 'asc' ? aN - bN : bN - aN;
                }
                const aL = aT.toLowerCase(), bL = bT.toLowerCase();
                return rkSortDir === 'asc' ? aL.localeCompare(bL) : bL.localeCompare(aL);
            });
            rows.forEach((r,i) => { tbody.appendChild(r); r.cells[0].textContent = i+1; });
        });
    });

    // ── PDF MODAL ──
    function rkGetParams() {
        const p = new URLSearchParams();
        p.set('year',  document.querySelector('select[name="year"]').value);
        const m = document.querySelector('select[name="month"]').value;
        const c = document.querySelector('select[name="category"]').value;
        if (m) p.set('month', m);
        if (c) p.set('category', c);
        return p;
    }
    function rkOpenPdf() {
        const params = rkGetParams();
        const label  = document.getElementById('rkPdfLabel');
        const dlBtn  = document.getElementById('rkPdfDlBtn');
        const parts  = ['Tahun ' + params.get('year')];
        if (params.get('month')) parts.push('Bulan ' + params.get('month'));
        if (params.get('category')) parts.push('Kategori terpilih');
        label.textContent = parts.join(' · ');
        dlBtn.href = '{{ route("revenue-kategori.exportPdf") }}?' + params.toString();
        params.set('preview', '1');
        document.getElementById('rkPdfLoading').style.display = 'flex';
        document.getElementById('rkPdfIframe').style.display  = 'none';
        document.getElementById('rkPdfIframe').src = '{{ route("revenue-kategori.exportPdf") }}?' + params.toString();
        document.getElementById('rkPdfModal').classList.add('show');
        document.body.style.overflow = 'hidden';
    }
    function rkPdfLoaded() {
        document.getElementById('rkPdfLoading').style.display = 'none';
        document.getElementById('rkPdfIframe').style.display  = 'block';
    }
    function rkClosePdf() {
        document.getElementById('rkPdfModal').classList.remove('show');
        document.getElementById('rkPdfIframe').src = '';
        document.body.style.overflow = '';
    }
    document.getElementById('rkPdfModal').addEventListener('click', e => { if (e.target === e.currentTarget) rkClosePdf(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') rkClosePdf(); });
</script>

@endsection