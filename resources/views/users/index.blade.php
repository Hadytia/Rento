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

    /* ── Alert ── */
    .alert-success {
        background:#ECFDF5; border:1px solid #6EE7B7; border-radius:10px;
        padding:11px 16px; font-family:Inter,sans-serif; font-size:13px; color:#065F46;
        margin-bottom:18px; display:flex; align-items:center; gap:8px;
    }

    /* ── Stat Cards ── */
    .stats-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:20px; }
    .stat-card {
        background:white; border-radius:12px; padding:18px 20px;
        border:1px solid #E5E7EB; display:flex; align-items:center; gap:14px;
    }
    .stat-icon {
        width:44px; height:44px; border-radius:11px;
        display:flex; align-items:center; justify-content:center; flex-shrink:0;
    }
    .stat-icon svg { width:20px; height:20px; }
    .stat-icon.blue   { background:#EFF6FF; color:#2D4DA3; }
    .stat-icon.green  { background:#ECFDF5; color:#059669; }
    .stat-icon.red    { background:#FEF2F2; color:#DC2626; }
    .stat-label { font-family:Inter,sans-serif; font-size:11px; font-weight:600; color:#6B7280; text-transform:uppercase; letter-spacing:.5px; margin-bottom:3px; }
    .stat-value { font-family:Inter,sans-serif; font-size:26px; font-weight:700; color:#0F172A; line-height:1; }

    /* ── Table Container ── */
    .table-container { background:#FFF; border-radius:14px; padding:22px; box-shadow:0 2px 12px rgba(0,0,0,.07); }
    .table-toolbar { display:flex; align-items:center; justify-content:space-between; margin-bottom:18px; }
    .table-label { font-family:Inter,sans-serif; font-size:14px; font-weight:600; color:#1E1E1E; }
    .count-badge {
        display:inline-flex; align-items:center; justify-content:center;
        background:#EFF6FF; color:#2D4DA3; border-radius:20px; padding:2px 10px;
        font-size:12px; font-weight:700; margin-left:8px; font-family:Inter,sans-serif;
    }
    .search-wrap {
        display:flex; align-items:center; gap:8px; border:1px solid #E5E5E5;
        border-radius:9px; padding:0 12px; height:38px; background:#FAFAFA; width:230px; transition:border-color .15s;
    }
    .search-wrap:focus-within { border-color:#2D4DA3; background:#fff; }
    .search-wrap input { border:none; outline:none; font-family:Inter,sans-serif; font-size:13px; color:#1E1E1E; width:100%; background:transparent; }
    .search-wrap input::placeholder { color:#B0B0B0; }

    /* ── Table ── */
    table { width:100%; border-collapse:collapse; }
    thead tr { background:#F8FAFC; }
    thead th {
        font-family:Inter,sans-serif; font-size:11px; font-weight:700; color:#94A3B8;
        letter-spacing:.06em; text-transform:uppercase; padding:11px 16px; text-align:left; white-space:nowrap;
    }
    tbody tr { border-bottom:1px solid #F1F5F9; transition:background .1s; }
    tbody tr:last-child { border-bottom:none; }
    tbody tr:hover { background:#F8FAFC; }
    tbody td { font-family:Inter,sans-serif; font-size:13px; color:#1E1E1E; padding:13px 16px; vertical-align:middle; }

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

    /* ── Identity Cell ── */
    .identity-cell { display:flex; align-items:center; gap:10px; }
    .avatar {
        width:36px; height:36px; border-radius:50%; color:white;
        display:flex; align-items:center; justify-content:center;
        font-size:12px; font-weight:700; flex-shrink:0; letter-spacing:.3px;
    }
    .identity-name { font-weight:600; color:#0F172A; font-size:13px; margin-bottom:2px; }
    .identity-email { font-size:11px; color:#94A3B8; }

    /* ── Contact Cell ── */
    .contact-phone { font-size:13px; color:#374151; font-weight:500; margin-bottom:2px; }
    .contact-address { font-size:11px; color:#94A3B8; max-width:160px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }

    /* ── KTP Mono ── */
    .ktp-mono {
        font-size:11.5px; color:#374151; font-family:monospace;
        background:#F3F4F6; padding:3px 8px; border-radius:5px; letter-spacing:.3px;
    }

    /* ── Company Badge ── */
    .company-pill {
        display:inline-block; font-size:11px; font-weight:700; padding:3px 10px;
        border-radius:20px; background:#EFF6FF; color:#2D4DA3; letter-spacing:.3px;
        font-family:Inter,sans-serif; white-space:nowrap;
    }

    /* ── Status Badge ── */
    .badge {
        display:inline-flex; align-items:center; gap:5px; border-radius:20px;
        padding:4px 12px; font-family:Inter,sans-serif; font-size:12px; font-weight:600;
    }
    .badge.active   { background:#ECFDF5; color:#059669; border:1px solid #6EE7B7; }
    .badge.inactive { background:#FEF2F2; color:#DC2626; border:1px solid #FECACA; }
    .badge-dot { width:6px; height:6px; border-radius:50%; display:inline-block; }
    .badge.active .badge-dot   { background:#059669; }
    .badge.inactive .badge-dot { background:#DC2626; }

    /* ── Action Buttons ── */
    .action-wrap { display:flex; gap:6px; }
    .action-btn {
        height:32px; padding:0 12px; border-radius:8px; font-family:Inter,sans-serif;
        font-size:12px; font-weight:600; cursor:pointer; display:inline-flex; align-items:center;
        gap:5px; transition:all .15s; border:none;
    }
    .btn-edit   { background:#EFF6FF; color:#2D4DA3; border:1px solid #BFDBFE; }
    .btn-edit:hover { background:#DBEAFE; }
    .btn-delete { background:#FEF2F2; color:#DC2626; border:1px solid #FECACA; }
    .btn-delete:hover { background:#FEE2E2; }
    .action-btn svg { width:13px; height:13px; }

    /* ── Empty ── */
    .empty-state { text-align:center; padding:56px 0; color:#94A3B8; }
    .empty-state svg { width:44px; height:44px; margin-bottom:12px; opacity:.3; }
    .empty-state p { font-size:14px; margin:0; font-family:Inter,sans-serif; }

    /* ── Modals ── */
    .modal-overlay { display:none; position:fixed; inset:0; z-index:999; align-items:center; justify-content:center; padding:20px; }
    .modal-overlay.show { display:flex; }
    .modal-backdrop { position:fixed; inset:0; background:rgba(15,23,42,.5); backdrop-filter:blur(3px); }
    .modal-box {
        position:relative; z-index:1; background:#FFF; border-radius:16px;
        width:100%; max-width:520px; max-height:90vh; overflow-y:auto;
        box-shadow:0 20px 50px rgba(0,0,0,.18);
    }
    .modal-header {
        padding:20px 24px 16px; border-bottom:1px solid #F1F5F9;
        display:flex; align-items:flex-start; justify-content:space-between;
        position:sticky; top:0; background:white; z-index:2;
    }
    .modal-header h2 { font-family:Inter,sans-serif; font-size:16px; font-weight:700; color:#0F172A; margin:0; }
    .modal-header p  { font-family:Inter,sans-serif; font-size:12px; color:#94A3B8; margin:3px 0 0 0; }
    .modal-close {
        width:28px; height:28px; background:#F1F5F9; border:none; border-radius:7px;
        cursor:pointer; display:flex; align-items:center; justify-content:center; color:#64748B; font-size:14px; flex-shrink:0;
    }
    .modal-close:hover { background:#E2E8F0; }
    .modal-body { padding:20px 24px; }
    .modal-section { font-family:Inter,sans-serif; font-size:11px; font-weight:700; color:#94A3B8; text-transform:uppercase; letter-spacing:.06em; margin:16px 0 10px; }
    .modal-section:first-child { margin-top:0; }
    .form-divider { height:1px; background:#F1F5F9; margin:14px 0; }
    .form-grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
    .form-group { margin-bottom:12px; }
    .form-group:last-child { margin-bottom:0; }
    .form-group label {
        display:block; font-family:Inter,sans-serif; font-size:12px; font-weight:600;
        color:#374151; margin-bottom:5px; text-transform:uppercase; letter-spacing:.04em;
    }
    .form-group input, .form-group select {
        width:100%; border:1px solid #E2E8F0; border-radius:9px; padding:9px 13px;
        font-family:Inter,sans-serif; font-size:13px; color:#0F172A; outline:none;
        box-sizing:border-box; background:#FAFAFA; transition:border-color .15s,background .15s;
    }
    .form-group input:focus, .form-group select:focus {
        border-color:#2D4DA3; background:#fff; box-shadow:0 0 0 3px rgba(45,77,163,.08);
    }
    .toggle-row {
        display:flex; align-items:center; justify-content:space-between;
        background:#F8FAFC; border-radius:10px; padding:12px 14px; margin-top:4px;
    }
    .toggle-label-wrap .tl-title { font-family:Inter,sans-serif; font-size:13px; font-weight:600; color:#0F172A; }
    .toggle-label-wrap .tl-sub   { font-family:Inter,sans-serif; font-size:11px; color:#94A3B8; margin-top:1px; }
    .toggle-wrap { display:flex; align-items:center; gap:8px; }
    .toggle-slider {
        width:40px; height:22px; border-radius:11px; position:relative;
        cursor:pointer; transition:background .2s;
    }
    .toggle-thumb {
        width:16px; height:16px; background:white; border-radius:50%;
        position:absolute; top:3px; transition:left .2s; box-shadow:0 1px 3px rgba(0,0,0,.2);
    }
    .toggle-text { font-family:Inter,sans-serif; font-size:12px; font-weight:600; }
    .modal-footer {
        padding:16px 24px; border-top:1px solid #F1F5F9; display:flex; gap:10px;
        justify-content:flex-end; position:sticky; bottom:0; background:white;
    }
    .btn-cancel {
        height:38px; padding:0 18px; background:white; border:1px solid #E2E8F0;
        border-radius:9px; font-family:Inter,sans-serif; font-size:13px; font-weight:500;
        cursor:pointer; color:#374151; transition:background .15s;
    }
    .btn-cancel:hover { background:#F8FAFC; }
    .btn-save {
        height:38px; padding:0 20px; background:#2D4DA3; border:none; border-radius:9px;
        font-family:Inter,sans-serif; font-size:13px; font-weight:600; color:#FFF;
        cursor:pointer; box-shadow:0 2px 6px rgba(45,77,163,.2); transition:background .15s;
    }
    .btn-save:hover { background:#253f8a; }

    /* ── Confirm Modal ── */
    .confirm-box {
        position:relative; z-index:1; background:#FFF; border-radius:18px;
        width:100%; max-width:380px; box-shadow:0 25px 60px rgba(0,0,0,.18); overflow:hidden;
    }
    .confirm-accent { height:4px; width:100%; background:#EF4444; }
    .confirm-body { padding:24px 24px 20px; }
    .confirm-icon-wrap {
        width:52px; height:52px; border-radius:14px; background:#FEF2F2;
        display:flex; align-items:center; justify-content:center; margin-bottom:14px;
    }
    .confirm-icon-wrap svg { width:24px; height:24px; color:#EF4444; }
    .confirm-subtitle { font-family:Inter,sans-serif; font-size:11px; font-weight:700; color:#94A3B8; text-transform:uppercase; letter-spacing:.06em; margin-bottom:4px; }
    .confirm-title   { font-family:Inter,sans-serif; font-size:16px; font-weight:700; color:#0F172A; margin-bottom:8px; }
    .confirm-desc    { font-family:Inter,sans-serif; font-size:13px; color:#64748B; line-height:1.6; }
    .confirm-footer  { padding:14px 24px 20px; display:flex; gap:10px; }
    .btn-confirm-cancel {
        flex:1; height:40px; border:1.5px solid #E2E8F0; border-radius:11px;
        font-family:Inter,sans-serif; font-size:13px; font-weight:600; color:#64748B;
        background:white; cursor:pointer; transition:background .15s;
    }
    .btn-confirm-cancel:hover { background:#F8FAFC; }
    .btn-confirm-delete {
        flex:1; height:40px; border:none; border-radius:11px;
        font-family:Inter,sans-serif; font-size:13px; font-weight:700; color:white;
        background:#EF4444; cursor:pointer; box-shadow:0 2px 8px rgba(239,68,68,.25); transition:background .15s;
    }
    .btn-confirm-delete:hover { background:#DC2626; }
</style>

{{-- Page Header --}}
<div class="page-header">
    <div class="page-title">
        <h1>Kelola Data User</h1>
        <p>Kelola data pelanggan, informasi kontak, dan status akun.</p>
    </div>
    <button class="btn-add" onclick="openAddModal()">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Tambah User
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

{{-- Stats --}}
@php
    $totalUsers    = $users->count();
    $activeUsers   = $users->where('status', 1)->count();
    $inactiveUsers = $users->where('status', 0)->count();
@endphp
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue">
            <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
        </div>
        <div>
            <div class="stat-label">Total User</div>
            <div class="stat-value">{{ $totalUsers }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">
            <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                <polyline points="22,4 12,14.01 9,11.01"/>
            </svg>
        </div>
        <div>
            <div class="stat-label">Aktif</div>
            <div class="stat-value" style="color:#059669;">{{ $activeUsers }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red">
            <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/>
                <line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
            </svg>
        </div>
        <div>
            <div class="stat-label">Nonaktif</div>
            <div class="stat-value" style="color:#DC2626;">{{ $inactiveUsers }}</div>
        </div>
    </div>
</div>

{{-- Table --}}
<div class="table-container">
    <div class="table-toolbar">
        <div style="display:flex;align-items:center;">
            <span class="table-label">Daftar User</span>
            <span class="count-badge">{{ $totalUsers }}</span>
        </div>
        <div class="search-wrap">
            <svg width="14" height="14" fill="none" stroke="#9E9E9E" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="text" id="searchInput" placeholder="Cari user..." onkeyup="filterTable()">
        </div>
    </div>

    <div style="overflow-x:auto;">
    <table id="userTable">
        <thead>
            <tr>
                <th style="width:90px">Aksi</th>
                <th class="sortable" data-col="1" data-type="text">
                    <span class="th-inner">Identitas
                        <span class="sort-icon">
                            <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                            <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                        </span>
                        <span class="sort-badge" id="badge-1"></span>
                    </span>
                </th>
                <th class="sortable" data-col="2" data-type="text">
                    <span class="th-inner">Kontak
                        <span class="sort-icon">
                            <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                            <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                        </span>
                        <span class="sort-badge" id="badge-2"></span>
                    </span>
                </th>
                <th style="min-width:140px">No. KTP</th>
                <th style="min-width:140px">Kontak Darurat</th>
                <th class="sortable" data-col="5" data-type="text">
                    <span class="th-inner">Kode Perusahaan
                        <span class="sort-icon">
                            <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                            <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                        </span>
                        <span class="sort-badge" id="badge-5"></span>
                    </span>
                </th>
                <th class="sortable" data-col="6" data-type="text">
                    <span class="th-inner">Status
                        <span class="sort-icon">
                            <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                            <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                        </span>
                        <span class="sort-badge" id="badge-6"></span>
                    </span>
                </th>
            </tr>
        </thead>
        <tbody>
            @php
                $avColors = ['#2D4DA3','#7C3AED','#059669','#D97706','#DC2626','#0891B2'];
            @endphp
            @forelse ($users as $user)
            @php
                $avColor  = $avColors[$user->id % count($avColors)];
                $initials = strtoupper(substr($user->name, 0, 1))
                          . strtoupper(substr(strstr($user->name, ' ') ?: ' ', 1, 1));
            @endphp
            <tr>
                {{-- Aksi --}}
                <td>
                    <div class="action-wrap">
                        <button class="action-btn btn-edit"
                            onclick="openEditModal(
                                {{ $user->id }},
                                '{{ addslashes($user->name) }}',
                                '{{ addslashes($user->email) }}',
                                '{{ addslashes($user->phone) }}',
                                '{{ addslashes($user->address) }}',
                                '{{ addslashes($user->id_card_number) }}',
                                '{{ addslashes($user->emergency_contact) }}',
                                '{{ addslashes($user->company_code) }}',
                                {{ $user->status }}
                            )" title="Edit">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                            Edit
                        </button>
                        <button class="action-btn btn-delete"
                            onclick="openDeleteModal({{ $user->id }}, '{{ addslashes($user->name) }}')" title="Hapus">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <polyline points="3,6 5,6 21,6"/>
                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                <path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/>
                            </svg>
                            Hapus
                        </button>
                    </div>
                </td>

                {{-- Identitas --}}
                <td>
                    <div class="identity-cell">
                        <div class="avatar" style="background:{{ $avColor }}">{{ $initials }}</div>
                        <div>
                            <div class="identity-name">{{ $user->name }}</div>
                            <div class="identity-email">{{ $user->email }}</div>
                        </div>
                    </div>
                </td>

                {{-- Kontak --}}
                <td>
                    <div class="contact-phone">{{ $user->phone ?: '-' }}</div>
                    <div class="contact-address">{{ $user->address ?: '-' }}</div>
                </td>

                {{-- No KTP --}}
                <td>
                    <span class="ktp-mono">{{ $user->id_card_number ?: '-' }}</span>
                </td>

                {{-- Kontak Darurat --}}
                <td style="font-size:13px; color:#374151;">{{ $user->emergency_contact ?: '-' }}</td>

                {{-- Company Code --}}
                <td>
                    @if($user->company_code)
                        <span class="company-pill">{{ $user->company_code }}</span>
                    @else
                        <span style="color:#D1D5DB; font-size:12px;">-</span>
                    @endif
                </td>

                {{-- Status --}}
                <td>
                    <span class="badge {{ $user->status ? 'active' : 'inactive' }}">
                        <span class="badge-dot"></span>
                        {{ $user->status ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7">
                    <div class="empty-state">
                        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                        <p>Belum ada data user.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>

{{-- ── MODAL ADD ── --}}
<div class="modal-overlay" id="modalAdd">
    <div class="modal-backdrop" onclick="closeModal('modalAdd')"></div>
    <div class="modal-box">
        <div class="modal-header">
            <div>
                <h2>Tambah User</h2>
                <p>Isi data pelanggan baru.</p>
            </div>
            <button class="modal-close" onclick="closeModal('modalAdd')">✕</button>
        </div>
        <form method="POST" action="{{ route('users.store') }}">
            @csrf
            <div class="modal-body">
                <div class="modal-section">Identitas User</div>
                <div class="form-group">
                    <label>Nama Lengkap <span style="color:#EF4444">*</span></label>
                    <input type="text" name="name" placeholder="Masukkan nama lengkap..." required>
                </div>
                <div class="form-group">
                    <label>Email <span style="color:#EF4444">*</span></label>
                    <input type="email" name="email" placeholder="email@example.com" required>
                </div>
                <div class="form-divider"></div>
                <div class="modal-section">Informasi Kontak</div>
                <div class="form-group">
                    <label>No. Telepon <span style="color:#EF4444">*</span></label>
                    <input type="text" name="phone" placeholder="08xx-xxxx-xxxx" required>
                </div>
                <div class="form-group">
                    <label>Alamat</label>
                    <input type="text" name="address" placeholder="Alamat lengkap...">
                </div>
                <div class="form-divider"></div>
                <div class="modal-section">Data Tambahan</div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label>No. KTP</label>
                        <input type="text" name="id_card_number" placeholder="16 digit NIK" maxlength="16" style="font-family:monospace;">
                    </div>
                    <div class="form-group">
                        <label>Kode Perusahaan <span style="color:#EF4444">*</span></label>
                        <input type="text" name="company_code" placeholder="Contoh: PT-001" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Kontak Darurat</label>
                    <input type="text" name="emergency_contact" placeholder="Nama - No. HP">
                </div>
                <div class="toggle-row">
                    <div class="toggle-label-wrap">
                        <div class="tl-title">Status Akun</div>
                        <div class="tl-sub">Aktifkan atau nonaktifkan akun user</div>
                    </div>
                    <div class="toggle-wrap">
                        <input type="checkbox" name="status" value="1" id="addToggleStatus" style="display:none;" checked>
                        <div class="toggle-slider" id="addToggleSlider" style="background:#2D4DA3;" onclick="toggleCheck('addToggleStatus','addToggleLabel','addToggleSlider')">
                            <div class="toggle-thumb" style="left:21px;"></div>
                        </div>
                        <span class="toggle-text" id="addToggleLabel" style="color:#059669;">Aktif</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal('modalAdd')">Batal</button>
                <button type="submit" class="btn-save">Simpan User</button>
            </div>
        </form>
    </div>
</div>

{{-- ── MODAL EDIT ── --}}
<div class="modal-overlay" id="modalEdit">
    <div class="modal-backdrop" onclick="closeModal('modalEdit')"></div>
    <div class="modal-box">
        <div class="modal-header">
            <div>
                <h2>Edit User</h2>
                <p>Perbarui informasi pelanggan.</p>
            </div>
            <button class="modal-close" onclick="closeModal('modalEdit')">✕</button>
        </div>
        <form method="POST" id="editForm" action="">
            @csrf @method('PUT')
            <div class="modal-body">
                <div class="modal-section">Identitas User</div>
                <div class="form-group">
                    <label>Nama Lengkap <span style="color:#EF4444">*</span></label>
                    <input type="text" name="name" id="editName" required>
                </div>
                <div class="form-group">
                    <label>Email <span style="color:#EF4444">*</span></label>
                    <input type="email" name="email" id="editEmail" required>
                </div>
                <div class="form-divider"></div>
                <div class="modal-section">Informasi Kontak</div>
                <div class="form-group">
                    <label>No. Telepon</label>
                    <input type="text" name="phone" id="editPhone">
                </div>
                <div class="form-group">
                    <label>Alamat</label>
                    <input type="text" name="address" id="editAddress">
                </div>
                <div class="form-divider"></div>
                <div class="modal-section">Data Tambahan</div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label>No. KTP</label>
                        <input type="text" name="id_card_number" id="editIdCard" maxlength="16" style="font-family:monospace;">
                    </div>
                    <div class="form-group">
                        <label>Kode Perusahaan</label>
                        <input type="text" name="company_code" id="editCompanyCode">
                    </div>
                </div>
                <div class="form-group">
                    <label>Kontak Darurat</label>
                    <input type="text" name="emergency_contact" id="editEmergencyContact">
                </div>
                <div class="toggle-row">
                    <div class="toggle-label-wrap">
                        <div class="tl-title">Status Akun</div>
                        <div class="tl-sub">Aktifkan atau nonaktifkan akun user</div>
                    </div>
                    <div class="toggle-wrap">
                        <input type="checkbox" name="status" value="1" id="editToggleStatus" style="display:none;">
                        <div class="toggle-slider" id="editToggleSlider" style="background:#E5E7EB;" onclick="toggleCheck('editToggleStatus','editToggleLabel','editToggleSlider')">
                            <div class="toggle-thumb" style="left:3px;"></div>
                        </div>
                        <span class="toggle-text" id="editToggleLabel" style="color:#DC2626;">Nonaktif</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal('modalEdit')">Batal</button>
                <button type="submit" class="btn-save">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

{{-- ── MODAL DELETE ── --}}
<div class="modal-overlay" id="modalDelete">
    <div class="modal-backdrop" onclick="closeModal('modalDelete')"></div>
    <div class="confirm-box">
        <div class="confirm-accent"></div>
        <div class="confirm-body">
            <div class="confirm-icon-wrap">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <polyline points="3,6 5,6 21,6"/>
                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                    <path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/>
                </svg>
            </div>
            <div class="confirm-subtitle">Hapus Data</div>
            <div class="confirm-title">Hapus User</div>
            <p class="confirm-desc">
                User <strong id="deleteUserName" style="color:#0F172A;"></strong> akan dihapus secara permanen dan tidak dapat dipulihkan.
            </p>
        </div>
        <div class="confirm-footer">
            <button class="btn-confirm-cancel" onclick="closeModal('modalDelete')">Batal</button>
            <button class="btn-confirm-delete" onclick="executeDelete()">Hapus</button>
        </div>
    </div>
</div>

<form id="deleteForm" method="POST" action="" style="display:none">
    @csrf @method('DELETE')
</form>

<script>
    // ── Toggle Switch ────────────────────────────────────────────────────────
    function toggleCheck(inputId, labelId, sliderId) {
        const input  = document.getElementById(inputId);
        const label  = document.getElementById(labelId);
        const slider = document.getElementById(sliderId);
        const thumb  = slider.querySelector('.toggle-thumb');
        input.checked = !input.checked;
        if (input.checked) {
            slider.style.background = '#2D4DA3';
            thumb.style.left = '21px';
            label.textContent = 'Aktif';
            label.style.color = '#059669';
        } else {
            slider.style.background = '#E5E7EB';
            thumb.style.left = '3px';
            label.textContent = 'Nonaktif';
            label.style.color = '#DC2626';
        }
    }

    // ── Modals ───────────────────────────────────────────────────────────────
    function openAddModal() { document.getElementById('modalAdd').classList.add('show'); }

    function openEditModal(id, name, email, phone, address, idCard, emergencyContact, companyCode, status) {
        document.getElementById('editForm').action              = '/users/' + id;
        document.getElementById('editName').value               = name;
        document.getElementById('editEmail').value              = email;
        document.getElementById('editPhone').value              = phone;
        document.getElementById('editAddress').value            = address;
        document.getElementById('editIdCard').value             = idCard;
        document.getElementById('editEmergencyContact').value   = emergencyContact;
        document.getElementById('editCompanyCode').value        = companyCode;

        const toggle = document.getElementById('editToggleStatus');
        const slider = document.getElementById('editToggleSlider');
        const label  = document.getElementById('editToggleLabel');
        const thumb  = slider.querySelector('.toggle-thumb');
        toggle.checked = status == 1;
        if (status == 1) {
            slider.style.background = '#2D4DA3'; thumb.style.left = '21px';
            label.textContent = 'Aktif'; label.style.color = '#059669';
        } else {
            slider.style.background = '#E5E7EB'; thumb.style.left = '3px';
            label.textContent = 'Nonaktif'; label.style.color = '#DC2626';
        }
        document.getElementById('modalEdit').classList.add('show');
    }

    function openDeleteModal(id, name) {
        document.getElementById('deleteUserName').textContent = name;
        document.getElementById('deleteForm').action = '/users/' + id;
        document.getElementById('modalDelete').classList.add('show');
    }

    function executeDelete() { document.getElementById('deleteForm').submit(); }

    function closeModal(id) { document.getElementById(id).classList.remove('show'); }

    // ── Search ───────────────────────────────────────────────────────────────
    function filterTable() {
        const q = document.getElementById('searchInput').value.toLowerCase();
        document.querySelectorAll('#userTable tbody tr').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(q) ? '' : 'none';
        });
    }

    // ── Sort ─────────────────────────────────────────────────────────────────
    const COL_TYPES = { 1:'text', 2:'text', 5:'text', 6:'text' };

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
        const tbody = document.querySelector('#userTable tbody');
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
</script>

@endsection