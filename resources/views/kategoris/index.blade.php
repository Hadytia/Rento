@extends('layouts.app')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

    /* ═══════════════════════════════════════════════════════════════════════
       DESIGN TOKENS
    ═══════════════════════════════════════════════════════════════════════ */
    :root {
        --primary: #4F46E5;
        --primary-dark: #4338CA;
        --primary-light: #EEF2FF;
        --primary-soft: #F5F3FF;
        --success: #059669;
        --danger: #DC2626;
        --gray-50: #FAFBFC;
        --gray-100: #F4F6F8;
        --gray-200: #E5E7EB;
        --gray-300: #D1D5DB;
        --gray-400: #9CA3AF;
        --gray-500: #6B7280;
        --gray-600: #4B5563;
        --gray-700: #374151;
        --gray-800: #1F2937;
        --gray-900: #111827;
    }

    /* ═══════════════════════════════════════════════════════════════════════
       PAGE HEADER — unchanged
    ═══════════════════════════════════════════════════════════════════════ */
    .page-header { display:flex; align-items:flex-end; justify-content:space-between; margin-bottom:32px; gap:24px; }
    .page-title h1 { font-family:Inter,sans-serif; font-size:26px; font-weight:700; color:var(--gray-900); margin:0; letter-spacing:-0.5px; background:linear-gradient(135deg, #111827 0%, #4F46E5 100%); -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent; }
    .page-title p { font-family:Inter,sans-serif; font-size:14px; color:var(--gray-500); margin:6px 0 0 0; }
    .btn-add { height:44px; padding:0 22px; background:linear-gradient(135deg, #4F46E5 0%, #6366F1 100%); color:#FFF; border:none; border-radius:12px; font-family:Inter,sans-serif; font-size:13px; font-weight:600; cursor:pointer; display:flex; align-items:center; gap:8px; white-space:nowrap; text-decoration:none; box-shadow:0 4px 14px rgba(79,70,229,.32); transition:all .2s ease; }
    .btn-add:hover { transform:translateY(-1px); color:#fff; box-shadow:0 8px 20px rgba(79,70,229,.4); }

    /* ═══════════════════════════════════════════════════════════════════════
       ALERT — unchanged
    ═══════════════════════════════════════════════════════════════════════ */
    .alert-success { background:linear-gradient(135deg, #ECFDF5 0%, #F0FDF9 100%); border:1px solid #A7F3D0; border-radius:12px; padding:13px 18px; font-family:Inter,sans-serif; font-size:13px; color:#065F46; margin-bottom:24px; display:flex; align-items:center; gap:10px; font-weight:500; }

    /* ═══════════════════════════════════════════════════════════════════════
       TABLE — unchanged
    ═══════════════════════════════════════════════════════════════════════ */
    .table-container { background:#FFF; border-radius:18px; padding:24px; border:1px solid var(--gray-200); box-shadow:0 1px 2px rgba(15,23,42,.04), 0 4px 12px rgba(15,23,42,.04); }
    .table-toolbar { display:flex; align-items:center; justify-content:space-between; margin-bottom:20px; padding-bottom:4px; }
    .table-label { font-family:Inter,sans-serif; font-size:15px; font-weight:600; color:var(--gray-900); }
    .count-badge { display:inline-flex; align-items:center; justify-content:center; background:linear-gradient(135deg, #EEF2FF 0%, #E0E7FF 100%); color:#4F46E5; border-radius:8px; padding:3px 10px; font-size:12px; font-weight:700; margin-left:10px; font-family:Inter,sans-serif; box-shadow:inset 0 0 0 1px rgba(79,70,229,.15); }
    .search-wrap { display:flex; align-items:center; gap:8px; border:1px solid var(--gray-200); border-radius:11px; padding:0 14px; height:42px; background:var(--gray-50); width:260px; transition:all .2s ease; }
    .search-wrap:focus-within { border-color:#4F46E5; background:#FFF; box-shadow:0 0 0 4px rgba(79,70,229,.08); }
    .search-wrap input { border:none; outline:none; font-family:Inter,sans-serif; font-size:13px; color:var(--gray-900); width:100%; background:transparent; font-weight:500; }
    .search-wrap input::placeholder { color:var(--gray-400); font-weight:400; }
    .table-scroll { overflow-x:auto; border-radius:12px; border:1px solid var(--gray-100); }
    .table-scroll::-webkit-scrollbar { height:10px; }
    .table-scroll::-webkit-scrollbar-thumb { background:var(--gray-200); border-radius:10px; border:2px solid #FFF; }
    table { width:100%; border-collapse:separate; border-spacing:0; }
    thead tr { background:var(--gray-50); }
    thead th { font-family:Inter,sans-serif; font-size:11px; font-weight:600; color:var(--gray-500); letter-spacing:.08em; text-transform:uppercase; padding:14px 18px; text-align:left; white-space:nowrap; background:var(--gray-50); border-bottom:1px solid var(--gray-200); }
    tbody tr { transition:background .15s ease; }
    tbody td { font-family:Inter,sans-serif; font-size:13px; color:var(--gray-800); padding:16px 18px; vertical-align:middle; background:#FFF; border-bottom:1px solid var(--gray-100); }
    tbody tr:last-child td { border-bottom:none; }
    tbody tr:hover td { background:#FAFBFF; }
    .sortable { cursor:pointer; user-select:none; transition:all .15s ease; }
    .sortable:hover { color:#4F46E5 !important; background:#F5F3FF !important; }
    .th-inner { display:inline-flex; align-items:center; gap:8px; }
    .sort-icon { display:inline-flex; flex-direction:column; align-items:center; gap:2px; flex-shrink:0; }
    .sort-icon svg { width:9px; height:6px; display:block; }
    .sortable:not(.sort-active) .tri-up, .sortable:not(.sort-active) .tri-down { fill:var(--gray-300); }
    th.sort-active { color:#4F46E5 !important; background:#F5F3FF !important; }
    th.sort-active.asc .tri-up { fill:#4F46E5; } th.sort-active.asc .tri-down { fill:#C7D2FE; }
    th.sort-active.desc .tri-up { fill:#C7D2FE; } th.sort-active.desc .tri-down { fill:#4F46E5; }
    .sort-badge { display:inline-flex; align-items:center; background:linear-gradient(135deg, #4F46E5 0%, #6366F1 100%); color:white; font-size:9px; font-weight:700; padding:2px 6px; border-radius:5px; letter-spacing:.5px; margin-left:4px; opacity:0; transition:opacity .15s; }
    th.sort-active .sort-badge { opacity:1; }
    .cat-name { font-weight:600; color:var(--gray-900); display:flex; align-items:center; gap:12px; }
    .cat-icon { width:38px; height:38px; border-radius:10px; background:linear-gradient(135deg, #EEF2FF 0%, #E0E7FF 100%); display:flex; align-items:center; justify-content:center; flex-shrink:0; box-shadow:inset 0 0 0 1px rgba(79,70,229,.1); }
    .cat-icon svg { width:18px; height:18px; color:#4F46E5; }
    .description-cell { color:var(--gray-500); font-size:13px; font-weight:500; max-width:280px; }
    .audit-name { font-size:12px; font-weight:600; color:var(--gray-700); margin-bottom:2px; }
    .audit-date { font-size:11px; color:var(--gray-400); font-family:'JetBrains Mono','Consolas',monospace; font-weight:500; }
    .audit-empty { color:var(--gray-300); font-size:13px; }
    .badge { display:inline-flex; align-items:center; gap:6px; border-radius:8px; padding:5px 12px; font-family:Inter,sans-serif; font-size:11.5px; font-weight:600; }
    .badge.active { background:linear-gradient(135deg,#ECFDF5,#D1FAE5); color:#047857; box-shadow:inset 0 0 0 1px rgba(5,150,105,.2); }
    .badge.inactive { background:linear-gradient(135deg,#FEF2F2,#FEE2E2); color:#B91C1C; box-shadow:inset 0 0 0 1px rgba(220,38,38,.2); }
    .badge-dot { width:6px; height:6px; border-radius:50%; display:inline-block; }
    .badge.active .badge-dot { background:#10B981; animation:pulse-green 2s infinite; }
    .badge.inactive .badge-dot { background:#EF4444; }
    @keyframes pulse-green { 0%,100%{box-shadow:0 0 0 0 rgba(16,185,129,.4);}50%{box-shadow:0 0 0 4px rgba(16,185,129,0);} }
    .action-wrap { display:flex; gap:6px; }
    .action-btn { height:32px; padding:0 12px; border-radius:8px; font-family:Inter,sans-serif; font-size:12px; font-weight:600; cursor:pointer; display:inline-flex; align-items:center; gap:5px; transition:all .15s ease; border:none; text-decoration:none; }
    .btn-edit { background:#EEF2FF; color:#4F46E5; box-shadow:inset 0 0 0 1px rgba(79,70,229,.15); }
    .btn-edit:hover { background:#4F46E5; color:#FFF; transform:translateY(-1px); box-shadow:0 4px 10px rgba(79,70,229,.3); }
    .btn-delete { background:#FEF2F2; color:#DC2626; box-shadow:inset 0 0 0 1px rgba(220,38,38,.15); }
    .btn-delete:hover { background:#DC2626; color:#FFF; transform:translateY(-1px); box-shadow:0 4px 10px rgba(220,38,38,.3); }
    .action-btn svg { width:13px; height:13px; }
    .view-only-tag { font-size:11px; color:var(--gray-400); font-family:Inter,sans-serif; font-weight:500; background:var(--gray-100); padding:5px 10px; border-radius:6px; }
    .empty-state { text-align:center; padding:64px 0; color:var(--gray-400); }
    .empty-state svg { width:48px; height:48px; margin-bottom:14px; opacity:.4; }
    .empty-state p { font-size:14px; margin:0; font-family:Inter,sans-serif; font-weight:500; }

    /* ═══════════════════════════════════════════════════════════════════════
       MODAL SHARED — overlay & animation
    ═══════════════════════════════════════════════════════════════════════ */
    .modal-overlay { display:none; position:fixed; inset:0; z-index:999; align-items:center; justify-content:center; padding:20px; }
    .modal-overlay.show { display:flex; animation:fadeIn .2s ease; }
    @keyframes fadeIn { from{opacity:0;} to{opacity:1;} }
    @keyframes slideUp { from{transform:translateY(24px);opacity:0;} to{transform:translateY(0);opacity:1;} }
    .modal-backdrop { position:fixed; inset:0; background:rgba(15,23,42,.55); backdrop-filter:blur(6px); }

    /* ═══════════════════════════════════════════════════════════════════════
       MODAL BOX (Add & Edit)
    ═══════════════════════════════════════════════════════════════════════ */
    .modal-box {
        position:relative; z-index:1; background:#FFF; border-radius:20px;
        width:100%; max-width:480px; max-height:90vh; overflow-y:auto;
        box-shadow:0 25px 60px rgba(0,0,0,.18);
        animation:slideUp .28s cubic-bezier(0.22,1,0.36,1);
        font-family:'Plus Jakarta Sans',Inter,sans-serif;
    }
    .modal-box::-webkit-scrollbar { width:6px; }
    .modal-box::-webkit-scrollbar-thumb { background:var(--gray-200); border-radius:6px; }

    /* gradient header */
    .modal-header {
        padding:0; position:sticky; top:0; z-index:2;
        border-radius:20px 20px 0 0; overflow:hidden;
    }
    .modal-header-inner {
        background:linear-gradient(135deg, #1e1b4b 0%, #4F46E5 60%, #6366F1 100%);
        padding:22px 26px 20px;
        position:relative; overflow:hidden;
        display:flex; align-items:center; gap:14px;
    }
    .modal-header-inner::before {
        content:''; position:absolute; top:-40px; right:-40px;
        width:140px; height:140px; border-radius:50%;
        background:rgba(255,255,255,0.06);
    }
    .modal-header-inner::after {
        content:''; position:absolute; bottom:-50px; left:30%;
        width:180px; height:180px; border-radius:50%;
        background:rgba(255,255,255,0.04);
    }
    .modal-hicon {
        width:42px; height:42px; background:rgba(255,255,255,0.15);
        border-radius:12px; display:flex; align-items:center; justify-content:center;
        border:1px solid rgba(255,255,255,0.2); flex-shrink:0; position:relative; z-index:1;
    }
    .modal-htitle { position:relative; z-index:1; }
    .modal-htitle h2 { font-size:1.1rem; font-weight:700; color:#fff; margin:0; letter-spacing:-0.01em; }
    .modal-htitle p { font-size:0.78rem; color:rgba(255,255,255,0.7); margin:3px 0 0; }
    .modal-hclose {
        position:absolute; top:16px; right:16px; z-index:3;
        width:32px; height:32px; background:rgba(255,255,255,0.15);
        border:1px solid rgba(255,255,255,0.2); border-radius:9px;
        display:flex; align-items:center; justify-content:center;
        color:rgba(255,255,255,0.85); font-size:14px; cursor:pointer;
        transition:background .2s;
    }
    .modal-hclose:hover { background:rgba(255,255,255,0.28); color:#fff; }

    /* body */
    .modal-body { padding:24px 26px; }

    /* section label */
    .modal-section {
        display:flex; align-items:center; gap:7px;
        font-size:0.65rem; font-weight:700; letter-spacing:0.08em;
        text-transform:uppercase; color:var(--gray-400);
        margin:0 0 16px;
    }
    .modal-section::after { content:''; flex:1; height:1px; background:var(--gray-100); }

    /* form groups */
    .form-group { margin-bottom:16px; }
    .form-group:last-child { margin-bottom:0; }
    .form-group label {
        display:flex; align-items:center; gap:5px;
        font-size:0.78rem; font-weight:600;
        color:var(--gray-700); margin-bottom:7px;
    }
    .form-group label .req { color:#ef4444; font-size:0.72rem; }

    /* input wrapper with icon */
    .fg-wrap { position:relative; }
    .fg-ico {
        position:absolute; left:11px; top:50%; transform:translateY(-50%);
        color:var(--gray-400); pointer-events:none; display:flex; align-items:center;
    }
    .fg-ico-top {
        position:absolute; left:11px; top:12px;
        color:var(--gray-400); pointer-events:none; display:flex; align-items:center;
    }
    .select-wrap { position:relative; }
    .select-arrow { position:absolute; right:11px; top:50%; transform:translateY(-50%); color:var(--gray-400); pointer-events:none; font-size:0.72rem; }

    /* inputs */
    .form-group input,
    .form-group select {
        width:100%; height:42px;
        background:#f8fafc; border:1.5px solid #e8edf5; border-radius:10px;
        padding:0 12px 0 34px;
        font-family:'Plus Jakarta Sans',Inter,sans-serif; font-size:0.85rem;
        color:var(--gray-900); outline:none; box-sizing:border-box;
        transition:all .18s ease; font-weight:500; appearance:none;
    }
    .form-group textarea {
        width:100%; background:#f8fafc; border:1.5px solid #e8edf5; border-radius:10px;
        padding:10px 12px 10px 34px;
        font-family:'Plus Jakarta Sans',Inter,sans-serif; font-size:0.85rem;
        color:var(--gray-900); outline:none; box-sizing:border-box;
        transition:all .18s ease; font-weight:500; resize:none; line-height:1.6;
        min-height:90px;
    }
    .form-group input::placeholder,
    .form-group textarea::placeholder { color:#c0cad9; font-weight:400; }
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color:#4F46E5; background:#fff;
        box-shadow:0 0 0 3px rgba(79,70,229,.1);
    }

    /* footer */
    .modal-footer {
        padding:16px 26px 22px; display:flex; gap:10px; justify-content:flex-end;
        position:sticky; bottom:0; background:white;
        border-top:1px solid var(--gray-100); border-radius:0 0 20px 20px;
    }
    .btn-cancel {
        height:42px; padding:0 20px;
        background:#f1f5f9; border:1.5px solid #e2e8f0; border-radius:10px;
        font-family:'Plus Jakarta Sans',Inter,sans-serif; font-size:0.85rem; font-weight:600;
        color:var(--gray-600); cursor:pointer; display:inline-flex; align-items:center; gap:6px;
        transition:all .15s ease;
    }
    .btn-cancel:hover { background:#e2e8f0; }
    .btn-save {
        height:42px; padding:0 26px;
        background:linear-gradient(135deg, #4F46E5, #6366F1); border:none; border-radius:10px;
        font-family:'Plus Jakarta Sans',Inter,sans-serif; font-size:0.85rem; font-weight:700;
        color:#fff; cursor:pointer; display:inline-flex; align-items:center; gap:7px;
        box-shadow:0 4px 14px rgba(79,70,229,.35); transition:all .2s ease;
    }
    .btn-save:hover { background:linear-gradient(135deg,#4338CA,#4F46E5); box-shadow:0 6px 20px rgba(79,70,229,.45); transform:translateY(-1px); }
    .btn-save:active { transform:translateY(0); }

    /* ═══════════════════════════════════════════════════════════════════════
       CONFIRM / DELETE MODAL
    ═══════════════════════════════════════════════════════════════════════ */
    .confirm-box {
        position:relative; z-index:1; background:#FFF; border-radius:20px;
        width:100%; max-width:400px;
        box-shadow:0 25px 60px rgba(0,0,0,.18);
        overflow:hidden; animation:slideUp .25s cubic-bezier(0.22,1,0.36,1);
        font-family:'Plus Jakarta Sans',Inter,sans-serif;
    }
    .confirm-header {
        background:linear-gradient(135deg, #7f1d1d 0%, #dc2626 60%, #ef4444 100%);
        padding:22px 24px 18px; position:relative; overflow:hidden;
    }
    .confirm-header::before {
        content:''; position:absolute; top:-30px; right:-30px;
        width:120px; height:120px; border-radius:50%; background:rgba(255,255,255,0.07);
    }
    .confirm-hclose {
        position:absolute; top:14px; right:14px;
        width:30px; height:30px; background:rgba(255,255,255,0.15);
        border:1px solid rgba(255,255,255,0.2); border-radius:8px;
        display:flex; align-items:center; justify-content:center;
        color:rgba(255,255,255,0.85); font-size:13px; cursor:pointer;
        transition:background .2s; z-index:2;
    }
    .confirm-hclose:hover { background:rgba(255,255,255,0.28); }
    .confirm-hicon {
        width:44px; height:44px; background:rgba(255,255,255,0.15);
        border-radius:12px; display:flex; align-items:center; justify-content:center;
        border:1px solid rgba(255,255,255,0.2); margin-bottom:12px; position:relative; z-index:1;
    }
    .confirm-htitle { position:relative; z-index:1; }
    .confirm-htitle h3 { font-size:1.05rem; font-weight:700; color:#fff; margin:0; }
    .confirm-htitle p { font-size:0.78rem; color:rgba(255,255,255,0.7); margin:3px 0 0; }
    .confirm-body { padding:22px 24px 18px; }
    .confirm-desc {
        font-size:0.875rem; color:var(--gray-600); line-height:1.65;
        background:#fef2f2; border:1px solid #fecaca; border-radius:10px; padding:13px 15px;
    }
    .confirm-desc strong { color:var(--gray-900); }
    .confirm-footer { padding:4px 24px 22px; display:flex; gap:10px; }
    .btn-confirm-cancel {
        flex:1; height:42px; border:1.5px solid var(--gray-200); border-radius:10px;
        font-family:'Plus Jakarta Sans',Inter,sans-serif; font-size:0.85rem; font-weight:600;
        color:var(--gray-600); background:white; cursor:pointer; transition:all .15s ease;
    }
    .btn-confirm-cancel:hover { background:var(--gray-50); }
    .btn-confirm-delete {
        flex:1; height:42px; border:none; border-radius:10px;
        font-family:'Plus Jakarta Sans',Inter,sans-serif; font-size:0.85rem; font-weight:700;
        color:white; background:linear-gradient(135deg,#dc2626,#ef4444); cursor:pointer;
        box-shadow:0 4px 14px rgba(220,38,38,.35); transition:all .2s ease;
        display:inline-flex; align-items:center; justify-content:center; gap:7px;
    }
    .btn-confirm-delete:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(220,38,38,.45); }
    .btn-confirm-delete:active { transform:translateY(0); }
</style>

{{-- ═══ PAGE HEADER ═══ --}}
<div class="page-header">
    <div class="page-title">
        <h1>Kelola Kategori</h1>
        <p>Tambah, edit, atau hapus kategori produk.</p>
    </div>
    @php
        $currentAdmin = \App\Models\Admin::where('email', Auth::user()->email)
                        ->where('status', 1)->where('is_deleted', 0)->first();
        $isSuperadmin = $currentAdmin && $currentAdmin->role === 'superadmin';
        $isAdminStaff = $currentAdmin && in_array($currentAdmin->role, ['admin', 'staff']);
        $canEdit = $currentAdmin && ($isSuperadmin || $isAdminStaff || $currentAdmin->can_edit == 1);
    @endphp
    @if($canEdit)
    <button class="btn-add" onclick="openAddModal()">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Tambah Kategori
    </button>
    @endif
</div>

@if (session('success'))
    <div class="alert-success">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22,4 12,14.01 9,11.01"/>
        </svg>
        {{ session('success') }}
    </div>
@endif

{{-- ═══ TABLE ═══ --}}
<div class="table-container">
    <div class="table-toolbar">
        <div style="display:flex;align-items:center;">
            <span class="table-label">Daftar Kategori</span>
            <span class="count-badge">{{ $kategoris->count() }}</span>
        </div>
        <div class="search-wrap">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="text" placeholder="Cari kategori..." id="searchInput" onkeyup="filterTable()">
        </div>
    </div>

    <div class="table-scroll">
    <table id="kategoriTable">
        <thead>
            <tr>
                <th style="width:140px">Aksi</th>
                <th class="sortable" data-col="1">
                    <span class="th-inner">Nama Kategori
                        <span class="sort-icon">
                            <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                            <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                        </span>
                        <span class="sort-badge" id="badge-1"></span>
                    </span>
                </th>
                <th class="sortable" data-col="2">
                    <span class="th-inner">Deskripsi
                        <span class="sort-icon">
                            <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                            <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                        </span>
                        <span class="sort-badge" id="badge-2"></span>
                    </span>
                </th>
                <th class="sortable" data-col="3" style="width:120px">
                    <span class="th-inner">Status
                        <span class="sort-icon">
                            <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                            <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                        </span>
                        <span class="sort-badge" id="badge-3"></span>
                    </span>
                </th>
                <th class="sortable" data-col="4">
                    <span class="th-inner">Created By
                        <span class="sort-icon">
                            <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                            <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                        </span>
                        <span class="sort-badge" id="badge-4"></span>
                    </span>
                </th>
                <th class="sortable" data-col="5">
                    <span class="th-inner">Created Date
                        <span class="sort-icon">
                            <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                            <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                        </span>
                        <span class="sort-badge" id="badge-5"></span>
                    </span>
                </th>
                <th class="sortable" data-col="6">
                    <span class="th-inner">Last Updated By
                        <span class="sort-icon">
                            <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                            <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                        </span>
                        <span class="sort-badge" id="badge-6"></span>
                    </span>
                </th>
                <th class="sortable" data-col="7">
                    <span class="th-inner">Last Updated Date
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
            @forelse ($kategoris as $kategori)
            <tr>
                <td>
                    <div class="action-wrap">
                        @if($canEdit)
                        <button class="action-btn btn-edit"
                            onclick="openEditModal(
                                {{ $kategori->id }},
                                '{{ addslashes($kategori->category_name) }}',
                                '{{ addslashes($kategori->description) }}',
                                '{{ $kategori->status }}'
                            )">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                            Edit
                        </button>
                        <button class="action-btn btn-delete"
                            onclick="openDeleteModal({{ $kategori->id }}, '{{ addslashes($kategori->category_name) }}')">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <polyline points="3,6 5,6 21,6"/>
                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                <path d="M10 11v6"/><path d="M14 11v6"/>
                                <path d="M9 6V4h6v2"/>
                            </svg>
                            Hapus
                        </button>
                        @else
                        <span class="view-only-tag">View Only</span>
                        @endif
                    </div>
                </td>
                <td>
                    <div class="cat-name">
                        @php
                            $iconMap = [
                                'outdoor gear'  => 'cat_outdoor_gear.png',
                                'vehicles'      => 'cat_vehicles.png',
                                'cooking tools' => 'cat_cooking_tools.png',
                                'electronic'    => 'cat_electronic.png',
                            ];
                            $key      = strtolower(trim($kategori->category_name));
                            $iconFile = $iconMap[$key] ?? null;
                        @endphp
                        <div class="cat-icon" style="{{ $iconFile ? 'background:transparent;padding:0;overflow:hidden;box-shadow:none;' : '' }}">
                            @if($iconFile)
                                <img src="{{ asset('categories/' . $iconFile) }}" alt="{{ $kategori->category_name }}" style="width:38px;height:38px;border-radius:10px;object-fit:cover;">
                            @else
                                <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/>
                                    <line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/>
                                    <line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/>
                                </svg>
                            @endif
                        </div>
                        <div>{{ $kategori->category_name }}</div>
                    </div>
                </td>
                <td><div class="description-cell">{{ $kategori->description ?: '-' }}</div></td>
                <td>
                    <span class="badge {{ $kategori->status ? 'active' : 'inactive' }}">
                        <span class="badge-dot"></span>
                        {{ $kategori->status ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td>
                    @if($kategori->created_by) <div class="audit-name">{{ $kategori->created_by }}</div>
                    @else <span class="audit-empty">-</span> @endif
                </td>
                <td>
                    @if($kategori->created_date) <div class="audit-date">{{ \Carbon\Carbon::parse($kategori->created_date)->format('d M Y, H:i') }}</div>
                    @else <span class="audit-empty">-</span> @endif
                </td>
                <td>
                    @if($kategori->last_updated_by) <div class="audit-name">{{ $kategori->last_updated_by }}</div>
                    @else <span class="audit-empty">-</span> @endif
                </td>
                <td>
                    @if($kategori->last_updated_date) <div class="audit-date">{{ \Carbon\Carbon::parse($kategori->last_updated_date)->format('d M Y, H:i') }}</div>
                    @else <span class="audit-empty">-</span> @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8">
                    <div class="empty-state">
                        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/>
                            <line x1="8" y1="18" x2="21" y2="18"/>
                        </svg>
                        <p>Belum ada kategori.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>

{{-- ═══ MODAL TAMBAH KATEGORI ═══ --}}
<div class="modal-overlay" id="modalAdd">
    <div class="modal-backdrop" onclick="closeModal('modalAdd')"></div>
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-header-inner">
                <button class="modal-hclose" onclick="closeModal('modalAdd')">✕</button>
                <div class="modal-hicon">
                    <svg width="20" height="20" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/>
                        <line x1="8" y1="18" x2="21" y2="18"/>
                        <line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/>
                        <line x1="3" y1="18" x2="3.01" y2="18"/>
                    </svg>
                </div>
                <div class="modal-htitle">
                    <h2>Tambah Kategori</h2>
                    <p>Buat kategori produk baru.</p>
                </div>
            </div>
        </div>
        <form method="POST" action="{{ route('kategoris.store') }}">
            @csrf
            <div class="modal-body">
                <div class="modal-section">
                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                    Informasi Kategori
                </div>

                <div class="form-group">
                    <label>Nama Kategori <span class="req">*</span></label>
                    <div class="fg-wrap">
                        <span class="fg-ico"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/></svg></span>
                        <input type="text" name="category_name" placeholder="cth. Outdoor Gear" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Deskripsi</label>
                    <div class="fg-wrap">
                        <span class="fg-ico-top"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg></span>
                        <textarea name="description" placeholder="Deskripsi singkat kategori..."></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <div class="fg-wrap select-wrap">
                        <span class="fg-ico"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg></span>
                        <select name="status">
                            <option value="1">✓ Aktif</option>
                            <option value="0">✗ Nonaktif</option>
                        </select>
                        <span class="select-arrow">▼</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal('modalAdd')">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
                    Batal
                </button>
                <button type="submit" class="btn-save">
                    <svg width="14" height="14" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/></svg>
                    Simpan Kategori
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ═══ MODAL EDIT KATEGORI ═══ --}}
<div class="modal-overlay" id="modalEdit">
    <div class="modal-backdrop" onclick="closeModal('modalEdit')"></div>
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-header-inner">
                <button class="modal-hclose" onclick="closeModal('modalEdit')">✕</button>
                <div class="modal-hicon">
                    <svg width="20" height="20" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                </div>
                <div class="modal-htitle">
                    <h2>Edit Kategori</h2>
                    <p>Perbarui informasi kategori.</p>
                </div>
            </div>
        </div>
        <form method="POST" id="editForm" action="">
            @csrf @method('PUT')
            <div class="modal-body">
                <div class="modal-section">
                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                    Informasi Kategori
                </div>

                <div class="form-group">
                    <label>Nama Kategori <span class="req">*</span></label>
                    <div class="fg-wrap">
                        <span class="fg-ico"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/></svg></span>
                        <input type="text" name="category_name" id="editCategoryName" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Deskripsi</label>
                    <div class="fg-wrap">
                        <span class="fg-ico-top"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg></span>
                        <textarea name="description" id="editDescription"></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <div class="fg-wrap select-wrap">
                        <span class="fg-ico"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg></span>
                        <select name="status" id="editStatus">
                            <option value="1">✓ Aktif</option>
                            <option value="0">✗ Nonaktif</option>
                        </select>
                        <span class="select-arrow">▼</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal('modalEdit')">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
                    Batal
                </button>
                <button type="submit" class="btn-save">
                    <svg width="14" height="14" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/></svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ═══ MODAL DELETE ═══ --}}
<div class="modal-overlay" id="modalDelete">
    <div class="modal-backdrop" onclick="closeModal('modalDelete')"></div>
    <div class="confirm-box">
        <div class="confirm-header">
            <button class="confirm-hclose" onclick="closeModal('modalDelete')">✕</button>
            <div class="confirm-hicon">
                <svg width="22" height="22" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                    <polyline points="3,6 5,6 21,6"/>
                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                    <path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/>
                </svg>
            </div>
            <div class="confirm-htitle">
                <h3>Hapus Kategori</h3>
                <p>Tindakan ini tidak dapat dibatalkan</p>
            </div>
        </div>
        <div class="confirm-body">
            <p class="confirm-desc">
                Kategori <strong id="deleteCategoryName"></strong> akan dihapus secara permanen dari sistem dan tidak dapat dipulihkan kembali.
            </p>
        </div>
        <div class="confirm-footer">
            <button class="btn-confirm-cancel" onclick="closeModal('modalDelete')">Batal</button>
            <button class="btn-confirm-delete" onclick="executeDelete()">
                <svg width="14" height="14" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24">
                    <polyline points="3,6 5,6 21,6"/>
                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                    <path d="M10 11v6"/><path d="M14 11v6"/>
                </svg>
                Ya, Hapus
            </button>
        </div>
    </div>
</div>

<form id="deleteForm" method="POST" action="" style="display:none">
    @csrf @method('DELETE')
</form>

<script>
    const COL_TYPES = { 1:'text', 2:'text', 3:'text', 4:'text', 5:'date', 6:'text', 7:'date' };
    let sortCol = -1, sortDir = 'asc';

    document.querySelectorAll('th.sortable').forEach(th => {
        th.addEventListener('click', () => {
            const col = parseInt(th.dataset.col);
            sortDir = (sortCol === col && sortDir === 'asc') ? 'desc' : 'asc';
            sortCol = col;
            updateSortIcons();
            sortTable(col, sortDir);
        });
    });

    function updateSortIcons() {
        document.querySelectorAll('th.sortable').forEach(th => {
            const col   = parseInt(th.dataset.col);
            const badge = document.getElementById('badge-' + col);
            const type  = COL_TYPES[col] || 'text';
            if (col === sortCol) {
                th.classList.add('sort-active');
                th.classList.remove('asc', 'desc');
                th.classList.add(sortDir);
                if (badge) {
                    badge.textContent = type === 'date'
                        ? (sortDir === 'asc' ? 'Lama→Baru' : 'Baru→Lama')
                        : (sortDir === 'asc' ? 'A-Z' : 'Z-A');
                }
            } else {
                th.classList.remove('sort-active', 'asc', 'desc');
                if (badge) badge.textContent = '';
            }
        });
    }

    function parseDate(str) {
        if (!str || str === '-') return 0;
        const months = {Jan:0,Feb:1,Mar:2,Apr:3,May:4,Jun:5,Jul:6,Aug:7,Sep:8,Oct:9,Nov:10,Dec:11};
        const m = str.match(/(\d{1,2})\s+(\w{3})\s+(\d{4}),?\s+(\d{1,2}):(\d{2})/);
        if (!m) return 0;
        return new Date(+m[3], months[m[2]] || 0, +m[1], +m[4], +m[5]).getTime();
    }

    function sortTable(col, dir) {
        const tbody = document.querySelector('#kategoriTable tbody');
        const rows  = Array.from(tbody.querySelectorAll('tr')).filter(r => r.cells.length > 1);
        const type  = COL_TYPES[col] || 'text';
        rows.sort((a, b) => {
            const aT = a.cells[col]?.innerText.trim() ?? '';
            const bT = b.cells[col]?.innerText.trim() ?? '';
            if (type === 'date') {
                const aD = parseDate(aT), bD = parseDate(bT);
                return dir === 'asc' ? aD - bD : bD - aD;
            }
            const aL = aT.toLowerCase(), bL = bT.toLowerCase();
            if (aL < bL) return dir === 'asc' ? -1 : 1;
            if (aL > bL) return dir === 'asc' ?  1 : -1;
            return 0;
        });
        rows.forEach(row => tbody.appendChild(row));
    }

    function openAddModal()  { document.getElementById('modalAdd').classList.add('show'); document.body.style.overflow = 'hidden'; }

    function openEditModal(id, categoryName, description, status) {
        document.getElementById('editForm').action         = '/kategoris/' + id;
        document.getElementById('editCategoryName').value  = categoryName;
        document.getElementById('editDescription').value   = description;
        document.getElementById('editStatus').value        = status;
        document.getElementById('modalEdit').classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function openDeleteModal(id, name) {
        document.getElementById('deleteCategoryName').textContent = name;
        document.getElementById('deleteForm').action = '/kategoris/' + id;
        document.getElementById('modalDelete').classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function executeDelete() { document.getElementById('deleteForm').submit(); }

    function closeModal(id) {
        document.getElementById(id).classList.remove('show');
        document.body.style.overflow = '';
    }

    function filterTable() {
        const input = document.getElementById('searchInput').value.toLowerCase();
        document.querySelectorAll('#kategoriTable tbody tr').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(input) ? '' : 'none';
        });
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') ['modalAdd','modalEdit','modalDelete'].forEach(id => closeModal(id));
    });
</script>

@endsection