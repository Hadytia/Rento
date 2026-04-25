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
    .page-header { margin-bottom:24px; }
    .page-header h1 { font-family:Inter,sans-serif; font-size:22px; font-weight:700; color:#1E1E1E; margin:0 0 4px 0; }
    .page-header p  { font-family:Inter,sans-serif; font-size:13px; color:#6B6B6B; margin:0; }

    .alert-success {
        background:#ECFDF5; border:1px solid #6EE7B7; border-radius:10px;
        padding:11px 16px; font-family:Inter,sans-serif; font-size:13px; color:#065F46;
        margin-bottom:18px; display:flex; align-items:center; gap:8px;
    }

    .toolbar { display:flex; align-items:center; gap:10px; margin-bottom:18px; flex-wrap:wrap; }
    .search-wrap {
        display:flex; align-items:center; gap:8px; border:1px solid #E5E5E5;
        border-radius:9px; padding:0 14px; height:40px; background:#FAFAFA;
        flex:1; min-width:200px; transition:border-color .15s;
    }
    .search-wrap:focus-within { border-color:#2D4DA3; background:#fff; }
    .search-wrap input { border:none; outline:none; font-family:Inter,sans-serif; font-size:13px; color:#1E1E1E; width:100%; background:transparent; }
    .search-wrap input::placeholder { color:#B0B0B0; }

    .filter-btn {
        display:flex; align-items:center; gap:7px; height:40px; padding:0 14px;
        background:white; border:1px solid #E5E5E5; border-radius:9px;
        font-family:Inter,sans-serif; font-size:13px; color:#1E1E1E;
        cursor:pointer; white-space:nowrap;
    }
    .filter-btn:hover { background:#F8FAFC; }
    .filter-btn.active { border-color:#2D4DA3; color:#2D4DA3; background:#EFF6FF; }

    .btn-export {
        display:flex; align-items:center; gap:7px; height:40px; padding:0 16px;
        background:#22C55E; border:none; border-radius:9px;
        font-family:Inter,sans-serif; font-size:13px; font-weight:600; color:white;
        cursor:pointer; white-space:nowrap; text-decoration:none;
        box-shadow:0 2px 6px rgba(34,197,94,.25);
    }
    .btn-export:hover { background:#16A34A; color:white; }

    .btn-create {
        display:flex; align-items:center; gap:7px; height:40px; padding:0 16px;
        background:#2D4DA3; border:none; border-radius:9px;
        font-family:Inter,sans-serif; font-size:13px; font-weight:600; color:white;
        cursor:pointer; white-space:nowrap; text-decoration:none;
        box-shadow:0 2px 6px rgba(45,77,163,.2);
    }
    .btn-create:hover { background:#253f8a; color:white; }

    .table-container { background:white; border-radius:14px; padding:22px; box-shadow:0 2px 12px rgba(0,0,0,.07); }
    .table-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; }
    .table-title { font-family:Inter,sans-serif; font-size:14px; font-weight:700; color:#0F172A; }
    .count-badge {
        display:inline-flex; align-items:center; justify-content:center;
        background:#EFF6FF; color:#2D4DA3; border-radius:20px;
        padding:2px 10px; font-size:12px; font-weight:700; margin-left:8px; font-family:Inter,sans-serif;
    }

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

    .trx-code    { font-weight:700; color:#0F172A; font-family:'Consolas',monospace; font-size:13px; }
    .cust-name   { font-weight:600; color:#0F172A; }
    .item-name   { color:#475569; }
    .period-text { font-size:12px; color:#64748B; white-space:nowrap; }
    .amount-text { font-weight:700; color:#0F172A; white-space:nowrap; }

    .badge {
        display:inline-flex; align-items:center; gap:5px; border-radius:20px;
        padding:4px 12px; font-size:12px; font-weight:600; font-family:Inter,sans-serif; border:1px solid; white-space:nowrap;
    }
    .badge .dot { width:6px; height:6px; border-radius:50%; flex-shrink:0; }
    .badge-active    { background:#EFF6FF; color:#2D4DA3; border-color:#BFDBFE; }
    .badge-active .dot    { background:#2D4DA3; }
    .badge-completed { background:#ECFDF5; color:#059669; border-color:#6EE7B7; }
    .badge-completed .dot { background:#059669; }
    .badge-overdue   { background:#FFF7ED; color:#EA580C; border-color:#FED7AA; }
    .badge-overdue .dot   { background:#EA580C; }
    .badge-cancelled { background:#FEF2F2; color:#DC2626; border-color:#FECACA; }
    .badge-cancelled .dot { background:#DC2626; }

    .action-wrap { display:flex; gap:5px; align-items:center; }
    .action-btn {
        width:32px; height:32px; border-radius:8px; border:1px solid #E5E5E5;
        background:white; cursor:pointer; display:flex; align-items:center; justify-content:center;
        color:#64748B; transition:all .15s; text-decoration:none; flex-shrink:0;
    }
    .action-btn:hover { background:#F8FAFC; border-color:#D0D0D0; }
    .action-btn.btn-return { color:#059669; border-color:#6EE7B7; }
    .action-btn.btn-return:hover { background:#ECFDF5; }
    .action-btn.btn-edit { color:#2D4DA3; border-color:#BFDBFE; }
    .action-btn.btn-edit:hover { background:#EFF6FF; }
    .action-btn.btn-delete { color:#DC2626; border-color:#FECACA; }
    .action-btn.btn-delete:hover { background:#FEF2F2; }
    .action-btn svg { width:14px; height:14px; }

    .empty-state { text-align:center; padding:48px 0; color:#94A3B8; }
    .empty-state svg { width:44px; height:44px; margin-bottom:12px; opacity:.3; }
    .empty-state p { font-size:14px; margin:0; font-family:Inter,sans-serif; }

    /* Modals */
    .modal-overlay { display:none; position:fixed; inset:0; z-index:999; align-items:center; justify-content:center; padding:20px; }
    .modal-overlay.show { display:flex; }
    .modal-backdrop { position:fixed; inset:0; background:rgba(15,23,42,.5); backdrop-filter:blur(3px); }

    /* Detail modal */
    .detail-box {
        position:relative; z-index:1; background:#FFF; border-radius:16px;
        width:100%; max-width:560px; max-height:90vh; overflow-y:auto;
        box-shadow:0 20px 50px rgba(0,0,0,.18);
    }
    .detail-header {
        display:flex; align-items:center; justify-content:space-between;
        padding:18px 22px 14px; border-bottom:1px solid #F1F5F9;
        position:sticky; top:0; background:white; z-index:2;
    }
    .detail-trx-chip {
        background:#F1F5F9; color:#64748B; border-radius:6px;
        padding:3px 10px; font-size:12px; font-weight:700; font-family:'Consolas',monospace;
    }
    .detail-title-text { font-family:Inter,sans-serif; font-size:16px; font-weight:700; color:#0F172A; }
    .detail-close {
        width:28px; height:28px; background:#F1F5F9; border:none; border-radius:7px;
        cursor:pointer; display:flex; align-items:center; justify-content:center; color:#64748B; font-size:14px;
    }
    .detail-close:hover { background:#E2E8F0; }
    .detail-section-label {
        font-family:Inter,sans-serif; font-size:11px; font-weight:700; color:#94A3B8;
        text-transform:uppercase; letter-spacing:.06em; padding:14px 22px 8px;
    }
    .detail-card { margin:0 22px 14px; border:1px solid #E2E8F0; border-radius:12px; display:flex; overflow:hidden; }
    .d-left  { flex:0 0 210px; padding:16px; border-right:1px solid #E2E8F0; }
    .d-right { flex:1; padding:16px; }
    .d-avatar {
        width:42px; height:42px; border-radius:50%; background:#EFF6FF; color:#2D4DA3;
        display:flex; align-items:center; justify-content:center; font-size:14px; font-weight:700; flex-shrink:0;
    }
    .d-cust-name { font-family:Inter,sans-serif; font-size:14px; font-weight:700; color:#0F172A; }
    .d-email     { font-family:Inter,sans-serif; font-size:12px; color:#2D4DA3; }
    .d-info-row  { margin-bottom:10px; }
    .d-info-label { font-family:Inter,sans-serif; font-size:11px; color:#94A3B8; text-transform:uppercase; letter-spacing:.04em; margin-bottom:2px; }
    .d-info-val   { font-family:Inter,sans-serif; font-size:13px; color:#0F172A; font-weight:500; }
    .detail-footer {
        display:flex; justify-content:flex-end; padding:14px 22px;
        border-top:1px solid #F1F5F9; position:sticky; bottom:0; background:white;
    }
    .btn-close-detail {
        height:36px; padding:0 20px; background:white; border:1px solid #E2E8F0;
        border-radius:9px; font-family:Inter,sans-serif; font-size:13px; cursor:pointer; color:#374151;
    }
    .btn-close-detail:hover { background:#F8FAFC; }

    /* Confirm modal */
    .confirm-box {
        position:relative; z-index:1; background:#FFF; border-radius:18px;
        width:100%; max-width:380px; box-shadow:0 25px 60px rgba(0,0,0,.18); overflow:hidden;
    }
    .confirm-accent { height:4px; width:100%; }
    .confirm-body { padding:24px 24px 16px; }
    .confirm-icon-wrap { width:48px; height:48px; border-radius:13px; display:flex; align-items:center; justify-content:center; margin-bottom:14px; }
    .confirm-icon-wrap svg { width:22px; height:22px; }
    .confirm-subtitle { font-family:Inter,sans-serif; font-size:11px; font-weight:700; color:#94A3B8; text-transform:uppercase; letter-spacing:.06em; margin-bottom:3px; }
    .confirm-title    { font-family:Inter,sans-serif; font-size:16px; font-weight:700; color:#0F172A; margin-bottom:8px; }
    .confirm-desc     { font-family:Inter,sans-serif; font-size:13px; color:#64748B; line-height:1.6; }
    .confirm-footer   { padding:12px 24px 20px; display:flex; gap:10px; }
    .btn-cf-cancel {
        flex:1; height:40px; border:1.5px solid #E2E8F0; border-radius:11px;
        font-family:Inter,sans-serif; font-size:13px; font-weight:600; color:#64748B; background:white; cursor:pointer;
    }
    .btn-cf-cancel:hover { background:#F8FAFC; }
    .btn-cf-ok {
        flex:1; height:40px; border:none; border-radius:11px;
        font-family:Inter,sans-serif; font-size:13px; font-weight:700; color:white; cursor:pointer;
    }

    /* ── Sort Header ── */
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
    .sort-badge { display:inline-flex; align-items:center; background:#2D4DA3; color:white; font-size:9px; font-weight:700; padding:1px 5px; border-radius:4px; letter-spacing:.5px; margin-left:2px; opacity:0; transition:opacity .15s; }
    th.sort-active .sort-badge { opacity:1; }
</style>

<div class="page-header">
    <h1>Laporan &amp; Transaksi</h1>
    <p>Lihat dan ekspor riwayat penyewaan.</p>
</div>

@if (session('success'))
    <div class="alert-success">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22,4 12,14.01 9,11.01"/>
        </svg>
        {{ session('success') }}
    </div>
@endif

<div class="toolbar">
    <div class="search-wrap">
        <svg width="14" height="14" fill="none" stroke="#9E9E9E" stroke-width="2" viewBox="0 0 24 24">
            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        <input type="text" placeholder="Cari transaksi..." id="searchInput" onkeyup="filterTable()">
    </div>

    <button class="filter-btn" id="statusFilterBtn" onclick="cycleStatus()">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <polygon points="22,3 2,3 10,12.46 10,19 14,21 14,12.46 22,3"/>
        </svg>
        <span id="statusLabel">Status: Semua</span>
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <polyline points="6,9 12,15 18,9"/>
        </svg>
    </button>

    <a href="{{ route('reports.export') }}" class="btn-export">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
            <polyline points="7,10 12,15 17,10"/>
            <line x1="12" y1="15" x2="12" y2="3"/>
        </svg>
        Ekspor CSV
    </a>

    @if(!$isDosen && $canEdit)
    <a href="{{ route('transaksi.create') }}" class="btn-create">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
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

    <table id="transactionTable">
        <thead>
            <tr>
                <th style="width:160px">Aksi</th>
                <th class="sortable" data-col="1"><span class="th-inner">ID Transaksi<span class="sort-icon"><svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg><svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg></span><span class="sort-badge" id="badge-1"></span></span></th>
                <th class="sortable" data-col="2"><span class="th-inner">Pelanggan<span class="sort-icon"><svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg><svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg></span><span class="sort-badge" id="badge-2"></span></span></th>
                <th class="sortable" data-col="3"><span class="th-inner">Barang<span class="sort-icon"><svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg><svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg></span><span class="sort-badge" id="badge-3"></span></span></th>
                <th class="sortable" data-col="4"><span class="th-inner">Periode<span class="sort-icon"><svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg><svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg></span><span class="sort-badge" id="badge-4"></span></span></th>
                <th class="sortable" data-col="5"><span class="th-inner">Total<span class="sort-icon"><svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg><svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg></span><span class="sort-badge" id="badge-5"></span></span></th>
                <th class="sortable" data-col="6"><span class="th-inner">Status<span class="sort-icon"><svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg><svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg></span><span class="sort-badge" id="badge-6"></span></span></th>
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
                <td><span class="trx-code">{{ $trx->trx_code }}</span></td>
                <td><span class="cust-name">{{ $trx->user->name ?? '-' }}</span></td>
                <td><span class="item-name">{{ $trx->product->product_name ?? '-' }}</span></td>
                <td>
                    <span class="period-text">
                        {{ \Carbon\Carbon::parse($trx->rental_start)->format('d M Y') }}
                        →
                        {{ \Carbon\Carbon::parse($trx->rental_end)->format('d M Y') }}
                    </span>
                </td>
                <td><span class="amount-text">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</span></td>
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
            </tr>
            @empty
            <tr><td colspan="7">
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

{{-- MODAL DETAIL --}}
<div class="modal-overlay" id="modalDetail">
    <div class="modal-backdrop" onclick="closeDetail()"></div>
    <div class="detail-box">
        <div class="detail-header">
            <div style="display:flex;align-items:center;gap:10px;">
                <span class="detail-trx-chip" id="dTrxId"></span>
                <span class="detail-title-text">Detail Transaksi</span>
            </div>
            <button class="detail-close" onclick="closeDetail()">✕</button>
        </div>

        <div class="detail-section-label">Informasi Pelanggan</div>
        <div class="detail-card">
            <div class="d-left">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
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
                <div class="d-info-row"><div class="d-info-label">ID Transaksi</div><div class="d-info-val" id="dTrxId2" style="font-family:Consolas,monospace;">-</div></div>
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
    let currentStatus  = 'all', statusIdx = 0;

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
            accent:'#22C55E', iconBg:'#ECFDF5', iconColor:'#22C55E',
            iconPath:'<polyline points="1,4 1,10 7,10"/><path d="M3.51 15a9 9 0 1 0 .49-3.45"/>',
            subtitle:'Pengembalian Barang', title:'Konfirmasi Pengembalian',
            desc: c => `Transaksi <strong style="color:#0F172A;font-family:Consolas,monospace">${c}</strong> akan dikembalikan. Jika terlambat, denda otomatis akan dibuat.`,
            btnBg:'#22C55E', btnHover:'#16A34A', btnLabel:'Ya, Kembalikan',
        },
        delete: {
            accent:'#EF4444', iconBg:'#FEF2F2', iconColor:'#EF4444',
            iconPath:'<polyline points="3,6 5,6 21,6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/>',
            subtitle:'Hapus Transaksi', title:'Yakin ingin menghapus?',
            desc: c => `Transaksi <strong style="color:#0F172A;font-family:Consolas,monospace">${c}</strong> akan dihapus secara permanen.`,
            btnBg:'#EF4444', btnHover:'#DC2626', btnLabel:'Ya, Hapus',
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
        btn.textContent      = cfg.btnLabel;
        btn.onmouseover = () => btn.style.background = cfg.btnHover;
        btn.onmouseout  = () => btn.style.background = cfg.btnBg;
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
    // col types: 1=text(ID), 2=text(Pelanggan), 3=text(Barang), 4=date(Periode), 5=number(Total), 6=text(Status)
    const COL_TYPES = { 1:'text', 2:'text', 3:'text', 4:'date', 5:'number', 6:'text' };

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
        // Format: "01 Jan 2026 → 05 Jan 2026" — ambil tanggal pertama
        const part = str.split('→')[0].trim();
        const months = {jan:0,feb:1,mar:2,apr:3,mei:4,may:4,jun:5,jul:6,agu:7,aug:7,sep:8,okt:9,oct:9,nov:10,des:11,dec:11};
        const parts = part.toLowerCase().split(' ');
        if (parts.length >= 3) {
            const d = parseInt(parts[0]);
            const m = months[parts[1]] ?? 0;
            const y = parseInt(parts[2]);
            return new Date(y, m, d).getTime();
        }
        return 0;
    }

    function parseNumber(str) {
        // Format: "Rp 1.400.000" → 1400000
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