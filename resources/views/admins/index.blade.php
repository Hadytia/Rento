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
    .page-header { display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:28px; }
    .page-title h1 {
        font-family:Inter,sans-serif; font-size:26px; font-weight:700;
        margin:0 0 6px 0; letter-spacing:-0.5px;
        background:linear-gradient(135deg, #111827 0%, #4F46E5 100%);
        -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent;
    }
    .page-title p {
        font-family:Inter,sans-serif; font-size:14px; color:var(--gray-500);
        margin:0; letter-spacing:-0.1px;
    }
    .btn-add {
        height:44px; padding:0 20px;
        background:linear-gradient(135deg, #4F46E5 0%, #6366F1 100%);
        color:#FFF; border:none; border-radius:11px;
        font-family:Inter,sans-serif; font-size:13px; font-weight:600;
        cursor:pointer; display:flex; align-items:center; gap:8px; white-space:nowrap;
        box-shadow:0 4px 12px rgba(79,70,229,.3), 0 1px 0 rgba(255,255,255,.15) inset;
        transition:all .2s ease; letter-spacing:.2px;
    }
    .btn-add:hover {
        transform:translateY(-1px);
        box-shadow:0 6px 16px rgba(79,70,229,.4), 0 1px 0 rgba(255,255,255,.15) inset;
    }
    .btn-add svg { width:14px; height:14px; }

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
       PENDING PANEL
    ═══════════════════════════════════════════════════════════════════════ */
    .pending-panel {
        background:#FFF; border-radius:16px; padding:22px;
        border:1px solid #FED7AA;
        box-shadow:0 1px 2px rgba(245,158,11,.06), 0 4px 12px rgba(245,158,11,.04);
        margin-bottom:24px;
    }
    .pending-header { display:flex; align-items:center; gap:10px; margin-bottom:6px; }
    .pending-dot {
        width:10px; height:10px; border-radius:50%; background:#F59E0B;
        flex-shrink:0; animation:pulse-warning 2s infinite;
    }
    @keyframes pulse-warning {
        0%,100% { box-shadow:0 0 0 0 rgba(245,158,11,.4); }
        50%     { box-shadow:0 0 0 6px rgba(245,158,11,0); }
    }
    .pending-header span {
        font-family:Inter,sans-serif; font-size:15px; font-weight:700;
        color:#B45309; letter-spacing:-0.2px;
    }
    .pending-count {
        background:linear-gradient(135deg, #F59E0B 0%, #FBBF24 100%);
        color:white; border-radius:8px; padding:2px 9px;
        font-size:11px; font-weight:700; font-family:Inter,sans-serif;
        box-shadow:0 2px 4px rgba(245,158,11,.2);
    }
    .pending-sub {
        font-family:Inter,sans-serif; font-size:12px; color:var(--gray-400);
        margin-bottom:16px; padding-left:20px; letter-spacing:-0.1px;
    }
    .pending-cards { display:flex; flex-wrap:wrap; gap:12px; }
    .pending-card {
        background:linear-gradient(135deg, #FFFBEB 0%, #FEF9C3 100%);
        border:1px solid #FDE68A; border-radius:14px;
        padding:16px 18px; flex:1; min-width:280px; max-width:380px;
        display:flex; align-items:center; justify-content:space-between; gap:14px;
        box-shadow:0 1px 3px rgba(245,158,11,.08);
    }
    .pending-info { display:flex; align-items:center; gap:12px; }
    .pending-actions { display:flex; gap:8px; flex-shrink:0; }
    .btn-acc {
        height:36px; padding:0 15px;
        background:linear-gradient(135deg, #22C55E 0%, #16A34A 100%);
        border:none; border-radius:9px;
        font-family:Inter,sans-serif; font-size:12px; font-weight:700; color:white;
        cursor:pointer; display:flex; align-items:center; gap:5px; transition:all .2s ease;
        box-shadow:0 2px 8px rgba(34,197,94,.25), 0 1px 0 rgba(255,255,255,.12) inset;
    }
    .btn-acc:hover {
        transform:translateY(-1px);
        box-shadow:0 4px 12px rgba(34,197,94,.35), 0 1px 0 rgba(255,255,255,.12) inset;
    }
    .btn-reject {
        height:36px; padding:0 15px;
        background:#FFF; border:1px solid #FECACA;
        border-radius:9px; font-family:Inter,sans-serif; font-size:12px; font-weight:700;
        color:#DC2626; cursor:pointer; display:flex; align-items:center; gap:5px;
        transition:all .15s ease; box-shadow:inset 0 0 0 1px rgba(220,38,38,.1);
    }
    .btn-reject:hover {
        background:#FEF2F2; transform:translateY(-1px);
        box-shadow:0 2px 6px rgba(220,38,38,.15), inset 0 0 0 1px #DC2626;
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
        margin-bottom:18px; padding-bottom:4px;
    }
    .table-label {
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
        padding:16px; vertical-align:middle; background:#FFF; border-bottom:1px solid var(--gray-100);
    }
    tbody tr:last-child td { border-bottom:none; }
    tbody tr:hover td { background:#FAFBFF; }

    /* ── Freeze Panes (Aksi 180px + Identitas Admin 280px) ── */
    #adminTable thead th:nth-child(1),
    #adminTable thead th:nth-child(2),
    #adminTable tbody td:nth-child(1),
    #adminTable tbody td:nth-child(2) {
        position:sticky; z-index:2;
    }
    #adminTable thead th:nth-child(1),
    #adminTable tbody td:nth-child(1) {
        left:0; width:180px; min-width:180px; max-width:180px;
    }
    #adminTable thead th:nth-child(2),
    #adminTable tbody td:nth-child(2) {
        left:180px; width:280px; min-width:280px;
        box-shadow:6px 0 12px -8px rgba(15,23,42,.12);
    }
    #adminTable thead th:nth-child(1),
    #adminTable thead th:nth-child(2) { z-index:3; }

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
    .av-wrap { display:flex; align-items:center; gap:12px; }
    .av {
        width:40px; height:40px; border-radius:50%;
        display:flex; align-items:center; justify-content:center;
        font-size:13px; font-weight:700; color:white; flex-shrink:0;
        box-shadow:0 2px 6px rgba(15,23,42,.12), inset 0 -1px 0 rgba(0,0,0,.08);
        position:relative;
    }
    .av::after {
        content:''; position:absolute; inset:0; border-radius:50%;
        box-shadow:inset 0 1px 0 rgba(255,255,255,.2);
    }
    .av-name  { font-weight:700; color:var(--gray-900); font-size:13px; letter-spacing:-0.1px; }
    .av-email { font-size:11px; color:var(--gray-400); margin-top:2px; font-weight:500; }

    .auth-username {
        font-size:13px; color:var(--gray-900); font-weight:600;
        font-family:'JetBrains Mono','Consolas',monospace; letter-spacing:.2px;
    }
    .auth-date { font-size:11px; color:var(--gray-400); margin-top:3px; }

    .role-name { font-weight:700; color:var(--gray-900); font-size:13px; margin-bottom:5px; letter-spacing:-0.1px; }
    .role-badge {
        display:inline-flex; align-items:center; border-radius:7px;
        padding:4px 10px; font-size:11px; font-weight:700; font-family:Inter,sans-serif;
        letter-spacing:.2px;
    }
    .role-superadmin {
        background:linear-gradient(135deg, #F3E8FF 0%, #E9D5FF 100%);
        color:#7C3AED;
        box-shadow:inset 0 0 0 1px rgba(124,58,237,.2);
    }
    .role-admin {
        background:linear-gradient(135deg, #EEF2FF 0%, #E0E7FF 100%);
        color:#4F46E5;
        box-shadow:inset 0 0 0 1px rgba(79,70,229,.2);
    }
    .role-staff {
        background:linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%);
        color:#059669;
        box-shadow:inset 0 0 0 1px rgba(5,150,105,.2);
    }
    .role-dosen {
        background:linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);
        color:#D97706;
        box-shadow:inset 0 0 0 1px rgba(217,119,6,.2);
    }

    .access-full {
        display:inline-flex; align-items:center; gap:6px;
        background:linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%);
        color:#047857; border-radius:8px; padding:5px 12px;
        font-size:11.5px; font-weight:600; font-family:Inter,sans-serif;
        box-shadow:inset 0 0 0 1px rgba(5,150,105,.2);
    }
    .access-full .dot { width:6px; height:6px; border-radius:50%; background:#10B981; }

    .btn-toggle-edit {
        display:inline-flex; align-items:center; gap:6px; border-radius:8px;
        padding:5px 12px; font-size:11.5px; font-weight:600; font-family:Inter,sans-serif;
        border:none; cursor:pointer; transition:all .15s ease;
    }
    .toggle-given {
        background:linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%);
        color:#047857;
        box-shadow:inset 0 0 0 1px rgba(5,150,105,.2);
    }
    .toggle-given:hover {
        background:#D1FAE5;
        transform:translateY(-1px);
        box-shadow:0 2px 6px rgba(5,150,105,.15), inset 0 0 0 1px #059669;
    }
    .toggle-viewonly {
        background:linear-gradient(135deg, #F1F5F9 0%, #E2E8F0 100%);
        color:#64748B;
        box-shadow:inset 0 0 0 1px rgba(100,116,139,.15);
    }
    .toggle-viewonly:hover {
        background:#E2E8F0;
        transform:translateY(-1px);
        box-shadow:0 2px 6px rgba(100,116,139,.12), inset 0 0 0 1px #94A3B8;
    }
    .toggle-dot { width:6px; height:6px; border-radius:50%; }

    .status-badge {
        display:inline-flex; align-items:center; gap:6px; border-radius:8px;
        padding:5px 12px; font-size:11.5px; font-weight:600; font-family:Inter,sans-serif;
        white-space:nowrap; letter-spacing:.2px;
    }
    .status-badge .dot { width:6px; height:6px; border-radius:50%; }
    .badge-aktif {
        background:linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%);
        color:#047857;
        box-shadow:inset 0 0 0 1px rgba(5,150,105,.2);
    }
    .badge-aktif .dot { background:#10B981; }
    .badge-nonaktif {
        background:linear-gradient(135deg, #FEF2F2 0%, #FEE2E2 100%);
        color:#B91C1C;
        box-shadow:inset 0 0 0 1px rgba(220,38,38,.2);
    }
    .badge-nonaktif .dot { background:#EF4444; }

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
    .stat-icon { width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .stat-icon svg { width:22px; height:22px; }
    .stat-icon.blue  { background:linear-gradient(135deg, #EEF2FF 0%, #E0E7FF 100%); color:#4F46E5; box-shadow:inset 0 0 0 1px rgba(79,70,229,.1); }
    .stat-icon.green { background:linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%); color:#059669; box-shadow:inset 0 0 0 1px rgba(5,150,105,.1); }
    .stat-icon.red   { background:linear-gradient(135deg, #FEF2F2 0%, #FEE2E2 100%); color:#DC2626; box-shadow:inset 0 0 0 1px rgba(220,38,38,.1); }
    .stat-label { font-family:Inter,sans-serif; font-size:11px; font-weight:600; color:var(--gray-500); text-transform:uppercase; letter-spacing:.8px; margin-bottom:6px; }
    .stat-value { font-family:Inter,sans-serif; font-size:28px; font-weight:700; color:var(--gray-900); line-height:1; letter-spacing:-1px; }

    /* ═══════════════════════════════════════════════════════════════════════
    ACTION BUTTONS
    ═══════════════════════════════════════════════════════════════════════ */
    .action-btn { height:32px; padding:0 12px; border-radius:8px; font-family:Inter,sans-serif; font-size:12px; font-weight:600; cursor:pointer; display:inline-flex; align-items:center; gap:5px; transition:all .15s ease; border:none; letter-spacing:.1px; }
    .btn-edit-row { background:#EEF2FF; color:#4F46E5; box-shadow:inset 0 0 0 1px rgba(79,70,229,.15); }
    .btn-edit-row:hover { background:#4F46E5; color:#FFF; transform:translateY(-1px); box-shadow:0 4px 10px rgba(79,70,229,.3); }
    .btn-delete-row { background:#FEF2F2; color:#DC2626; box-shadow:inset 0 0 0 1px rgba(220,38,38,.15); }
    .btn-delete-row:hover { background:#DC2626; color:#FFF; transform:translateY(-1px); box-shadow:0 4px 10px rgba(220,38,38,.3); }
    .action-btn svg { width:13px; height:13px; }

    /* ═══════════════════════════════════════════════════════════════════════
       MODALS - MODERN DESIGN SYSTEM
    ═══════════════════════════════════════════════════════════════════════ */
    .modal-overlay {
        display:none; position:fixed; inset:0; z-index:999;
        align-items:center; justify-content:center; padding:20px;
    }
    .modal-overlay.show { display:flex; animation:fadeIn .2s ease; }
    @keyframes fadeIn { from {opacity:0;} to {opacity:1;} }
    
    .modal-backdrop {
        position:fixed; inset:0;
        background:rgba(15,23,42,.55); backdrop-filter:blur(6px);
    }

    .form-modal {
        position:relative; z-index:1; background:#FFF; border-radius:18px;
        width:100%; max-width:540px; max-height:90vh; overflow:hidden;
        box-shadow:0 25px 60px rgba(0,0,0,.25);
        animation:slideUp .28s cubic-bezier(0.22,1,0.36,1);
    }
    @keyframes slideUp {
        from { transform:translateY(24px); opacity:0; }
        to   { transform:translateY(0); opacity:1; }
    }

    /* ── Modal Header dengan Gradient Biru ── */
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
    }
    .modal-header::after {
        content:''; position:absolute; bottom:-60px; left:-60px;
        width:200px; height:200px; border-radius:50%;
        background:rgba(255,255,255,0.04);
    }
    .modal-header-content {
        position:relative; z-index:1;
        display:flex; align-items:flex-start; justify-content:space-between; gap:16px;
    }
    .modal-icon-box {
        width:52px; height:52px; border-radius:12px;
        background:rgba(255,255,255,0.15); backdrop-filter:blur(10px);
        border:1px solid rgba(255,255,255,0.2);
        display:flex; align-items:center; justify-content:center;
        flex-shrink:0;
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
    .modal-close-btn:hover {
        background:rgba(255,255,255,0.25);
        transform:rotate(90deg);
    }
    .modal-close-btn svg { width:18px; height:18px; color:#fff; }

    /* ── Modal Body ── */
    .modal-body {
        padding:28px 32px;
        max-height:calc(90vh - 200px);
        overflow-y:auto;
    }
    .modal-body::-webkit-scrollbar { width:8px; }
    .modal-body::-webkit-scrollbar-track { background:transparent; }
    .modal-body::-webkit-scrollbar-thumb {
        background:#e2e8f0; border-radius:10px;
    }

    /* ── Section Labels dengan Icon ── */
    .form-section {
        display:flex; align-items:center; gap:10px;
        margin:24px 0 16px;
        font-family:'Plus Jakarta Sans',sans-serif;
        font-size:0.65rem; font-weight:700;
        color:#94a3b8; text-transform:uppercase; letter-spacing:.08em;
    }
    .form-section:first-child { margin-top:0; }
    .form-section svg {
        width:14px; height:14px; flex-shrink:0;
    }
    .form-section::after {
        content:''; flex:1; height:1px; background:#f1f5f9;
    }

    /* ── Form Groups ── */
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
    .form-group label .required { color:#ef4444; margin-left:2px; }

    /* ── Input Fields dengan Icon ── */
    .fg-ico {
        position:relative;
    }
    .fg-ico .input-icon {
        position:absolute; left:12px; top:50%;
        transform:translateY(-50%);
        width:16px; height:16px; color:#94a3b8;
        pointer-events:none;
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
    .fg-ico input::placeholder {
        color:#cbd5e1; font-weight:400;
    }

    /* ── Select Custom Arrow ── */
    .fg-ico select {
        appearance:none;
        background-image:url("data:image/svg+xml,%3Csvg width='12' height='8' viewBox='0 0 12 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1.5L6 6.5L11 1.5' stroke='%2394a3b8' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
        background-repeat:no-repeat;
        background-position:right 14px center;
        padding-right:38px;
    }

    /* ── Toggle Password Section ── */
    .toggle-password-section {
        display:flex; justify-content:space-between; align-items:center;
        margin:24px 0 0;
    }
    .toggle-password-section .form-section {
        margin:0; flex:1;
    }
    .pw-reveal {
        font-family:'Plus Jakarta Sans',sans-serif;
        font-size:0.75rem; font-weight:700;
        color:#3b82f6; cursor:pointer;
        padding:6px 12px; border-radius:6px;
        transition:all .15s ease;
    }
    .pw-reveal:hover {
        background:#eff6ff;
    }

    /* ── Toggle Slider Modern ── */
    .toggle-wrap {
        display:flex; align-items:center; justify-content:space-between;
        padding:12px 0;
    }
    .toggle-label {
        font-family:'Plus Jakarta Sans',sans-serif;
        font-size:0.7rem; font-weight:700;
        color:#64748b; text-transform:uppercase; letter-spacing:.06em;
    }
    .toggle-control { display:flex; align-items:center; gap:10px; }
    .toggle-slider {
        width:48px; height:26px; background:#e2e8f0; border-radius:20px;
        position:relative; cursor:pointer; transition:all .2s ease;
        box-shadow:inset 0 2px 4px rgba(0,0,0,0.06);
    }
    .toggle-slider.on {
        background:linear-gradient(135deg, #2563eb, #3b82f6);
    }
    .toggle-thumb {
        width:22px; height:22px; background:white; border-radius:50%;
        position:absolute; top:2px; left:2px; transition:all .2s ease;
        box-shadow:0 2px 4px rgba(0,0,0,0.2);
    }
    .toggle-slider.on .toggle-thumb { left:24px; }
    .toggle-text {
        font-family:'Plus Jakarta Sans',sans-serif;
        font-size:0.8rem; font-weight:600; color:#64748b;
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
    .btn-cancel:hover {
        background:#e2e8f0; border-color:#cbd5e1;
    }
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
    .btn-save:hover {
        transform:translateY(-1px);
        box-shadow:0 6px 20px rgba(37,99,235,.45);
    }

    /* ═══════════════════════════════════════════════════════════════════════
       CONFIRM MODAL
    ═══════════════════════════════════════════════════════════════════════ */
    .confirm-box {
        position:relative; z-index:1; background:#FFF; border-radius:18px;
        width:100%; max-width:420px;
        box-shadow:0 25px 60px rgba(0,0,0,.25);
        overflow:hidden; animation:slideUp .28s cubic-bezier(0.22,1,0.36,1);
    }
    .confirm-accent { height:4px; width:100%; }
    .confirm-body { padding:32px; }
    .confirm-icon-wrap {
        width:56px; height:56px; border-radius:14px;
        display:flex; align-items:center; justify-content:center; margin-bottom:18px;
    }
    .confirm-icon-wrap svg { width:24px; height:24px; }
    .confirm-subtitle {
        font-family:'Plus Jakarta Sans',sans-serif; font-size:0.65rem; font-weight:700;
        color:#94a3b8; text-transform:uppercase; letter-spacing:.08em;
        margin-bottom:6px;
    }
    .confirm-title {
        font-family:'Plus Jakarta Sans',sans-serif; font-size:1.25rem; font-weight:800;
        color:#1e293b; margin-bottom:12px; letter-spacing:-0.3px;
    }
    .confirm-desc {
        font-family:'Plus Jakarta Sans',sans-serif; font-size:0.9rem;
        color:#64748b; line-height:1.6; margin:0; font-weight:500;
    }
    .confirm-footer { padding:0 32px 32px; display:flex; gap:12px; }
    .btn-cf-cancel {
        flex:1; height:42px;
        background:#f1f5f9; border:1.5px solid #e2e8f0;
        border-radius:10px;
        font-family:'Plus Jakarta Sans',sans-serif;
        font-size:0.875rem; font-weight:700;
        color:#64748b; cursor:pointer;
        transition:all .2s ease;
    }
    .btn-cf-cancel:hover {
        background:#e2e8f0; border-color:#cbd5e1;
    }
    .btn-cf-ok {
        flex:1; height:42px; border:none; border-radius:10px;
        font-family:'Plus Jakarta Sans',sans-serif;
        font-size:0.875rem; font-weight:700;
        color:#fff; cursor:pointer;
        transition:all .2s ease;
    }

    /* ═══════════════════════════════════════════════════════════════════════
    MODAL DELETE ADMIN — sama dengan modal delete user
    ═══════════════════════════════════════════════════════════════════════ */
    .confirm-box {
        position:relative; z-index:1; background:#FFF; border-radius:18px;
        width:100%; max-width:460px; overflow:hidden;
        box-shadow:0 25px 60px rgba(0,0,0,.25);
        animation:slideUp .28s cubic-bezier(0.22,1,0.36,1);
    }
    .confirm-header {
        position:relative;
        background:linear-gradient(135deg, #7f1d1d 0%, #dc2626 60%, #ef4444 100%);
        padding:28px 32px; overflow:hidden;
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
        background:#f1f5f9; border:1.5px solid #e2e8f0; border-radius:10px;
        font-family:'Plus Jakarta Sans',sans-serif; font-size:0.875rem; font-weight:700;
        color:#64748b; cursor:pointer; transition:all .2s ease;
    }
    .btn-confirm-cancel:hover { background:#e2e8f0; border-color:#cbd5e1; }
    .btn-confirm-delete {
        flex:1; height:42px; border:none; border-radius:10px;
        background:linear-gradient(135deg, #dc2626, #ef4444);
        font-family:'Plus Jakarta Sans',sans-serif; font-size:0.875rem; font-weight:700;
        color:#fff; cursor:pointer;
        box-shadow:0 4px 14px rgba(220,38,38,.35); transition:all .2s ease;
    }
    .btn-confirm-delete:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(220,38,38,.45); }
</style>

{{-- Header --}}
<div class="page-header">
    <div class="page-title">
        <h1>Kelola Akun Admin</h1>
        <p>Kelola akun administrator, atur hak akses, dan lacak aktivitas audit.</p>
    </div>
    <button class="btn-add" onclick="openAddModal()">
        <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Tambah Data Admin
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

@php
    $totalAdmins    = $admins->count();
    $activeAdmins   = $admins->where('status', 1)->count();
    $inactiveAdmins = $admins->where('status', 0)->count();
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
            <div class="stat-label">Total Admin</div>
            <div class="stat-value">{{ $totalAdmins }}</div>
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
            <div class="stat-value" style="color:#059669;">{{ $activeAdmins }}</div>
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
            <div class="stat-value" style="color:#DC2626;">{{ $inactiveAdmins }}</div>
        </div>
    </div>
</div>

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
        @php
            $avGradients = [
                'linear-gradient(135deg, #4F46E5 0%, #6366F1 100%)',
                'linear-gradient(135deg, #7C3AED 0%, #A855F7 100%)',
                'linear-gradient(135deg, #059669 0%, #10B981 100%)',
                'linear-gradient(135deg, #F59E0B 0%, #FBBF24 100%)',
                'linear-gradient(135deg, #DC2626 0%, #EF4444 100%)',
            ];
        @endphp
        <div class="pending-card">
            <div class="pending-info">
                <div class="av" style="background:{{ $avGradients[$dosen->id % 5] }}; width:38px; height:38px; font-size:12px;">
                    {{ strtoupper(substr($dosen->name,0,2)) }}
                </div>
                <div>
                    <div style="font-family:Inter,sans-serif;font-size:13px;font-weight:700;color:var(--gray-900);letter-spacing:-0.1px;">{{ $dosen->name }}</div>
                    <div style="font-family:Inter,sans-serif;font-size:11px;color:var(--gray-400);font-weight:500;">{{ $dosen->email }}</div>
                    <div style="font-family:'JetBrains Mono','Consolas',monospace;font-size:11px;color:var(--gray-400);margin-top:2px;font-weight:500;">
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
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="text" placeholder="Cari admin..." id="searchInput" onkeyup="filterTable()">
        </div>
    </div>

    <div class="table-scroll">
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
            @php
                $avGradients = [
                    'linear-gradient(135deg, #4F46E5 0%, #6366F1 100%)',
                    'linear-gradient(135deg, #7C3AED 0%, #A855F7 100%)',
                    'linear-gradient(135deg, #059669 0%, #10B981 100%)',
                    'linear-gradient(135deg, #F59E0B 0%, #FBBF24 100%)',
                    'linear-gradient(135deg, #DC2626 0%, #EF4444 100%)',
                ];
            @endphp
            @forelse ($admins as $admin)
            @php
                $avColor = $avGradients[$admin->id % 5];
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
                            <span class="toggle-dot" style="background:{{ $admin->can_edit ? '#10B981' : '#94A3B8' }}"></span>
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

                {{-- Created By --}}
                <td>
                    @if($admin->created_by)
                        <div class="audit-name">{{ $admin->created_by }}</div>
                    @else
                        <span class="audit-empty">-</span>
                    @endif
                </td>

                {{-- Created Date --}}
                <td>
                    @if($admin->created_date)
                        <div class="audit-date">{{ \Carbon\Carbon::parse($admin->created_date)->format('d M Y, H:i') }}</div>
                    @else
                        <span class="audit-empty">-</span>
                    @endif
                </td>

                {{-- Last Updated By --}}
                <td>
                    @if($admin->last_updated_by)
                        <div class="audit-name">{{ $admin->last_updated_by }}</div>
                    @else
                        <span class="audit-empty">-</span>
                    @endif
                </td>

                {{-- Last Updated Date --}}
                <td>
                    @if($admin->last_updated_date)
                        <div class="audit-date">{{ \Carbon\Carbon::parse($admin->last_updated_date)->format('d M Y, H:i') }}</div>
                    @else
                        <span class="audit-empty">-</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="10" class="empty-row">Belum ada data admin.</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════════
     MODAL DELETE ADMIN
══════════════════════════════════════════════════════════════════════════ --}}
<div class="modal-overlay" id="modalConfirm">
    <div class="modal-backdrop" onclick="closeConfirmModal()"></div>
    <div class="confirm-box">
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
                    <h2 id="confirmTitle">Hapus Admin</h2>
                    <p id="confirmSubtitle">Tindakan ini tidak dapat dibatalkan</p>
                </div>
            </div>
            <button class="confirm-close-btn" type="button" onclick="closeConfirmModal()">
                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <div class="confirm-body">
            <p class="confirm-desc" id="confirmDesc"></p>
        </div>
        <div class="confirm-footer">
            <button class="btn-confirm-cancel" onclick="closeConfirmModal()">Batal</button>
            <button class="btn-confirm-delete" id="confirmBtn" onclick="executeConfirm()">Ya, Hapus</button>
        </div>
    </div>
</div>

<form id="formApprove" method="POST" action="" style="display:none">@csrf @method('PATCH')</form>
<form id="formReject"  method="POST" action="" style="display:none">@csrf @method('PATCH')</form>
<form id="formToggle"  method="POST" action="" style="display:none">@csrf @method('PATCH')</form>
<form id="formDelete"  method="POST" action="" style="display:none">@csrf @method('DELETE')</form>

{{-- ══════════════════════════════════════════════════════════════════════════
     MODAL TAMBAH ADMIN
══════════════════════════════════════════════════════════════════════════ --}}
<div class="modal-overlay" id="modalAdd">
    <div class="modal-backdrop" onclick="closeModal('modalAdd')"></div>
    <div class="form-modal">
        {{-- Header dengan Gradient Biru --}}
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
                    <h2>Tambah Admin Baru</h2>
                    <p>Isi formulir untuk menambahkan akun administrator</p>
                </div>
            </div>
            <button class="modal-close-btn" type="button" onclick="closeModal('modalAdd')">
                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('admins.store') }}">
            @csrf
            <div class="modal-body">
                {{-- Section: Identitas --}}
                <div class="form-section">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    Identitas Admin
                </div>
                <div class="form-group">
                    <label>Nama Lengkap <span class="required">*</span></label>
                    <div class="fg-ico">
                        <svg class="input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        <input type="text" name="name" placeholder="Masukkan nama lengkap..." required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Alamat Email <span class="required">*</span></label>
                    <div class="fg-ico">
                        <svg class="input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                        <input type="email" name="email" placeholder="nama@email.com" required>
                    </div>
                </div>

                {{-- Section: Autentikasi --}}
                <div class="form-section">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    Akun & Autentikasi
                </div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label>Password <span class="required">*</span></label>
                        <div class="fg-ico">
                            <svg class="input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                            <input type="password" name="password" placeholder="Minimal 8 karakter" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Konfirmasi Password <span class="required">*</span></label>
                        <div class="fg-ico">
                            <svg class="input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            <input type="password" name="password_confirmation" placeholder="Ketik ulang password">
                        </div>
                    </div>
                </div>

                {{-- Section: Role & Status --}}
                <div class="form-section">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    Role & Status
                </div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label>Role <span class="required">*</span></label>
                        <div class="fg-ico">
                            <svg class="input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                <circle cx="8.5" cy="7" r="4"/>
                                <line x1="20" y1="8" x2="20" y2="14"/>
                                <line x1="23" y1="11" x2="17" y2="11"/>
                            </svg>
                            <select name="role" required>
                                <option value="" disabled selected>Pilih Role</option>
                                <option value="superadmin">Super Admin</option>
                                <option value="admin">Admin</option>
                                <option value="staff">Staff</option>
                                <option value="dosen">Dosen</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <div class="fg-ico">
                            <svg class="input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                            </svg>
                            <select name="status">
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal('modalAdd')">Batal</button>
                <button type="submit" class="btn-save">Simpan Admin</button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════════
     MODAL EDIT ADMIN
══════════════════════════════════════════════════════════════════════════ --}}
<div class="modal-overlay" id="modalEdit">
    <div class="modal-backdrop" onclick="closeModal('modalEdit')"></div>
    <div class="form-modal">
        {{-- Header dengan Gradient Biru --}}
        <div class="modal-header">
            <div class="modal-header-content">
                <div class="modal-icon-box">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                </div>
                <div class="modal-title-wrap">
                    <h2>Edit Data Admin</h2>
                    <p>Perbarui informasi akun administrator</p>
                </div>
            </div>
            <button class="modal-close-btn" type="button" onclick="closeModal('modalEdit')">
                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        {{-- Form --}}
        <form method="POST" id="editForm" action="">
            @csrf @method('PUT')
            <div class="modal-body">
                {{-- Section: Identitas --}}
                <div class="form-section">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    Identitas Admin
                </div>
                <div class="form-group">
                    <label>Nama Lengkap <span class="required">*</span></label>
                    <div class="fg-ico">
                        <svg class="input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        <input type="text" name="name" id="editName" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Alamat Email <span class="required">*</span></label>
                    <div class="fg-ico">
                        <svg class="input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                        <input type="email" name="email" id="editEmail" required>
                    </div>
                </div>

                {{-- Section: Password (Toggle) --}}
                <div class="toggle-password-section">
                    <div class="form-section">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                        Password
                    </div>
                    <span class="pw-reveal" onclick="togglePasswordFields()">Ubah Password</span>
                </div>
                <div id="passwordFields" style="display:none;">
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label>Password Baru</label>
                            <div class="fg-ico">
                                <svg class="input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                </svg>
                                <input type="password" name="password" placeholder="Minimal 8 karakter">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Konfirmasi Password</label>
                            <div class="fg-ico">
                                <svg class="input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                                <input type="password" name="password_confirmation" placeholder="Ketik ulang password">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section: Role & Status --}}
                <div class="form-section">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    Role & Status
                </div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label>Role <span class="required">*</span></label>
                        <div class="fg-ico">
                            <svg class="input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                <circle cx="8.5" cy="7" r="4"/>
                                <line x1="20" y1="8" x2="20" y2="14"/>
                                <line x1="23" y1="11" x2="17" y2="11"/>
                            </svg>
                            <select name="role" id="editRole">
                                <option value="superadmin">Super Admin</option>
                                <option value="admin">Admin</option>
                                <option value="staff">Staff</option>
                                <option value="dosen">Dosen</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="status" id="editStatusValue" value="1">
                        <label>Status Akun</label>
                        <div class="toggle-wrap" style="padding-top:4px;">
                            <span class="toggle-label">Status</span>
                            <div class="toggle-control">
                                <div class="toggle-slider on" id="editToggleSlider" onclick="toggleEditStatus()">
                                    <div class="toggle-thumb"></div>
                                </div>
                                <span class="toggle-text" id="editToggleLabel">Aktif</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal('modalEdit')">Batal</button>
                <button type="submit" class="btn-save">Update Admin</button>
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
    const COL_TYPES = { 
        1:'text', 2:'text', 3:'text', 4:'text', 5:'text',
        6:'text', 7:'date', 8:'text', 9:'date'
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
                    if (type === 'date') badge.textContent = sortDir === 'asc' ? 'Lama→Baru' : 'Baru→Lama';
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

    function sortTable(col, dir) {
        const tbody = document.querySelector('#adminTable tbody');
        const rows  = Array.from(tbody.querySelectorAll('tr')).filter(r => r.cells.length > 1);
        const type  = COL_TYPES[col] || 'text';

        rows.sort((a, b) => {
            const aRaw = a.cells[col]?.innerText.trim() ?? '';
            const bRaw = b.cells[col]?.innerText.trim() ?? '';
            
            if (type === 'date') {
                const aVal = parseDate(aRaw);
                const bVal = parseDate(bRaw);
                return dir === 'asc' ? aVal - bVal : bVal - aVal;
            } else {
                const aVal = aRaw.toLowerCase();
                const bVal = bRaw.toLowerCase();
                if (aVal < bVal) return dir === 'asc' ? -1 : 1;
                if (aVal > bVal) return dir === 'asc' ?  1 : -1;
                return 0;
            }
        });
        rows.forEach(r => tbody.appendChild(r));
    }

    // ── Confirm Modal ────────────────────────────────────────────────────────
    let confirmAction = null;

    const confirmConfig = {
        approve: {
            icon:'<polyline points="20,6 9,17 4,12"/>',
            iconBg:'linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%)', iconColor:'#059669',
            accent:'linear-gradient(90deg, #22C55E 0%, #16A34A 100%)',
            subtitle:'Persetujuan Akses', title:'Setujui Akses Dosen',
            btnBg:'linear-gradient(135deg, #22C55E 0%, #16A34A 100%)',
            btnShadow:'0 4px 12px rgba(34,197,94,.3), 0 1px 0 rgba(255,255,255,.12) inset',
            btnHoverShadow:'0 6px 16px rgba(34,197,94,.4), 0 1px 0 rgba(255,255,255,.12) inset',
            btnLabel:'Ya, Setujui',
            desc: n => `Akun <strong style="color:var(--gray-900)">${n}</strong> akan disetujui dan dapat login ke sistem.`,
        },
        reject: {
            icon:'<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>',
            iconBg:'linear-gradient(135deg, #FEF2F2 0%, #FEE2E2 100%)', iconColor:'#DC2626',
            accent:'linear-gradient(90deg, #EF4444 0%, #F87171 100%)',
            subtitle:'Penolakan Akses', title:'Tolak Akses Dosen',
            btnBg:'linear-gradient(135deg, #EF4444 0%, #DC2626 100%)',
            btnShadow:'0 4px 12px rgba(239,68,68,.3), 0 1px 0 rgba(255,255,255,.12) inset',
            btnHoverShadow:'0 6px 16px rgba(239,68,68,.4), 0 1px 0 rgba(255,255,255,.12) inset',
            btnLabel:'Ya, Tolak',
            desc: n => `Akun <strong style="color:var(--gray-900)">${n}</strong> akan ditolak dan tidak dapat mengakses sistem.`,
        },
        toggle_on: {
            icon:'<rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>',
            iconBg:'linear-gradient(135deg, #EEF2FF 0%, #E0E7FF 100%)', iconColor:'#4F46E5',
            accent:'linear-gradient(90deg, #4F46E5 0%, #6366F1 100%)',
            subtitle:'Manajemen Hak Akses', title:'Berikan Akses Edit',
            btnBg:'linear-gradient(135deg, #4F46E5 0%, #6366F1 100%)',
            btnShadow:'0 4px 12px rgba(79,70,229,.3), 0 1px 0 rgba(255,255,255,.12) inset',
            btnHoverShadow:'0 6px 16px rgba(79,70,229,.4), 0 1px 0 rgba(255,255,255,.12) inset',
            btnLabel:'Ya, Berikan',
            desc: n => `<strong style="color:var(--gray-900)">${n}</strong> akan mendapatkan hak akses edit konten di seluruh sistem.`,
        },
        toggle_off: {
            icon:'<rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 9.9-1"/><line x1="2" y1="2" x2="22" y2="22"/>',
            iconBg:'linear-gradient(135deg, #F1F5F9 0%, #E2E8F0 100%)', iconColor:'#64748B',
            accent:'linear-gradient(90deg, #64748B 0%, #94A3B8 100%)',
            subtitle:'Manajemen Hak Akses', title:'Cabut Akses Edit',
            btnBg:'linear-gradient(135deg, #64748B 0%, #475569 100%)',
            btnShadow:'0 4px 12px rgba(100,116,139,.3), 0 1px 0 rgba(255,255,255,.12) inset',
            btnHoverShadow:'0 6px 16px rgba(100,116,139,.4), 0 1px 0 rgba(255,255,255,.12) inset',
            btnLabel:'Ya, Cabut',
            desc: n => `Akses edit <strong style="color:var(--gray-900)">${n}</strong> akan dicabut. Akun kembali ke mode <em>View Only</em>.`,
        },
        delete: {
            icon:'<polyline points="3,6 5,6 21,6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/>',
            iconBg:'linear-gradient(135deg, #FEF2F2 0%, #FEE2E2 100%)', iconColor:'#DC2626',
            accent:'linear-gradient(90deg, #EF4444 0%, #F87171 100%)',
            subtitle:'Hapus Admin', title:'Hapus Akun Admin',
            btnBg:'linear-gradient(135deg, #EF4444 0%, #DC2626 100%)',
            btnShadow:'0 4px 12px rgba(239,68,68,.3), 0 1px 0 rgba(255,255,255,.12) inset',
            btnHoverShadow:'0 6px 16px rgba(239,68,68,.4), 0 1px 0 rgba(255,255,255,.12) inset',
            btnLabel:'Ya, Hapus',
            desc: n => `Akun <strong style="color:var(--gray-900)">${n}</strong> akan dihapus secara permanen.`,
        },
    };

    function openConfirmModal(type, id, name, canEdit = false) {
        const routes = {
            approve: `/admins/${id}/approve`,
            reject:  `/admins/${id}/reject`,
            toggle:  `/admins/${id}/toggle-edit`,
            delete:  `/admins/${id}`,
        };
        confirmAction = {
            formId: `form${type.charAt(0).toUpperCase() + type.slice(1)}`,
            action: routes[type]
        };

        const titles = {
            approve:     ['Setujui Akses Dosen',  'Akun akan disetujui dan dapat login ke sistem.'],
            reject:      ['Tolak Akses Dosen',    'Akun akan ditolak dan tidak dapat mengakses sistem.'],
            toggle:      ['Toggle Akses Edit',    canEdit ? `Akses edit ${name} akan dicabut.` : `${name} akan mendapatkan hak akses edit.`],
            delete:      ['Hapus Akun Admin',     `Admin <strong style="color:#1e293b;font-weight:700;">${name}</strong> akan dihapus secara permanen dari sistem.`],
        };

        const [title, desc] = titles[type];
        document.getElementById('confirmTitle').textContent   = title;
        document.getElementById('confirmDesc').innerHTML      = desc;
        document.getElementById('confirmBtn').textContent     =
            type === 'approve' ? 'Ya, Setujui' :
            type === 'reject'  ? 'Ya, Tolak'   :
            type === 'toggle'  ? 'Ya, Lanjutkan' : 'Ya, Hapus';

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
        if (id === 'modalEdit') {
            document.getElementById('passwordFields').style.display = 'none';
        }
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

    function toggleEditStatus() {
        const currentValue = document.getElementById('editStatusValue').value;
        const newValue = currentValue == '1' ? '0' : '1';
        document.getElementById('editStatusValue').value = newValue;
        
        const slider = document.getElementById('editToggleSlider');
        const label  = document.getElementById('editToggleLabel');
        slider.classList.toggle('on', newValue == '1');
        label.textContent = newValue == '1' ? 'Aktif' : 'Nonaktif';
    }

    function togglePasswordFields() {
        const pf = document.getElementById('passwordFields');
        pf.style.display = pf.style.display === 'none' ? 'block' : 'none';
    }

    // Close modal on backdrop click
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