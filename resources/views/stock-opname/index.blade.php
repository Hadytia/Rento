@extends('layouts.app')

@section('title', 'Stock Opname')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    :root {
        --primary: #2563eb;
        --gray-50: #FAFBFC; --gray-100: #F4F6F8; --gray-200: #E5E7EB;
        --gray-300: #D1D5DB; --gray-400: #9CA3AF; --gray-500: #6B7280;
        --gray-600: #4B5563; --gray-700: #374151; --gray-800: #1F2937; --gray-900: #111827;
    }

    /* ── PAGE HEADER ── */
    .so-page-header { display:flex; align-items:flex-end; justify-content:space-between; margin-bottom:28px; gap:24px; }
    .so-page-title h1 {
        font-family:'Plus Jakarta Sans',sans-serif; font-size:24px; font-weight:700;
        color:var(--gray-900); margin:0; letter-spacing:-0.5px;
    }
    .so-page-title p { font-family:'Plus Jakarta Sans',sans-serif; font-size:13px; color:var(--gray-500); margin:5px 0 0; }
    .so-timestamp { display:flex; align-items:center; gap:6px; font-family:'Plus Jakarta Sans',sans-serif;
        font-size:12px; color:var(--gray-500); background:#fff; border:1px solid var(--gray-200);
        border-radius:10px; padding:6px 12px; white-space:nowrap; }
    .so-timestamp svg { width:14px; height:14px; flex-shrink:0; }

    /* ── SUMMARY CARDS ── */
    .so-cards { display:grid; grid-template-columns:repeat(4,1fr); gap:14px; margin-bottom:24px; }
    .so-cards-row2 { display:grid; grid-template-columns:repeat(3,1fr); gap:14px; margin-bottom:24px; }
    .so-card {
        background:#fff; border-radius:14px; padding:18px 20px;
        border:1px solid var(--gray-200); position:relative; overflow:hidden;
        transition:all .2s ease; box-shadow:0 1px 2px rgba(15,23,42,.04);
    }
    .so-card::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; opacity:0; transition:opacity .2s; }
    .so-card:hover { transform:translateY(-2px); box-shadow:0 8px 20px -6px rgba(15,23,42,.1); border-color:transparent; }
    .so-card:hover::before { opacity:1; }
    .so-card-icon { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; margin-bottom:12px; }
    .so-card-icon svg { width:18px; height:18px; }
    .so-card-val { font-family:'Plus Jakarta Sans',sans-serif; font-size:26px; font-weight:700; line-height:1; margin-bottom:4px; }
    .so-card-lbl { font-family:'Plus Jakarta Sans',sans-serif; font-size:11px; color:var(--gray-500); font-weight:500; }

    /* ── TABLE CONTAINER ── */
    .so-table-container { background:#fff; border-radius:16px; padding:22px; border:1px solid var(--gray-200); box-shadow:0 1px 2px rgba(15,23,42,.04), 0 4px 12px rgba(15,23,42,.04); }

    /* ── TOOLBAR ── */
    .so-toolbar { display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; gap:12px; flex-wrap:wrap; }
    .so-toolbar-left { display:flex; align-items:center; gap:10px; flex:1; min-width:0; flex-wrap:wrap; }
    .so-toolbar-right { display:flex; align-items:center; gap:8px; flex-shrink:0; }
    .so-search-wrap { display:flex; align-items:center; gap:8px; border:1px solid var(--gray-200); border-radius:10px; padding:0 12px; height:40px; background:var(--gray-50); width:240px; transition:all .2s; }
    .so-search-wrap:focus-within { border-color:#2563eb; background:#fff; box-shadow:0 0 0 3px rgba(37,99,235,.08); }
    .so-search-wrap input { border:none; outline:none; font-family:'Plus Jakarta Sans',sans-serif; font-size:13px; color:var(--gray-900); width:100%; background:transparent; font-weight:500; }
    .so-search-wrap input::placeholder { color:var(--gray-400); font-weight:400; }
    .so-filter-select {
        height:40px; background:var(--gray-50); border:1px solid var(--gray-200);
        border-radius:10px; padding:0 30px 0 10px; font-family:'Plus Jakarta Sans',sans-serif;
        font-size:13px; color:var(--gray-700); font-weight:500; cursor:pointer; outline:none;
        appearance:none; min-width:150px;
        background-image:url("data:image/svg+xml,%3Csvg width='12' height='8' viewBox='0 0 12 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1.5L6 6.5L11 1.5' stroke='%239CA3AF' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
        background-repeat:no-repeat; background-position:right 10px center;
        transition:all .2s;
    }
    .so-filter-select:focus { border-color:#2563eb; background-color:#fff; box-shadow:0 0 0 3px rgba(37,99,235,.08); }
    .so-btn-filter {
        height:40px; padding:0 18px; border-radius:10px; font-family:'Plus Jakarta Sans',sans-serif;
        font-size:13px; font-weight:600; color:#fff; border:none; cursor:pointer;
        background:linear-gradient(135deg,#1e3a5f 0%,#2563eb 60%,#3b82f6 100%);
        box-shadow:0 4px 12px rgba(37,99,235,.25); transition:all .2s;
    }
    .so-btn-filter:hover { transform:translateY(-1px); box-shadow:0 6px 16px rgba(37,99,235,.35); }
    .so-btn-reset {
        height:40px; padding:0 14px; border-radius:10px; font-family:'Plus Jakarta Sans',sans-serif;
        font-size:13px; font-weight:600; color:var(--gray-600); cursor:pointer;
        background:#f1f5f9; border:1.5px solid var(--gray-200); transition:all .2s;
    }
    .so-btn-reset:hover { background:var(--gray-200); }
    .so-btn-pdf {
        height:40px; padding:0 14px; display:inline-flex; align-items:center; gap:6px;
        font-family:'Plus Jakarta Sans',sans-serif; font-size:12px; font-weight:600;
        color:#fff; border:none; border-radius:10px; cursor:pointer;
        background:linear-gradient(135deg,#7f1d1d 0%,#dc2626 60%,#ef4444 100%);
        box-shadow:0 3px 10px rgba(220,38,38,.25); transition:all .2s;
    }
    .so-btn-pdf:hover { transform:translateY(-1px); box-shadow:0 5px 14px rgba(220,38,38,.35); }
    .so-btn-csv {
        height:40px; padding:0 14px; display:inline-flex; align-items:center; gap:6px;
        font-family:'Plus Jakarta Sans',sans-serif; font-size:12px; font-weight:600;
        color:#fff; border:none; border-radius:10px; cursor:pointer;
        background:linear-gradient(135deg,#14532d 0%,#16a34a 60%,#22c55e 100%);
        box-shadow:0 3px 10px rgba(22,163,74,.25); transition:all .2s;
    }
    .so-btn-csv:hover { transform:translateY(-1px); box-shadow:0 5px 14px rgba(22,163,74,.35); }
    .so-btn-csv svg, .so-btn-pdf svg { width:14px; height:14px; flex-shrink:0; }
    .count-badge { display:inline-flex; align-items:center; justify-content:center; background:#eff6ff; color:#2563eb; border-radius:8px; padding:3px 10px; font-size:12px; font-weight:700; margin-left:8px; font-family:'Plus Jakarta Sans',sans-serif; box-shadow:inset 0 0 0 1px rgba(37,99,235,.15); }

    /* ── TABLE ── */
    .so-table-scroll { overflow-x:auto; border-radius:12px; border:1px solid var(--gray-100); }
    .so-table-scroll::-webkit-scrollbar { height:8px; }
    .so-table-scroll::-webkit-scrollbar-track { background:transparent; }
    .so-table-scroll::-webkit-scrollbar-thumb { background:var(--gray-200); border-radius:10px; border:2px solid #fff; }
    table { width:100%; border-collapse:separate; border-spacing:0; }
    thead tr { background:var(--gray-50); }
    thead th {
        font-family:'Plus Jakarta Sans',sans-serif; font-size:11px; font-weight:600;
        color:var(--gray-500); letter-spacing:.07em; text-transform:uppercase;
        padding:13px 16px; text-align:left; white-space:nowrap;
        background:var(--gray-50); border-bottom:1px solid var(--gray-200);
    }
    thead th.center { text-align:center; }
    tbody td {
        font-family:'Plus Jakarta Sans',sans-serif; font-size:13px; color:var(--gray-800);
        padding:14px 16px; vertical-align:middle; background:#fff;
        border-bottom:1px solid var(--gray-100);
    }
    tbody td.center { text-align:center; }
    tbody tr:last-child td { border-bottom:none; }
    tbody tr:hover td { background:#FAFBFF; }

    /* ── SORT ── */
    .sortable { cursor:pointer; user-select:none; transition:all .15s ease; }
    .sortable:hover { color:#2563eb !important; background:#eff6ff !important; }
    .th-inner { display:inline-flex; align-items:center; gap:7px; }
    .sort-icon { display:inline-flex; flex-direction:column; align-items:center; gap:2px; flex-shrink:0; }
    .sort-icon svg { width:9px; height:6px; display:block; transition:fill .15s; }
    .sortable:not(.sort-active) .tri-up,
    .sortable:not(.sort-active) .tri-down { fill:var(--gray-300); }
    .sortable:hover:not(.sort-active) .tri-up,
    .sortable:hover:not(.sort-active) .tri-down { fill:var(--gray-400); }
    th.sort-active { color:#2563eb !important; background:#eff6ff !important; }
    th.sort-active.asc  .tri-up   { fill:#2563eb; }
    th.sort-active.asc  .tri-down { fill:#bfdbfe; }
    th.sort-active.desc .tri-up   { fill:#bfdbfe; }
    th.sort-active.desc .tri-down { fill:#2563eb; }
    .sort-badge {
        display:inline-flex; align-items:center;
        background:linear-gradient(135deg,#2563eb,#3b82f6); color:white;
        font-size:9px; font-weight:700; padding:2px 6px; border-radius:5px;
        letter-spacing:.4px; margin-left:3px; opacity:0; transition:opacity .15s;
        box-shadow:0 2px 4px rgba(37,99,235,.2);
    }
    th.sort-active .sort-badge { opacity:1; }

    /* ── PILLS & BADGES ── */
    .pill { display:inline-flex; align-items:center; gap:4px; padding:3px 9px; border-radius:99px; font-size:11px; font-weight:600; font-family:'Plus Jakarta Sans',sans-serif; }
    .pill-dot { width:5px; height:5px; border-radius:50%; display:inline-block; }
    .pill-normal  { background:#dcfce7; color:#16a34a; }
    .pill-kurang  { background:#fef9c3; color:#ca8a04; }
    .pill-kritis  { background:#fee2e2; color:#dc2626; }
    .pill-habis   { background:#f1f5f9; color:#64748b; }
    .pill-baik    { background:#dcfce7; color:#16a34a; }
    .pill-rusak   { background:#fee2e2; color:#dc2626; }
    .pill-default { background:#f1f5f9; color:#64748b; }
    .pill-disewa  { background:#f5f3ff; color:#7c3aed; }
    .pill-avail-ok { background:#dcfce7; color:#16a34a; }
    .pill-avail-no { background:#f1f5f9; color:#94a3b8; }
    .pill-penalty { background:#fee2e2; color:#dc2626; }
    .mono { font-family:'JetBrains Mono','Consolas',monospace; font-size:12px; }

    /* ── LEGEND ── */
    .so-legend { background:var(--gray-50); border:1px solid var(--gray-200); border-radius:12px; padding:14px 18px; margin-top:18px; display:flex; flex-wrap:wrap; gap:16px; }
    .so-leg-item { display:flex; align-items:center; gap:7px; font-family:'Plus Jakarta Sans',sans-serif; font-size:12px; color:var(--gray-600); }

    /* ── EMPTY ── */
    .empty-state { text-align:center; padding:56px 0; color:var(--gray-400); }
    .empty-state svg { width:44px; height:44px; margin-bottom:12px; opacity:.35; }
    .empty-state p { font-size:13px; margin:0; font-family:'Plus Jakarta Sans',sans-serif; font-weight:500; }

    /* ── MODAL PDF ── */
    .modal-overlay { display:none; position:fixed; inset:0; z-index:999; align-items:center; justify-content:center; padding:20px; }
    .modal-overlay.show { display:flex; animation:soFadeIn .2s ease; }
    @keyframes soFadeIn { from{opacity:0;} to{opacity:1;} }
    .modal-backdrop { position:fixed; inset:0; background:rgba(15,23,42,.55); backdrop-filter:blur(6px); }
    .modal-box {
        position:relative; z-index:1; background:#fff; border-radius:18px;
        width:min(920px,95vw); height:min(88vh,720px);
        overflow:hidden; display:flex; flex-direction:column;
        box-shadow:0 25px 60px rgba(0,0,0,.25);
        animation:soSlideUp .28s cubic-bezier(0.22,1,0.36,1);
    }
    @keyframes soSlideUp { from{transform:translateY(24px);opacity:0;} to{transform:translateY(0);opacity:1;} }
    .modal-header {
        position:relative; padding:24px 28px; flex-shrink:0; overflow:hidden;
        background:linear-gradient(135deg, #1e3a5f 0%, #2563eb 60%, #3b82f6 100%);
    }
    .modal-header::before { content:''; position:absolute; top:-40px; right:-40px; width:180px; height:180px; border-radius:50%; background:rgba(255,255,255,0.06); pointer-events:none; }
    .modal-header::after  { content:''; position:absolute; bottom:-60px; left:-60px; width:200px; height:200px; border-radius:50%; background:rgba(255,255,255,0.04); pointer-events:none; }
    .modal-header-content { position:relative; z-index:1; display:flex; align-items:center; justify-content:space-between; gap:16px; }
    .modal-icon-box { width:46px; height:46px; border-radius:12px; flex-shrink:0; background:rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.2); display:flex; align-items:center; justify-content:center; }
    .modal-icon-box svg { width:22px; height:22px; color:#fff; }
    .modal-title-wrap h2 { font-family:'Plus Jakarta Sans',sans-serif; font-size:18px; font-weight:800; color:#fff; margin:0 0 3px; }
    .modal-title-wrap p  { font-family:'Plus Jakarta Sans',sans-serif; font-size:12px; color:rgba(255,255,255,0.7); margin:0; }
    .modal-header-actions { display:flex; align-items:center; gap:10px; flex-shrink:0; }
    .btn-modal-download {
        display:inline-flex; align-items:center; gap:6px; height:38px; padding:0 16px;
        font-family:'Plus Jakarta Sans',sans-serif; font-size:12px; font-weight:700;
        color:#fff; background:rgba(255,255,255,0.2); border:1px solid rgba(255,255,255,0.3);
        border-radius:10px; cursor:pointer; text-decoration:none;
        transition:all .2s; white-space:nowrap;
    }
    .btn-modal-download:hover { background:rgba(255,255,255,0.3); }
    .btn-modal-download svg { width:15px; height:15px; }
    .modal-close-btn { width:36px; height:36px; border-radius:10px; background:rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.2); display:flex; align-items:center; justify-content:center; cursor:pointer; transition:all .2s; flex-shrink:0; }
    .modal-close-btn:hover { background:rgba(255,255,255,0.25); transform:rotate(90deg); }
    .modal-close-btn svg { width:17px; height:17px; color:#fff; }
    .pdf-loading { flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:12px; color:var(--gray-400); font-family:'Plus Jakarta Sans',sans-serif; font-size:13px; }
    .pdf-loading .spin { width:36px; height:36px; border:3px solid #e0e7ff; border-top-color:#2563eb; border-radius:50%; animation:soSpin .8s linear infinite; }
    @keyframes soSpin { to{transform:rotate(360deg);} }
    #pdfIframe { flex:1; border:none; width:100%; }
</style>

<div style="padding: 24px 0 0;">

    {{-- PAGE HEADER --}}
    <div class="so-page-header">
        <div class="so-page-title">
            <h1>📦 Stock Opname</h1>
            <p>Laporan kondisi stok produk secara real-time</p>
        </div>
        <div class="so-timestamp">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/>
            </svg>
            {{ now()->format('d F Y, H:i') }} WIB
        </div>
    </div>

    {{-- SUMMARY CARDS ROW 1 --}}
    <div class="so-cards">
        @php
        $c1 = [
            ['lbl'=>'Total Produk',  'val'=>$summary['total_produk'],    'color'=>'#2563eb', 'bg'=>'#eff6ff', 'accent'=>'#2563eb',
             'icon'=>'<path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>'],
            ['lbl'=>'Total Stok',   'val'=>$summary['total_stok'],       'color'=>'#0891b2', 'bg'=>'#ecfeff', 'accent'=>'#0891b2',
             'icon'=>'<path d="M5 8h14M5 8a2 2 0 0 1 0-4h14a2 2 0 0 1 0 4M5 8v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8m-9 4h4"/>'],
            ['lbl'=>'Sedang Disewa','val'=>$summary['total_on_rent'],    'color'=>'#7c3aed', 'bg'=>'#f5f3ff', 'accent'=>'#7c3aed',
             'icon'=>'<polyline points="23,4 23,10 17,10"/><polyline points="1,20 1,14 7,14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/>'],
            ['lbl'=>'Tersedia',     'val'=>$summary['total_available'],  'color'=>'#16a34a', 'bg'=>'#f0fdf4', 'accent'=>'#16a34a',
             'icon'=>'<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22,4 12,14.01 9,11.01"/>'],
        ];
        @endphp
        @foreach($c1 as $c)
        <div class="so-card" style="border-color:{{ $c['color'] }}22;">
            <style>.so-card:nth-child({{ $loop->iteration }})::before { background:{{ $c['accent'] }}; }</style>
            <div class="so-card-icon" style="background:{{ $c['bg'] }}; color:{{ $c['color'] }};">
                <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">{!! $c['icon'] !!}</svg>
            </div>
            <div class="so-card-val" style="color:{{ $c['color'] }};">{{ $c['val'] }}</div>
            <div class="so-card-lbl">{{ $c['lbl'] }}</div>
        </div>
        @endforeach
    </div>

    {{-- SUMMARY CARDS ROW 2 --}}
    <div class="so-cards-row2">
        @php
        $c2 = [
            ['lbl'=>'Dalam Penalty','val'=>$summary['total_penalty'],  'color'=>'#dc2626', 'bg'=>'#fef2f2', 'accent'=>'#dc2626',
             'icon'=>'<path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>'],
            ['lbl'=>'Stok Kritis',  'val'=>$summary['produk_kritis'],  'color'=>'#ea580c', 'bg'=>'#fff7ed', 'accent'=>'#ea580c',
             'icon'=>'<path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>'],
            ['lbl'=>'Stok Kurang',  'val'=>$summary['produk_kurang'],  'color'=>'#ca8a04', 'bg'=>'#fefce8', 'accent'=>'#ca8a04',
             'icon'=>'<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>'],
        ];
        @endphp
        @foreach($c2 as $c)
        <div class="so-card" style="border-color:{{ $c['color'] }}22;">
            <div class="so-card-icon" style="background:{{ $c['bg'] }}; color:{{ $c['color'] }};">
                <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">{!! $c['icon'] !!}</svg>
            </div>
            <div class="so-card-val" style="color:{{ $c['color'] }};">{{ $c['val'] }}</div>
            <div class="so-card-lbl">{{ $c['lbl'] }}</div>
        </div>
        @endforeach
    </div>

    {{-- TABLE CONTAINER --}}
    <div class="so-table-container">

        {{-- TOOLBAR --}}
        <div class="so-toolbar">
            <div class="so-toolbar-left">
                {{-- Search --}}
                <div class="so-search-wrap">
                    <svg width="14" height="14" fill="none" stroke="#9CA3AF" stroke-width="2.2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input type="text" id="soSearch" placeholder="Cari produk..." onkeyup="soFilterTable()">
                </div>

                {{-- Filter Kategori --}}
                <form method="GET" action="{{ route('stock-opname.index') }}" id="soFilterForm" style="display:contents;">
                    <input type="hidden" name="search" id="soSearchHidden" value="{{ $search }}">
                    <select name="category" class="so-filter-select" onchange="document.getElementById('soFilterForm').submit()">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $category == $cat->id ? 'selected' : '' }}>{{ $cat->category_name }}</option>
                        @endforeach
                    </select>
                    <select name="status_filter" class="so-filter-select" onchange="document.getElementById('soFilterForm').submit()">
                        <option value="">Semua Status</option>
                        <option value="Normal" {{ $statusFilter=='Normal' ? 'selected' : '' }}>Normal</option>
                        <option value="Kurang" {{ $statusFilter=='Kurang' ? 'selected' : '' }}>Kurang</option>
                        <option value="Kritis" {{ $statusFilter=='Kritis' ? 'selected' : '' }}>Kritis</option>
                        <option value="Habis"  {{ $statusFilter=='Habis'  ? 'selected' : '' }}>Habis</option>
                    </select>
                    @if($search || $category || $statusFilter)
                    <a href="{{ route('stock-opname.index') }}" class="so-btn-reset">Reset</a>
                    @endif
                </form>

                <div style="font-family:'Plus Jakarta Sans',sans-serif; font-size:13px; color:var(--gray-500);">
                    <span style="font-weight:600; color:var(--gray-800);" id="soCount">{{ $data->count() }}</span> produk
                </div>
            </div>

            <div class="so-toolbar-right">
                {{-- PDF --}}
                <button type="button" class="so-btn-pdf" onclick="soOpenPdfPreview()">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14,2 14,8 20,8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
                    </svg>
                    Preview PDF
                </button>
                {{-- CSV --}}
                <a href="{{ route('stock-opname.exportCsv', request()->query()) }}" class="so-btn-csv">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414A1 1 0 0 1 19 9.414V19a2 2 0 0 1-2 2z"/>
                    </svg>
                    Export CSV
                </a>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="so-table-scroll">
            <table id="soTable">
                <thead>
                    <tr>
                        <th style="width:36px; padding-left:18px;">No</th>
                        <th class="sortable" data-col="1" data-type="text" style="min-width:160px;">
                            <span class="th-inner">Produk
                                <span class="sort-icon">
                                    <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                                    <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                                </span>
                                <span class="sort-badge" id="badge-1"></span>
                            </span>
                        </th>
                        <th class="sortable" data-col="2" data-type="text" style="min-width:110px;">
                            <span class="th-inner">Kategori
                                <span class="sort-icon">
                                    <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                                    <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                                </span>
                                <span class="sort-badge" id="badge-2"></span>
                            </span>
                        </th>
                        <th class="center" style="min-width:80px;">Kondisi</th>
                        <th class="sortable center" data-col="4" data-type="number" style="min-width:80px;">
                            <span class="th-inner">Stok Awal
                                <span class="sort-icon">
                                    <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                                    <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                                </span>
                                <span class="sort-badge" id="badge-4"></span>
                            </span>
                        </th>
                        <th class="sortable center" data-col="5" data-type="number" style="min-width:90px;">
                            <span class="th-inner">Stok Saat Ini
                                <span class="sort-icon">
                                    <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                                    <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                                </span>
                                <span class="sort-badge" id="badge-5"></span>
                            </span>
                        </th>
                        <th class="sortable center" data-col="6" data-type="number" style="min-width:75px;">
                            <span class="th-inner">Disewa
                                <span class="sort-icon">
                                    <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                                    <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                                </span>
                                <span class="sort-badge" id="badge-6"></span>
                            </span>
                        </th>
                        <th class="sortable center" data-col="7" data-type="number" style="min-width:80px;">
                            <span class="th-inner">Tersedia
                                <span class="sort-icon">
                                    <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                                    <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                                </span>
                                <span class="sort-badge" id="badge-7"></span>
                            </span>
                        </th>
                        <th class="sortable center" data-col="8" data-type="number" style="min-width:75px;">
                            <span class="th-inner">Penalty
                                <span class="sort-icon">
                                    <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                                    <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                                </span>
                                <span class="sort-badge" id="badge-8"></span>
                            </span>
                        </th>
                        <th class="center" style="min-width:70px;">Selisih</th>
                        <th class="sortable center" data-col="10" data-type="text" style="min-width:90px;">
                            <span class="th-inner">Status
                                <span class="sort-icon">
                                    <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                                    <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                                </span>
                                <span class="sort-badge" id="badge-10"></span>
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $i => $row)
                    @php
                    $pillStatus = match($row->stock_status) {
                        'Normal' => 'pill-normal', 'Kurang' => 'pill-kurang',
                        'Kritis' => 'pill-kritis', 'Habis'  => 'pill-habis', default => 'pill-habis',
                    };
                    $dotStatus = match($row->stock_status) {
                        'Normal'=>'#16a34a','Kurang'=>'#ca8a04','Kritis'=>'#dc2626',default=>'#94a3b8',
                    };
                    $pillCond = match(strtolower($row->condition ?? '')) {
                        'baik','good'=>'pill-baik','rusak','damaged'=>'pill-rusak',default=>'pill-default',
                    };
                    @endphp
                    <tr>
                        <td style="color:var(--gray-400); font-size:12px; padding-left:18px;">{{ $i + 1 }}</td>
                        <td style="font-weight:600; color:var(--gray-900);">{{ $row->product_name }}</td>
                        <td style="color:var(--gray-500);">{{ $row->category_name ?? '-' }}</td>
                        <td class="center">
                            <span class="pill {{ $pillCond }}">{{ ucfirst($row->condition ?? '-') }}</span>
                        </td>
                        <td class="center mono" style="color:var(--gray-500);">{{ $row->stock_initial }}</td>
                        <td class="center mono" style="font-weight:600; color:var(--gray-800);">{{ $row->stock }}</td>
                        <td class="center">
                            @if($row->on_rent > 0)
                                <span class="pill pill-disewa">🔄 {{ $row->on_rent }}</span>
                            @else
                                <span style="color:var(--gray-300); font-size:12px;">—</span>
                            @endif
                        </td>
                        <td class="center">
                            <span class="pill {{ $row->available > 0 ? 'pill-avail-ok' : 'pill-avail-no' }}">
                                {{ $row->available }}
                            </span>
                        </td>
                        <td class="center">
                            @if($row->in_penalty > 0)
                                <span class="pill pill-penalty">⚠️ {{ $row->in_penalty }}</span>
                            @else
                                <span style="color:var(--gray-300); font-size:12px;">—</span>
                            @endif
                        </td>
                        <td class="center mono">
                            @if($row->selisih != 0)
                                <span style="color:#ea580c; font-weight:600;">{{ $row->selisih > 0 ? '+' : '' }}{{ $row->selisih }}</span>
                            @else
                                <span style="color:var(--gray-300);">0</span>
                            @endif
                        </td>
                        <td class="center">
                            <span class="pill {{ $pillStatus }}">
                                <span class="pill-dot" style="background:{{ $dotStatus }};"></span>
                                {{ $row->stock_status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11">
                            <div class="empty-state">
                                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                <p>Tidak ada data produk ditemukan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- LEGEND --}}
        <div class="so-legend">
            <span style="font-family:'Plus Jakarta Sans',sans-serif; font-size:11px; font-weight:700; color:var(--gray-500); text-transform:uppercase; letter-spacing:.05em; align-self:center;">Keterangan:</span>
            <div class="so-leg-item"><span class="pill-dot" style="background:#16a34a; width:7px; height:7px;"></span><strong>Normal</strong> — tersedia &gt;20% stok</div>
            <div class="so-leg-item"><span class="pill-dot" style="background:#ca8a04; width:7px; height:7px;"></span><strong>Kurang</strong> — tersedia ≤20% stok</div>
            <div class="so-leg-item"><span class="pill-dot" style="background:#dc2626; width:7px; height:7px;"></span><strong>Kritis</strong> — tersedia = 0</div>
            <div class="so-leg-item"><span class="pill-dot" style="background:#94a3b8; width:7px; height:7px;"></span><strong>Habis</strong> — total stok = 0</div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════
     MODAL PREVIEW PDF
═══════════════════════════════════════ --}}
<div class="modal-overlay" id="soPdfModal">
    <div class="modal-backdrop" onclick="soClosePdfPreview()"></div>
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-header-content">
                <div style="display:flex; align-items:center; gap:14px;">
                    <div class="modal-icon-box">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14,2 14,8 20,8"/>
                        </svg>
                    </div>
                    <div class="modal-title-wrap">
                        <h2>Preview Laporan PDF</h2>
                        <p id="soPdfFilterLabel">Semua data</p>
                    </div>
                </div>
                <div class="modal-header-actions">
                    <a id="soPdfDownloadBtn" href="#" class="btn-modal-download">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="7,10 12,15 17,10"/>
                            <line x1="12" y1="15" x2="12" y2="3"/>
                        </svg>
                        Download PDF
                    </a>
                    <button class="modal-close-btn" onclick="soClosePdfPreview()">
                        <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="pdf-loading" id="soPdfLoading">
            <div class="spin"></div>
            <span>Memuat preview PDF...</span>
        </div>
        <iframe id="soPdfIframe" style="display:none; flex:1; border:none; width:100%;" onload="soPdfLoaded()"></iframe>
    </div>
</div>

<script>
    // ════ SEARCH CLIENT-SIDE ════
    function soFilterTable() {
        const q = document.getElementById('soSearch').value.toLowerCase();
        let count = 0;
        document.querySelectorAll('#soTable tbody tr').forEach(row => {
            const show = row.innerText.toLowerCase().includes(q);
            row.style.display = show ? '' : 'none';
            if (show && row.cells.length > 1) count++;
        });
        document.getElementById('soCount').textContent = count;
        document.getElementById('soSearchHidden').value = document.getElementById('soSearch').value;
    }

    // ════ SORT ════
    let soSortCol = -1, soSortDir = 'asc';

    document.querySelectorAll('#soTable th.sortable').forEach(th => {
        th.addEventListener('click', () => {
            const col = parseInt(th.dataset.col);
            soSortDir = (soSortCol === col && soSortDir === 'asc') ? 'desc' : 'asc';
            soSortCol = col;
            soUpdateSortIcons();
            soSortTable(col, soSortDir, th.dataset.type);
        });
    });

    function soUpdateSortIcons() {
        document.querySelectorAll('#soTable th.sortable').forEach(th => {
            const col   = parseInt(th.dataset.col);
            const type  = th.dataset.type;
            const badge = document.getElementById('badge-' + col);
            if (col === soSortCol) {
                th.classList.add('sort-active');
                th.classList.remove('asc', 'desc');
                th.classList.add(soSortDir);
                if (badge) badge.textContent = type === 'number'
                    ? (soSortDir === 'asc' ? 'Kecil→Besar' : 'Besar→Kecil')
                    : (soSortDir === 'asc' ? 'A-Z' : 'Z-A');
            } else {
                th.classList.remove('sort-active', 'asc', 'desc');
                if (badge) badge.textContent = '';
            }
        });
    }

    function soSortTable(col, dir, type) {
        const tbody = document.querySelector('#soTable tbody');
        const rows  = Array.from(tbody.querySelectorAll('tr')).filter(r => r.cells.length > 1);
        rows.sort((a, b) => {
            const aT = a.cells[col]?.innerText.trim() ?? '';
            const bT = b.cells[col]?.innerText.trim() ?? '';
            if (type === 'number') {
                const aN = parseFloat(aT.replace(/[^\d.-]/g,'')) || 0;
                const bN = parseFloat(bT.replace(/[^\d.-]/g,'')) || 0;
                return dir === 'asc' ? aN - bN : bN - aN;
            }
            const aL = aT.toLowerCase(), bL = bT.toLowerCase();
            if (aL < bL) return dir === 'asc' ? -1 :  1;
            if (aL > bL) return dir === 'asc' ?  1 : -1;
            return 0;
        });
        rows.forEach(r => tbody.appendChild(r));
        // Renumber
        let n = 0;
        rows.forEach(r => { if (r.style.display !== 'none') r.cells[0].textContent = ++n; });
    }

    // ════ PDF PREVIEW MODAL ════
    function soGetFilterParams() {
        const cat    = document.querySelector('select[name="category"]').value;
        const status = document.querySelector('select[name="status_filter"]').value;
        const search = document.getElementById('soSearch').value.trim();
        const p = new URLSearchParams();
        if (search) p.set('search', search);
        if (cat)    p.set('category', cat);
        if (status) p.set('status_filter', status);
        return p;
    }

    function soOpenPdfPreview() {
        const params   = soGetFilterParams();
        const label    = document.getElementById('soPdfFilterLabel');
        const dlBtn    = document.getElementById('soPdfDownloadBtn');
        const loading  = document.getElementById('soPdfLoading');
        const iframe   = document.getElementById('soPdfIframe');

        // Build label
        const parts = [];
        if (params.get('search'))        parts.push(`"${params.get('search')}"`);
        if (params.get('category'))      parts.push('kategori terpilih');
        if (params.get('status_filter')) parts.push(`status: ${params.get('status_filter')}`);
        label.textContent = parts.length ? 'Filter aktif: ' + parts.join(' · ') : 'Semua data';

        // Download URL (tanpa preview flag)
        dlBtn.href = '{{ route("stock-opname.exportPdf") }}?' + params.toString();

        // Preview URL
        params.set('preview', '1');
        const previewUrl = '{{ route("stock-opname.exportPdf") }}?' + params.toString();

        // Reset
        loading.style.display = 'flex';
        iframe.style.display  = 'none';
        iframe.src = previewUrl;

        document.getElementById('soPdfModal').classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function soPdfLoaded() {
        document.getElementById('soPdfLoading').style.display = 'none';
        document.getElementById('soPdfIframe').style.display  = 'block';
    }

    function soClosePdfPreview() {
        document.getElementById('soPdfModal').classList.remove('show');
        document.getElementById('soPdfIframe').src = '';
        document.body.style.overflow = '';
    }

    document.getElementById('soPdfModal').addEventListener('click', function(e) {
        if (e.target === this) soClosePdfPreview();
    });

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') soClosePdfPreview();
    });
</script>

@endsection