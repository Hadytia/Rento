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
    /* ── Page Header ── */
    .page-header { margin-bottom: 24px; }
    .page-header h1 { font-family:Inter,sans-serif; font-size:22px; font-weight:700; color:#1E1E1E; margin:0 0 4px 0; }
    .page-header p  { font-family:Inter,sans-serif; font-size:13px; color:#6B6B6B; margin:0; }

    /* ── Alerts ── */
    .alert-success, .alert-error {
        border-radius:10px; padding:11px 16px; font-family:Inter,sans-serif;
        font-size:13px; margin-bottom:18px; display:flex; align-items:center; gap:8px;
    }
    .alert-success { background:#ECFDF5; border:1px solid #6EE7B7; color:#065F46; }
    .alert-error   { background:#FEF2F2; border:1px solid #FECACA; color:#991B1B; }

    /* ── Stats Grid ── */
    .stats-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:14px; margin-bottom:24px; }
    .stat-card {
        background:#FFF; border-radius:14px; padding:18px 20px;
        box-shadow:0 2px 10px rgba(0,0,0,.06); border:1px solid #F1F5F9;
        display:flex; flex-direction:column; gap:6px;
    }
    .stat-label { font-family:Inter,sans-serif; font-size:11px; font-weight:700; color:#94A3B8; text-transform:uppercase; letter-spacing:.06em; }
    .stat-value { font-family:Inter,sans-serif; font-size:26px; font-weight:800; color:#0F172A; line-height:1; }
    .stat-value.red    { color:#EF4444; }
    .stat-value.orange { color:#F59E0B; }
    .stat-icon { width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center; margin-bottom:4px; }

    /* ── Two-column layout ── */
    .content-grid { display:flex; gap:18px; align-items:flex-start; }
    .left-col  { width:300px; flex-shrink:0; }
    .right-col { flex:1; min-width:0; }

    /* ── Action Needed panel ── */
    .action-panel { background:#FFF; border-radius:14px; padding:18px; box-shadow:0 2px 10px rgba(0,0,0,.06); border:1px solid #FED7AA; }
    .action-panel-header { display:flex; align-items:center; gap:8px; margin-bottom:4px; }
    .action-panel-header .dot { width:8px; height:8px; border-radius:50%; background:#F59E0B; flex-shrink:0; box-shadow:0 0 0 3px rgba(245,158,11,.2); }
    .action-panel-header span { font-family:Inter,sans-serif; font-size:14px; font-weight:700; color:#B45309; }
    .action-panel-sub { font-family:Inter,sans-serif; font-size:12px; color:#94A3B8; margin-bottom:14px; padding-left:16px; }

    /* ── Overdue card ── */
    .overdue-card { background:#FFFBEB; border:1px solid #FDE68A; border-radius:12px; padding:14px; margin-bottom:10px; }
    .overdue-card:last-child { margin-bottom:0; }
    .overdue-top  { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:8px; }
    .overdue-name { font-family:Inter,sans-serif; font-size:13px; font-weight:700; color:#0F172A; }
    .trx-chip { background:#EFF6FF; color:#2D4DA3; border-radius:6px; padding:2px 8px; font-size:11px; font-weight:700; font-family:Inter,sans-serif; }
    .overdue-meta { font-family:Inter,sans-serif; font-size:12px; color:#64748B; margin-bottom:2px; display:flex; align-items:center; gap:5px; }
    .overdue-late { font-family:Inter,sans-serif; font-size:12px; font-weight:700; color:#EF4444; margin:6px 0 10px; display:flex; align-items:center; gap:5px; }
    .btn-reminder {
        width:100%; height:36px; background:#F59E0B; border:none; border-radius:9px;
        font-family:Inter,sans-serif; font-size:12px; font-weight:700; color:white;
        cursor:pointer; display:flex; align-items:center; justify-content:center; gap:6px;
        transition:background .15s; box-shadow:0 2px 6px rgba(245,158,11,.3);
    }
    .btn-reminder:hover { background:#D97706; }
    .empty-overdue { background:#FFF; border-radius:10px; padding:20px; text-align:center; font-family:Inter,sans-serif; font-size:13px; color:#94A3B8; }

    /* ── Right panel table ── */
    .table-panel { background:#FFF; border-radius:14px; padding:22px; box-shadow:0 2px 10px rgba(0,0,0,.06); }
    .table-toolbar { display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; }
    .table-panel-header h2 { font-family:Inter,sans-serif; font-size:15px; font-weight:700; color:#0F172A; margin:0 0 3px 0; }
    .table-panel-header p  { font-family:Inter,sans-serif; font-size:12px; color:#94A3B8; margin:0; }
    .search-wrap {
        display:flex; align-items:center; gap:8px; border:1px solid #E5E5E5;
        border-radius:9px; padding:0 12px; height:36px; background:#FAFAFA; width:210px; transition:border-color .15s;
    }
    .search-wrap:focus-within { border-color:#2D4DA3; background:#fff; }
    .search-wrap input { border:none; outline:none; font-family:Inter,sans-serif; font-size:13px; color:#1E1E1E; width:100%; background:transparent; }
    .search-wrap input::placeholder { color:#B0B0B0; }

    table { width:100%; border-collapse:collapse; }
    thead tr { background:#F8FAFC; }
    thead th {
        font-family:Inter,sans-serif; font-size:11px; font-weight:700; color:#94A3B8;
        letter-spacing:.06em; text-transform:uppercase; padding:10px 14px; text-align:left; white-space:nowrap;
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

    /* ── Customer cell ── */
    .cust-name { font-weight:700; color:#0F172A; font-size:13px; }
    .cust-meta  { font-size:11px; color:#94A3B8; margin-top:2px; }

    /* ── Type badge ── */
    .type-badge { display:inline-flex; align-items:center; gap:4px; border-radius:6px; padding:3px 9px; font-size:11px; font-weight:700; font-family:Inter,sans-serif; }
    .type-late   { background:#FFF7ED; color:#C2410C; }
    .type-damage { background:#FEF2F2; color:#DC2626; }
    .type-other  { background:#F1F5F9; color:#475569; }
    .type-desc { font-size:11px; color:#94A3B8; margin-top:3px; max-width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }

    /* ── Amount ── */
    .amount-text { font-weight:700; color:#0F172A; font-size:13px; white-space:nowrap; }

    /* ── Overdue days ── */
    .overdue-days { font-weight:700; color:#EF4444; font-size:13px; white-space:nowrap; }

    /* ── Status badge ── */
    .status-badge { display:inline-flex; align-items:center; gap:5px; border-radius:20px; padding:4px 12px; font-size:12px; font-weight:600; font-family:Inter,sans-serif; border:1px solid; }
    .status-badge .dot { width:6px; height:6px; border-radius:50%; }
    .badge-paid   { background:#ECFDF5; color:#059669; border-color:#6EE7B7; }
    .badge-paid .dot   { background:#059669; }
    .badge-unpaid { background:#FEF2F2; color:#DC2626; border-color:#FECACA; }
    .badge-unpaid .dot { background:#DC2626; }

    /* ── Action buttons ── */
    .act-wrap { display:flex; gap:6px; }
    .act-btn { width:32px; height:32px; border-radius:9px; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all .15s; }
    .act-finish { background:#ECFDF5; color:#059669; border:1px solid #6EE7B7; }
    .act-finish:hover { background:#D1FAE5; }
    .act-paid   { background:#EFF6FF; color:#2D4DA3; border:1px solid #BFDBFE; }
    .act-paid:hover   { background:#DBEAFE; }
    .act-btn svg { width:14px; height:14px; }

    /* ── Confirm Modal ── */
    .modal-overlay { display:none; position:fixed; inset:0; z-index:999; align-items:center; justify-content:center; padding:20px; }
    .modal-overlay.show { display:flex; }
    .modal-backdrop { position:fixed; inset:0; background:rgba(15,23,42,.5); backdrop-filter:blur(3px); }
    .confirm-box { position:relative; z-index:1; background:#FFF; border-radius:18px; width:100%; max-width:380px; box-shadow:0 25px 60px rgba(0,0,0,.18); overflow:hidden; }
    .confirm-accent { height:4px; width:100%; }
    .confirm-body { padding:24px 24px 16px; }
    .confirm-icon-wrap { width:48px; height:48px; border-radius:13px; display:flex; align-items:center; justify-content:center; margin-bottom:14px; }
    .confirm-icon-wrap svg { width:22px; height:22px; }
    .confirm-subtitle { font-family:Inter,sans-serif; font-size:11px; font-weight:700; color:#94A3B8; text-transform:uppercase; letter-spacing:.06em; margin-bottom:3px; }
    .confirm-title    { font-family:Inter,sans-serif; font-size:16px; font-weight:700; color:#0F172A; margin-bottom:8px; }
    .confirm-desc     { font-family:Inter,sans-serif; font-size:13px; color:#64748B; line-height:1.6; }
    .confirm-footer   { padding:12px 24px 20px; display:flex; gap:10px; }
    .btn-cf-cancel { flex:1; height:40px; border:1.5px solid #E2E8F0; border-radius:11px; font-family:Inter,sans-serif; font-size:13px; font-weight:600; color:#64748B; background:white; cursor:pointer; }
    .btn-cf-cancel:hover { background:#F8FAFC; }
    .btn-cf-ok { flex:1; height:40px; border:none; border-radius:11px; font-family:Inter,sans-serif; font-size:13px; font-weight:700; color:white; cursor:pointer; transition:background .15s; }

    .empty-row { text-align:center; padding:40px 0; color:#94A3B8; font-family:Inter,sans-serif; font-size:13px; }
</style>

{{-- Header --}}
<div class="page-header">
    <h1>Penalties & Returns</h1>
    <p>Manage late returns and trigger email reminders.</p>
</div>

{{-- Flash --}}
@if(session('success'))
    <div class="alert-success">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22,4 12,14.01 9,11.01"/>
        </svg>
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="alert-error">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        {{ session('error') }}
    </div>
@endif

{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background:#EFF6FF;">
            <svg width="18" height="18" fill="none" stroke="#2D4DA3" stroke-width="2" viewBox="0 0 24 24">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>
        </div>
        <div class="stat-label">Total Penalties</div>
        <div class="stat-value">{{ $stats['total_penalties'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#FEF2F2;">
            <svg width="18" height="18" fill="none" stroke="#EF4444" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
        </div>
        <div class="stat-label">Unpaid</div>
        <div class="stat-value red">{{ $stats['unpaid_penalties'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#F0FDF4;">
            <svg width="18" height="18" fill="none" stroke="#22C55E" stroke-width="2" viewBox="0 0 24 24">
                <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
            </svg>
        </div>
        <div class="stat-label">Total Amount</div>
        <div class="stat-value" style="font-size:20px;">Rp {{ number_format($stats['total_amount'], 0, ',', '.') }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#FFFBEB;">
            <svg width="18" height="18" fill="none" stroke="#F59E0B" stroke-width="2" viewBox="0 0 24 24">
                <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
            </svg>
        </div>
        <div class="stat-label">Unpaid Amount</div>
        <div class="stat-value orange" style="font-size:20px;">Rp {{ number_format($stats['unpaid_amount'], 0, ',', '.') }}</div>
    </div>
</div>

{{-- Content --}}
<div class="content-grid">

    {{-- LEFT: Action Needed --}}
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
                <div style="width:100%;height:36px;background:#F1F5F9;border-radius:9px;display:flex;align-items:center;justify-content:center;font-family:Inter,sans-serif;font-size:12px;color:#94A3B8;">
                    🔒 View Only
                </div>
                @endif
            </div>
            @empty
            <div class="empty-overdue">
                <div style="font-size:24px;margin-bottom:6px;">🎉</div>
                No overdue transactions!
            </div>
            @endforelse
        </div>
    </div>

    {{-- RIGHT: Penalties Table --}}
    <div class="right-col">
        <div class="table-panel">
            <div class="table-toolbar">
                <div class="table-panel-header">
                    <h2>Active Penalties & Returns</h2>
                    <p>Track and manage ongoing penalties and returns.</p>
                </div>
                <div class="search-wrap">
                    <svg width="14" height="14" fill="none" stroke="#9E9E9E" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input type="text" id="searchInput" placeholder="Cari penalty...">
                </div>
            </div>

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
                                    <span style="font-size:11px;color:#94A3B8;font-family:Inter,sans-serif;">View Only</span>
                                @else
                                    <span style="font-size:12px;color:#CBD5E1;">—</span>
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
                                <span style="color:#CBD5E1;">—</span>
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
                    </tr>
                    @empty
                    <tr><td colspan="6" class="empty-row">No penalties found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ── MODAL KONFIRMASI ── --}}
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
        3: 'number',  // Amount  → strip "Rp" + titik
        4: 'number',  // Overdue → strip " Days" / " Day"
        5: 'text',    // Status
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
                if (badge) badge.textContent = type === 'number'
                    ? (sortDir === 'asc' ? '0→9' : '9→0')
                    : (sortDir === 'asc' ? 'A-Z' : 'Z-A');
            } else {
                th.classList.remove('sort-active', 'asc', 'desc');
                if (badge) badge.textContent = '';
            }
        });
    }

    function parseVal(text, type) {
        if (type === 'number') {
            // Strip "Rp", titik ribuan, "Days", "Day", spasi
            const clean = text.replace(/Rp/gi, '').replace(/\./g, '').replace(/days?/gi, '').replace(/,/g, '.').replace(/—/g, '0').trim();
            return parseFloat(clean) || 0;
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
            accent: '#22C55E', iconBg: '#ECFDF5', iconColor: '#22C55E',
            iconPath: '<polyline points="20,6 9,17 4,12"/>',
            subtitle: 'Selesaikan Penalty', title: 'Mark as Finished',
            desc: n => `Penalty milik <strong style="color:#0F172A">${n}</strong> akan ditandai selesai dan transaksi terkait menjadi Completed.`,
            btnBg: '#22C55E', btnHover: '#16A34A', btnLabel: 'Ya, Selesaikan',
        },
        resolve: {
            accent: '#2D4DA3', iconBg: '#EFF6FF', iconColor: '#2D4DA3',
            iconPath: '<line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>',
            subtitle: 'Tandai Sudah Dibayar', title: 'Mark as Paid',
            desc: n => `Penalty milik <strong style="color:#0F172A">${n}</strong> akan ditandai sebagai <em>Paid / Resolved</em>.`,
            btnBg: '#2D4DA3', btnHover: '#253f8a', btnLabel: 'Ya, Tandai Paid',
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
        btn.textContent      = cfg.btnLabel;
        btn.onmouseover = () => btn.style.background = cfg.btnHover;
        btn.onmouseout  = () => btn.style.background = cfg.btnBg;

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