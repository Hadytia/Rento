@extends('layouts.app')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

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
    .page-header {
        display:flex; align-items:flex-end; justify-content:space-between;
        margin-bottom:32px; gap:24px;
    }
    .page-title h1 {
        font-family:Inter,sans-serif; font-size:26px; font-weight:700;
        color:var(--gray-900); margin:0; letter-spacing:-0.5px;
        background:linear-gradient(135deg, #111827 0%, #4F46E5 100%);
        -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent;
    }
    .page-title p {
        font-family:Inter,sans-serif; font-size:14px; color:var(--gray-500);
        margin:6px 0 0 0; letter-spacing:-0.1px;
    }
    .btn-add {
        height:44px; padding:0 22px;
        background:linear-gradient(135deg, #4F46E5 0%, #6366F1 100%);
        color:#FFF; border:none; border-radius:12px;
        font-family:Inter,sans-serif; font-size:13px; font-weight:600;
        cursor:pointer; display:flex; align-items:center; gap:8px; white-space:nowrap;
        box-shadow:0 4px 14px rgba(79,70,229,.32), 0 1px 0 rgba(255,255,255,.15) inset;
        transition:all .2s ease; letter-spacing:.2px;
    }
    .btn-add:hover { transform:translateY(-1px); box-shadow:0 8px 20px rgba(79,70,229,.4), 0 1px 0 rgba(255,255,255,.15) inset; }
    .btn-add:active { transform:translateY(0); }

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
       STAT CARDS
    ═══════════════════════════════════════════════════════════════════════ */
    .stats-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:18px; margin-bottom:28px; }
    .stat-card {
        background:#FFF; border-radius:16px; padding:22px 24px;
        border:1px solid var(--gray-200);
        display:flex; align-items:center; gap:16px;
        position:relative; overflow:hidden;
        transition:all .25s ease;
        box-shadow:0 1px 2px rgba(15,23,42,.04);
    }
    .stat-card::before {
        content:''; position:absolute; top:0; left:0; right:0; height:3px;
        opacity:0; transition:opacity .25s ease;
    }
    .stat-card:hover { transform:translateY(-2px); box-shadow:0 12px 24px -8px rgba(15,23,42,.1), 0 4px 8px -4px rgba(15,23,42,.05); border-color:transparent; }
    .stat-card:hover::before { opacity:1; }
    .stat-card.blue::before  { background:linear-gradient(90deg, #4F46E5, #818CF8); }
    .stat-card.green::before { background:linear-gradient(90deg, #059669, #34D399); }
    .stat-card.red::before   { background:linear-gradient(90deg, #DC2626, #F87171); }
    .stat-icon { width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center; flex-shrink:0; position:relative; }
    .stat-icon svg { width:22px; height:22px; position:relative; z-index:1; }
    .stat-icon.blue  { background:linear-gradient(135deg, #EEF2FF 0%, #E0E7FF 100%); color:#4F46E5; box-shadow:inset 0 0 0 1px rgba(79,70,229,.1); }
    .stat-icon.green { background:linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%); color:#059669; box-shadow:inset 0 0 0 1px rgba(5,150,105,.1); }
    .stat-icon.red   { background:linear-gradient(135deg, #FEF2F2 0%, #FEE2E2 100%); color:#DC2626; box-shadow:inset 0 0 0 1px rgba(220,38,38,.1); }
    .stat-label { font-family:Inter,sans-serif; font-size:11px; font-weight:600; color:var(--gray-500); text-transform:uppercase; letter-spacing:.8px; margin-bottom:6px; }
    .stat-value { font-family:Inter,sans-serif; font-size:28px; font-weight:700; color:var(--gray-900); line-height:1; letter-spacing:-1px; }

    /* ═══════════════════════════════════════════════════════════════════════
       TABLE CONTAINER
    ═══════════════════════════════════════════════════════════════════════ */
    .table-container { background:#FFF; border-radius:18px; padding:24px; border:1px solid var(--gray-200); box-shadow:0 1px 2px rgba(15,23,42,.04), 0 4px 12px rgba(15,23,42,.04); }
    .table-toolbar { display:flex; align-items:center; justify-content:space-between; margin-bottom:20px; padding-bottom:4px; }
    .table-label { font-family:Inter,sans-serif; font-size:15px; font-weight:600; color:var(--gray-900); letter-spacing:-0.2px; }
    .count-badge { display:inline-flex; align-items:center; justify-content:center; background:linear-gradient(135deg, #EEF2FF 0%, #E0E7FF 100%); color:#4F46E5; border-radius:8px; padding:3px 10px; font-size:12px; font-weight:700; margin-left:10px; font-family:Inter,sans-serif; box-shadow:inset 0 0 0 1px rgba(79,70,229,.15); }
    .search-wrap { display:flex; align-items:center; gap:8px; border:1px solid var(--gray-200); border-radius:11px; padding:0 14px; height:42px; background:var(--gray-50); width:260px; transition:all .2s ease; }
    .search-wrap:focus-within { border-color:#4F46E5; background:#FFF; box-shadow:0 0 0 4px rgba(79,70,229,.08); }
    .search-wrap input { border:none; outline:none; font-family:Inter,sans-serif; font-size:13px; color:var(--gray-900); width:100%; background:transparent; font-weight:500; }
    .search-wrap input::placeholder { color:var(--gray-400); font-weight:400; }

    /* ═══════════════════════════════════════════════════════════════════════
       TABLE
    ═══════════════════════════════════════════════════════════════════════ */
    .table-scroll { overflow-x:auto; border-radius:12px; border:1px solid var(--gray-100); }
    .table-scroll::-webkit-scrollbar { height:10px; }
    .table-scroll::-webkit-scrollbar-track { background:transparent; }
    .table-scroll::-webkit-scrollbar-thumb { background:var(--gray-200); border-radius:10px; border:2px solid #FFF; }
    .table-scroll::-webkit-scrollbar-thumb:hover { background:var(--gray-300); }
    table { width:100%; border-collapse:separate; border-spacing:0; }
    thead tr { background:var(--gray-50); }
    thead th { font-family:Inter,sans-serif; font-size:11px; font-weight:600; color:var(--gray-500); letter-spacing:.08em; text-transform:uppercase; padding:14px 18px; text-align:left; white-space:nowrap; background:var(--gray-50); border-bottom:1px solid var(--gray-200); }
    tbody tr { transition:background .15s ease; }
    tbody td { font-family:Inter,sans-serif; font-size:13px; color:var(--gray-800); padding:16px 18px; vertical-align:middle; background:#FFF; border-bottom:1px solid var(--gray-100); }
    tbody tr:last-child td { border-bottom:none; }
    tbody tr:hover td { background:#FAFBFF; }

    /* ── Freeze Panes ── */
    th.freeze-1, td.freeze-1 { position:sticky; left:0; z-index:3; min-width:160px; width:160px; }
    th.freeze-2, td.freeze-2 { position:sticky; left:160px; z-index:3; min-width:220px; width:220px; }
    th.freeze-3, td.freeze-3 { position:sticky; left:380px; z-index:3; min-width:210px; width:210px; box-shadow:6px 0 12px -8px rgba(15,23,42,.12); }
    thead th.freeze-1, thead th.freeze-2, thead th.freeze-3 { z-index:4; }

    /* ── Sort ── */
    .sortable { cursor:pointer; user-select:none; transition:all .15s ease; }
    .sortable:hover { color:#4F46E5 !important; background:#F5F3FF !important; }
    .th-inner { display:inline-flex; align-items:center; gap:8px; }
    .sort-icon { display:inline-flex; flex-direction:column; align-items:center; gap:2px; flex-shrink:0; }
    .sort-icon svg { width:9px; height:6px; display:block; transition:fill .15s; }
    .sortable:not(.sort-active) .tri-up, .sortable:not(.sort-active) .tri-down { fill:var(--gray-300); }
    .sortable:hover:not(.sort-active) .tri-up, .sortable:hover:not(.sort-active) .tri-down { fill:var(--gray-400); }
    th.sort-active { color:#4F46E5 !important; background:#F5F3FF !important; }
    th.sort-active.asc  .tri-up   { fill:#4F46E5; }
    th.sort-active.asc  .tri-down { fill:#C7D2FE; }
    th.sort-active.desc .tri-up   { fill:#C7D2FE; }
    th.sort-active.desc .tri-down { fill:#4F46E5; }
    .sort-badge { display:inline-flex; align-items:center; background:linear-gradient(135deg, #4F46E5 0%, #6366F1 100%); color:white; font-size:9px; font-weight:700; padding:2px 6px; border-radius:5px; letter-spacing:.5px; margin-left:4px; opacity:0; transition:opacity .15s; box-shadow:0 2px 4px rgba(79,70,229,.2); }
    th.sort-active .sort-badge { opacity:1; }

    /* ── Cell Contents ── */
    .identity-cell { display:flex; align-items:center; gap:12px; }
    .avatar { width:38px; height:38px; border-radius:50%; color:white; display:flex; align-items:center; justify-content:center; font-size:13px; font-weight:700; flex-shrink:0; letter-spacing:.3px; box-shadow:0 2px 6px rgba(15,23,42,.12), inset 0 -1px 0 rgba(0,0,0,.08); position:relative; }
    .avatar::after { content:''; position:absolute; inset:0; border-radius:50%; box-shadow:inset 0 1px 0 rgba(255,255,255,.2); }
    .identity-name { font-weight:600; color:var(--gray-900); font-size:13.5px; margin-bottom:2px; letter-spacing:-0.1px; }
    .identity-email { font-size:11.5px; color:var(--gray-400); font-weight:500; }
    .contact-phone { font-size:13px; color:var(--gray-700); font-weight:600; margin-bottom:3px; letter-spacing:-0.1px; }
    .contact-address { font-size:11.5px; color:var(--gray-400); max-width:180px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; font-weight:500; }
    .ktp-mono { font-size:11.5px; color:var(--gray-700); font-family:'JetBrains Mono', 'Consolas', monospace; background:var(--gray-100); padding:5px 10px; border-radius:6px; letter-spacing:.5px; font-weight:500; border:1px solid var(--gray-200); }
    .company-pill { display:inline-block; font-size:11px; font-weight:700; padding:4px 11px; border-radius:8px; background:linear-gradient(135deg, #EEF2FF 0%, #E0E7FF 100%); color:#4F46E5; letter-spacing:.4px; font-family:Inter,sans-serif; white-space:nowrap; box-shadow:inset 0 0 0 1px rgba(79,70,229,.15); }
    .badge { display:inline-flex; align-items:center; gap:6px; border-radius:8px; padding:5px 12px; font-family:Inter,sans-serif; font-size:11.5px; font-weight:600; letter-spacing:.2px; }
    .badge.active { background:linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%); color:#047857; box-shadow:inset 0 0 0 1px rgba(5,150,105,.2); }
    .badge.inactive { background:linear-gradient(135deg, #FEF2F2 0%, #FEE2E2 100%); color:#B91C1C; box-shadow:inset 0 0 0 1px rgba(220,38,38,.2); }
    .badge-dot { width:6px; height:6px; border-radius:50%; display:inline-block; box-shadow:0 0 0 2px rgba(255,255,255,.5); }
    .badge.active .badge-dot   { background:#10B981; animation:pulse-green 2s infinite; }
    .badge.inactive .badge-dot { background:#EF4444; }
    @keyframes pulse-green { 0%,100% { box-shadow:0 0 0 0 rgba(16,185,129,.4); } 50% { box-shadow:0 0 0 4px rgba(16,185,129,0); } }
    .audit-cell { min-width:160px; }
    .audit-name { font-size:12px; font-weight:600; color:var(--gray-700); margin-bottom:2px; letter-spacing:-0.1px; }
    .audit-date { font-size:11px; color:var(--gray-400); font-family:'JetBrains Mono', 'Consolas', monospace; font-weight:500; }
    .audit-empty { color:var(--gray-300); font-size:13px; }
    .action-wrap { display:flex; gap:6px; }
    .action-btn { height:32px; padding:0 12px; border-radius:8px; font-family:Inter,sans-serif; font-size:12px; font-weight:600; cursor:pointer; display:inline-flex; align-items:center; gap:5px; transition:all .15s ease; border:none; letter-spacing:.1px; }
    .btn-edit { background:#EEF2FF; color:#4F46E5; box-shadow:inset 0 0 0 1px rgba(79,70,229,.15); }
    .btn-edit:hover { background:#4F46E5; color:#FFF; transform:translateY(-1px); box-shadow:0 4px 10px rgba(79,70,229,.3); }
    .btn-delete { background:#FEF2F2; color:#DC2626; box-shadow:inset 0 0 0 1px rgba(220,38,38,.15); }
    .btn-delete:hover { background:#DC2626; color:#FFF; transform:translateY(-1px); box-shadow:0 4px 10px rgba(220,38,38,.3); }
    .action-btn svg { width:13px; height:13px; }
    .empty-state { text-align:center; padding:64px 0; color:var(--gray-400); }
    .empty-state svg { width:48px; height:48px; margin-bottom:14px; opacity:.4; }
    .empty-state p { font-size:14px; margin:0; font-family:Inter,sans-serif; font-weight:500; }

    /* ═══════════════════════════════════════════════════════════════════════
       MODAL OVERLAY & ANIMATION
    ═══════════════════════════════════════════════════════════════════════ */
    .modal-overlay {
        display:none; position:fixed; inset:0; z-index:999;
        align-items:center; justify-content:center; padding:20px;
    }
    .modal-overlay.show { display:flex; animation:fadeIn .2s ease; }
    @keyframes fadeIn { from{opacity:0;} to{opacity:1;} }

    .modal-backdrop {
        position:fixed; inset:0;
        background:rgba(15,23,42,.55); backdrop-filter:blur(6px);
    }

    .modal-box {
        position:relative; z-index:1; background:#FFF; border-radius:18px;
        width:100%; max-width:560px; max-height:90vh; overflow:hidden;
        box-shadow:0 25px 60px rgba(0,0,0,.25);
        animation:slideUp .28s cubic-bezier(0.22,1,0.36,1);
    }
    @keyframes slideUp {
        from { transform:translateY(24px); opacity:0; }
        to   { transform:translateY(0); opacity:1; }
    }

    /* ── Modal Header Gradient Biru ── */
    .modal-header {
        position:relative;
        background:linear-gradient(135deg, #1e3a5f 0%, #2563eb 60%, #3b82f6 100%);
        padding:28px 32px;
        overflow:hidden;
    }
    .modal-header::before {
        content:''; position:absolute; top:-40px; right:-40px;
        width:180px; height:180px; border-radius:50%;
        background:rgba(255,255,255,0.06);
        pointer-events:none;
    }
    .modal-header::after {
        content:''; position:absolute; bottom:-60px; left:-60px;
        width:200px; height:200px; border-radius:50%;
        background:rgba(255,255,255,0.04);
        pointer-events:none;
    }
    .modal-header-content {
        position:relative; z-index:1;
        display:flex; align-items:flex-start; gap:16px;
    }
    .modal-icon-box {
        width:52px; height:52px; border-radius:12px; flex-shrink:0;
        background:rgba(255,255,255,0.15); backdrop-filter:blur(10px);
        border:1px solid rgba(255,255,255,0.2);
        display:flex; align-items:center; justify-content:center;
        box-shadow:0 8px 16px rgba(0,0,0,0.1);
    }
    .modal-icon-box svg { width:24px; height:24px; color:#fff; }
    .modal-title-wrap { flex:1; }
    .modal-title-wrap h2 {
        font-family:'Plus Jakarta Sans',sans-serif; font-size:20px; font-weight:800;
        color:#fff; margin:0 0 4px 0; letter-spacing:-0.3px;
    }
    .modal-title-wrap p {
        font-family:'Plus Jakarta Sans',sans-serif; font-size:13px;
        color:rgba(255,255,255,0.7); margin:0; font-weight:500;
    }
    .modal-close-btn {
        position:absolute; top:20px; right:20px; z-index:2;
        width:36px; height:36px; border-radius:10px;
        background:rgba(255,255,255,0.15); backdrop-filter:blur(10px);
        border:1px solid rgba(255,255,255,0.2);
        display:flex; align-items:center; justify-content:center;
        cursor:pointer; transition:all .2s ease;
    }
    .modal-close-btn:hover { background:rgba(255,255,255,0.25); transform:rotate(90deg); }
    .modal-close-btn svg { width:18px; height:18px; color:#fff; }

    /* ── Modal Body ── */
    .modal-body {
        padding:28px 32px;
        max-height:calc(90vh - 200px);
        overflow-y:auto;
    }
    .modal-body::-webkit-scrollbar { width:6px; }
    .modal-body::-webkit-scrollbar-track { background:transparent; }
    .modal-body::-webkit-scrollbar-thumb { background:#e2e8f0; border-radius:10px; }

    /* ── Section Labels ── */
    .modal-section {
        display:flex; align-items:center; gap:10px;
        margin:24px 0 16px;
        font-family:'Plus Jakarta Sans',sans-serif;
        font-size:0.65rem; font-weight:700;
        color:#94a3b8; text-transform:uppercase; letter-spacing:.08em;
    }
    .modal-section:first-child { margin-top:0; }
    .modal-section svg { width:14px; height:14px; flex-shrink:0; color:#94a3b8; }
    .modal-section::after { content:''; flex:1; height:1px; background:#f1f5f9; }

    .form-divider { height:1px; background:#f1f5f9; margin:20px 0; }
    .form-grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
    .form-group { margin-bottom:16px; }
    .form-group:last-child { margin-bottom:0; }
    .form-group label {
        display:block;
        font-family:'Plus Jakarta Sans',sans-serif;
        font-size:0.7rem; font-weight:700;
        color:#64748b; margin-bottom:8px;
        text-transform:uppercase; letter-spacing:.06em;
    }
    .form-group label .req { color:#ef4444; margin-left:2px; }

    /* ── Input Fields dengan Icon ── */
    .fg-ico { position:relative; }
    .fg-ico .input-icon {
        position:absolute; left:12px; top:50%; transform:translateY(-50%);
        width:16px; height:16px; color:#94a3b8; pointer-events:none;
    }
    .fg-ico input,
    .fg-ico select {
        width:100%; height:42px;
        padding-left:34px; padding-right:14px;
        border:1.5px solid #e8edf5; border-radius:10px;
        background:#f8fafc;
        font-family:'Plus Jakarta Sans',sans-serif;
        font-size:0.875rem; font-weight:500; color:#1e293b;
        outline:none; box-sizing:border-box;
        transition:all .2s ease;
    }
    .fg-ico input:focus,
    .fg-ico select:focus {
        border-color:#3b82f6; background:#fff;
        box-shadow:0 0 0 3px rgba(59,130,246,.1);
    }
    .fg-ico input::placeholder { color:#cbd5e1; font-weight:400; }

    /* ── Select Custom Arrow ── */
    .fg-ico select {
        appearance:none;
        background-image:url("data:image/svg+xml,%3Csvg width='12' height='8' viewBox='0 0 12 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1.5L6 6.5L11 1.5' stroke='%2394a3b8' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
        background-repeat:no-repeat;
        background-position:right 14px center;
        padding-right:38px;
    }

    /* ── Toggle Status Row ── */
    .toggle-row {
        display:flex; align-items:center; justify-content:space-between;
        background:#f8fafc; border:1.5px solid #e8edf5;
        border-radius:10px; padding:14px 16px; margin-top:4px;
    }
    .toggle-label-wrap .tl-title {
        font-family:'Plus Jakarta Sans',sans-serif; font-size:13.5px; font-weight:600;
        color:#1e293b; letter-spacing:-0.1px;
    }
    .toggle-label-wrap .tl-sub {
        font-family:'Plus Jakarta Sans',sans-serif; font-size:11.5px;
        color:#94a3b8; margin-top:2px;
    }
    .toggle-wrap { display:flex; align-items:center; gap:10px; }
    .toggle-slider {
        width:46px; height:26px; border-radius:13px; position:relative;
        cursor:pointer; transition:background .25s ease;
        box-shadow:inset 0 2px 4px rgba(0,0,0,0.06);
    }
    .toggle-thumb {
        width:20px; height:20px; background:white; border-radius:50%;
        position:absolute; top:3px; transition:left .25s ease;
        box-shadow:0 2px 4px rgba(0,0,0,0.2);
    }
    .toggle-text {
        font-family:'Plus Jakarta Sans',sans-serif;
        font-size:12px; font-weight:700;
    }

    /* ── Modal Footer ── */
    .modal-footer {
        padding:20px 32px 28px;
        border-top:1px solid #f1f5f9;
        display:flex; gap:12px; justify-content:flex-end;
        background:#fafbfc;
    }
    .btn-cancel {
        height:42px; padding:0 24px;
        background:#f1f5f9; border:1.5px solid #e2e8f0;
        border-radius:10px;
        font-family:'Plus Jakarta Sans',sans-serif;
        font-size:0.875rem; font-weight:700;
        color:#64748b; cursor:pointer;
        transition:all .2s ease;
    }
    .btn-cancel:hover { background:#e2e8f0; border-color:#cbd5e1; }
    .btn-save {
        height:42px; padding:0 28px;
        background:linear-gradient(135deg, #2563eb, #3b82f6);
        border:none; border-radius:10px;
        font-family:'Plus Jakarta Sans',sans-serif;
        font-size:0.875rem; font-weight:700;
        color:#fff; cursor:pointer;
        box-shadow:0 4px 14px rgba(37,99,235,.35);
        transition:all .2s ease;
    }
    .btn-save:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(37,99,235,.45); }

    /* ═══════════════════════════════════════════════════════════════════════
       MODAL DELETE — GRADIENT MERAH
    ═══════════════════════════════════════════════════════════════════════ */
    .confirm-box {
        position:relative; z-index:1; background:#FFF; border-radius:18px;
        width:100%; max-width:460px; overflow:hidden;
        box-shadow:0 25px 60px rgba(0,0,0,.25);
        animation:slideUp .28s cubic-bezier(0.22,1,0.36,1);
    }

    /* Header merah gradient */
    .confirm-header {
        position:relative;
        background:linear-gradient(135deg, #7f1d1d 0%, #dc2626 60%, #ef4444 100%);
        padding:28px 32px;
        overflow:hidden;
    }
    .confirm-header::before {
        content:''; position:absolute; top:-40px; right:-40px;
        width:160px; height:160px; border-radius:50%;
        background:rgba(255,255,255,0.06); pointer-events:none;
    }
    .confirm-header::after {
        content:''; position:absolute; bottom:-50px; left:-50px;
        width:180px; height:180px; border-radius:50%;
        background:rgba(255,255,255,0.04); pointer-events:none;
    }
    .confirm-header-content {
        position:relative; z-index:1;
        display:flex; align-items:flex-start; gap:16px;
    }
    .confirm-icon-box {
        width:52px; height:52px; border-radius:12px; flex-shrink:0;
        background:rgba(255,255,255,0.15); backdrop-filter:blur(10px);
        border:1px solid rgba(255,255,255,0.2);
        display:flex; align-items:center; justify-content:center;
        box-shadow:0 8px 16px rgba(0,0,0,0.1);
    }
    .confirm-icon-box svg { width:24px; height:24px; color:#fff; }
    .confirm-title-wrap h2 {
        font-family:'Plus Jakarta Sans',sans-serif; font-size:20px; font-weight:800;
        color:#fff; margin:0 0 4px 0; letter-spacing:-0.3px;
    }
    .confirm-title-wrap p {
        font-family:'Plus Jakarta Sans',sans-serif; font-size:13px;
        color:rgba(255,255,255,0.7); margin:0; font-weight:500;
    }
    .confirm-close-btn {
        position:absolute; top:20px; right:20px; z-index:2;
        width:36px; height:36px; border-radius:10px;
        background:rgba(255,255,255,0.15); backdrop-filter:blur(10px);
        border:1px solid rgba(255,255,255,0.2);
        display:flex; align-items:center; justify-content:center;
        cursor:pointer; transition:all .2s ease;
    }
    .confirm-close-btn:hover { background:rgba(255,255,255,0.25); transform:rotate(90deg); }
    .confirm-close-btn svg { width:18px; height:18px; color:#fff; }

    .confirm-body { padding:28px 32px 8px; }
    .confirm-desc {
        font-family:'Plus Jakarta Sans',sans-serif; font-size:14px;
        color:#64748b; line-height:1.65; margin:0; font-weight:500;
    }
    .confirm-footer { padding:16px 32px 28px; display:flex; gap:12px; }
    .btn-confirm-cancel {
        flex:1; height:42px;
        background:#f1f5f9; border:1.5px solid #e2e8f0;
        border-radius:10px;
        font-family:'Plus Jakarta Sans',sans-serif;
        font-size:0.875rem; font-weight:700;
        color:#64748b; cursor:pointer;
        transition:all .2s ease;
    }
    .btn-confirm-cancel:hover { background:#e2e8f0; border-color:#cbd5e1; }
    .btn-confirm-delete {
        flex:1; height:42px; border:none; border-radius:10px;
        background:linear-gradient(135deg, #dc2626, #ef4444);
        font-family:'Plus Jakarta Sans',sans-serif;
        font-size:0.875rem; font-weight:700;
        color:#fff; cursor:pointer;
        box-shadow:0 4px 14px rgba(220,38,38,.35);
        transition:all .2s ease;
    }
    .btn-confirm-delete:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(220,38,38,.45); }
</style>

{{-- ═══════════════════════════════════════════════════════════════════════
     PAGE HEADER
═══════════════════════════════════════════════════════════════════════ --}}
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
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22,4 12,14.01 9,11.01"/>
        </svg>
        {{ session('success') }}
    </div>
@endif

{{-- ═══════════════════════════════════════════════════════════════════════
     STATS
═══════════════════════════════════════════════════════════════════════ --}}
@php
    $totalUsers    = $users->count();
    $activeUsers   = $users->where('status', 1)->count();
    $inactiveUsers = $users->where('status', 0)->count();
@endphp
<div class="stats-grid">
    <div class="stat-card blue">
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
    <div class="stat-card green">
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
    <div class="stat-card red">
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

{{-- ═══════════════════════════════════════════════════════════════════════
     TABLE
═══════════════════════════════════════════════════════════════════════ --}}
<div class="table-container">
    <div class="table-toolbar">
        <div style="display:flex;align-items:center;">
            <span class="table-label">Daftar User</span>
            <span class="count-badge">{{ $totalUsers }}</span>
        </div>
        <div class="search-wrap">
            <svg width="14" height="14" fill="none" stroke="#9CA3AF" stroke-width="2.2" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="text" id="searchInput" placeholder="Cari user..." onkeyup="filterTable()">
        </div>
    </div>

    <div class="table-scroll">
    <table id="userTable">
        <thead>
            <tr>
                <th class="freeze-1">Aksi</th>
                <th class="freeze-2 sortable" data-col="1" data-type="text">
                    <span class="th-inner">Identitas
                        <span class="sort-icon">
                            <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                            <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                        </span>
                        <span class="sort-badge" id="badge-1"></span>
                    </span>
                </th>
                <th class="freeze-3 sortable" data-col="2" data-type="text">
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
                <th class="sortable" data-col="7" data-type="text">
                    <span class="th-inner">Created By
                        <span class="sort-icon">
                            <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                            <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                        </span>
                        <span class="sort-badge" id="badge-7"></span>
                    </span>
                </th>
                <th class="sortable" data-col="8" data-type="date">
                    <span class="th-inner">Created Date
                        <span class="sort-icon">
                            <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                            <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                        </span>
                        <span class="sort-badge" id="badge-8"></span>
                    </span>
                </th>
                <th class="sortable" data-col="9" data-type="text">
                    <span class="th-inner">Last Updated By
                        <span class="sort-icon">
                            <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                            <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                        </span>
                        <span class="sort-badge" id="badge-9"></span>
                    </span>
                </th>
                <th class="sortable" data-col="10" data-type="date">
                    <span class="th-inner">Last Updated Date
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
            @php
                $avColors = [
                    'linear-gradient(135deg, #4F46E5 0%, #6366F1 100%)',
                    'linear-gradient(135deg, #7C3AED 0%, #A78BFA 100%)',
                    'linear-gradient(135deg, #059669 0%, #34D399 100%)',
                    'linear-gradient(135deg, #D97706 0%, #FBBF24 100%)',
                    'linear-gradient(135deg, #DC2626 0%, #F87171 100%)',
                    'linear-gradient(135deg, #0891B2 0%, #22D3EE 100%)',
                ];
            @endphp
            @forelse ($users as $user)
            @php
                $avColor  = $avColors[$user->id % count($avColors)];
                $initials = strtoupper(substr($user->name, 0, 1))
                          . strtoupper(substr(strstr($user->name, ' ') ?: ' ', 1, 1));
            @endphp
            <tr>
                {{-- Aksi --}}
                <td class="freeze-1">
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
                                '{{ addslashes($user->no_emergency_contact) }}'
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
                <td class="freeze-2">
                    <div class="identity-cell">
                        <div class="avatar" style="background:{{ $avColor }}">{{ $initials }}</div>
                        <div>
                            <div class="identity-name">{{ $user->name }}</div>
                            <div class="identity-email">{{ $user->email }}</div>
                        </div>
                    </div>
                </td>

                {{-- Kontak --}}
                <td class="freeze-3">
                    <div class="contact-phone">{{ $user->phone ?: '-' }}</div>
                    <div class="contact-address">{{ $user->address ?: '-' }}</div>
                </td>

                {{-- No KTP --}}
                <td><span class="ktp-mono">{{ $user->id_card_number ?: '-' }}</span></td>

                {{-- Kontak Darurat --}}
                <td style="font-size:13px; color:var(--gray-700); font-weight:500;">{{ $user->emergency_contact ?: '-' }}</td>

                {{-- Company Code --}}
                <td>
                    @if($user->company_code)
                        <span class="company-pill">{{ $user->company_code }}</span>
                    @else
                        <span style="color:var(--gray-300); font-size:13px;">-</span>
                    @endif
                </td>

                {{-- Status --}}
                <td>
                    <span class="badge {{ $user->status ? 'active' : 'inactive' }}">
                        <span class="badge-dot"></span>
                        {{ $user->status ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>

                {{-- Created By --}}
                <td class="audit-cell">
                    @if($user->created_by)
                        <div class="audit-name">{{ $user->created_by }}</div>
                    @else
                        <span class="audit-empty">-</span>
                    @endif
                </td>

                {{-- Created Date --}}
                <td class="audit-cell">
                    @if($user->created_date)
                        <div class="audit-date">{{ \Carbon\Carbon::parse($user->created_date)->format('d M Y, H:i') }}</div>
                    @else
                        <span class="audit-empty">-</span>
                    @endif
                </td>

                {{-- Last Updated By --}}
                <td class="audit-cell">
                    @if($user->last_updated_by)
                        <div class="audit-name">{{ $user->last_updated_by }}</div>
                    @else
                        <span class="audit-empty">-</span>
                    @endif
                </td>

                {{-- Last Updated Date --}}
                <td class="audit-cell">
                    @if($user->last_updated_date)
                        <div class="audit-date">{{ \Carbon\Carbon::parse($user->last_updated_date)->format('d M Y, H:i') }}</div>
                    @else
                        <span class="audit-empty">-</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="11">
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

{{-- ══════════════════════════════════════════════════════════════════════════
     MODAL TAMBAH USER
══════════════════════════════════════════════════════════════════════════ --}}
<div class="modal-overlay" id="modalAdd">
    <div class="modal-backdrop" onclick="closeModal('modalAdd')"></div>
    <div class="modal-box">

        {{-- Header Gradient Biru --}}
        <div class="modal-header">
            <div class="modal-header-content">
                <div class="modal-icon-box">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="8.5" cy="7" r="4"/>
                        <line x1="20" y1="8" x2="20" y2="14"/>
                        <line x1="23" y1="11" x2="17" y2="11"/>
                    </svg>
                </div>
                <div class="modal-title-wrap">
                    <h2>Tambah User Baru</h2>
                    <p>Isi formulir untuk menambahkan pelanggan baru</p>
                </div>
            </div>
            <button class="modal-close-btn" type="button" onclick="closeModal('modalAdd')">
                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        <form method="POST" action="{{ route('users.store') }}">
            @csrf
            <div class="modal-body">

                {{-- Section: Identitas --}}
                <div class="modal-section">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                    </svg>
                    Identitas User
                </div>
                <div class="form-group">
                    <label>Nama Lengkap <span class="req">*</span></label>
                    <div class="fg-ico">
                        <svg class="input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                        </svg>
                        <input type="text" name="name" placeholder="Masukkan nama lengkap..." required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Alamat Email <span class="req">*</span></label>
                    <div class="fg-ico">
                        <svg class="input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                        <input type="email" name="email" placeholder="email@example.com" required>
                    </div>
                </div>

                <div class="form-divider"></div>

                {{-- Section: Kontak --}}
                <div class="modal-section">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.65 3.35 2 2 0 0 1 3.62 1h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 8.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                    </svg>
                    Informasi Kontak
                </div>
                <div class="form-group">
                    <label>No. Telepon <span class="req">*</span></label>
                    <div class="fg-ico">
                        <svg class="input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.65 3.35 2 2 0 0 1 3.62 1h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 8.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                        <input type="text" name="phone" placeholder="08xx-xxxx-xxxx" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Alamat Lengkap</label>
                    <div class="fg-ico">
                        <svg class="input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                        </svg>
                        <input type="text" name="address" placeholder="Jl. contoh No. 1, Kota">
                    </div>
                </div>

                <div class="form-divider"></div>

                {{-- Section: Data Tambahan --}}
                <div class="modal-section">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/>
                    </svg>
                    Data Tambahan
                </div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label>No. KTP</label>
                        <div class="fg-ico">
                            <svg class="input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/>
                            </svg>
                            <input type="text" name="id_card_number" placeholder="16 digit NIK" maxlength="16" style="font-family:monospace;letter-spacing:.5px;">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Kode Perusahaan <span class="req">*</span></label>
                        <div class="fg-ico">
                            <svg class="input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                                <polyline points="9,22 9,12 15,12 15,22"/>
                            </svg>
                            <input type="text" name="company_code" placeholder="Contoh: PT-001" required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Kontak Darurat</label>
                    <div class="fg-ico">
                        <svg class="input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                            <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg>
                        <input type="text" name="emergency_contact" placeholder="Nama - No. HP">
                    </div>
                </div>

                <div class="form-group">
                    <label>No. HP Darurat</label>
                    <div class="fg-ico">
                        <svg class="input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.65 3.35 2 2 0 0 1 3.62 1h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 8.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                        <input type="text" name="no_emergency_contact" placeholder="08xx-xxxx-xxxx" maxlength="20">
                    </div>
                </div>

                {{-- Toggle Status --}}
                <div class="toggle-row">
                    <div class="toggle-label-wrap">
                        <div class="tl-title">Status Akun</div>
                        <div class="tl-sub">Aktifkan atau nonaktifkan akun user</div>
                    </div>
                    <div class="toggle-wrap">
                        <input type="checkbox" name="status" value="1" id="addToggleStatus" style="display:none;" checked>
                        <div class="toggle-slider" id="addToggleSlider" style="background:linear-gradient(135deg,#2563eb,#3b82f6);" onclick="toggleCheck('addToggleStatus','addToggleLabel','addToggleSlider')">
                            <div class="toggle-thumb" style="left:23px;"></div>
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

{{-- ══════════════════════════════════════════════════════════════════════════
     MODAL EDIT USER
══════════════════════════════════════════════════════════════════════════ --}}
<div class="modal-overlay" id="modalEdit">
    <div class="modal-backdrop" onclick="closeModal('modalEdit')"></div>
    <div class="modal-box">

        {{-- Header Gradient Biru --}}
        <div class="modal-header">
            <div class="modal-header-content">
                <div class="modal-icon-box">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                </div>
                <div class="modal-title-wrap">
                    <h2>Edit Data User</h2>
                    <p>Perbarui informasi pelanggan</p>
                </div>
            </div>
            <button class="modal-close-btn" type="button" onclick="closeModal('modalEdit')">
                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        <form method="POST" id="editForm" action="">
            @csrf @method('PUT')
            <div class="modal-body">

                {{-- Section: Identitas --}}
                <div class="modal-section">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                    </svg>
                    Identitas User
                </div>
                <div class="form-group">
                    <label>Nama Lengkap <span class="req">*</span></label>
                    <div class="fg-ico">
                        <svg class="input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                        </svg>
                        <input type="text" name="name" id="editName" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Alamat Email <span class="req">*</span></label>
                    <div class="fg-ico">
                        <svg class="input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                        <input type="email" name="email" id="editEmail" required>
                    </div>
                </div>

                <div class="form-divider"></div>

                {{-- Section: Kontak --}}
                <div class="modal-section">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.65 3.35 2 2 0 0 1 3.62 1h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 8.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                    </svg>
                    Informasi Kontak
                </div>
                <div class="form-group">
                    <label>No. Telepon</label>
                    <div class="fg-ico">
                        <svg class="input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.65 3.35 2 2 0 0 1 3.62 1h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 8.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                        <input type="text" name="phone" id="editPhone">
                    </div>
                </div>
                <div class="form-group">
                    <label>Alamat Lengkap</label>
                    <div class="fg-ico">
                        <svg class="input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                        </svg>
                        <input type="text" name="address" id="editAddress">
                    </div>
                </div>

                <div class="form-divider"></div>

                {{-- Section: Data Tambahan --}}
                <div class="modal-section">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/>
                    </svg>
                    Data Tambahan
                </div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label>No. KTP</label>
                        <div class="fg-ico">
                            <svg class="input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/>
                            </svg>
                            <input type="text" name="id_card_number" id="editIdCard" maxlength="16" style="font-family:monospace;letter-spacing:.5px;">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Kode Perusahaan</label>
                        <div class="fg-ico">
                            <svg class="input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                                <polyline points="9,22 9,12 15,12 15,22"/>
                            </svg>
                            <input type="text" name="company_code" id="editCompanyCode">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Kontak Darurat</label>
                    <div class="fg-ico">
                        <svg class="input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                            <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg>
                        <input type="text" name="emergency_contact" id="editEmergencyContact">
                    </div>
                </div>

                <div class="form-group">
                    <label>No. HP Darurat</label>
                    <div class="fg-ico">
                        <svg class="input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.65 3.35 2 2 0 0 1 3.62 1h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 8.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                        <input type="text" name="no_emergency_contact" id="editNoEmergencyContact" maxlength="20">
                    </div>
                </div>

                {{-- Toggle Status --}}
                <div class="toggle-row">
                    <div class="toggle-label-wrap">
                        <div class="tl-title">Status Akun</div>
                        <div class="tl-sub">Aktifkan atau nonaktifkan akun user</div>
                    </div>
                    <div class="toggle-wrap">
                        <input type="checkbox" name="status" value="1" id="editToggleStatus" style="display:none;">
                        <div class="toggle-slider" id="editToggleSlider" style="background:#e2e8f0;" onclick="toggleCheck('editToggleStatus','editToggleLabel','editToggleSlider')">
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

{{-- ══════════════════════════════════════════════════════════════════════════
     MODAL DELETE USER
══════════════════════════════════════════════════════════════════════════ --}}
<div class="modal-overlay" id="modalDelete">
    <div class="modal-backdrop" onclick="closeModal('modalDelete')"></div>
    <div class="confirm-box">

        {{-- Header Gradient Merah --}}
        <div class="confirm-header">
            <div class="confirm-header-content">
                <div class="confirm-icon-box">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <polyline points="3,6 5,6 21,6"/>
                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                        <path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/>
                    </svg>
                </div>
                <div class="confirm-title-wrap">
                    <h2>Hapus User</h2>
                    <p>Tindakan ini tidak dapat dibatalkan</p>
                </div>
            </div>
            <button class="confirm-close-btn" type="button" onclick="closeModal('modalDelete')">
                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        <div class="confirm-body">
            <p class="confirm-desc">
                User <strong id="deleteUserName" style="color:#1e293b;font-weight:700;"></strong> akan dihapus secara permanen dari sistem dan tidak dapat dipulihkan kembali.
            </p>
        </div>

        <div class="confirm-footer">
            <button class="btn-confirm-cancel" onclick="closeModal('modalDelete')">Batal</button>
            <button class="btn-confirm-delete" onclick="executeDelete()">Ya, Hapus</button>
        </div>
    </div>
</div>

<form id="deleteForm" method="POST" action="" style="display:none">
    @csrf @method('DELETE')
</form>

<script>
    function toggleCheck(inputId, labelId, sliderId) {
        const input  = document.getElementById(inputId);
        const label  = document.getElementById(labelId);
        const slider = document.getElementById(sliderId);
        const thumb  = slider.querySelector('.toggle-thumb');
        input.checked = !input.checked;
        if (input.checked) {
            slider.style.background = 'linear-gradient(135deg,#2563eb,#3b82f6)';
            thumb.style.left = '23px';
            label.textContent = 'Aktif';
            label.style.color = '#059669';
        } else {
            slider.style.background = '#e2e8f0';
            thumb.style.left = '3px';
            label.textContent = 'Nonaktif';
            label.style.color = '#DC2626';
        }
    }

    function openAddModal() { document.getElementById('modalAdd').classList.add('show'); }

    function openEditModal(id, name, email, phone, address, idCard, emergencyContact, companyCode, status, noEmergencyContact) {
        document.getElementById('editForm').action              = '/users/' + id;
        document.getElementById('editName').value               = name;
        document.getElementById('editEmail').value              = email;
        document.getElementById('editPhone').value              = phone;
        document.getElementById('editAddress').value            = address;
        document.getElementById('editIdCard').value             = idCard;
        document.getElementById('editEmergencyContact').value   = emergencyContact;
        document.getElementById('editNoEmergencyContact').value = noEmergencyContact;
        document.getElementById('editCompanyCode').value        = companyCode;

        const toggle = document.getElementById('editToggleStatus');
        const slider = document.getElementById('editToggleSlider');
        const label  = document.getElementById('editToggleLabel');
        const thumb  = slider.querySelector('.toggle-thumb');
        toggle.checked = status == 1;
        if (status == 1) {
            slider.style.background = 'linear-gradient(135deg,#2563eb,#3b82f6)'; thumb.style.left = '23px';
            label.textContent = 'Aktif'; label.style.color = '#059669';
        } else {
            slider.style.background = '#e2e8f0'; thumb.style.left = '3px';
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

    // Close on ESC
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') ['modalAdd','modalEdit','modalDelete'].forEach(id => closeModal(id));
    });

    function filterTable() {
        const q = document.getElementById('searchInput').value.toLowerCase();
        document.querySelectorAll('#userTable tbody tr').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(q) ? '' : 'none';
        });
    }

    let sortCol = -1, sortDir = 'asc';

    document.querySelectorAll('th.sortable').forEach(th => {
        th.addEventListener('click', () => {
            const col = parseInt(th.dataset.col);
            sortDir = (sortCol === col && sortDir === 'asc') ? 'desc' : 'asc';
            sortCol = col;
            updateSortIcons();
            sortTable(col, sortDir, th.dataset.type);
        });
    });

    function updateSortIcons() {
        document.querySelectorAll('th.sortable').forEach(th => {
            const col   = parseInt(th.dataset.col);
            const type  = th.dataset.type;
            const badge = document.getElementById('badge-' + col);
            if (col === sortCol) {
                th.classList.add('sort-active');
                th.classList.remove('asc', 'desc');
                th.classList.add(sortDir);
                if (badge) badge.textContent = type === 'date'
                    ? (sortDir === 'asc' ? 'Lama→Baru' : 'Baru→Lama')
                    : (sortDir === 'asc' ? 'A-Z' : 'Z-A');
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

    function sortTable(col, dir, type) {
        const tbody = document.querySelector('#userTable tbody');
        const rows  = Array.from(tbody.querySelectorAll('tr')).filter(r => r.cells.length > 1);
        rows.sort((a, b) => {
            const aT = a.cells[col]?.innerText.trim() ?? '';
            const bT = b.cells[col]?.innerText.trim() ?? '';
            if (type === 'date') {
                return dir === 'asc' ? parseDate(aT) - parseDate(bT) : parseDate(bT) - parseDate(aT);
            }
            const aL = aT.toLowerCase(), bL = bT.toLowerCase();
            if (aL < bL) return dir === 'asc' ? -1 : 1;
            if (aL > bL) return dir === 'asc' ?  1 : -1;
            return 0;
        });
        rows.forEach(r => tbody.appendChild(r));
    }
</script>

@endsection