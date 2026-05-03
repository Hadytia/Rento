@extends('layouts.app')

@section('page_title', 'Laporan & Transaksi')

@section('content')

@php
    $currentAdmin = \App\Models\Admin::where('email', Auth::user()->email)
                    ->where('status', 1)->where('is_deleted', 0)->first();
    $isDosen      = $currentAdmin && $currentAdmin->role === 'dosen';
    $isSuperadmin = $currentAdmin && $currentAdmin->role === 'superadmin';
    $isAdminStaff = $currentAdmin && in_array($currentAdmin->role, ['admin','staff']);
    $canEdit      = $isSuperadmin || $isAdminStaff || ($currentAdmin && $currentAdmin->can_edit == 1);
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
       ALERT
    ═══════════════════════════════════════════════════════════════════════ */
    .alert-success {
        background:linear-gradient(135deg, #ECFDF5 0%, #F0FDF9 100%);
        border:1px solid #A7F3D0; border-radius:12px;
        padding:13px 18px; font-family:Inter,sans-serif; font-size:13px;
        color:#065F46; margin-bottom:24px; display:flex; align-items:center; gap:10px;
        font-weight:500; box-shadow:0 1px 2px rgba(5,150,105,.06);
    }

    /* ═══════════════════════════════════════════════════════════════════════
       TOOLBAR
    ═══════════════════════════════════════════════════════════════════════ */
    .toolbar { display:flex; align-items:center; gap:12px; margin-bottom:22px; flex-wrap:wrap; }
    .search-wrap {
        display:flex; align-items:center; gap:8px; border:1px solid var(--gray-200);
        border-radius:11px; padding:0 14px; height:44px; background:var(--gray-50);
        flex:1; min-width:240px; transition:all .2s ease;
    }
    .search-wrap:focus-within {
        border-color:#4F46E5; background:#FFF;
        box-shadow:0 0 0 4px rgba(79,70,229,.08);
    }
    .search-wrap svg { flex-shrink:0; color:var(--gray-400); }
    .search-wrap input {
        border:none; outline:none; font-family:Inter,sans-serif;
        font-size:13px; color:var(--gray-900); width:100%; background:transparent; font-weight:500;
    }
    .search-wrap input::placeholder { color:var(--gray-400); font-weight:400; }

    .filter-btn {
        display:flex; align-items:center; gap:8px; height:44px; padding:0 16px;
        background:white; border:1px solid var(--gray-200); border-radius:11px;
        font-family:Inter,sans-serif; font-size:13px; color:var(--gray-700);
        cursor:pointer; white-space:nowrap; font-weight:500; transition:all .15s ease;
    }
    .filter-btn:hover { background:var(--gray-50); border-color:var(--gray-300); }
    .filter-btn.active {
        border-color:#4F46E5; color:#4F46E5; background:#F5F3FF;
        box-shadow:inset 0 0 0 1px rgba(79,70,229,.15);
    }
    .filter-btn svg { width:14px; height:14px; flex-shrink:0; }

    .btn-export {
        display:flex; align-items:center; gap:8px; height:44px; padding:0 18px;
        background:linear-gradient(135deg, #22C55E 0%, #16A34A 100%);
        border:none; border-radius:11px;
        font-family:Inter,sans-serif; font-size:13px; font-weight:600; color:white;
        cursor:pointer; white-space:nowrap; text-decoration:none;
        box-shadow:0 4px 12px rgba(34,197,94,.3), 0 1px 0 rgba(255,255,255,.15) inset;
        transition:all .2s ease; letter-spacing:.2px;
    }
    .btn-export:hover {
        transform:translateY(-1px); color:white;
        box-shadow:0 6px 16px rgba(34,197,94,.4), 0 1px 0 rgba(255,255,255,.15) inset;
    }
    .btn-export svg { width:14px; height:14px; flex-shrink:0; }

    .btn-create {
        display:flex; align-items:center; gap:8px; height:44px; padding:0 18px;
        background:linear-gradient(135deg, #4F46E5 0%, #6366F1 100%);
        border:none; border-radius:11px;
        font-family:Inter,sans-serif; font-size:13px; font-weight:600; color:white;
        cursor:pointer; white-space:nowrap; text-decoration:none;
        box-shadow:0 4px 12px rgba(79,70,229,.3), 0 1px 0 rgba(255,255,255,.15) inset;
        transition:all .2s ease; letter-spacing:.2px;
    }
    .btn-create:hover {
        transform:translateY(-1px); color:white;
        box-shadow:0 6px 16px rgba(79,70,229,.4), 0 1px 0 rgba(255,255,255,.15) inset;
    }
    .btn-create svg { width:14px; height:14px; flex-shrink:0; }

    /* ═══════════════════════════════════════════════════════════════════════
       TABLE CONTAINER
    ═══════════════════════════════════════════════════════════════════════ */
    .table-container {
        background:#FFF; border-radius:18px; padding:24px;
        border:1px solid var(--gray-200);
        box-shadow:0 1px 2px rgba(15,23,42,.04), 0 4px 12px rgba(15,23,42,.04);
    }
    .table-header {
        display:flex; align-items:center; justify-content:space-between;
        margin-bottom:18px; padding-bottom:4px;
    }
    .table-title {
        font-family:Inter,sans-serif; font-size:15px; font-weight:600;
        color:var(--gray-900); letter-spacing:-0.2px;
    }
    .count-badge {
        display:inline-flex; align-items:center; justify-content:center;
        background:linear-gradient(135deg, #EEF2FF 0%, #E0E7FF 100%);
        color:#4F46E5; border-radius:8px; padding:3px 10px;
        font-size:12px; font-weight:700; margin-left:10px; font-family:Inter,sans-serif;
        box-shadow:inset 0 0 0 1px rgba(79,70,229,.15);
    }

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

    /* ── Freeze Panes (Aksi 200px + ID Transaksi 160px) ── */
    #transactionTable thead th:nth-child(1),
    #transactionTable thead th:nth-child(2),
    #transactionTable tbody td:nth-child(1),
    #transactionTable tbody td:nth-child(2) {
        position:sticky; z-index:2;
    }
    #transactionTable thead th:nth-child(1),
    #transactionTable tbody td:nth-child(1) {
        left:0; width:210px; min-width:210px; max-width:210px;
    }
    #transactionTable thead th:nth-child(2),
    #transactionTable tbody td:nth-child(2) {
        left:210px; width:160px; min-width:160px;
        box-shadow:6px 0 12px -8px rgba(15,23,42,.12);
    }
    #transactionTable thead th:nth-child(1),
    #transactionTable thead th:nth-child(2) { z-index:3; }

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
    .trx-code {
        font-weight:700; color:var(--gray-900);
        font-family:'JetBrains Mono','Consolas',monospace; font-size:13px;
        background:var(--gray-100); padding:4px 8px; border-radius:6px;
        letter-spacing:.3px;
    }
    .cust-name { font-weight:600; color:var(--gray-900); letter-spacing:-0.1px; }
    .item-name { color:var(--gray-600); font-weight:500; }
    .period-text {
        font-size:12px; color:var(--gray-500); white-space:nowrap;
        font-family:'JetBrains Mono','Consolas',monospace; font-weight:500;
    }
    .amount-text {
        font-weight:700; color:var(--gray-900); white-space:nowrap;
        letter-spacing:-0.2px;
    }

    /* ── Audit Trail ── */
    .audit-name {
        font-size:12px; font-weight:600; color:var(--gray-700);
        margin-bottom:2px; letter-spacing:-0.1px; font-family:Inter,sans-serif;
    }
    .audit-date {
        font-size:11px; color:var(--gray-400);
        font-family:'JetBrains Mono','Consolas',monospace; font-weight:500;
    }
    .audit-empty { color:var(--gray-300); font-size:13px; }

    /* ── Status Badges ── */
    .badge {
        display:inline-flex; align-items:center; gap:6px; border-radius:8px;
        padding:5px 12px; font-size:11.5px; font-weight:600; font-family:Inter,sans-serif;
        white-space:nowrap; letter-spacing:.2px;
    }
    .badge .dot {
        width:6px; height:6px; border-radius:50%; flex-shrink:0;
        box-shadow:0 0 0 2px rgba(255,255,255,.5);
    }
    .badge-active {
        background:linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%);
        color:#1E40AF;
        box-shadow:inset 0 0 0 1px rgba(79,70,229,.2);
    }
    .badge-active .dot { background:#4F46E5; animation:pulse-blue 2s infinite; }
    .badge-completed {
        background:linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%);
        color:#047857;
        box-shadow:inset 0 0 0 1px rgba(5,150,105,.2);
    }
    .badge-completed .dot { background:#10B981; }
    .badge-overdue {
        background:linear-gradient(135deg, #FFF7ED 0%, #FFEDD5 100%);
        color:#C2410C;
        box-shadow:inset 0 0 0 1px rgba(234,88,12,.2);
    }
    .badge-overdue .dot { background:#EA580C; animation:pulse-orange 2s infinite; }
    .badge-cancelled {
        background:linear-gradient(135deg, #FEF2F2 0%, #FEE2E2 100%);
        color:#B91C1C;
        box-shadow:inset 0 0 0 1px rgba(220,38,38,.2);
    }
    .badge-cancelled .dot { background:#EF4444; }

    @keyframes pulse-blue {
        0%,100% { box-shadow:0 0 0 0 rgba(79,70,229,.4); }
        50%     { box-shadow:0 0 0 4px rgba(79,70,229,0); }
    }
    @keyframes pulse-orange {
        0%,100% { box-shadow:0 0 0 0 rgba(234,88,12,.4); }
        50%     { box-shadow:0 0 0 4px rgba(234,88,12,0); }
    }

    /* ── Action Buttons ── */
    .action-wrap { display:flex; gap:6px; align-items:center; }
    .action-btn {
        width:34px; height:34px; border-radius:8px;
        background:#FFF; cursor:pointer; display:flex; align-items:center; justify-content:center;
        transition:all .15s ease; text-decoration:none; flex-shrink:0; border:none;
        box-shadow:inset 0 0 0 1px var(--gray-200);
        color:var(--gray-600);
    }
    .action-btn:hover {
        transform:translateY(-1px);
        box-shadow:0 4px 8px rgba(15,23,42,.1), inset 0 0 0 1px var(--gray-300);
    }
    .action-btn.btn-return {
        color:#059669;
        box-shadow:inset 0 0 0 1px rgba(5,150,105,.2);
    }
    .action-btn.btn-return:hover {
        background:#ECFDF5; color:#047857;
        box-shadow:0 4px 10px rgba(5,150,105,.2), inset 0 0 0 1px #059669;
    }
    .action-btn.btn-edit {
        color:#4F46E5;
        box-shadow:inset 0 0 0 1px rgba(79,70,229,.2);
    }
    .action-btn.btn-edit:hover {
        background:#EFF6FF; color:#4338CA;
        box-shadow:0 4px 10px rgba(79,70,229,.2), inset 0 0 0 1px #4F46E5;
    }
    .action-btn.btn-delete {
        color:#DC2626;
        box-shadow:inset 0 0 0 1px rgba(220,38,38,.2);
    }
    .action-btn.btn-delete:hover {
        background:#FEF2F2; color:#B91C1C;
        box-shadow:0 4px 10px rgba(220,38,38,.2), inset 0 0 0 1px #DC2626;
    }
    .action-btn svg { width:14px; height:14px; }

    /* ── Empty ── */
    .empty-state { text-align:center; padding:64px 0; color:var(--gray-400); }
    .empty-state svg { width:48px; height:48px; margin-bottom:14px; opacity:.4; }
    .empty-state p { font-size:14px; margin:0; font-family:Inter,sans-serif; font-weight:500; }

    /* ═══════════════════════════════════════════════════════════════════════
       MODALS
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

    /* Detail Modal */
    .detail-box {
        position:relative; z-index:1; background:#FFF; border-radius:20px;
        width:100%; max-width:580px; max-height:90vh; overflow-y:auto;
        box-shadow:0 25px 60px rgba(0,0,0,.2), 0 1px 0 rgba(255,255,255,.1) inset;
        animation:slideUp .25s ease;
    }
    @keyframes slideUp {
        from { transform:translateY(20px); opacity:0; }
        to   { transform:translateY(0); opacity:1; }
    }
    .detail-header {
        display:flex; align-items:center; justify-content:space-between;
        padding:24px 28px 18px; border-bottom:1px solid var(--gray-100);
        position:sticky; top:0; background:white; z-index:2;
        border-radius:20px 20px 0 0;
    }
    .detail-trx-chip {
        background:var(--gray-100); color:var(--gray-700); border-radius:8px;
        padding:4px 11px; font-size:12px; font-weight:700;
        font-family:'JetBrains Mono','Consolas',monospace; letter-spacing:.4px;
    }
    .detail-title-text {
        font-family:Inter,sans-serif; font-size:16px; font-weight:700;
        color:var(--gray-900); letter-spacing:-0.2px;
    }
    .detail-close {
        width:32px; height:32px; background:var(--gray-100); border:none;
        border-radius:9px; cursor:pointer; display:flex;
        align-items:center; justify-content:center;
        color:var(--gray-500); font-size:14px; transition:all .15s ease;
    }
    .detail-close:hover { background:var(--gray-200); color:var(--gray-700); }

    .detail-section-label {
        font-family:Inter,sans-serif; font-size:11px; font-weight:700;
        color:var(--gray-400); text-transform:uppercase; letter-spacing:.08em;
        padding:18px 28px 10px;
    }
    .detail-card {
        margin:0 28px 16px; border:1px solid var(--gray-200); border-radius:14px;
        display:flex; overflow:hidden; background:var(--gray-50);
    }
    .d-left  { flex:0 0 220px; padding:18px; border-right:1px solid var(--gray-200); background:#FFF; }
    .d-right { flex:1; padding:18px; }
    .d-avatar {
        width:44px; height:44px; border-radius:50%;
        background:linear-gradient(135deg, #4F46E5 0%, #6366F1 100%);
        color:#FFF; display:flex; align-items:center; justify-content:center;
        font-size:14px; font-weight:700; flex-shrink:0;
        box-shadow:0 2px 6px rgba(79,70,229,.3), inset 0 -1px 0 rgba(0,0,0,.08);
    }
    .d-cust-name {
        font-family:Inter,sans-serif; font-size:14px; font-weight:700;
        color:var(--gray-900); letter-spacing:-0.2px;
    }
    .d-email {
        font-family:Inter,sans-serif; font-size:12px; color:#4F46E5;
        font-weight:500;
    }
    .d-info-row  { margin-bottom:12px; }
    .d-info-row:last-child { margin-bottom:0; }
    .d-info-label {
        font-family:Inter,sans-serif; font-size:11px; color:var(--gray-400);
        text-transform:uppercase; letter-spacing:.05em; margin-bottom:3px; font-weight:600;
    }
    .d-info-val {
        font-family:Inter,sans-serif; font-size:13px; color:var(--gray-900);
        font-weight:500; letter-spacing:-0.1px;
    }

    .detail-footer {
        display:flex; justify-content:flex-end; padding:16px 28px 22px;
        border-top:1px solid var(--gray-100); position:sticky; bottom:0; background:white;
        border-radius:0 0 20px 20px;
    }
    .btn-close-detail {
        height:40px; padding:0 22px; background:white; border:1px solid var(--gray-200);
        border-radius:10px; font-family:Inter,sans-serif; font-size:13px;
        cursor:pointer; color:var(--gray-700); font-weight:500; transition:all .15s ease;
    }
    .btn-close-detail:hover { background:var(--gray-50); border-color:var(--gray-300); }

    /* Confirm Modal */
    .confirm-box {
        position:relative; z-index:1; background:#FFF; border-radius:20px;
        width:100%; max-width:420px;
        box-shadow:0 25px 60px rgba(0,0,0,.2);
        overflow:hidden; animation:slideUp .25s ease;
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

<div class="page-header">
    <h1>Laporan &amp; Transaksi</h1>
    <p>Lihat dan ekspor riwayat penyewaan.</p>
</div>

@if (session('success'))
    <div class="alert-success">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22,4 12,14.01 9,11.01"/>
        </svg>
        {{ session('success') }}
    </div>
@endif

<div class="toolbar">
    <div class="search-wrap">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        <input type="text" placeholder="Cari transaksi..." id="searchInput" onkeyup="filterTable()">
    </div>

    <button class="filter-btn" id="statusFilterBtn" onclick="cycleStatus()">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <polygon points="22,3 2,3 10,12.46 10,19 14,21 14,12.46 22,3"/>
        </svg>
        <span id="statusLabel">Status: Semua</span>
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <polyline points="6,9 12,15 18,9"/>
        </svg>
    </button>

    <a href="{{ route('reports.export') }}" class="btn-export">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
            <polyline points="7,10 12,15 17,10"/>
            <line x1="12" y1="15" x2="12" y2="3"/>
        </svg>
        Ekspor CSV
    </a>

    @if(!$isDosen && $canEdit)
    <a href="{{ route('transaksi.create') }}" class="btn-create">
        <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Buat Transaksi
    </a>
    @endif
</div>

<div class="table-container">
    <div class="table-header">
        <div style="display:flex;align-items:center;">
            <span class="table-title">Transaksi Terbaru</span>
            <span class="count-badge">{{ $transactions->count() }}</span>
        </div>
    </div>

    <div class="table-scroll">
    <table id="transactionTable">
        <thead>
            <tr>
                <th style="width:200px">Aksi</th>
                <th class="sortable" data-col="1"><span class="th-inner">ID Transaksi<span class="sort-icon"><svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg><svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg></span><span class="sort-badge" id="badge-1"></span></span></th>
                <th class="sortable" data-col="2"><span class="th-inner">Pelanggan<span class="sort-icon"><svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg><svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg></span><span class="sort-badge" id="badge-2"></span></span></th>
                <th class="sortable" data-col="3"><span class="th-inner">Barang<span class="sort-icon"><svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg><svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg></span><span class="sort-badge" id="badge-3"></span></span></th>
                <th class="sortable" data-col="4"><span class="th-inner">Periode<span class="sort-icon"><svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg><svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg></span><span class="sort-badge" id="badge-4"></span></span></th>
                <th class="sortable" data-col="5"><span class="th-inner">Total<span class="sort-icon"><svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg><svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg></span><span class="sort-badge" id="badge-5"></span></span></th>
                <th class="sortable" data-col="6"><span class="th-inner">Status<span class="sort-icon"><svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg><svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg></span><span class="sort-badge" id="badge-6"></span></span></th>
                <th class="sortable" data-col="7"><span class="th-inner">Created By<span class="sort-icon"><svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg><svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg></span><span class="sort-badge" id="badge-7"></span></span></th>
                <th class="sortable" data-col="8"><span class="th-inner">Created Date<span class="sort-icon"><svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg><svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg></span><span class="sort-badge" id="badge-8"></span></span></th>
                <th class="sortable" data-col="9"><span class="th-inner">Last Updated By<span class="sort-icon"><svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg><svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg></span><span class="sort-badge" id="badge-9"></span></span></th>
                <th class="sortable" data-col="10"><span class="th-inner">Last Updated Date<span class="sort-icon"><svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg><svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg></span><span class="sort-badge" id="badge-10"></span></span></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $trx)
            @php
                $statusLabel = match(strtolower($trx->trx_status)) {
                    'active'    => 'Aktif',
                    'completed' => 'Selesai',
                    'overdue'   => 'Terlambat',
                    'cancelled' => 'Dibatalkan',
                    default     => $trx->trx_status,
                };
            @endphp
            <tr data-status="{{ strtolower($trx->trx_status) }}">
                {{-- Aksi --}}
                <td>
                    <div class="action-wrap">
                        <button class="action-btn" title="Lihat Detail"
                            onclick="openDetail(
                                '{{ $trx->trx_code }}',
                                '{{ addslashes($trx->user->name ?? '-') }}',
                                '{{ addslashes($trx->user->email ?? '-') }}',
                                '{{ addslashes($trx->user->phone ?? '-') }}',
                                '{{ addslashes($trx->user->address ?? '-') }}',
                                '{{ addslashes($trx->user->emergency_contact ?? '-') }}',
                                '{{ addslashes($trx->product->product_name ?? '-') }}',
                                '{{ $trx->rental_start }}',
                                '{{ $trx->rental_end }}',
                                '{{ number_format($trx->total_amount, 0, chr(44), chr(46)) }}',
                                '{{ number_format($trx->paid_amount, 0, chr(44), chr(46)) }}',
                                '{{ addslashes($trx->payment_method ?? '-') }}',
                                '{{ strtolower($trx->trx_status) }}'
                            )">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>

                        <a class="action-btn" href="{{ route('reports.download', $trx->id) }}" title="Unduh CSV">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="7,10 12,15 17,10"/>
                                <line x1="12" y1="15" x2="12" y2="3"/>
                            </svg>
                        </a>

                        @if(!$isDosen && $canEdit)
                        <a class="action-btn btn-edit" href="{{ route('transaksi.edit', $trx->id) }}" title="Edit">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                        </a>

                        @if(in_array($trx->trx_status, ['Active','Overdue']))
                        <button class="action-btn btn-return" title="Kembalikan"
                            onclick="openConfirm('return', {{ $trx->id }}, '{{ $trx->trx_code }}')">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <polyline points="1,4 1,10 7,10"/>
                                <path d="M3.51 15a9 9 0 1 0 .49-3.45"/>
                            </svg>
                        </button>
                        @endif

                        <button class="action-btn btn-delete" title="Hapus"
                            onclick="openConfirm('delete', {{ $trx->id }}, '{{ $trx->trx_code }}')">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <polyline points="3,6 5,6 21,6"/>
                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                <path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/>
                            </svg>
                        </button>
                        @endif
                    </div>
                </td>

                {{-- ID Transaksi --}}
                <td><span class="trx-code">{{ $trx->trx_code }}</span></td>

                {{-- Pelanggan --}}
                <td><span class="cust-name">{{ $trx->user->name ?? '-' }}</span></td>

                {{-- Barang --}}
                <td><span class="item-name">{{ $trx->product->product_name ?? '-' }}</span></td>

                {{-- Periode --}}
                <td>
                    <span class="period-text">
                        {{ \Carbon\Carbon::parse($trx->rental_start)->format('d M Y') }}
                        →
                        {{ \Carbon\Carbon::parse($trx->rental_end)->format('d M Y') }}
                    </span>
                </td>

                {{-- Total --}}
                <td><span class="amount-text">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</span></td>

                {{-- Status --}}
                <td>
                    @php
                        $bc = match(strtolower($trx->trx_status)) {
                            'active'    => 'badge-active',
                            'completed' => 'badge-completed',
                            'overdue'   => 'badge-overdue',
                            'cancelled' => 'badge-cancelled',
                            default     => 'badge-active',
                        };
                    @endphp
                    <span class="badge {{ $bc }}"><span class="dot"></span>{{ $statusLabel }}</span>
                </td>

                {{-- Created By --}}
                <td>
                    @if($trx->created_by)
                        <div class="audit-name">{{ $trx->created_by }}</div>
                    @else
                        <span class="audit-empty">-</span>
                    @endif
                </td>

                {{-- Created Date --}}
                <td>
                    @if($trx->created_date)
                        <div class="audit-date">{{ \Carbon\Carbon::parse($trx->created_date)->format('d M Y, H:i') }}</div>
                    @else
                        <span class="audit-empty">-</span>
                    @endif
                </td>

                {{-- Last Updated By --}}
                <td>
                    @if($trx->last_updated_by)
                        <div class="audit-name">{{ $trx->last_updated_by }}</div>
                    @else
                        <span class="audit-empty">-</span>
                    @endif
                </td>

                {{-- Last Updated Date --}}
                <td>
                    @if($trx->last_updated_date)
                        <div class="audit-date">{{ \Carbon\Carbon::parse($trx->last_updated_date)->format('d M Y, H:i') }}</div>
                    @else
                        <span class="audit-empty">-</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="11">
                <div class="empty-state">
                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14,2 14,8 20,8"/>
                    </svg>
                    <p>Belum ada transaksi.</p>
                </div>
            </td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>

{{-- MODAL DETAIL --}}
<div class="modal-overlay" id="modalDetail">
    <div class="modal-backdrop" onclick="closeDetail()"></div>
    <div class="detail-box">
        <div class="detail-header">
            <div style="display:flex;align-items:center;gap:12px;">
                <span class="detail-trx-chip" id="dTrxId"></span>
                <span class="detail-title-text">Detail Transaksi</span>
            </div>
            <button class="detail-close" onclick="closeDetail()">✕</button>
        </div>

        <div class="detail-section-label">Informasi Pelanggan</div>
        <div class="detail-card">
            <div class="d-left">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
                    <div class="d-avatar" id="dAvatar">BS</div>
                    <div>
                        <div class="d-cust-name" id="dName">-</div>
                        <div class="d-email" id="dEmail">-</div>
                    </div>
                </div>
            </div>
            <div class="d-right">
                <div class="d-info-row"><div class="d-info-label">Alamat</div><div class="d-info-val" id="dAddress">-</div></div>
                <div class="d-info-row"><div class="d-info-label">Nomor HP</div><div class="d-info-val" id="dPhone">-</div></div>
                <div class="d-info-row"><div class="d-info-label">Kontak Darurat</div><div class="d-info-val" id="dEmergency">-</div></div>
            </div>
        </div>

        <div class="detail-section-label">Informasi Penyewaan</div>
        <div class="detail-card">
            <div class="d-left">
                <div class="d-info-row"><div class="d-info-label">Barang Sewa</div><div class="d-info-val" id="dItem">-</div></div>
                <div class="d-info-row"><div class="d-info-label">ID Transaksi</div><div class="d-info-val" id="dTrxId2" style="font-family:'JetBrains Mono','Consolas',monospace;">-</div></div>
                <div class="d-info-row">
                    <div class="d-info-label">Status</div>
                    <span class="badge badge-active" id="dStatusBadge"><span class="dot"></span></span>
                </div>
                <div class="d-info-row"><div class="d-info-label">Periode</div><div class="d-info-val" id="dPeriod">-</div></div>
            </div>
            <div class="d-right">
                <div class="d-info-row"><div class="d-info-label">Total Tagihan</div><div class="d-info-val" id="dAmount">-</div></div>
                <div class="d-info-row"><div class="d-info-label">Jumlah Dibayar</div><div class="d-info-val" id="dPaid">-</div></div>
                <div class="d-info-row"><div class="d-info-label">Metode Pembayaran</div><div class="d-info-val" id="dPayment">-</div></div>
                <div class="d-info-row">
                    <div class="d-info-label">Status</div>
                    <span class="badge badge-active" id="dStatusBadge2"><span class="dot"></span></span>
                </div>
            </div>
        </div>

        <div class="detail-footer">
            <button class="btn-close-detail" onclick="closeDetail()">Tutup</button>
        </div>
    </div>
</div>

{{-- MODAL KONFIRMASI --}}
<div class="modal-overlay" id="modalConfirm">
    <div class="modal-backdrop" onclick="closeConfirm()"></div>
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
            <button class="btn-cf-cancel" onclick="closeConfirm()">Batal</button>
            <button class="btn-cf-ok" id="cfOkBtn" onclick="executeConfirm()">Konfirmasi</button>
        </div>
    </div>
</div>

<form id="formReturn" method="POST" action="" style="display:none">@csrf @method('PATCH')</form>
<form id="formDelete" method="POST" action="" style="display:none">@csrf @method('DELETE')</form>

<script>
    function filterTable() {
        const input = document.getElementById('searchInput').value.toLowerCase();
        document.querySelectorAll('#transactionTable tbody tr[data-status]').forEach(row => {
            const matchSearch = row.innerText.toLowerCase().includes(input);
            const matchStatus = currentStatus === 'all' || row.dataset.status === currentStatus;
            row.style.display = (matchSearch && matchStatus) ? '' : 'none';
        });
    }

    const statuses     = ['all','active','completed','overdue','cancelled'];
    const statusLabels = {
        all:       'Status: Semua',
        active:    'Aktif',
        completed: 'Selesai',
        overdue:   'Terlambat',
        cancelled: 'Dibatalkan'
    };
    let currentStatus = 'all', statusIdx = 0;

    function cycleStatus() {
        statusIdx     = (statusIdx + 1) % statuses.length;
        currentStatus = statuses[statusIdx];
        document.getElementById('statusLabel').textContent = statusLabels[currentStatus];
        document.getElementById('statusFilterBtn').classList.toggle('active', currentStatus !== 'all');
        filterTable();
    }

    function openDetail(trxCode, name, email, phone, address, emergency, item, start, end, amount, paid, payment, status) {
        const initials = name.split(' ').map(w=>w[0]).join('').substring(0,2).toUpperCase();
        document.getElementById('dTrxId').textContent     = trxCode;
        document.getElementById('dTrxId2').textContent    = trxCode;
        document.getElementById('dName').textContent      = name;
        document.getElementById('dAvatar').textContent    = initials;
        document.getElementById('dEmail').textContent     = email;
        document.getElementById('dPhone').textContent     = phone;
        document.getElementById('dAddress').textContent   = address;
        document.getElementById('dEmergency').textContent = emergency;
        document.getElementById('dItem').textContent      = item;
        document.getElementById('dPeriod').textContent    = start + ' → ' + end;
        document.getElementById('dAmount').textContent    = 'Rp ' + amount;
        document.getElementById('dPaid').textContent      = 'Rp ' + paid;
        document.getElementById('dPayment').textContent   = payment;

        const badgeMap = {
            active:    ['badge-active',    'Aktif'],
            completed: ['badge-completed', 'Selesai'],
            overdue:   ['badge-overdue',   'Terlambat'],
            cancelled: ['badge-cancelled', 'Dibatalkan'],
        };
        const [cls, label] = badgeMap[status] || ['badge-active', status];
        ['dStatusBadge','dStatusBadge2'].forEach(id => {
            const el = document.getElementById(id);
            el.className = 'badge ' + cls;
            el.innerHTML = '<span class="dot"></span>' + label;
        });
        document.getElementById('modalDetail').classList.add('show');
    }
    function closeDetail() { document.getElementById('modalDetail').classList.remove('show'); }

    let cfType = null, cfId = null;
    const cfConfig = {
        return: {
            accent:'linear-gradient(90deg, #22C55E 0%, #16A34A 100%)',
            iconBg:'linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%)', iconColor:'#059669',
            iconPath:'<polyline points="1,4 1,10 7,10"/><path d="M3.51 15a9 9 0 1 0 .49-3.45"/>',
            subtitle:'Pengembalian Barang', title:'Konfirmasi Pengembalian',
            desc: c => `Transaksi <strong style="color:#0F172A;font-family:'JetBrains Mono','Consolas',monospace">${c}</strong> akan dikembalikan. Jika terlambat, denda otomatis akan dibuat.`,
            btnBg:'linear-gradient(135deg, #22C55E 0%, #16A34A 100%)',
            btnShadow:'0 4px 12px rgba(34,197,94,.3), 0 1px 0 rgba(255,255,255,.15) inset',
            btnHoverShadow:'0 6px 16px rgba(34,197,94,.4), 0 1px 0 rgba(255,255,255,.15) inset',
            btnLabel:'Ya, Kembalikan',
        },
        delete: {
            accent:'linear-gradient(90deg, #EF4444 0%, #F87171 100%)',
            iconBg:'linear-gradient(135deg, #FEF2F2 0%, #FEE2E2 100%)', iconColor:'#DC2626',
            iconPath:'<polyline points="3,6 5,6 21,6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/>',
            subtitle:'Hapus Transaksi', title:'Yakin ingin menghapus?',
            desc: c => `Transaksi <strong style="color:#0F172A;font-family:'JetBrains Mono','Consolas',monospace">${c}</strong> akan dihapus secara permanen.`,
            btnBg:'linear-gradient(135deg, #EF4444 0%, #DC2626 100%)',
            btnShadow:'0 4px 12px rgba(239,68,68,.3), 0 1px 0 rgba(255,255,255,.15) inset',
            btnHoverShadow:'0 6px 16px rgba(239,68,68,.4), 0 1px 0 rgba(255,255,255,.15) inset',
            btnLabel:'Ya, Hapus',
        },
    };

    function openConfirm(type, id, code) {
        const cfg = cfConfig[type];
        cfType = type; cfId = id;
        document.getElementById('cfAccent').style.background   = cfg.accent;
        document.getElementById('cfIconWrap').style.background = cfg.iconBg;
        document.getElementById('cfIcon').style.color          = cfg.iconColor;
        document.getElementById('cfIcon').innerHTML            = cfg.iconPath;
        document.getElementById('cfSubtitle').textContent      = cfg.subtitle;
        document.getElementById('cfTitle').textContent         = cfg.title;
        document.getElementById('cfDesc').innerHTML            = cfg.desc(code);
        const btn = document.getElementById('cfOkBtn');
        btn.style.background = cfg.btnBg;
        btn.style.boxShadow  = cfg.btnShadow;
        btn.textContent      = cfg.btnLabel;
        btn.onmouseover = () => { btn.style.boxShadow = cfg.btnHoverShadow; btn.style.transform = 'translateY(-1px)'; };
        btn.onmouseout  = () => { btn.style.boxShadow = cfg.btnShadow; btn.style.transform = 'translateY(0)'; };
        document.getElementById('modalConfirm').classList.add('show');
    }
    function closeConfirm() { document.getElementById('modalConfirm').classList.remove('show'); }
    function executeConfirm() {
        if (cfType === 'return') {
            const f = document.getElementById('formReturn');
            f.action = '/transaksi/' + cfId + '/return';
            f.submit();
        } else {
            const f = document.getElementById('formDelete');
            f.action = '/transaksi/' + cfId;
            f.submit();
        }
    }

    // ── Sort ──
    const COL_TYPES = {
        1:'text', 2:'text', 3:'text', 4:'date', 5:'number', 6:'text',
        7:'text', 8:'date', 9:'text', 10:'date'
    };

    let sortCol = -1, sortDir = 'asc';
    document.querySelectorAll('th.sortable').forEach(th => {
        th.addEventListener('click', () => {
            const col = parseInt(th.dataset.col);
            sortDir = (sortCol === col) ? (sortDir === 'asc' ? 'desc' : 'asc') : 'asc';
            sortCol = col;
            updateSortIcons();
            sortTableFn(col, sortDir);
        });
    });

    function updateSortIcons() {
        document.querySelectorAll('th.sortable').forEach(th => {
            const col   = parseInt(th.dataset.col);
            const badge = th.querySelector('.sort-badge');
            const type  = COL_TYPES[col] || 'text';
            if (col === sortCol) {
                th.classList.add('sort-active'); th.classList.remove('asc','desc'); th.classList.add(sortDir);
                if (badge) {
                    if (type === 'number') badge.textContent = sortDir === 'asc' ? '0→9' : '9→0';
                    else if (type === 'date') badge.textContent = sortDir === 'asc' ? 'Lama→Baru' : 'Baru→Lama';
                    else badge.textContent = sortDir === 'asc' ? 'A-Z' : 'Z-A';
                }
            } else {
                th.classList.remove('sort-active','asc','desc');
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

    function parseNumber(str) {
        return parseFloat(str.replace(/[^0-9]/g, '')) || 0;
    }

    function sortTableFn(col, dir) {
        const tbody = document.querySelector('#transactionTable tbody');
        if (!tbody) return;
        const rows = Array.from(tbody.querySelectorAll('tr')).filter(r => r.cells.length > 1);
        const type = COL_TYPES[col] || 'text';

        rows.sort((a, b) => {
            const aRaw = a.cells[col]?.innerText.trim() ?? '';
            const bRaw = b.cells[col]?.innerText.trim() ?? '';
            let aVal, bVal;

            if (type === 'number') {
                aVal = parseNumber(aRaw);
                bVal = parseNumber(bRaw);
                return dir === 'asc' ? aVal - bVal : bVal - aVal;
            } else if (type === 'date') {
                aVal = parseDate(aRaw);
                bVal = parseDate(bRaw);
                return dir === 'asc' ? aVal - bVal : bVal - aVal;
            } else {
                aVal = aRaw.toLowerCase();
                bVal = bRaw.toLowerCase();
                if (aVal < bVal) return dir === 'asc' ? -1 : 1;
                if (aVal > bVal) return dir === 'asc' ?  1 : -1;
                return 0;
            }
        });
        rows.forEach(r => tbody.appendChild(r));
    }
</script>

@endsection