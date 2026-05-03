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

    /* ── Action Buttons ── */
    .action-wrap { display:flex; gap:6px; }
    .action-btn {
        height:34px; padding:0 13px; border-radius:8px; font-family:Inter,sans-serif;
        font-size:12px; font-weight:600; cursor:pointer; display:inline-flex; align-items:center;
        gap:5px; transition:all .15s ease; border:none;
    }
    .btn-edit-row {
        color:#4F46E5; background:#FFF;
        box-shadow:inset 0 0 0 1px rgba(79,70,229,.2);
    }
    .btn-edit-row:hover {
        background:#EFF6FF; color:#4338CA;
        transform:translateY(-1px);
        box-shadow:0 4px 10px rgba(79,70,229,.2), inset 0 0 0 1px #4F46E5;
    }
    .btn-delete-row {
        color:#DC2626; background:#FFF;
        box-shadow:inset 0 0 0 1px rgba(220,38,38,.2);
    }
    .btn-delete-row:hover {
        background:#FEF2F2; color:#B91C1C;
        transform:translateY(-1px);
        box-shadow:0 4px 10px rgba(220,38,38,.2), inset 0 0 0 1px #DC2626;
    }
    .action-btn svg { width:13px; height:13px; }

    .empty-row {
        text-align:center; padding:64px 0; color:var(--gray-400);
        font-family:Inter,sans-serif; font-size:14px; font-weight:500;
    }

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

    .form-modal {
        position:relative; z-index:1; background:#FFF; border-radius:20px;
        width:100%; max-width:500px; max-height:90vh; overflow-y:auto;
        box-shadow:0 25px 60px rgba(0,0,0,.2), 0 1px 0 rgba(255,255,255,.1) inset;
        animation:slideUp .25s ease;
    }
    @keyframes slideUp {
        from { transform:translateY(20px); opacity:0; }
        to   { transform:translateY(0); opacity:1; }
    }
    .modal-header {
        padding:24px 28px 18px; border-bottom:1px solid var(--gray-100);
        display:flex; align-items:center; justify-content:space-between;
        position:sticky; top:0; background:white; z-index:2;
        border-radius:20px 20px 0 0;
    }
    .modal-header h2 {
        font-family:Inter,sans-serif; font-size:16px; font-weight:700;
        color:var(--gray-900); margin:0; letter-spacing:-0.2px;
    }
    .modal-close {
        width:32px; height:32px; background:var(--gray-100); border:none;
        border-radius:9px; cursor:pointer; display:flex;
        align-items:center; justify-content:center;
        color:var(--gray-500); font-size:14px; transition:all .15s ease;
    }
    .modal-close:hover { background:var(--gray-200); color:var(--gray-700); }

    .modal-body { padding:20px 28px; }
    .form-section {
        font-family:Inter,sans-serif; font-size:11px; font-weight:700;
        color:var(--gray-400); text-transform:uppercase; letter-spacing:.08em;
        margin:18px 0 12px;
    }
    .form-section:first-child { margin-top:0; }
    .form-divider { height:1px; background:var(--gray-100); margin:16px 0; }
    .form-grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
    .form-group { margin-bottom:14px; }
    .form-group:last-child { margin-bottom:0; }
    .form-group label {
        display:block; font-family:Inter,sans-serif; font-size:12px;
        font-weight:600; color:var(--gray-700); margin-bottom:6px;
        text-transform:uppercase; letter-spacing:.05em;
    }
    .form-group input,.form-group select {
        width:100%; border:1px solid var(--gray-200); border-radius:10px; padding:10px 14px;
        font-family:Inter,sans-serif; font-size:13px; color:var(--gray-900); outline:none;
        box-sizing:border-box; background:var(--gray-50); transition:all .2s ease;
        font-weight:500;
    }
    .form-group input:focus,.form-group select:focus {
        border-color:#4F46E5; background:#FFF;
        box-shadow:0 0 0 4px rgba(79,70,229,.08);
    }

    .modal-footer {
        padding:16px 28px 22px; border-top:1px solid var(--gray-100);
        display:flex; gap:10px; justify-content:flex-end;
        position:sticky; bottom:0; background:white;
        border-radius:0 0 20px 20px;
    }
    .btn-cancel {
        height:40px; padding:0 20px; background:white; border:1px solid var(--gray-200);
        border-radius:10px; font-family:Inter,sans-serif; font-size:13px;
        font-weight:500; cursor:pointer; color:var(--gray-700); transition:all .15s ease;
    }
    .btn-cancel:hover { background:var(--gray-50); border-color:var(--gray-300); }
    .btn-save {
        height:40px; padding:0 22px;
        background:linear-gradient(135deg, #4F46E5 0%, #6366F1 100%);
        border:none; border-radius:10px; font-family:Inter,sans-serif;
        font-size:13px; font-weight:600; color:#FFF; cursor:pointer;
        box-shadow:0 4px 12px rgba(79,70,229,.3), 0 1px 0 rgba(255,255,255,.12) inset;
        transition:all .2s ease;
    }
    .btn-save:hover {
        transform:translateY(-1px);
        box-shadow:0 6px 16px rgba(79,70,229,.4), 0 1px 0 rgba(255,255,255,.12) inset;
    }

    .toggle-wrap {
        display:flex; align-items:center; justify-content:space-between; padding:12px 0;
    }
    .toggle-label {
        font-family:Inter,sans-serif; font-size:13px; font-weight:600;
        color:var(--gray-700);
    }
    .toggle-slider {
        width:44px; height:24px; background:var(--gray-200); border-radius:20px;
        position:relative; cursor:pointer; transition:background .2s;
    }
    .toggle-slider.on { background:#4F46E5; }
    .toggle-thumb {
        width:20px; height:20px; background:white; border-radius:50%;
        position:absolute; top:2px; left:2px; transition:left .2s;
        box-shadow:0 1px 3px rgba(0,0,0,.2);
    }
    .toggle-slider.on .toggle-thumb { left:22px; }
    .toggle-text {
        font-family:Inter,sans-serif; font-size:13px; color:var(--gray-500);
        margin-left:10px;
    }
    .pw-reveal {
        font-family:Inter,sans-serif; font-size:11px; color:#4F46E5;
        font-weight:600; cursor:pointer;
    }
    .pw-reveal:hover { text-decoration:underline; }

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

{{-- Modals tetap sama seperti sebelumnya --}}
{{-- (Kode modal tidak berubah, saya skip untuk menghemat space) --}}

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
                            <span class="toggle-label" style="font-size:12px;text-transform:uppercase;letter-spacing:.04em;color:var(--gray-700);">Status</span>
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
        btn.style.boxShadow  = cfg.btnShadow;
        btn.textContent      = cfg.btnLabel;
        btn.onmouseover = () => { btn.style.boxShadow = cfg.btnHoverShadow; btn.style.transform = 'translateY(-1px)'; };
        btn.onmouseout  = () => { btn.style.boxShadow = cfg.btnShadow; btn.style.transform = 'translateY(0)'; };

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