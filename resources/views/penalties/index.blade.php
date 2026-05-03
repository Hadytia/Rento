@extends('layouts.app')

@section('page_title', 'Penalties & Returns')

@section('content')

@php
    $currentAdmin = \App\Models\Admin::where('email', Auth::user()->email)
                    ->where('status', 1)->where('is_deleted', 0)->first();
    $isDosen      = $currentAdmin && $currentAdmin->role === 'dosen';
    $isSuperadmin = $currentAdmin && $currentAdmin->role === 'superadmin';
    $isAdminStaff = $currentAdmin && in_array($currentAdmin->role, ['admin','staff']);
    $canEdit      = $isSuperadmin || $isAdminStaff || ($isDosen && $currentAdmin->can_edit);
@endphp

<style>
    /* ═══════════════════════════════════════════════════════════════════════
       DESIGN TOKENS
    ═══════════════════════════════════════════════════════════════════════ */
    :root {
        --primary: #4F46E5;
        --primary-dark: #4338CA;
        --primary-light: #EEF2FF;
        --primary-soft: #F5F3FF;
        --success: #059669;
        --success-light: #D1FAE5;
        --danger: #DC2626;
        --danger-light: #FEE2E2;
        --warning: #F59E0B;
        --warning-light: #FEF3C7;
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
       PAGE HEADER
    ═══════════════════════════════════════════════════════════════════════ */
    .page-header { margin-bottom:28px; }
    .page-header h1 {
        font-family:Inter,sans-serif; font-size:26px; font-weight:700;
        margin:0 0 6px 0; letter-spacing:-0.5px;
        background:linear-gradient(135deg, #111827 0%, #4F46E5 100%);
        -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent;
    }
    .page-header p {
        font-family:Inter,sans-serif; font-size:14px; color:var(--gray-500);
        margin:0; letter-spacing:-0.1px;
    }

    /* ═══════════════════════════════════════════════════════════════════════
       ALERTS
    ═══════════════════════════════════════════════════════════════════════ */
    .alert-success, .alert-error {
        border-radius:12px; padding:13px 18px; font-family:Inter,sans-serif;
        font-size:13px; margin-bottom:24px; display:flex; align-items:center; gap:10px;
        font-weight:500;
    }
    .alert-success {
        background:linear-gradient(135deg, #ECFDF5 0%, #F0FDF9 100%);
        border:1px solid #A7F3D0; color:#065F46;
        box-shadow:0 1px 2px rgba(5,150,105,.06);
    }
    .alert-error {
        background:linear-gradient(135deg, #FEF2F2 0%, #FEE2E2 100%);
        border:1px solid #FECACA; color:#991B1B;
        box-shadow:0 1px 2px rgba(220,38,38,.06);
    }

    /* ═══════════════════════════════════════════════════════════════════════
       STATS GRID
    ═══════════════════════════════════════════════════════════════════════ */
    .stats-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:18px; margin-bottom:28px; }
    .stat-card {
        background:#FFF; border-radius:16px; padding:22px;
        border:1px solid var(--gray-200);
        box-shadow:0 1px 2px rgba(15,23,42,.04), 0 4px 12px rgba(15,23,42,.04);
        display:flex; flex-direction:column; gap:8px; transition:all .25s ease;
        position:relative; overflow:hidden;
    }
    .stat-card::before {
        content:''; position:absolute; top:0; left:0; right:0; height:3px;
        opacity:0; transition:opacity .25s ease;
    }
    .stat-card:hover {
        transform:translateY(-2px);
        box-shadow:0 12px 24px -8px rgba(15,23,42,.1), 0 4px 8px -4px rgba(15,23,42,.05);
    }
    .stat-card:hover::before { opacity:1; }
    .stat-card:nth-child(1)::before { background:linear-gradient(90deg, #4F46E5 0%, #6366F1 100%); }
    .stat-card:nth-child(2)::before { background:linear-gradient(90deg, #EF4444 0%, #F87171 100%); }
    .stat-card:nth-child(3)::before { background:linear-gradient(90deg, #22C55E 0%, #16A34A 100%); }
    .stat-card:nth-child(4)::before { background:linear-gradient(90deg, #F59E0B 0%, #FBBF24 100%); }
    
    .stat-icon {
        width:44px; height:44px; border-radius:12px;
        display:flex; align-items:center; justify-content:center; margin-bottom:8px;
        box-shadow:0 2px 6px rgba(15,23,42,.12), inset 0 -1px 0 rgba(0,0,0,.08);
    }
    .stat-label {
        font-family:Inter,sans-serif; font-size:11px; font-weight:700;
        color:var(--gray-400); text-transform:uppercase; letter-spacing:.08em;
    }
    .stat-value {
        font-family:Inter,sans-serif; font-size:28px; font-weight:800;
        color:var(--gray-900); line-height:1; letter-spacing:-0.5px;
    }
    .stat-value.red    { color:#EF4444; }
    .stat-value.orange { color:#F59E0B; }

    /* ═══════════════════════════════════════════════════════════════════════
       CONTENT GRID
    ═══════════════════════════════════════════════════════════════════════ */
    .content-grid { display:flex; gap:20px; align-items:flex-start; }
    .left-col  { width:320px; flex-shrink:0; }
    .right-col { flex:1; min-width:0; overflow:hidden; }

    /* ═══════════════════════════════════════════════════════════════════════
       ACTION PANEL (LEFT SIDEBAR)
    ═══════════════════════════════════════════════════════════════════════ */
    .action-panel {
        background:#FFF; border-radius:16px; padding:20px;
        border:1px solid #FED7AA;
        box-shadow:0 1px 2px rgba(245,158,11,.06), 0 4px 12px rgba(245,158,11,.04);
    }
    .action-panel-header { display:flex; align-items:center; gap:10px; margin-bottom:6px; }
    .action-panel-header .dot {
        width:10px; height:10px; border-radius:50%; background:#F59E0B;
        flex-shrink:0; animation:pulse-warning 2s infinite;
    }
    @keyframes pulse-warning {
        0%,100% { box-shadow:0 0 0 0 rgba(245,158,11,.4); }
        50%     { box-shadow:0 0 0 6px rgba(245,158,11,0); }
    }
    .action-panel-header span {
        font-family:Inter,sans-serif; font-size:15px; font-weight:700;
        color:#B45309; letter-spacing:-0.2px;
    }
    .action-panel-sub {
        font-family:Inter,sans-serif; font-size:12px; color:var(--gray-400);
        margin-bottom:16px; padding-left:20px; letter-spacing:-0.1px;
    }

    .overdue-card {
        background:linear-gradient(135deg, #FFFBEB 0%, #FEF9C3 100%);
        border:1px solid #FDE68A; border-radius:14px; padding:16px; margin-bottom:12px;
        box-shadow:0 1px 3px rgba(245,158,11,.08);
    }
    .overdue-card:last-child { margin-bottom:0; }
    .overdue-top  { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:10px; }
    .overdue-name {
        font-family:Inter,sans-serif; font-size:14px; font-weight:700;
        color:var(--gray-900); letter-spacing:-0.2px;
    }
    .trx-chip {
        background:linear-gradient(135deg, #EEF2FF 0%, #E0E7FF 100%);
        color:#4F46E5; border-radius:7px; padding:3px 9px;
        font-size:11px; font-weight:700; font-family:'JetBrains Mono','Consolas',monospace;
        box-shadow:inset 0 0 0 1px rgba(79,70,229,.15);
    }
    .overdue-meta {
        font-family:Inter,sans-serif; font-size:12px; color:var(--gray-600);
        margin-bottom:4px; display:flex; align-items:center; gap:6px; font-weight:500;
    }
    .overdue-late {
        font-family:Inter,sans-serif; font-size:12.5px; font-weight:700;
        color:#DC2626; margin:8px 0 12px; display:flex; align-items:center; gap:6px;
    }
    .btn-reminder {
        width:100%; height:40px;
        background:linear-gradient(135deg, #F59E0B 0%, #FBBF24 100%);
        border:none; border-radius:10px;
        font-family:Inter,sans-serif; font-size:12.5px; font-weight:700; color:white;
        cursor:pointer; display:flex; align-items:center; justify-content:center; gap:7px;
        transition:all .2s ease; letter-spacing:.2px;
        box-shadow:0 4px 12px rgba(245,158,11,.3), 0 1px 0 rgba(255,255,255,.15) inset;
    }
    .btn-reminder:hover {
        transform:translateY(-1px);
        box-shadow:0 6px 16px rgba(245,158,11,.4), 0 1px 0 rgba(255,255,255,.15) inset;
    }
    .empty-overdue {
        background:#FFF; border-radius:12px; padding:28px; text-align:center;
        font-family:Inter,sans-serif; font-size:13.5px; color:var(--gray-400);
    }

    /* ═══════════════════════════════════════════════════════════════════════
       TABLE PANEL (RIGHT SIDE)
    ═══════════════════════════════════════════════════════════════════════ */
    .table-panel {
        background:#FFF; border-radius:18px; padding:24px;
        border:1px solid var(--gray-200);
        box-shadow:0 1px 2px rgba(15,23,42,.04), 0 4px 12px rgba(15,23,42,.04);
    }
    .table-toolbar {
        display:flex; align-items:center; justify-content:space-between;
        margin-bottom:18px; padding-bottom:4px;
    }
    .table-panel-header h2 {
        font-family:Inter,sans-serif; font-size:15px; font-weight:600;
        color:var(--gray-900); margin:0 0 4px 0; letter-spacing:-0.2px;
    }
    .table-panel-header p {
        font-family:Inter,sans-serif; font-size:12.5px; color:var(--gray-400);
        margin:0; letter-spacing:-0.1px;
    }
    .search-wrap {
        display:flex; align-items:center; gap:8px; border:1px solid var(--gray-200);
        border-radius:11px; padding:0 14px; height:40px; background:var(--gray-50);
        width:240px; transition:all .2s ease;
    }
    .search-wrap:focus-within {
        border-color:#4F46E5; background:#FFF;
        box-shadow:0 0 0 4px rgba(79,70,229,.08);
    }
    .search-wrap svg { flex-shrink:0; color:var(--gray-400); }
    .search-wrap input {
        border:none; outline:none; font-family:Inter,sans-serif; font-size:13px;
        color:var(--gray-900); width:100%; background:transparent; font-weight:500;
    }
    .search-wrap input::placeholder { color:var(--gray-400); font-weight:400; }

    /* ═══════════════════════════════════════════════════════════════════════
       TABLE
    ═══════════════════════════════════════════════════════════════════════ */
    .table-scroll {
        overflow-x:auto; border-radius:12px; border:1px solid var(--gray-100);
    }
    .table-scroll::-webkit-scrollbar { height:10px; }
    .table-scroll::-webkit-scrollbar-track { background:transparent; }
    .table-scroll::-webkit-scrollbar-thumb {
        background:var(--gray-200); border-radius:10px; border:2px solid #FFF;
    }
    .table-scroll::-webkit-scrollbar-thumb:hover { background:var(--gray-300); }

    table { width:100%; border-collapse:separate; border-spacing:0; }
    thead tr { background:var(--gray-50); }
    thead th {
        font-family:Inter,sans-serif; font-size:11px; font-weight:600;
        color:var(--gray-500); letter-spacing:.08em; text-transform:uppercase;
        padding:14px 16px; text-align:left; white-space:nowrap;
        background:var(--gray-50); border-bottom:1px solid var(--gray-200);
    }
    tbody tr { transition:background .15s ease; }
    tbody td {
        font-family:Inter,sans-serif; font-size:13px; color:var(--gray-800);
        padding:16px; vertical-align:middle;
        background:#FFF; border-bottom:1px solid var(--gray-100);
    }
    tbody tr:last-child td { border-bottom:none; }
    tbody tr:hover td { background:#FAFBFF; }

    /* ── Freeze Panes (Actions 90px + Customer 180px) ── */
    #penaltyTable thead th:nth-child(1),
    #penaltyTable thead th:nth-child(2),
    #penaltyTable tbody td:nth-child(1),
    #penaltyTable tbody td:nth-child(2) {
        position:sticky; z-index:2;
    }
    #penaltyTable thead th:nth-child(1),
    #penaltyTable tbody td:nth-child(1) {
        left:0; width:90px; min-width:90px; max-width:90px;
    }
    #penaltyTable thead th:nth-child(2),
    #penaltyTable tbody td:nth-child(2) {
        left:90px; width:180px; min-width:180px;
        box-shadow:6px 0 12px -8px rgba(15,23,42,.12);
    }
    #penaltyTable thead th:nth-child(1),
    #penaltyTable thead th:nth-child(2) { z-index:3; }

    /* ═══════════════════════════════════════════════════════════════════════
       SORT
    ═══════════════════════════════════════════════════════════════════════ */
    .sortable { cursor:pointer; user-select:none; transition:all .15s ease; }
    .sortable:hover { color:#4F46E5 !important; background:#F5F3FF !important; }
    .th-inner { display:inline-flex; align-items:center; gap:8px; }
    .sort-icon { display:inline-flex; flex-direction:column; align-items:center; gap:2px; flex-shrink:0; }
    .sort-icon svg { width:9px; height:6px; display:block; transition:fill .15s; }
    .sortable:not(.sort-active) .tri-up,
    .sortable:not(.sort-active) .tri-down { fill:var(--gray-300); }
    .sortable:hover:not(.sort-active) .tri-up,
    .sortable:hover:not(.sort-active) .tri-down { fill:var(--gray-400); }
    th.sort-active { color:#4F46E5 !important; background:#F5F3FF !important; }
    th.sort-active.asc  .tri-up   { fill:#4F46E5; }
    th.sort-active.asc  .tri-down { fill:#C7D2FE; }
    th.sort-active.desc .tri-up   { fill:#C7D2FE; }
    th.sort-active.desc .tri-down { fill:#4F46E5; }
    .sort-badge {
        display:inline-flex; align-items:center;
        background:linear-gradient(135deg, #4F46E5 0%, #6366F1 100%);
        color:white; font-size:9px; font-weight:700;
        padding:2px 6px; border-radius:5px; letter-spacing:.5px;
        margin-left:4px; opacity:0; transition:opacity .15s;
        box-shadow:0 2px 4px rgba(79,70,229,.2);
    }
    th.sort-active .sort-badge { opacity:1; }

    /* ═══════════════════════════════════════════════════════════════════════
       CELL CONTENTS
    ═══════════════════════════════════════════════════════════════════════ */
    .cust-name { font-weight:700; color:var(--gray-900); font-size:13px; letter-spacing:-0.1px; }
    .cust-meta  {
        font-size:11px; color:var(--gray-400); margin-top:3px;
        font-family:'JetBrains Mono','Consolas',monospace; font-weight:500;
    }

    .type-badge {
        display:inline-flex; align-items:center; gap:4px; border-radius:7px;
        padding:4px 10px; font-size:11px; font-weight:700; font-family:Inter,sans-serif;
        letter-spacing:.2px;
    }
    .type-late {
        background:linear-gradient(135deg, #FFF7ED 0%, #FFEDD5 100%);
        color:#C2410C;
        box-shadow:inset 0 0 0 1px rgba(234,88,12,.2);
    }
    .type-damage {
        background:linear-gradient(135deg, #FEF2F2 0%, #FEE2E2 100%);
        color:#DC2626;
        box-shadow:inset 0 0 0 1px rgba(220,38,38,.2);
    }
    .type-other {
        background:linear-gradient(135deg, #F1F5F9 0%, #E2E8F0 100%);
        color:#475569;
        box-shadow:inset 0 0 0 1px rgba(71,85,105,.15);
    }
    .type-desc {
        font-size:11px; color:var(--gray-400); margin-top:4px; max-width:200px;
        white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
    }

    .amount-text { font-weight:700; color:var(--gray-900); font-size:13px; white-space:nowrap; letter-spacing:-0.2px; }
    .overdue-days { font-weight:700; color:#DC2626; font-size:13px; white-space:nowrap; }

    /* ── Audit Trail ── */
    .audit-name {
        font-size:12px; font-weight:600; color:var(--gray-700);
        margin-bottom:2px; letter-spacing:-0.1px; font-family:Inter,sans-serif;
    }
    .audit-date {
        font-size:11px; color:var(--gray-400);
        font-family:'JetBrains Mono','Consolas',monospace; font-weight:500;
    }

    /* ── Status Badges ── */
    .status-badge {
        display:inline-flex; align-items:center; gap:6px; border-radius:8px;
        padding:5px 12px; font-size:11.5px; font-weight:600; font-family:Inter,sans-serif;
        white-space:nowrap; letter-spacing:.2px;
    }
    .status-badge .dot { width:6px; height:6px; border-radius:50%; flex-shrink:0; }
    .badge-paid {
        background:linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%);
        color:#047857;
        box-shadow:inset 0 0 0 1px rgba(5,150,105,.2);
    }
    .badge-paid .dot { background:#10B981; }
    .badge-unpaid {
        background:linear-gradient(135deg, #FEF2F2 0%, #FEE2E2 100%);
        color:#B91C1C;
        box-shadow:inset 0 0 0 1px rgba(220,38,38,.2);
    }
    .badge-unpaid .dot { background:#EF4444; }

    /* ── Action Buttons ── */
    .act-wrap { display:flex; gap:6px; }
    .act-btn {
        width:34px; height:34px; border-radius:8px; border:none;
        cursor:pointer; display:flex; align-items:center; justify-content:center;
        transition:all .15s ease;
    }
    .act-finish {
        color:#059669;
        box-shadow:inset 0 0 0 1px rgba(5,150,105,.2);
        background:#FFF;
    }
    .act-finish:hover {
        background:#ECFDF5; color:#047857;
        transform:translateY(-1px);
        box-shadow:0 4px 10px rgba(5,150,105,.2), inset 0 0 0 1px #059669;
    }
    .act-paid {
        color:#4F46E5;
        box-shadow:inset 0 0 0 1px rgba(79,70,229,.2);
        background:#FFF;
    }
    .act-paid:hover {
        background:#EFF6FF; color:#4338CA;
        transform:translateY(-1px);
        box-shadow:0 4px 10px rgba(79,70,229,.2), inset 0 0 0 1px #4F46E5;
    }
    .act-btn svg { width:14px; height:14px; }

    .empty-row {
        text-align:center; padding:64px 0; color:var(--gray-400);
        font-family:Inter,sans-serif; font-size:14px; font-weight:500;
    }

    /* ═══════════════════════════════════════════════════════════════════════
       MODAL
    ═══════════════════════════════════════════════════════════════════════ */
    .modal-overlay {
        display:none; position:fixed; inset:0; z-index:999;
        align-items:center; justify-content:center; padding:20px;
        animation:fadeIn .2s ease;
    }
    .modal-overlay.show { display:flex; }
    @keyframes fadeIn { from {opacity:0;} to {opacity:1;} }
    .modal-backdrop {
        position:fixed; inset:0;
        background:rgba(15,23,42,.6); backdrop-filter:blur(6px);
    }
    .confirm-box {
        position:relative; z-index:1; background:#FFF; border-radius:20px;
        width:100%; max-width:420px;
        box-shadow:0 25px 60px rgba(0,0,0,.2);
        overflow:hidden; animation:slideUp .25s ease;
    }
    @keyframes slideUp {
        from { transform:translateY(20px); opacity:0; }
        to   { transform:translateY(0); opacity:1; }
    }
    .confirm-accent { height:4px; width:100%; }
    .confirm-body { padding:28px 28px 20px; }
    .confirm-icon-wrap {
        width:56px; height:56px; border-radius:16px;
        display:flex; align-items:center; justify-content:center; margin-bottom:16px;
    }
    .confirm-icon-wrap svg { width:24px; height:24px; }
    .confirm-subtitle {
        font-family:Inter,sans-serif; font-size:11px; font-weight:700;
        color:var(--gray-400); text-transform:uppercase; letter-spacing:.08em;
        margin-bottom:5px;
    }
    .confirm-title {
        font-family:Inter,sans-serif; font-size:18px; font-weight:700;
        color:var(--gray-900); margin-bottom:10px; letter-spacing:-0.3px;
    }
    .confirm-desc {
        font-family:Inter,sans-serif; font-size:13.5px;
        color:var(--gray-500); line-height:1.6; margin:0;
    }
    .confirm-footer { padding:14px 28px 24px; display:flex; gap:10px; }
    .btn-cf-cancel {
        flex:1; height:42px; border:1.5px solid var(--gray-200);
        border-radius:11px; font-family:Inter,sans-serif;
        font-size:13px; font-weight:600; color:var(--gray-600);
        background:white; cursor:pointer; transition:all .15s ease;
    }
    .btn-cf-cancel:hover { background:var(--gray-50); border-color:var(--gray-300); }
    .btn-cf-ok {
        flex:1; height:42px; border:none; border-radius:11px;
        font-family:Inter,sans-serif; font-size:13px; font-weight:700;
        color:white; cursor:pointer; transition:all .2s ease;
    }
</style>

{{-- Header --}}
<div class="page-header">
    <h1>Penalties & Returns</h1>
    <p>Manage late returns and trigger email reminders.</p>
</div>

@if(session('success'))
    <div class="alert-success">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22,4 12,14.01 9,11.01"/>
        </svg>
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="alert-error">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        {{ session('error') }}
    </div>
@endif

{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background:linear-gradient(135deg, #EEF2FF 0%, #E0E7FF 100%);">
            <svg width="18" height="18" fill="none" stroke="#4F46E5" stroke-width="2" viewBox="0 0 24 24">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>
        </div>
        <div class="stat-label">Total Penalties</div>
        <div class="stat-value">{{ $stats['total_penalties'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:linear-gradient(135deg, #FEF2F2 0%, #FEE2E2 100%);">
            <svg width="18" height="18" fill="none" stroke="#EF4444" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
        </div>
        <div class="stat-label">Unpaid</div>
        <div class="stat-value red">{{ $stats['unpaid_penalties'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%);">
            <svg width="18" height="18" fill="none" stroke="#059669" stroke-width="2" viewBox="0 0 24 24">
                <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
            </svg>
        </div>
        <div class="stat-label">Total Amount</div>
        <div class="stat-value" style="font-size:22px;">Rp {{ number_format($stats['total_amount'], 0, ',', '.') }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);">
            <svg width="18" height="18" fill="none" stroke="#F59E0B" stroke-width="2" viewBox="0 0 24 24">
                <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
            </svg>
        </div>
        <div class="stat-label">Unpaid Amount</div>
        <div class="stat-value orange" style="font-size:22px;">Rp {{ number_format($stats['unpaid_amount'], 0, ',', '.') }}</div>
    </div>
</div>

{{-- Content --}}
<div class="content-grid">

    {{-- LEFT --}}
    <div class="left-col">
        <div class="action-panel">
            <div class="action-panel-header">
                <div class="dot"></div>
                <span>Action Needed</span>
            </div>
            <p class="action-panel-sub">Overdue items requiring reminders</p>

            @forelse($overdueTransactions as $trx)
            <div class="overdue-card">
                <div class="overdue-top">
                    <span class="overdue-name">{{ $trx->customer_name }}</span>
                    <span class="trx-chip">{{ $trx->trx_code }}</span>
                </div>
                <div class="overdue-meta">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                        <line x1="3" y1="6" x2="21" y2="6"/>
                    </svg>
                    {{ $trx->product_name }}
                </div>
                <div class="overdue-meta">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    Due: {{ \Carbon\Carbon::parse($trx->rental_end)->format('d M Y') }}
                </div>
                <div class="overdue-late">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/>
                    </svg>
                    {{ $trx->days_late }} {{ $trx->days_late == 1 ? 'Day' : 'Days' }} Late
                </div>
                @if($canEdit)
                <form action="{{ route('penalties.send-reminder') }}" method="POST">
                    @csrf
                    <input type="hidden" name="transaction_id" value="{{ $trx->id }}">
                    <button type="submit" class="btn-reminder">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                        Send Email Reminder
                    </button>
                </form>
                @else
                <div style="width:100%;height:40px;background:var(--gray-100);border-radius:10px;display:flex;align-items:center;justify-content:center;font-family:Inter,sans-serif;font-size:12px;color:var(--gray-400);">
                    🔒 View Only
                </div>
                @endif
            </div>
            @empty
            <div class="empty-overdue">
                <div style="font-size:32px;margin-bottom:8px;">🎉</div>
                No overdue transactions!
            </div>
            @endforelse
        </div>
    </div>

    {{-- RIGHT --}}
    <div class="right-col">
        <div class="table-panel">
            <div class="table-toolbar">
                <div class="table-panel-header">
                    <h2>Active Penalties & Returns</h2>
                    <p>Track and manage ongoing penalties and returns.</p>
                </div>
                <div class="search-wrap">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input type="text" id="searchInput" placeholder="Cari penalty...">
                </div>
            </div>

            <div class="table-scroll">
            <table id="penaltyTable">
                <thead>
                    <tr>
                        <th style="width:90px">Actions</th>
                        <th class="sortable" data-col="1" data-type="text">
                            <span class="th-inner">Customer
                                <span class="sort-icon">
                                    <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                                    <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                                </span>
                                <span class="sort-badge" id="badge-1"></span>
                            </span>
                        </th>
                        <th class="sortable" data-col="2" data-type="text">
                            <span class="th-inner">Type & Reason
                                <span class="sort-icon">
                                    <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                                    <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                                </span>
                                <span class="sort-badge" id="badge-2"></span>
                            </span>
                        </th>
                        <th class="sortable" data-col="3" data-type="number">
                            <span class="th-inner">Amount
                                <span class="sort-icon">
                                    <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                                    <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                                </span>
                                <span class="sort-badge" id="badge-3"></span>
                            </span>
                        </th>
                        <th class="sortable" data-col="4" data-type="number">
                            <span class="th-inner">Overdue
                                <span class="sort-icon">
                                    <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                                    <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                                </span>
                                <span class="sort-badge" id="badge-4"></span>
                            </span>
                        </th>
                        <th class="sortable" data-col="5" data-type="text">
                            <span class="th-inner">Status
                                <span class="sort-icon">
                                    <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                                    <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                                </span>
                                <span class="sort-badge" id="badge-5"></span>
                            </span>
                        </th>
                        <th class="sortable" data-col="6" data-type="text">
                            <span class="th-inner">Created By
                                <span class="sort-icon">
                                    <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                                    <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                                </span>
                                <span class="sort-badge" id="badge-6"></span>
                            </span>
                        </th>
                        <th class="sortable" data-col="7" data-type="date">
                            <span class="th-inner">Created Date
                                <span class="sort-icon">
                                    <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                                    <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                                </span>
                                <span class="sort-badge" id="badge-7"></span>
                            </span>
                        </th>
                        <th class="sortable" data-col="8" data-type="text">
                            <span class="th-inner">Last Updated By
                                <span class="sort-icon">
                                    <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                                    <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                                </span>
                                <span class="sort-badge" id="badge-8"></span>
                            </span>
                        </th>
                        <th class="sortable" data-col="9" data-type="date">
                            <span class="th-inner">Last Updated Date
                                <span class="sort-icon">
                                    <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                                    <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                                </span>
                                <span class="sort-badge" id="badge-9"></span>
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penalties as $penalty)
                    <tr>
                        {{-- Actions --}}
                        <td>
                            <div class="act-wrap">
                                @if($penalty->resolved == 0 && $canEdit)
                                    <button class="act-btn act-finish" title="Mark as Finished"
                                        onclick="openConfirm('finish', {{ $penalty->id }}, '{{ addslashes($penalty->customer_name) }}')">
                                        <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <polyline points="20,6 9,17 4,12"/>
                                        </svg>
                                    </button>
                                    <button class="act-btn act-paid" title="Mark as Paid"
                                        onclick="openConfirm('resolve', {{ $penalty->id }}, '{{ addslashes($penalty->customer_name) }}')">
                                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <line x1="12" y1="1" x2="12" y2="23"/>
                                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                                        </svg>
                                    </button>
                                @elseif($penalty->resolved == 0 && !$canEdit)
                                    <span style="font-size:11px;color:var(--gray-400);font-family:Inter,sans-serif;">View Only</span>
                                @else
                                    <span style="font-size:12px;color:var(--gray-300);">—</span>
                                @endif
                            </div>
                        </td>

                        {{-- Customer --}}
                        <td>
                            <div class="cust-name">{{ $penalty->customer_name }}</div>
                            <div class="cust-meta">{{ $penalty->trx_code }} · {{ $penalty->product_name }}</div>
                        </td>

                        {{-- Type --}}
                        <td>
                            @php
                                $typeClass = match($penalty->penalty_type) {
                                    'Late Return' => 'type-late',
                                    'Damage'      => 'type-damage',
                                    default       => 'type-other',
                                };
                            @endphp
                            <span class="type-badge {{ $typeClass }}">{{ $penalty->penalty_type }}</span>
                            <div class="type-desc" title="{{ $penalty->description }}">{{ $penalty->description }}</div>
                        </td>

                        {{-- Amount --}}
                        <td><span class="amount-text">Rp {{ number_format($penalty->penalty_amount, 0, ',', '.') }}</span></td>

                        {{-- Overdue --}}
                        <td>
                            @if($penalty->overdue_days > 0)
                                <span class="overdue-days">{{ $penalty->overdue_days }} {{ $penalty->overdue_days == 1 ? 'Day' : 'Days' }}</span>
                            @else
                                <span style="color:var(--gray-300);">—</span>
                            @endif
                        </td>

                        {{-- Status --}}
                        <td>
                            @if($penalty->resolved == 1)
                                <span class="status-badge badge-paid"><span class="dot"></span>Paid</span>
                            @else
                                <span class="status-badge badge-unpaid"><span class="dot"></span>Unpaid</span>
                            @endif
                        </td>

                        {{-- Created By --}}
                        <td>
                            @if($penalty->created_by)
                                <div class="audit-name">{{ $penalty->created_by }}</div>
                            @else
                                <span style="color:var(--gray-300);">-</span>
                            @endif
                        </td>

                        {{-- Created Date --}}
                        <td>
                            @if($penalty->created_date)
                                <div class="audit-date">{{ \Carbon\Carbon::parse($penalty->created_date)->format('d M Y, H:i') }}</div>
                            @else
                                <span style="color:var(--gray-300);">-</span>
                            @endif
                        </td>

                        {{-- Last Updated By --}}
                        <td>
                            @if($penalty->last_updated_by)
                                <div class="audit-name">{{ $penalty->last_updated_by }}</div>
                            @else
                                <span style="color:var(--gray-300);">-</span>
                            @endif
                        </td>

                        {{-- Last Updated Date --}}
                        <td>
                            @if($penalty->last_updated_date)
                                <div class="audit-date">{{ \Carbon\Carbon::parse($penalty->last_updated_date)->format('d M Y, H:i') }}</div>
                            @else
                                <span style="color:var(--gray-300);">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="10" class="empty-row">No penalties found.</td></tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL KONFIRMASI --}}
<div class="modal-overlay" id="modalConfirm">
    <div class="modal-backdrop" onclick="closeConfirmModal()"></div>
    <div class="confirm-box">
        <div class="confirm-accent" id="cfAccent"></div>
        <div class="confirm-body">
            <div class="confirm-icon-wrap" id="cfIconWrap">
                <svg id="cfIcon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"></svg>
            </div>
            <div class="confirm-subtitle" id="cfSubtitle"></div>
            <div class="confirm-title"   id="cfTitle"></div>
            <p class="confirm-desc"      id="cfDesc"></p>
        </div>
        <div class="confirm-footer">
            <button class="btn-cf-cancel" onclick="closeConfirmModal()">Batal</button>
            <button class="btn-cf-ok" id="cfOkBtn" onclick="executeConfirm()">Konfirmasi</button>
        </div>
    </div>
</div>

<form id="formFinish"  method="POST" action="" style="display:none">@csrf @method('PATCH')</form>
<form id="formResolve" method="POST" action="" style="display:none">@csrf @method('PATCH')</form>

<script>
    // ── Search ───────────────────────────────────────────────────────────────
    document.getElementById('searchInput').addEventListener('keyup', function () {
        const q = this.value.toLowerCase();
        document.querySelectorAll('#penaltyTable tbody tr').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(q) ? '' : 'none';
        });
    });

    // ── Sort ─────────────────────────────────────────────────────────────────
    const COL_TYPES = {
        1: 'text',    // Customer
        2: 'text',    // Type & Reason
        3: 'number',  // Amount
        4: 'number',  // Overdue
        5: 'text',    // Status
        6: 'text',    // Created By
        7: 'date',    // Created Date
        8: 'text',    // Last Updated By
        9: 'date',    // Last Updated Date
    };

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
                    if (type === 'number') badge.textContent = sortDir === 'asc' ? '0→9' : '9→0';
                    else if (type === 'date') badge.textContent = sortDir === 'asc' ? 'Lama→Baru' : 'Baru→Lama';
                    else badge.textContent = sortDir === 'asc' ? 'A-Z' : 'Z-A';
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

    function parseVal(text, type) {
        if (type === 'number') {
            const clean = text.replace(/Rp/gi,'').replace(/\./g,'').replace(/days?/gi,'').replace(/,/g,'.').replace(/—/g,'0').trim();
            return parseFloat(clean) || 0;
        }
        if (type === 'date') {
            return parseDate(text);
        }
        return text.trim().toLowerCase();
    }

    function sortTable(col, dir) {
        const tbody = document.querySelector('#penaltyTable tbody');
        const rows  = Array.from(tbody.querySelectorAll('tr')).filter(r => r.cells.length > 1);
        const type  = COL_TYPES[col] || 'text';
        rows.sort((a, b) => {
            const aVal = parseVal(a.cells[col]?.innerText ?? '', type);
            const bVal = parseVal(b.cells[col]?.innerText ?? '', type);
            if (aVal < bVal) return dir === 'asc' ? -1 : 1;
            if (aVal > bVal) return dir === 'asc' ?  1 : -1;
            return 0;
        });
        rows.forEach(r => tbody.appendChild(r));
    }

    // ── Confirm Modal ────────────────────────────────────────────────────────
    let confirmType = null, confirmId = null;

    const cfConfig = {
        finish: {
            accent: 'linear-gradient(90deg, #22C55E 0%, #16A34A 100%)',
            iconBg: 'linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%)', iconColor: '#059669',
            iconPath: '<polyline points="20,6 9,17 4,12"/>',
            subtitle: 'Selesaikan Penalty', title: 'Mark as Finished',
            desc: n => `Penalty milik <strong style="color:var(--gray-900)">${n}</strong> akan ditandai selesai dan transaksi terkait menjadi Completed.`,
            btnBg: 'linear-gradient(135deg, #22C55E 0%, #16A34A 100%)',
            btnShadow: '0 4px 12px rgba(34,197,94,.3), 0 1px 0 rgba(255,255,255,.15) inset',
            btnHoverShadow: '0 6px 16px rgba(34,197,94,.4), 0 1px 0 rgba(255,255,255,.15) inset',
            btnLabel: 'Ya, Selesaikan',
        },
        resolve: {
            accent: 'linear-gradient(90deg, #4F46E5 0%, #6366F1 100%)',
            iconBg: 'linear-gradient(135deg, #EEF2FF 0%, #E0E7FF 100%)', iconColor: '#4F46E5',
            iconPath: '<line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>',
            subtitle: 'Tandai Sudah Dibayar', title: 'Mark as Paid',
            desc: n => `Penalty milik <strong style="color:var(--gray-900)">${n}</strong> akan ditandai sebagai <em>Paid / Resolved</em>.`,
            btnBg: 'linear-gradient(135deg, #4F46E5 0%, #6366F1 100%)',
            btnShadow: '0 4px 12px rgba(79,70,229,.3), 0 1px 0 rgba(255,255,255,.15) inset',
            btnHoverShadow: '0 6px 16px rgba(79,70,229,.4), 0 1px 0 rgba(255,255,255,.15) inset',
            btnLabel: 'Ya, Tandai Paid',
        },
    };

    function openConfirm(type, id, name) {
        const cfg = cfConfig[type];
        confirmType = type; confirmId = id;
        document.getElementById('cfAccent').style.background   = cfg.accent;
        document.getElementById('cfIconWrap').style.background = cfg.iconBg;
        document.getElementById('cfIcon').style.color          = cfg.iconColor;
        document.getElementById('cfIcon').innerHTML            = cfg.iconPath;
        document.getElementById('cfSubtitle').textContent      = cfg.subtitle;
        document.getElementById('cfTitle').textContent         = cfg.title;
        document.getElementById('cfDesc').innerHTML            = cfg.desc(name);
        const btn = document.getElementById('cfOkBtn');
        btn.style.background = cfg.btnBg;
        btn.style.boxShadow  = cfg.btnShadow;
        btn.textContent      = cfg.btnLabel;
        btn.onmouseover = () => { btn.style.boxShadow = cfg.btnHoverShadow; btn.style.transform = 'translateY(-1px)'; };
        btn.onmouseout  = () => { btn.style.boxShadow = cfg.btnShadow; btn.style.transform = 'translateY(0)'; };
        document.getElementById('modalConfirm').classList.add('show');
    }

    function closeConfirmModal() { document.getElementById('modalConfirm').classList.remove('show'); }

    function executeConfirm() {
        if (confirmType === 'finish') {
            const f = document.getElementById('formFinish');
            f.action = '/penalties/' + confirmId + '/finish';
            f.submit();
        } else {
            const f = document.getElementById('formResolve');
            f.action = '/penalties/' + confirmId + '/resolve';
            f.submit();
        }
    }
</script>

@endsection