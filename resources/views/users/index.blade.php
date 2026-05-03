@extends('layouts.app')

@section('content')

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
        transition:all .2s ease;
        letter-spacing:.2px;
    }
    .btn-add:hover {
        transform:translateY(-1px);
        box-shadow:0 8px 20px rgba(79,70,229,.4), 0 1px 0 rgba(255,255,255,.15) inset;
    }
    .btn-add:active { transform:translateY(0); }

    /* ═══════════════════════════════════════════════════════════════════════
       ALERT
    ═══════════════════════════════════════════════════════════════════════ */
    .alert-success {
        background:linear-gradient(135deg, #ECFDF5 0%, #F0FDF9 100%);
        border:1px solid #A7F3D0; border-radius:12px;
        padding:13px 18px; font-family:Inter,sans-serif; font-size:13px;
        color:#065F46; margin-bottom:24px; display:flex; align-items:center; gap:10px;
        font-weight:500;
        box-shadow:0 1px 2px rgba(5,150,105,.06);
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
    .stat-card:hover {
        transform:translateY(-2px);
        box-shadow:0 12px 24px -8px rgba(15,23,42,.1), 0 4px 8px -4px rgba(15,23,42,.05);
        border-color:transparent;
    }
    .stat-card:hover::before { opacity:1; }
    .stat-card.blue::before  { background:linear-gradient(90deg, #4F46E5, #818CF8); }
    .stat-card.green::before { background:linear-gradient(90deg, #059669, #34D399); }
    .stat-card.red::before   { background:linear-gradient(90deg, #DC2626, #F87171); }

    .stat-icon {
        width:52px; height:52px; border-radius:14px;
        display:flex; align-items:center; justify-content:center; flex-shrink:0;
        position:relative;
    }
    .stat-icon svg { width:22px; height:22px; position:relative; z-index:1; }
    .stat-icon.blue {
        background:linear-gradient(135deg, #EEF2FF 0%, #E0E7FF 100%); color:#4F46E5;
        box-shadow:inset 0 0 0 1px rgba(79,70,229,.1);
    }
    .stat-icon.green {
        background:linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%); color:#059669;
        box-shadow:inset 0 0 0 1px rgba(5,150,105,.1);
    }
    .stat-icon.red {
        background:linear-gradient(135deg, #FEF2F2 0%, #FEE2E2 100%); color:#DC2626;
        box-shadow:inset 0 0 0 1px rgba(220,38,38,.1);
    }
    .stat-label {
        font-family:Inter,sans-serif; font-size:11px; font-weight:600;
        color:var(--gray-500); text-transform:uppercase; letter-spacing:.8px;
        margin-bottom:6px;
    }
    .stat-value {
        font-family:Inter,sans-serif; font-size:28px; font-weight:700;
        color:var(--gray-900); line-height:1; letter-spacing:-1px;
    }

    /* ═══════════════════════════════════════════════════════════════════════
       TABLE CONTAINER
    ═══════════════════════════════════════════════════════════════════════ */
    .table-container {
        background:#FFF; border-radius:18px; padding:24px;
        border:1px solid var(--gray-200);
        box-shadow:0 1px 2px rgba(15,23,42,.04), 0 4px 12px rgba(15,23,42,.04);
    }
    .table-toolbar {
        display:flex; align-items:center; justify-content:space-between;
        margin-bottom:20px; padding-bottom:4px;
    }
    .table-label {
        font-family:Inter,sans-serif; font-size:15px; font-weight:600;
        color:var(--gray-900); letter-spacing:-0.2px;
    }
    .count-badge {
        display:inline-flex; align-items:center; justify-content:center;
        background:linear-gradient(135deg, #EEF2FF 0%, #E0E7FF 100%);
        color:#4F46E5; border-radius:8px; padding:3px 10px;
        font-size:12px; font-weight:700; margin-left:10px;
        font-family:Inter,sans-serif;
        box-shadow:inset 0 0 0 1px rgba(79,70,229,.15);
    }
    .search-wrap {
        display:flex; align-items:center; gap:8px;
        border:1px solid var(--gray-200); border-radius:11px;
        padding:0 14px; height:42px; background:var(--gray-50);
        width:260px; transition:all .2s ease;
    }
    .search-wrap:focus-within {
        border-color:#4F46E5; background:#FFF;
        box-shadow:0 0 0 4px rgba(79,70,229,.08);
    }
    .search-wrap input {
        border:none; outline:none; font-family:Inter,sans-serif;
        font-size:13px; color:var(--gray-900); width:100%;
        background:transparent; font-weight:500;
    }
    .search-wrap input::placeholder { color:var(--gray-400); font-weight:400; }

    /* ═══════════════════════════════════════════════════════════════════════
       TABLE
    ═══════════════════════════════════════════════════════════════════════ */
    .table-scroll {
        overflow-x:auto;
        border-radius:12px;
        border:1px solid var(--gray-100);
    }
    .table-scroll::-webkit-scrollbar { height:10px; }
    .table-scroll::-webkit-scrollbar-track { background:transparent; }
    .table-scroll::-webkit-scrollbar-thumb {
        background:var(--gray-200); border-radius:10px;
        border:2px solid #FFF;
    }
    .table-scroll::-webkit-scrollbar-thumb:hover { background:var(--gray-300); }

    table { width:100%; border-collapse:separate; border-spacing:0; }
    thead tr { background:var(--gray-50); }
    thead th {
        font-family:Inter,sans-serif; font-size:11px; font-weight:600;
        color:var(--gray-500); letter-spacing:.08em; text-transform:uppercase;
        padding:14px 18px; text-align:left; white-space:nowrap;
        background:var(--gray-50);
        border-bottom:1px solid var(--gray-200);
    }
    tbody tr { transition:background .15s ease; }
    tbody td {
        font-family:Inter,sans-serif; font-size:13px; color:var(--gray-800);
        padding:16px 18px; vertical-align:middle;
        background:#FFF; border-bottom:1px solid var(--gray-100);
    }
    tbody tr:last-child td { border-bottom:none; }
    tbody tr:hover td { background:#FAFBFF; }

    /* ═══════════════════════════════════════════════════════════════════════
       FREEZE PANES
    ═══════════════════════════════════════════════════════════════════════ */
    th.freeze-1, td.freeze-1 {
        position:sticky; left:0; z-index:3; min-width:160px; width:160px;
    }
    th.freeze-2, td.freeze-2 {
        position:sticky; left:160px; z-index:3; min-width:220px; width:220px;
    }
    th.freeze-3, td.freeze-3 {
        position:sticky; left:380px; z-index:3; min-width:210px; width:210px;
        box-shadow:6px 0 12px -8px rgba(15,23,42,.12);
    }
    thead th.freeze-1, thead th.freeze-2, thead th.freeze-3 { z-index:4; }

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
    .identity-cell { display:flex; align-items:center; gap:12px; }
    .avatar {
        width:38px; height:38px; border-radius:50%; color:white;
        display:flex; align-items:center; justify-content:center;
        font-size:13px; font-weight:700; flex-shrink:0; letter-spacing:.3px;
        box-shadow:0 2px 6px rgba(15,23,42,.12), inset 0 -1px 0 rgba(0,0,0,.08);
        position:relative;
    }
    .avatar::after {
        content:''; position:absolute; inset:0; border-radius:50%;
        box-shadow:inset 0 1px 0 rgba(255,255,255,.2);
    }
    .identity-name {
        font-weight:600; color:var(--gray-900); font-size:13.5px;
        margin-bottom:2px; letter-spacing:-0.1px;
    }
    .identity-email { font-size:11.5px; color:var(--gray-400); font-weight:500; }

    .contact-phone {
        font-size:13px; color:var(--gray-700); font-weight:600;
        margin-bottom:3px; letter-spacing:-0.1px;
    }
    .contact-address {
        font-size:11.5px; color:var(--gray-400); max-width:180px;
        overflow:hidden; text-overflow:ellipsis; white-space:nowrap; font-weight:500;
    }

    .ktp-mono {
        font-size:11.5px; color:var(--gray-700);
        font-family:'JetBrains Mono', 'Consolas', monospace;
        background:var(--gray-100); padding:5px 10px; border-radius:6px;
        letter-spacing:.5px; font-weight:500;
        border:1px solid var(--gray-200);
    }

    .company-pill {
        display:inline-block; font-size:11px; font-weight:700;
        padding:4px 11px; border-radius:8px;
        background:linear-gradient(135deg, #EEF2FF 0%, #E0E7FF 100%);
        color:#4F46E5; letter-spacing:.4px;
        font-family:Inter,sans-serif; white-space:nowrap;
        box-shadow:inset 0 0 0 1px rgba(79,70,229,.15);
    }

    /* ── Status Badge ── */
    .badge {
        display:inline-flex; align-items:center; gap:6px; border-radius:8px;
        padding:5px 12px; font-family:Inter,sans-serif;
        font-size:11.5px; font-weight:600; letter-spacing:.2px;
    }
    .badge.active {
        background:linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%);
        color:#047857;
        box-shadow:inset 0 0 0 1px rgba(5,150,105,.2);
    }
    .badge.inactive {
        background:linear-gradient(135deg, #FEF2F2 0%, #FEE2E2 100%);
        color:#B91C1C;
        box-shadow:inset 0 0 0 1px rgba(220,38,38,.2);
    }
    .badge-dot {
        width:6px; height:6px; border-radius:50%; display:inline-block;
        box-shadow:0 0 0 2px rgba(255,255,255,.5);
    }
    .badge.active .badge-dot   { background:#10B981; animation:pulse-green 2s infinite; }
    .badge.inactive .badge-dot { background:#EF4444; }
    @keyframes pulse-green {
        0%,100% { box-shadow:0 0 0 0 rgba(16,185,129,.4); }
        50%     { box-shadow:0 0 0 4px rgba(16,185,129,0); }
    }

    /* ── Audit Trail ── */
    .audit-cell { min-width:160px; }
    .audit-name {
        font-size:12px; font-weight:600; color:var(--gray-700);
        margin-bottom:2px; letter-spacing:-0.1px;
    }
    .audit-date {
        font-size:11px; color:var(--gray-400);
        font-family:'JetBrains Mono', 'Consolas', monospace;
        font-weight:500;
    }
    .audit-empty { color:var(--gray-300); font-size:13px; }

    /* ── Action Buttons ── */
    .action-wrap { display:flex; gap:6px; }
    .action-btn {
        height:32px; padding:0 12px; border-radius:8px;
        font-family:Inter,sans-serif; font-size:12px; font-weight:600;
        cursor:pointer; display:inline-flex; align-items:center;
        gap:5px; transition:all .15s ease; border:none;
        letter-spacing:.1px;
    }
    .btn-edit {
        background:#EEF2FF; color:#4F46E5;
        box-shadow:inset 0 0 0 1px rgba(79,70,229,.15);
    }
    .btn-edit:hover {
        background:#4F46E5; color:#FFF;
        transform:translateY(-1px);
        box-shadow:0 4px 10px rgba(79,70,229,.3);
    }
    .btn-delete {
        background:#FEF2F2; color:#DC2626;
        box-shadow:inset 0 0 0 1px rgba(220,38,38,.15);
    }
    .btn-delete:hover {
        background:#DC2626; color:#FFF;
        transform:translateY(-1px);
        box-shadow:0 4px 10px rgba(220,38,38,.3);
    }
    .action-btn svg { width:13px; height:13px; }

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
    .modal-box {
        position:relative; z-index:1; background:#FFF; border-radius:20px;
        width:100%; max-width:540px; max-height:90vh; overflow-y:auto;
        box-shadow:0 25px 60px rgba(0,0,0,.2), 0 1px 0 rgba(255,255,255,.1) inset;
        animation:slideUp .25s ease;
    }
    @keyframes slideUp {
        from { transform:translateY(20px); opacity:0; }
        to   { transform:translateY(0); opacity:1; }
    }
    .modal-header {
        padding:24px 28px 18px; border-bottom:1px solid var(--gray-100);
        display:flex; align-items:flex-start; justify-content:space-between;
        position:sticky; top:0; background:white; z-index:2;
        border-radius:20px 20px 0 0;
    }
    .modal-header h2 {
        font-family:Inter,sans-serif; font-size:18px; font-weight:700;
        color:var(--gray-900); margin:0; letter-spacing:-0.3px;
    }
    .modal-header p {
        font-family:Inter,sans-serif; font-size:13px;
        color:var(--gray-500); margin:4px 0 0 0;
    }
    .modal-close {
        width:32px; height:32px; background:var(--gray-100); border:none;
        border-radius:9px; cursor:pointer; display:flex;
        align-items:center; justify-content:center;
        color:var(--gray-500); font-size:14px; flex-shrink:0;
        transition:all .15s ease;
    }
    .modal-close:hover { background:var(--gray-200); color:var(--gray-700); }
    .modal-body { padding:24px 28px; }
    .modal-section {
        font-family:Inter,sans-serif; font-size:11px; font-weight:700;
        color:var(--gray-400); text-transform:uppercase; letter-spacing:.08em;
        margin:18px 0 12px;
    }
    .modal-section:first-child { margin-top:0; }
    .form-divider { height:1px; background:var(--gray-100); margin:18px 0; }
    .form-grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
    .form-group { margin-bottom:14px; }
    .form-group:last-child { margin-bottom:0; }
    .form-group label {
        display:block; font-family:Inter,sans-serif; font-size:12px;
        font-weight:600; color:var(--gray-700); margin-bottom:6px;
        text-transform:uppercase; letter-spacing:.05em;
    }
    .form-group input, .form-group select {
        width:100%; border:1px solid var(--gray-200); border-radius:10px;
        padding:11px 14px; font-family:Inter,sans-serif; font-size:13.5px;
        color:var(--gray-900); outline:none; box-sizing:border-box;
        background:var(--gray-50); transition:all .15s ease;
        font-weight:500;
    }
    .form-group input:focus, .form-group select:focus {
        border-color:#4F46E5; background:#FFF;
        box-shadow:0 0 0 4px rgba(79,70,229,.08);
    }
    .toggle-row {
        display:flex; align-items:center; justify-content:space-between;
        background:linear-gradient(135deg, var(--gray-50) 0%, #FFF 100%);
        border:1px solid var(--gray-200);
        border-radius:12px; padding:14px 16px; margin-top:6px;
    }
    .toggle-label-wrap .tl-title {
        font-family:Inter,sans-serif; font-size:13.5px; font-weight:600;
        color:var(--gray-900); letter-spacing:-0.1px;
    }
    .toggle-label-wrap .tl-sub {
        font-family:Inter,sans-serif; font-size:11.5px;
        color:var(--gray-400); margin-top:2px;
    }
    .toggle-wrap { display:flex; align-items:center; gap:10px; }
    .toggle-slider {
        width:42px; height:24px; border-radius:12px; position:relative;
        cursor:pointer; transition:background .25s ease;
    }
    .toggle-thumb {
        width:18px; height:18px; background:white; border-radius:50%;
        position:absolute; top:3px; transition:left .25s ease;
        box-shadow:0 2px 4px rgba(0,0,0,.2);
    }
    .toggle-text { font-family:Inter,sans-serif; font-size:12px; font-weight:600; }

    .modal-footer {
        padding:18px 28px; border-top:1px solid var(--gray-100);
        display:flex; gap:10px; justify-content:flex-end;
        position:sticky; bottom:0; background:white;
        border-radius:0 0 20px 20px;
    }
    .btn-cancel {
        height:40px; padding:0 20px; background:white;
        border:1px solid var(--gray-200); border-radius:10px;
        font-family:Inter,sans-serif; font-size:13px; font-weight:500;
        cursor:pointer; color:var(--gray-700); transition:all .15s ease;
    }
    .btn-cancel:hover { background:var(--gray-50); border-color:var(--gray-300); }
    .btn-save {
        height:40px; padding:0 22px;
        background:linear-gradient(135deg, #4F46E5 0%, #6366F1 100%);
        border:none; border-radius:10px;
        font-family:Inter,sans-serif; font-size:13px; font-weight:600;
        color:#FFF; cursor:pointer;
        box-shadow:0 4px 12px rgba(79,70,229,.3), 0 1px 0 rgba(255,255,255,.15) inset;
        transition:all .2s ease;
    }
    .btn-save:hover {
        transform:translateY(-1px);
        box-shadow:0 6px 16px rgba(79,70,229,.4), 0 1px 0 rgba(255,255,255,.15) inset;
    }

    /* ═══════════════════════════════════════════════════════════════════════
       CONFIRM MODAL
    ═══════════════════════════════════════════════════════════════════════ */
    .confirm-box {
        position:relative; z-index:1; background:#FFF; border-radius:20px;
        width:100%; max-width:400px;
        box-shadow:0 25px 60px rgba(0,0,0,.2);
        overflow:hidden; animation:slideUp .25s ease;
    }
    .confirm-accent {
        height:4px; width:100%;
        background:linear-gradient(90deg, #EF4444 0%, #F87171 100%);
    }
    .confirm-body { padding:28px 28px 22px; }
    .confirm-icon-wrap {
        width:56px; height:56px; border-radius:16px;
        background:linear-gradient(135deg, #FEF2F2 0%, #FEE2E2 100%);
        display:flex; align-items:center; justify-content:center;
        margin-bottom:18px;
        box-shadow:inset 0 0 0 1px rgba(220,38,38,.1);
    }
    .confirm-icon-wrap svg { width:26px; height:26px; color:#DC2626; }
    .confirm-subtitle {
        font-family:Inter,sans-serif; font-size:11px; font-weight:700;
        color:var(--gray-400); text-transform:uppercase; letter-spacing:.08em;
        margin-bottom:6px;
    }
    .confirm-title {
        font-family:Inter,sans-serif; font-size:18px; font-weight:700;
        color:var(--gray-900); margin-bottom:10px; letter-spacing:-0.3px;
    }
    .confirm-desc {
        font-family:Inter,sans-serif; font-size:13.5px;
        color:var(--gray-500); line-height:1.6;
    }
    .confirm-footer { padding:16px 28px 24px; display:flex; gap:10px; }
    .btn-confirm-cancel {
        flex:1; height:42px; border:1.5px solid var(--gray-200);
        border-radius:11px; font-family:Inter,sans-serif;
        font-size:13px; font-weight:600; color:var(--gray-600);
        background:white; cursor:pointer; transition:all .15s ease;
    }
    .btn-confirm-cancel:hover { background:var(--gray-50); border-color:var(--gray-300); }
    .btn-confirm-delete {
        flex:1; height:42px; border:none; border-radius:11px;
        font-family:Inter,sans-serif; font-size:13px; font-weight:700;
        color:white;
        background:linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
        cursor:pointer;
        box-shadow:0 4px 12px rgba(239,68,68,.3), 0 1px 0 rgba(255,255,255,.15) inset;
        transition:all .2s ease;
    }
    .btn-confirm-delete:hover {
        transform:translateY(-1px);
        box-shadow:0 6px 16px rgba(239,68,68,.4), 0 1px 0 rgba(255,255,255,.15) inset;
    }
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
                <td>
                    <span class="ktp-mono">{{ $user->id_card_number ?: '-' }}</span>
                </td>

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

{{-- ═══════════════════════════════════════════════════════════════════════
     MODAL ADD
═══════════════════════════════════════════════════════════════════════ --}}
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
                        <div class="toggle-slider" id="addToggleSlider" style="background:#4F46E5;" onclick="toggleCheck('addToggleStatus','addToggleLabel','addToggleSlider')">
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

{{-- ═══════════════════════════════════════════════════════════════════════
     MODAL EDIT
═══════════════════════════════════════════════════════════════════════ --}}
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

{{-- ═══════════════════════════════════════════════════════════════════════
     MODAL DELETE
═══════════════════════════════════════════════════════════════════════ --}}
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
                User <strong id="deleteUserName" style="color:var(--gray-900);"></strong> akan dihapus secara permanen dan tidak dapat dipulihkan.
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
    function toggleCheck(inputId, labelId, sliderId) {
        const input  = document.getElementById(inputId);
        const label  = document.getElementById(labelId);
        const slider = document.getElementById(sliderId);
        const thumb  = slider.querySelector('.toggle-thumb');
        input.checked = !input.checked;
        if (input.checked) {
            slider.style.background = '#4F46E5';
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
            slider.style.background = '#4F46E5'; thumb.style.left = '21px';
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
                if (badge) {
                    if (type === 'date') {
                        badge.textContent = sortDir === 'asc' ? 'Lama→Baru' : 'Baru→Lama';
                    } else {
                        badge.textContent = sortDir === 'asc' ? 'A-Z' : 'Z-A';
                    }
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

    function sortTable(col, dir, type) {
        const tbody = document.querySelector('#userTable tbody');
        const rows  = Array.from(tbody.querySelectorAll('tr')).filter(r => r.cells.length > 1);
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
        rows.forEach(r => tbody.appendChild(r));
    }
</script>

@endsection