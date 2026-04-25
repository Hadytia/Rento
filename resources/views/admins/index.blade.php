@extends('layouts.app')

@section('content')

<style>
    /* ── Base ── */
    .page-header { display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:24px; }
    .page-title h1 { font-family:Inter,sans-serif; font-size:22px; font-weight:700; color:#1E1E1E; margin:0; }
    .page-title p  { font-family:Inter,sans-serif; font-size:13px; color:#6B6B6B; margin:4px 0 0 0; }
    .btn-add {
        height:40px; padding:0 18px; background:#2D4DA3; color:#FFF; border:none;
        border-radius:10px; font-family:Inter,sans-serif; font-size:13px; font-weight:600;
        cursor:pointer; display:flex; align-items:center; gap:7px; white-space:nowrap;
        box-shadow:0 2px 8px rgba(45,77,163,.18); transition:background .15s;
    }
    .btn-add:hover { background:#253f8a; }

    .alert-success {
        background:#ECFDF5; border:1px solid #6EE7B7; border-radius:10px;
        padding:11px 16px; font-family:Inter,sans-serif; font-size:13px; color:#065F46;
        margin-bottom:18px; display:flex; align-items:center; gap:8px;
    }

    /* ── Pending Dosen Panel ── */
    .pending-panel {
        background:#FFF; border-radius:14px; padding:18px;
        box-shadow:0 2px 10px rgba(0,0,0,.06); border:1px solid #FED7AA;
        margin-bottom:20px;
    }
    .pending-header { display:flex; align-items:center; gap:8px; margin-bottom:4px; }
    .pending-dot { width:8px; height:8px; border-radius:50%; background:#F59E0B; flex-shrink:0; box-shadow:0 0 0 3px rgba(245,158,11,.2); }
    .pending-header span { font-family:Inter,sans-serif; font-size:14px; font-weight:700; color:#B45309; }
    .pending-count { background:#F59E0B; color:white; border-radius:20px; padding:1px 8px; font-size:11px; font-weight:700; font-family:Inter,sans-serif; }
    .pending-sub { font-family:Inter,sans-serif; font-size:12px; color:#94A3B8; margin-bottom:14px; padding-left:16px; }
    .pending-cards { display:flex; flex-wrap:wrap; gap:10px; }
    .pending-card {
        background:#FFFBEB; border:1px solid #FDE68A; border-radius:12px;
        padding:14px 16px; flex:1; min-width:260px; max-width:360px;
        display:flex; align-items:center; justify-content:space-between; gap:12px;
    }
    .pending-info { display:flex; align-items:center; gap:10px; }
    .pending-actions { display:flex; gap:8px; flex-shrink:0; }
    .btn-acc {
        height:34px; padding:0 14px; background:#22C55E; border:none; border-radius:9px;
        font-family:Inter,sans-serif; font-size:12px; font-weight:700; color:white;
        cursor:pointer; display:flex; align-items:center; gap:5px; transition:background .15s;
        box-shadow:0 2px 5px rgba(34,197,94,.25);
    }
    .btn-acc:hover { background:#16A34A; }
    .btn-reject {
        height:34px; padding:0 14px; background:#FEF2F2; border:1px solid #FECACA;
        border-radius:9px; font-family:Inter,sans-serif; font-size:12px; font-weight:700;
        color:#DC2626; cursor:pointer; display:flex; align-items:center; gap:5px; transition:all .15s;
    }
    .btn-reject:hover { background:#FEE2E2; }

    /* ── Table Container ── */
    .table-container { background:#FFF; border-radius:14px; padding:22px; box-shadow:0 2px 12px rgba(0,0,0,.07); }
    .table-toolbar { display:flex; align-items:center; justify-content:space-between; margin-bottom:18px; }
    .table-label { font-family:Inter,sans-serif; font-size:14px; font-weight:700; color:#0F172A; }
    .count-badge {
        display:inline-flex; align-items:center; justify-content:center;
        background:#EFF6FF; color:#2D4DA3; border-radius:20px;
        padding:2px 10px; font-size:12px; font-weight:700; margin-left:8px; font-family:Inter,sans-serif;
    }
    .search-wrap {
        display:flex; align-items:center; gap:8px; border:1px solid #E5E5E5;
        border-radius:9px; padding:0 12px; height:38px; background:#FAFAFA; width:230px; transition:border-color .15s;
    }
    .search-wrap:focus-within { border-color:#2D4DA3; background:#fff; }
    .search-wrap input { border:none; outline:none; font-family:Inter,sans-serif; font-size:13px; color:#1E1E1E; width:100%; background:transparent; }
    .search-wrap input::placeholder { color:#B0B0B0; }

    table { width:100%; border-collapse:collapse; }
    thead tr { background:#F8FAFC; }
    thead th {
        font-family:Inter,sans-serif; font-size:11px; font-weight:700; color:#94A3B8;
        letter-spacing:.06em; text-transform:uppercase; padding:11px 14px; text-align:left; white-space:nowrap;
    }
    tbody tr { border-bottom:1px solid #F1F5F9; transition:background .1s; }
    tbody tr:last-child { border-bottom:none; }
    tbody tr:hover { background:#F8FAFC; }
    tbody td { font-family:Inter,sans-serif; font-size:13px; color:#1E1E1E; padding:13px 14px; vertical-align:middle; }

    /* ── Sort ── */
    .sortable { cursor:pointer; user-select:none; transition:color .15s,background .15s; }
    .sortable:hover { color:#2D4DA3; background:#EFF6FF; }
    .th-inner { display:inline-flex; align-items:center; gap:7px; }
    .sort-icon { display:inline-flex; flex-direction:column; align-items:center; gap:2px; flex-shrink:0; }
    .sort-icon svg { width:9px; height:6px; display:block; transition:fill .15s; }
    .sortable:not(.sort-active) .tri-up   { fill:#CBD5E1; }
    .sortable:not(.sort-active) .tri-down { fill:#CBD5E1; }
    .sortable:hover:not(.sort-active) .tri-up   { fill:#94A3B8; }
    .sortable:hover:not(.sort-active) .tri-down { fill:#94A3B8; }
    th.sort-active { color:#2D4DA3; background:#EFF6FF; }
    th.sort-active.asc  .tri-up   { fill:#2D4DA3; }
    th.sort-active.asc  .tri-down { fill:#BFDBFE; }
    th.sort-active.desc .tri-up   { fill:#BFDBFE; }
    th.sort-active.desc .tri-down { fill:#2D4DA3; }
    .sort-badge {
        display:inline-flex; align-items:center; background:#2D4DA3; color:white;
        font-size:9px; font-weight:700; padding:1px 5px; border-radius:4px;
        letter-spacing:.5px; margin-left:2px; opacity:0; transition:opacity .15s;
    }
    th.sort-active .sort-badge { opacity:1; }

    /* ── Avatar ── */
    .av-wrap { display:flex; align-items:center; gap:11px; }
    .av { width:38px; height:38px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:13px; font-weight:700; color:white; flex-shrink:0; }
    .av-name  { font-weight:700; color:#0F172A; font-size:13px; }
    .av-email { font-size:11px; color:#94A3B8; margin-top:1px; }

    /* ── Auth cell ── */
    .auth-username { font-size:13px; color:#0F172A; font-weight:600; font-family:'Consolas',monospace; }
    .auth-date     { font-size:11px; color:#94A3B8; margin-top:2px; }

    /* ── Role badge ── */
    .role-name { font-weight:700; color:#0F172A; font-size:13px; margin-bottom:4px; }
    .role-badge { display:inline-flex; align-items:center; border-radius:6px; padding:2px 9px; font-size:11px; font-weight:700; font-family:Inter,sans-serif; }
    .role-superadmin { background:#F3E8FF; color:#7C3AED; }
    .role-admin      { background:#EFF6FF; color:#2D4DA3; }
    .role-staff      { background:#F0FDF4; color:#16A34A; }
    .role-dosen      { background:#FFFBEB; color:#D97706; }

    /* ── Edit access ── */
    .access-full { display:inline-flex; align-items:center; gap:5px; background:#ECFDF5; color:#059669; border:1px solid #6EE7B7; border-radius:20px; padding:4px 12px; font-size:12px; font-weight:600; font-family:Inter,sans-serif; }
    .access-full .dot { width:6px; height:6px; border-radius:50%; background:#059669; }
    .btn-toggle-edit {
        display:inline-flex; align-items:center; gap:6px; border-radius:20px;
        padding:4px 12px; font-size:12px; font-weight:600; font-family:Inter,sans-serif;
        border:1px solid; cursor:pointer; transition:all .15s;
    }
    .toggle-given    { background:#ECFDF5; color:#059669; border-color:#6EE7B7; }
    .toggle-given:hover { background:#D1FAE5; }
    .toggle-viewonly { background:#F1F5F9; color:#64748B; border-color:#CBD5E1; }
    .toggle-viewonly:hover { background:#E2E8F0; }
    .toggle-dot { width:6px; height:6px; border-radius:50%; }

    /* ── Status badge ── */
    .status-badge { display:inline-flex; align-items:center; gap:5px; border-radius:20px; padding:4px 12px; font-size:12px; font-weight:600; font-family:Inter,sans-serif; border:1px solid; }
    .status-badge .dot { width:6px; height:6px; border-radius:50%; }
    .badge-aktif    { background:#ECFDF5; color:#059669; border-color:#6EE7B7; }
    .badge-aktif .dot    { background:#059669; }
    .badge-nonaktif { background:#FEF2F2; color:#DC2626; border-color:#FECACA; }
    .badge-nonaktif .dot { background:#DC2626; }

    /* ── Action buttons ── */
    .action-wrap { display:flex; gap:6px; }
    .action-btn {
        height:32px; padding:0 12px; border-radius:8px; font-family:Inter,sans-serif;
        font-size:12px; font-weight:600; cursor:pointer; display:inline-flex; align-items:center;
        gap:5px; transition:all .15s; border:none;
    }
    .btn-edit-row   { background:#EFF6FF; color:#2D4DA3; border:1px solid #BFDBFE; }
    .btn-edit-row:hover   { background:#DBEAFE; }
    .btn-delete-row { background:#FEF2F2; color:#DC2626; border:1px solid #FECACA; }
    .btn-delete-row:hover { background:#FEE2E2; }
    .action-btn svg { width:13px; height:13px; }

    .empty-row { text-align:center; padding:40px 0; color:#94A3B8; font-family:Inter,sans-serif; font-size:13px; }

    /* ── Modals ── */
    .modal-overlay { display:none; position:fixed; inset:0; z-index:999; align-items:center; justify-content:center; padding:20px; }
    .modal-overlay.show { display:flex; }
    .modal-backdrop { position:fixed; inset:0; background:rgba(15,23,42,.5); backdrop-filter:blur(3px); }

    .form-modal {
        position:relative; z-index:1; background:#FFF; border-radius:16px;
        width:100%; max-width:480px; max-height:90vh; overflow-y:auto;
        box-shadow:0 20px 50px rgba(0,0,0,.18);
    }
    .modal-header {
        padding:20px 24px 16px; border-bottom:1px solid #F1F5F9;
        display:flex; align-items:center; justify-content:space-between;
        position:sticky; top:0; background:white; z-index:2;
    }
    .modal-header h2 { font-family:Inter,sans-serif; font-size:16px; font-weight:700; color:#0F172A; margin:0; }
    .modal-close { width:28px; height:28px; background:#F1F5F9; border:none; border-radius:7px; cursor:pointer; display:flex; align-items:center; justify-content:center; color:#64748B; font-size:14px; }
    .modal-close:hover { background:#E2E8F0; }
    .modal-body { padding:20px 24px; }
    .form-section { font-family:Inter,sans-serif; font-size:11px; font-weight:700; color:#94A3B8; text-transform:uppercase; letter-spacing:.06em; margin:16px 0 10px; }
    .form-section:first-child { margin-top:0; }
    .form-divider { height:1px; background:#F1F5F9; margin:14px 0; }
    .form-grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
    .form-group { margin-bottom:12px; }
    .form-group:last-child { margin-bottom:0; }
    .form-group label { display:block; font-family:Inter,sans-serif; font-size:12px; font-weight:600; color:#374151; margin-bottom:5px; text-transform:uppercase; letter-spacing:.04em; }
    .form-group input,.form-group select {
        width:100%; border:1px solid #E2E8F0; border-radius:9px; padding:9px 13px;
        font-family:Inter,sans-serif; font-size:13.5px; color:#0F172A; outline:none;
        box-sizing:border-box; background:#FAFAFA; transition:border-color .15s;
    }
    .form-group input:focus,.form-group select:focus { border-color:#2D4DA3; background:#fff; box-shadow:0 0 0 3px rgba(45,77,163,.08); }
    .modal-footer { padding:16px 24px; border-top:1px solid #F1F5F9; display:flex; gap:10px; justify-content:flex-end; }
    .btn-cancel { height:38px; padding:0 18px; background:white; border:1px solid #E2E8F0; border-radius:9px; font-family:Inter,sans-serif; font-size:13px; font-weight:500; cursor:pointer; color:#374151; }
    .btn-cancel:hover { background:#F8FAFC; }
    .btn-save { height:38px; padding:0 20px; background:#2D4DA3; border:none; border-radius:9px; font-family:Inter,sans-serif; font-size:13px; font-weight:600; color:#FFF; cursor:pointer; box-shadow:0 2px 6px rgba(45,77,163,.2); }
    .btn-save:hover { background:#253f8a; }

    .toggle-wrap { display:flex; align-items:center; justify-content:space-between; padding:12px 0; }
    .toggle-label { font-family:Inter,sans-serif; font-size:13px; font-weight:600; color:#374151; }
    .toggle-slider { width:42px; height:22px; background:#E2E8F0; border-radius:20px; position:relative; cursor:pointer; transition:background .2s; }
    .toggle-slider.on { background:#2D4DA3; }
    .toggle-thumb { width:18px; height:18px; background:white; border-radius:50%; position:absolute; top:2px; left:2px; transition:left .2s; box-shadow:0 1px 3px rgba(0,0,0,.2); }
    .toggle-slider.on .toggle-thumb { left:22px; }
    .toggle-text { font-family:Inter,sans-serif; font-size:13px; color:#64748B; margin-left:10px; }
    .pw-reveal { font-family:Inter,sans-serif; font-size:11px; color:#2D4DA3; font-weight:600; cursor:pointer; }
    .pw-reveal:hover { text-decoration:underline; }

    .confirm-box { position:relative; z-index:1; background:#FFF; border-radius:18px; width:100%; max-width:380px; box-shadow:0 25px 60px rgba(0,0,0,.18); overflow:hidden; }
    .confirm-accent { height:4px; width:100%; }
    .confirm-body { padding:24px 24px 16px; }
    .confirm-icon-wrap { width:50px; height:50px; border-radius:14px; display:flex; align-items:center; justify-content:center; margin-bottom:14px; }
    .confirm-icon-wrap svg { width:22px; height:22px; }
    .confirm-subtitle { font-family:Inter,sans-serif; font-size:11px; font-weight:700; color:#94A3B8; text-transform:uppercase; letter-spacing:.06em; margin-bottom:3px; }
    .confirm-title    { font-family:Inter,sans-serif; font-size:16px; font-weight:700; color:#0F172A; margin-bottom:8px; }
    .confirm-desc     { font-family:Inter,sans-serif; font-size:13px; color:#64748B; line-height:1.6; }
    .confirm-footer   { padding:12px 24px 20px; display:flex; gap:10px; }
    .btn-cf-cancel { flex:1; height:40px; border:1.5px solid #E2E8F0; border-radius:11px; font-family:Inter,sans-serif; font-size:13px; font-weight:600; color:#64748B; background:white; cursor:pointer; }
    .btn-cf-cancel:hover { background:#F8FAFC; }
    .btn-cf-ok { flex:1; height:40px; border:none; border-radius:11px; font-family:Inter,sans-serif; font-size:13px; font-weight:700; color:white; cursor:pointer; }
</style>

{{-- Header --}}
<div class="page-header">
    <div class="page-title">
        <h1>Kelola Akun Admin</h1>
        <p>Kelola akun administrator, atur hak akses, dan lacak aktivitas audit.</p>
    </div>
    <button class="btn-add" onclick="openAddModal()">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Tambah Data Admin
    </button>
</div>

@if (session('success'))
    <div class="alert-success">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22,4 12,14.01 9,11.01"/>
        </svg>
        {{ session('success') }}
    </div>
@endif

{{-- Pending Dosen --}}
@if($pendingDosens->count() > 0)
<div class="pending-panel">
    <div class="pending-header">
        <div class="pending-dot"></div>
        <span>Permintaan Akses Dosen</span>
        <span class="pending-count">{{ $pendingDosens->count() }}</span>
    </div>
    <p class="pending-sub">Dosen berikut mendaftar via Google dan menunggu persetujuan.</p>
    <div class="pending-cards">
        @foreach($pendingDosens as $dosen)
        @php $avColors = ['#2563EB','#7C3AED','#059669','#D97706','#DC2626']; @endphp
        <div class="pending-card">
            <div class="pending-info">
                <div class="av" style="background:{{ $avColors[$dosen->id % 5] }}; width:36px; height:36px; font-size:12px;">
                    {{ strtoupper(substr($dosen->name,0,2)) }}
                </div>
                <div>
                    <div style="font-family:Inter,sans-serif;font-size:13px;font-weight:700;color:#0F172A;">{{ $dosen->name }}</div>
                    <div style="font-family:Inter,sans-serif;font-size:11px;color:#94A3B8;">{{ $dosen->email }}</div>
                    <div style="font-family:Inter,sans-serif;font-size:11px;color:#94A3B8;margin-top:1px;">
                        {{ \Carbon\Carbon::parse($dosen->created_date)->format('d M Y, H:i') }}
                    </div>
                </div>
            </div>
            <div class="pending-actions">
                <button type="button" class="btn-acc"
                    onclick="openConfirmModal('approve', {{ $dosen->id }}, '{{ addslashes($dosen->name) }}')">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <polyline points="20,6 9,17 4,12"/>
                    </svg>
                    ACC
                </button>
                <button type="button" class="btn-reject"
                    onclick="openConfirmModal('reject', {{ $dosen->id }}, '{{ addslashes($dosen->name) }}')">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                    Tolak
                </button>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Table --}}
<div class="table-container">
    <div class="table-toolbar">
        <div style="display:flex;align-items:center;">
            <span class="table-label">Tabel Admin</span>
            <span class="count-badge">{{ $admins->count() }}</span>
        </div>
        <div class="search-wrap">
            <svg width="14" height="14" fill="none" stroke="#9E9E9E" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="text" placeholder="Cari admin..." id="searchInput" onkeyup="filterTable()">
        </div>
    </div>

    <table id="adminTable">
        <thead>
            <tr>
                <th style="width:130px">Aksi</th>
                <th class="sortable" data-col="1" data-type="text">
                    <span class="th-inner">Identitas Admin
                        <span class="sort-icon">
                            <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                            <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                        </span>
                        <span class="sort-badge" id="badge-1"></span>
                    </span>
                </th>
                <th class="sortable" data-col="2" data-type="text">
                    <span class="th-inner">Akun & Autentikasi
                        <span class="sort-icon">
                            <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                            <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                        </span>
                        <span class="sort-badge" id="badge-2"></span>
                    </span>
                </th>
                <th class="sortable" data-col="3" data-type="text">
                    <span class="th-inner">Role & Hak Akses
                        <span class="sort-icon">
                            <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                            <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                        </span>
                        <span class="sort-badge" id="badge-3"></span>
                    </span>
                </th>
                <th class="sortable" data-col="4" data-type="text">
                    <span class="th-inner">Akses Edit
                        <span class="sort-icon">
                            <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                            <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                        </span>
                        <span class="sort-badge" id="badge-4"></span>
                    </span>
                </th>
                <th class="sortable" data-col="5" data-type="text" style="width:100px">
                    <span class="th-inner">Status
                        <span class="sort-icon">
                            <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                            <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                        </span>
                        <span class="sort-badge" id="badge-5"></span>
                    </span>
                </th>
            </tr>
        </thead>
        <tbody>
            @php $avColors = ['#2563EB','#7C3AED','#059669','#D97706','#DC2626']; @endphp
            @forelse ($admins as $admin)
            @php
                $avColor = $avColors[$admin->id % 5];
                $roleLabel = match($admin->role) {
                    'superadmin' => 'Super Admin',
                    'admin'      => 'Admin',
                    'staff'      => 'Staff',
                    'dosen'      => 'Dosen',
                    default      => ucfirst($admin->role),
                };
                $accessLabel = match($admin->role) {
                    'superadmin' => 'Semua Akses',
                    'admin'      => 'Kelola Produk',
                    'staff'      => 'Terbatas',
                    'dosen'      => 'View Only',
                    default      => 'Terbatas',
                };
                $roleClass = match($admin->role) {
                    'superadmin' => 'role-superadmin',
                    'admin'      => 'role-admin',
                    'staff'      => 'role-staff',
                    'dosen'      => 'role-dosen',
                    default      => 'role-admin',
                };
            @endphp
            <tr>
                {{-- Aksi --}}
                <td>
                    <div class="action-wrap">
                        <button class="action-btn btn-edit-row"
                            onclick="openEditModal({{ $admin->id }},'{{ addslashes($admin->name) }}','{{ addslashes($admin->email) }}','{{ $admin->role }}','{{ $admin->status }}')">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                            Edit
                        </button>
                        <button class="action-btn btn-delete-row"
                            onclick="openConfirmModal('delete', {{ $admin->id }}, '{{ addslashes($admin->name) }}')">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <polyline points="3,6 5,6 21,6"/>
                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                <path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/>
                            </svg>
                            Hapus
                        </button>
                    </div>
                </td>

                {{-- Identitas Admin --}}
                <td>
                    <div class="av-wrap">
                        <div class="av" style="background:{{ $avColor }}">{{ strtoupper(substr($admin->name,0,2)) }}</div>
                        <div>
                            <div class="av-name">{{ $admin->name }}</div>
                            <div class="av-email">{{ $admin->email }}</div>
                        </div>
                    </div>
                </td>

                {{-- Akun & Autentikasi --}}
                <td>
                    <div class="auth-username">{{ strtolower(explode('@',$admin->email)[0]) }}</div>
                    <div class="auth-date">Dibuat: {{ \Carbon\Carbon::parse($admin->created_date)->format('d M Y') }}</div>
                </td>

                {{-- Role & Hak Akses --}}
                <td>
                    <div class="role-name">{{ $roleLabel }}</div>
                    <span class="role-badge {{ $roleClass }}">{{ $accessLabel }}</span>
                </td>

                {{-- Akses Edit --}}
                <td>
                    @if(in_array($admin->role, ['superadmin','admin','staff']))
                        <span class="access-full"><span class="dot"></span>Full</span>
                    @else
                        <button type="button" class="btn-toggle-edit {{ $admin->can_edit ? 'toggle-given' : 'toggle-viewonly' }}"
                            onclick="openConfirmModal('toggle', {{ $admin->id }}, '{{ addslashes($admin->name) }}', {{ $admin->can_edit ? 'true' : 'false' }})">
                            <span class="toggle-dot" style="background:{{ $admin->can_edit ? '#059669' : '#94A3B8' }}"></span>
                            {{ $admin->can_edit ? 'Diberikan' : 'View Only' }}
                        </button>
                    @endif
                </td>

                {{-- Status --}}
                <td>
                    <span class="status-badge {{ $admin->status ? 'badge-aktif' : 'badge-nonaktif' }}">
                        <span class="dot"></span>
                        {{ $admin->status ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="empty-row">Belum ada data admin.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- ── MODAL KONFIRMASI ── --}}
<div id="modalConfirm" class="modal-overlay">
    <div class="modal-backdrop" onclick="closeConfirmModal()"></div>
    <div class="confirm-box">
        <div class="confirm-accent" id="confirmAccent"></div>
        <div class="confirm-body">
            <div class="confirm-icon-wrap" id="confirmIconWrap">
                <svg id="confirmIcon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"></svg>
            </div>
            <div class="confirm-subtitle" id="confirmSubtitle"></div>
            <div class="confirm-title"   id="confirmTitle"></div>
            <p class="confirm-desc"      id="confirmDesc"></p>
        </div>
        <div class="confirm-footer">
            <button class="btn-cf-cancel" onclick="closeConfirmModal()">Batal</button>
            <button class="btn-cf-ok" id="confirmBtn" onclick="executeConfirm()">Konfirmasi</button>
        </div>
    </div>
</div>

<form id="formApprove" method="POST" action="" style="display:none">@csrf @method('PATCH')</form>
<form id="formReject"  method="POST" action="" style="display:none">@csrf @method('PATCH')</form>
<form id="formToggle"  method="POST" action="" style="display:none">@csrf @method('PATCH')</form>
<form id="formDelete"  method="POST" action="" style="display:none">@csrf @method('DELETE')</form>

{{-- ── MODAL ADD ── --}}
<div class="modal-overlay" id="modalAdd">
    <div class="modal-backdrop" onclick="closeModal('modalAdd')"></div>
    <div class="form-modal">
        <div class="modal-header">
            <h2>Tambah Data Admin</h2>
            <button class="modal-close" onclick="closeModal('modalAdd')">✕</button>
        </div>
        <form method="POST" action="{{ route('admins.store') }}">
            @csrf
            <div class="modal-body">
                <div class="form-section">Identitas Admin</div>
                <div class="form-group">
                    <label>Nama Lengkap <span style="color:#EF4444">*</span></label>
                    <input type="text" name="name" placeholder="Masukkan nama lengkap..." required>
                </div>
                <div class="form-group">
                    <label>Email <span style="color:#EF4444">*</span></label>
                    <input type="email" name="email" placeholder="Masukkan email..." required>
                </div>
                <div class="form-divider"></div>
                <div class="form-section">Akun & Autentikasi</div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label>Password <span style="color:#EF4444">*</span></label>
                        <input type="password" name="password" placeholder="Password..." required>
                    </div>
                    <div class="form-group">
                        <label>Konfirmasi</label>
                        <input type="password" name="password_confirmation" placeholder="Ulangi...">
                    </div>
                </div>
                <div class="form-divider"></div>
                <div class="form-section">Role & Status</div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label>Role <span style="color:#EF4444">*</span></label>
                        <select name="role" required>
                            <option value="" disabled selected>— Pilih Role —</option>
                            <option value="superadmin">Super Admin</option>
                            <option value="admin">Admin</option>
                            <option value="staff">Staff</option>
                            <option value="dosen">Dosen</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status">
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal('modalAdd')">Batal</button>
                <button type="submit" class="btn-save">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- ── MODAL EDIT ── --}}
<div class="modal-overlay" id="modalEdit">
    <div class="modal-backdrop" onclick="closeModal('modalEdit')"></div>
    <div class="form-modal">
        <div class="modal-header">
            <h2>Edit Data Admin</h2>
            <button class="modal-close" onclick="closeModal('modalEdit')">✕</button>
        </div>
        <form method="POST" id="editForm" action="">
            @csrf @method('PUT')
            <div class="modal-body">
                <div class="form-section">Identitas Admin</div>
                <div class="form-group">
                    <label>Nama Lengkap <span style="color:#EF4444">*</span></label>
                    <input type="text" name="name" id="editName" required>
                </div>
                <div class="form-group">
                    <label>Email <span style="color:#EF4444">*</span></label>
                    <input type="email" name="email" id="editEmail" required>
                </div>
                <div class="form-divider"></div>
                <div class="form-section" style="display:flex;justify-content:space-between;align-items:center;">
                    Akun & Autentikasi
                    <span class="pw-reveal" onclick="togglePasswordFields()">Ubah password</span>
                </div>
                <div id="passwordFields" style="display:none;">
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label>Password Baru</label>
                            <input type="password" name="password" placeholder="Password baru...">
                        </div>
                        <div class="form-group">
                            <label>Konfirmasi</label>
                            <input type="password" name="password_confirmation" placeholder="Ulangi...">
                        </div>
                    </div>
                </div>
                <div class="form-divider"></div>
                <div class="form-section">Role & Status</div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label>Role</label>
                        <select name="role" id="editRole">
                            <option value="superadmin">Super Admin</option>
                            <option value="admin">Admin</option>
                            <option value="staff">Staff</option>
                            <option value="dosen">Dosen</option>
                        </select>
                    </div>
                    <div>
                        <input type="hidden" name="status" id="editStatusValue" value="1">
                        <div class="toggle-wrap" style="padding:0;margin-top:22px;">
                            <span class="toggle-label" style="font-size:12px;text-transform:uppercase;letter-spacing:.04em;color:#374151;">Status</span>
                            <div style="display:flex;align-items:center;">
                                <div class="toggle-slider on" id="editToggleSlider" onclick="toggleEditStatus()">
                                    <div class="toggle-thumb"></div>
                                </div>
                                <span class="toggle-text" id="editToggleLabel">Aktif</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal('modalEdit')">Batal</button>
                <button type="submit" class="btn-save">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
    // ── Search ───────────────────────────────────────────────────────────────
    function filterTable() {
        const input = document.getElementById('searchInput').value.toLowerCase();
        document.querySelectorAll('#adminTable tbody tr').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(input) ? '' : 'none';
        });
    }

    // ── Sort ─────────────────────────────────────────────────────────────────
    const COL_TYPES = { 1:'text', 2:'text', 3:'text', 4:'text', 5:'text' };

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
            if (col === sortCol) {
                th.classList.add('sort-active');
                th.classList.remove('asc', 'desc');
                th.classList.add(sortDir);
                if (badge) badge.textContent = sortDir === 'asc' ? 'A-Z' : 'Z-A';
            } else {
                th.classList.remove('sort-active', 'asc', 'desc');
                if (badge) badge.textContent = '';
            }
        });
    }

    function sortTable(col, dir) {
        const tbody = document.querySelector('#adminTable tbody');
        const rows  = Array.from(tbody.querySelectorAll('tr')).filter(r => r.cells.length > 1);
        rows.sort((a, b) => {
            const aT = a.cells[col]?.innerText.trim().toLowerCase() ?? '';
            const bT = b.cells[col]?.innerText.trim().toLowerCase() ?? '';
            if (aT < bT) return dir === 'asc' ? -1 : 1;
            if (aT > bT) return dir === 'asc' ?  1 : -1;
            return 0;
        });
        rows.forEach(r => tbody.appendChild(r));
    }

    // ── Confirm Modal ────────────────────────────────────────────────────────
    let confirmAction = null;

    const confirmConfig = {
        approve: {
            icon:'<polyline points="20,6 9,17 4,12"/>',
            iconBg:'#DCFCE7', iconColor:'#22C55E', accent:'#22C55E',
            subtitle:'Persetujuan Akses', title:'Setujui Akses Dosen',
            btnBg:'#22C55E', btnHover:'#16A34A', btnLabel:'Ya, Setujui',
            desc: n => `Akun <strong style="color:#0F172A">${n}</strong> akan disetujui dan dapat login ke sistem.`,
        },
        reject: {
            icon:'<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>',
            iconBg:'#FEE2E2', iconColor:'#EF4444', accent:'#EF4444',
            subtitle:'Penolakan Akses', title:'Tolak Akses Dosen',
            btnBg:'#EF4444', btnHover:'#DC2626', btnLabel:'Ya, Tolak',
            desc: n => `Akun <strong style="color:#0F172A">${n}</strong> akan ditolak dan tidak dapat mengakses sistem.`,
        },
        toggle_on: {
            icon:'<rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>',
            iconBg:'#DBEAFE', iconColor:'#2563EB', accent:'#2563EB',
            subtitle:'Manajemen Hak Akses', title:'Berikan Akses Edit',
            btnBg:'#2563EB', btnHover:'#1D4ED8', btnLabel:'Ya, Berikan',
            desc: n => `<strong style="color:#0F172A">${n}</strong> akan mendapatkan hak akses edit konten di seluruh sistem.`,
        },
        toggle_off: {
            icon:'<rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 9.9-1"/><line x1="2" y1="2" x2="22" y2="22"/>',
            iconBg:'#F1F5F9', iconColor:'#64748B', accent:'#64748B',
            subtitle:'Manajemen Hak Akses', title:'Cabut Akses Edit',
            btnBg:'#64748B', btnHover:'#475569', btnLabel:'Ya, Cabut',
            desc: n => `Akses edit <strong style="color:#0F172A">${n}</strong> akan dicabut. Akun kembali ke mode <em>View Only</em>.`,
        },
        delete: {
            icon:'<polyline points="3,6 5,6 21,6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/>',
            iconBg:'#FEE2E2', iconColor:'#EF4444', accent:'#EF4444',
            subtitle:'Hapus Admin', title:'Hapus Akun Admin',
            btnBg:'#EF4444', btnHover:'#DC2626', btnLabel:'Ya, Hapus',
            desc: n => `Akun <strong style="color:#0F172A">${n}</strong> akan dihapus secara permanen.`,
        },
    };

    function openConfirmModal(type, id, name, canEdit = false) {
        const key = type === 'toggle' ? (canEdit ? 'toggle_off' : 'toggle_on') : type;
        const cfg = confirmConfig[key];
        const routes = {
            approve: `/admins/${id}/approve`,
            reject:  `/admins/${id}/reject`,
            toggle:  `/admins/${id}/toggle-edit`,
            delete:  `/admins/${id}`,
        };
        confirmAction = { formId:`form${type.charAt(0).toUpperCase()+type.slice(1)}`, action:routes[type] };

        document.getElementById('confirmAccent').style.background   = cfg.accent;
        document.getElementById('confirmIconWrap').style.background = cfg.iconBg;
        document.getElementById('confirmIcon').style.color          = cfg.iconColor;
        document.getElementById('confirmIcon').innerHTML            = cfg.icon;
        document.getElementById('confirmSubtitle').textContent      = cfg.subtitle;
        document.getElementById('confirmTitle').textContent         = cfg.title;
        document.getElementById('confirmDesc').innerHTML            = cfg.desc(name);

        const btn = document.getElementById('confirmBtn');
        btn.style.background = cfg.btnBg;
        btn.textContent      = cfg.btnLabel;
        btn.onmouseover = () => btn.style.background = cfg.btnHover;
        btn.onmouseout  = () => btn.style.background = cfg.btnBg;

        document.getElementById('modalConfirm').classList.add('show');
    }

    function closeConfirmModal() {
        document.getElementById('modalConfirm').classList.remove('show');
        confirmAction = null;
    }

    function executeConfirm() {
        if (!confirmAction) return;
        const form = document.getElementById(confirmAction.formId);
        form.action = confirmAction.action;
        form.submit();
    }

    // ── Add / Edit Modal ─────────────────────────────────────────────────────
    function openAddModal() { document.getElementById('modalAdd').classList.add('show'); }
    function closeModal(id) {
        document.getElementById(id).classList.remove('show');
        document.getElementById('passwordFields').style.display = 'none';
    }

    function openEditModal(id, name, email, role, status) {
        document.getElementById('editForm').action  = '/admins/' + id;
        document.getElementById('editName').value   = name;
        document.getElementById('editEmail').value  = email;
        document.getElementById('editRole').value   = role;

        const isActive = status == 1;
        document.getElementById('editStatusValue').value = isActive ? 1 : 0;
        const slider = document.getElementById('editToggleSlider');
        const label  = document.getElementById('editToggleLabel');
        slider.classList.toggle('on', isActive);
        label.textContent = isActive ? 'Aktif' : 'Nonaktif';

        document.getElementById('modalEdit').classList.add('show');
    }

    let editStatusActive = true;
    function toggleEditStatus() {
        editStatusActive = !editStatusActive;
        document.getElementById('editStatusValue').value = editStatusActive ? 1 : 0;
        document.getElementById('editToggleSlider').classList.toggle('on', editStatusActive);
        document.getElementById('editToggleLabel').textContent = editStatusActive ? 'Aktif' : 'Nonaktif';
    }

    function togglePasswordFields() {
        const pf = document.getElementById('passwordFields');
        pf.style.display = pf.style.display === 'none' ? 'block' : 'none';
    }

    document.querySelectorAll('#modalAdd, #modalEdit').forEach(overlay => {
        overlay.addEventListener('click', function(e) {
            if (e.target === this) closeModal(this.id);
        });
    });
    document.getElementById('modalConfirm').addEventListener('click', function(e) {
        if (e.target === this) closeConfirmModal();
    });
</script>

@endsection