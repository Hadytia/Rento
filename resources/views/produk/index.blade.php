@extends('layouts.app')

@section('content')

<style>
    /* ═══════════════════════════════════════════════════════════════════════
       DESIGN TOKENS
    ═══════════════════════════════════════════════════════════════════════ */
    :root {
        --primary: #2D4DA3;
        --primary-dark: #253f8a;
        --primary-light: #EFF6FF;
        --primary-soft: #F0F4FF;
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
        background:linear-gradient(135deg, #111827 0%, #2D4DA3 100%);
        -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent;
    }
    .page-title p {
        font-family:Inter,sans-serif; font-size:14px; color:var(--gray-500);
        margin:6px 0 0 0; letter-spacing:-0.1px;
    }
    .btn-add {
        height:44px; padding:0 22px;
        background:linear-gradient(135deg, #2D4DA3 0%, #4F6FCA 100%);
        color:#FFF; border:none; border-radius:12px;
        font-family:Inter,sans-serif; font-size:13px; font-weight:600;
        cursor:pointer; display:flex; align-items:center; gap:8px; white-space:nowrap;
        box-shadow:0 4px 14px rgba(45,77,163,.32), 0 1px 0 rgba(255,255,255,.15) inset;
        transition:all .2s ease; letter-spacing:.2px; text-decoration:none;
    }
    .btn-add:hover {
        transform:translateY(-1px);
        box-shadow:0 8px 20px rgba(45,77,163,.4), 0 1px 0 rgba(255,255,255,.15) inset;
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
        font-weight:500; box-shadow:0 1px 2px rgba(5,150,105,.06);
    }

    /* ═══════════════════════════════════════════════════════════════════════
       STAT CARDS
    ═══════════════════════════════════════════════════════════════════════ */
    .stats-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:18px; margin-bottom:28px; }
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
    .stat-card.blue::before   { background:linear-gradient(90deg, #2D4DA3, #4F6FCA); }
    .stat-card.green::before  { background:linear-gradient(90deg, #059669, #34D399); }
    .stat-card.amber::before  { background:linear-gradient(90deg, #D97706, #FBBF24); }
    .stat-card.red::before    { background:linear-gradient(90deg, #DC2626, #F87171); }

    .stat-icon {
        width:52px; height:52px; border-radius:14px;
        display:flex; align-items:center; justify-content:center; flex-shrink:0;
    }
    .stat-icon svg { width:22px; height:22px; position:relative; z-index:1; }
    .stat-icon.blue  { background:linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%); color:#2D4DA3; box-shadow:inset 0 0 0 1px rgba(45,77,163,.1); }
    .stat-icon.green { background:linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%); color:#059669; box-shadow:inset 0 0 0 1px rgba(5,150,105,.1); }
    .stat-icon.amber { background:linear-gradient(135deg, #FFFBEB 0%, #FDE68A 100%); color:#D97706; box-shadow:inset 0 0 0 1px rgba(217,119,6,.1); }
    .stat-icon.red   { background:linear-gradient(135deg, #FEF2F2 0%, #FEE2E2 100%); color:#DC2626; box-shadow:inset 0 0 0 1px rgba(220,38,38,.1); }
    .stat-label { font-family:Inter,sans-serif; font-size:11px; font-weight:600; color:var(--gray-500); text-transform:uppercase; letter-spacing:.8px; margin-bottom:6px; }
    .stat-value { font-family:Inter,sans-serif; font-size:28px; font-weight:700; color:var(--gray-900); line-height:1; letter-spacing:-1px; }

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
    .table-label { font-family:Inter,sans-serif; font-size:15px; font-weight:600; color:var(--gray-900); letter-spacing:-0.2px; }
    .count-badge {
        display:inline-flex; align-items:center; justify-content:center;
        background:linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%);
        color:#2D4DA3; border-radius:8px; padding:3px 10px;
        font-size:12px; font-weight:700; margin-left:10px;
        font-family:Inter,sans-serif; box-shadow:inset 0 0 0 1px rgba(45,77,163,.15);
    }
    .search-wrap {
        display:flex; align-items:center; gap:8px;
        border:1px solid var(--gray-200); border-radius:11px;
        padding:0 14px; height:42px; background:var(--gray-50);
        width:260px; transition:all .2s ease;
    }
    .search-wrap:focus-within {
        border-color:#2D4DA3; background:#FFF;
        box-shadow:0 0 0 4px rgba(45,77,163,.08);
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
        overflow-x:auto; border-radius:12px; border:1px solid var(--gray-100);
    }
    .table-scroll::-webkit-scrollbar { height:10px; }
    .table-scroll::-webkit-scrollbar-track { background:transparent; }
    .table-scroll::-webkit-scrollbar-thumb { background:var(--gray-200); border-radius:10px; border:2px solid #FFF; }
    .table-scroll::-webkit-scrollbar-thumb:hover { background:var(--gray-300); }

    table { width:100%; border-collapse:separate; border-spacing:0; }
    thead tr { background:var(--gray-50); }
    thead th {
        font-family:Inter,sans-serif; font-size:11px; font-weight:600;
        color:var(--gray-500); letter-spacing:.08em; text-transform:uppercase;
        padding:14px 18px; text-align:left; white-space:nowrap;
        background:var(--gray-50); border-bottom:1px solid var(--gray-200);
    }
    tbody tr { transition:background .15s ease; }
    tbody td {
        font-family:Inter,sans-serif; font-size:13px; color:var(--gray-800);
        padding:16px 18px; vertical-align:middle;
        background:#FFF; border-bottom:1px solid var(--gray-100);
    }
    tbody tr:last-child td { border-bottom:none; }
    tbody tr:hover td { background:#F8FAFF; }

    /* ═══════════════════════════════════════════════════════════════════════
       FREEZE PANES
    ═══════════════════════════════════════════════════════════════════════ */
    th.freeze-1, td.freeze-1 { position:sticky; left:0; z-index:3; width:180px; min-width:180px; }
    th.freeze-2, td.freeze-2 { position:sticky; left:180px; z-index:3; min-width:280px; width:280px; box-shadow:6px 0 12px -8px rgba(15,23,42,.12); }
    thead th.freeze-1, thead th.freeze-2 { z-index:4; background:var(--gray-50); }
    tbody td.freeze-1, tbody td.freeze-2 { background:#FFF; }
    tbody tr:hover td.freeze-1,
    tbody tr:hover td.freeze-2 { background:#F8FAFF; }

    /* ═══════════════════════════════════════════════════════════════════════
       SORT
    ═══════════════════════════════════════════════════════════════════════ */
    .sortable { cursor:pointer; user-select:none; transition:all .15s ease; }
    .sortable:hover { color:#2D4DA3 !important; background:#EFF6FF !important; }
    .th-inner { display:inline-flex; align-items:center; gap:8px; }
    .sort-icon { display:inline-flex; flex-direction:column; align-items:center; gap:2px; flex-shrink:0; }
    .sort-icon svg { width:9px; height:6px; display:block; transition:fill .15s; }
    .sortable:not(.sort-active) .tri-up,
    .sortable:not(.sort-active) .tri-down { fill:var(--gray-300); }
    .sortable:hover:not(.sort-active) .tri-up,
    .sortable:hover:not(.sort-active) .tri-down { fill:var(--gray-400); }
    th.sort-active { color:#2D4DA3 !important; background:#EFF6FF !important; }
    th.sort-active.asc  .tri-up   { fill:#2D4DA3; }
    th.sort-active.asc  .tri-down { fill:#BFDBFE; }
    th.sort-active.desc .tri-up   { fill:#BFDBFE; }
    th.sort-active.desc .tri-down { fill:#2D4DA3; }
    .sort-badge {
        display:inline-flex; align-items:center;
        background:linear-gradient(135deg, #2D4DA3 0%, #4F6FCA 100%);
        color:white; font-size:9px; font-weight:700;
        padding:2px 6px; border-radius:5px; letter-spacing:.5px;
        margin-left:4px; opacity:0; transition:opacity .15s;
        box-shadow:0 2px 4px rgba(45,77,163,.2);
    }
    th.sort-active .sort-badge { opacity:1; }

    /* ═══════════════════════════════════════════════════════════════════════
       PRODUCT IMAGE HOVER & ZOOM
    ═══════════════════════════════════════════════════════════════════════ */
    .product-cell { display:flex; align-items:center; gap:13px; }
    .product-img-wrap {
        width:44px; height:44px; border-radius:12px;
        overflow:hidden; cursor:pointer; position:relative;
        background:var(--gray-50); border:1px solid var(--gray-200);
        flex-shrink:0; transition:all .3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow:0 2px 6px rgba(15,23,42,.08);
    }
    .product-img-wrap:hover {
        transform:scale(1.18);
        box-shadow:0 12px 24px rgba(45,77,163,.2), 0 4px 8px rgba(45,77,163,.1);
        z-index:10; border-color:#2D4DA3;
    }
    .product-img { width:100%; height:100%; object-fit:cover; transition:transform .3s ease; }
    .product-img-wrap:hover .product-img { transform:scale(1.12); }
    .product-img-placeholder {
        width:44px; height:44px; border-radius:12px;
        background:linear-gradient(135deg, var(--gray-100) 0%, var(--gray-200) 100%);
        border:1px solid var(--gray-200); display:flex; align-items:center;
        justify-content:center; flex-shrink:0;
    }
    .product-name { font-weight:600; color:var(--gray-900); font-size:13.5px; letter-spacing:-0.1px; }
    .cat-pill {
        display:inline-flex; align-items:center; gap:5px;
        background:linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%);
        color:#2D4DA3; border-radius:8px; padding:4px 11px;
        font-size:11.5px; font-weight:600; font-family:Inter,sans-serif;
        white-space:nowrap; box-shadow:inset 0 0 0 1px rgba(45,77,163,.15);
    }
    .price-text { font-weight:700; color:var(--gray-900); font-size:13px; white-space:nowrap; letter-spacing:-0.2px; }
    .price-unit { font-size:11px; color:var(--gray-400); font-weight:400; }

    /* ── Stock Badge ── */
    .stock-badge {
        display:inline-flex; align-items:center; justify-content:center;
        min-width:30px; height:30px; padding:0 8px; border-radius:8px;
        font-family:Inter,sans-serif; font-size:12px; font-weight:700; white-space:nowrap;
    }
    .stock-high  { background:linear-gradient(135deg,#ECFDF5,#D1FAE5); color:#047857; box-shadow:inset 0 0 0 1px rgba(5,150,105,.2); }
    .stock-med   { background:linear-gradient(135deg,#FFFBEB,#FDE68A); color:#B45309; box-shadow:inset 0 0 0 1px rgba(217,119,6,.2); }
    .stock-low   { background:linear-gradient(135deg,#FEF2F2,#FEE2E2); color:#B91C1C; box-shadow:inset 0 0 0 1px rgba(220,38,38,.2); }

    /* ── Condition Badge ── */
    .cond-badge {
        display:inline-flex; align-items:center; gap:6px; border-radius:8px;
        padding:5px 12px; font-family:Inter,sans-serif;
        font-size:11.5px; font-weight:600; letter-spacing:.2px;
    }
    .cond-badge-dot { width:6px; height:6px; border-radius:50%; display:inline-block; }
    .cond-new       { background:linear-gradient(135deg,#EFF6FF,#DBEAFE); color:#1D4ED8; box-shadow:inset 0 0 0 1px rgba(37,99,235,.2); }
    .cond-new .cond-badge-dot       { background:#2563EB; }
    .cond-excellent { background:linear-gradient(135deg,#ECFDF5,#D1FAE5); color:#047857; box-shadow:inset 0 0 0 1px rgba(5,150,105,.2); }
    .cond-excellent .cond-badge-dot { background:#10B981; }
    .cond-good      { background:linear-gradient(135deg,#F0FDF4,#DCFCE7); color:#15803D; box-shadow:inset 0 0 0 1px rgba(22,163,74,.2); }
    .cond-good .cond-badge-dot      { background:#22C55E; }
    .cond-fair      { background:linear-gradient(135deg,#FFFBEB,#FDE68A); color:#B45309; box-shadow:inset 0 0 0 1px rgba(217,119,6,.2); }
    .cond-fair .cond-badge-dot      { background:#F59E0B; }
    .cond-poor      { background:linear-gradient(135deg,#FEF2F2,#FEE2E2); color:#B91C1C; box-shadow:inset 0 0 0 1px rgba(220,38,38,.2); }
    .cond-poor .cond-badge-dot      { background:#EF4444; }

    /* ── Audit Trail ── */
    .audit-cell { min-width:150px; }
    .audit-name { font-size:12px; font-weight:600; color:var(--gray-700); margin-bottom:2px; letter-spacing:-0.1px; }
    .audit-date { font-size:11px; color:var(--gray-400); font-family:'JetBrains Mono','Consolas',monospace; font-weight:500; }
    .audit-empty { color:var(--gray-300); font-size:13px; }

    /* ── Min Sewa ── */
    .min-sewa-badge {
        display:inline-flex; align-items:center; gap:5px;
        background:var(--gray-100); color:var(--gray-600);
        border-radius:8px; padding:4px 10px;
        font-size:12px; font-weight:600; font-family:Inter,sans-serif;
        border:1px solid var(--gray-200);
    }

    /* ── Action Buttons ── */
    .action-wrap { display:flex; gap:6px; }
    .action-btn {
        height:32px; padding:0 12px; border-radius:8px;
        font-family:Inter,sans-serif; font-size:12px; font-weight:600;
        cursor:pointer; display:inline-flex; align-items:center;
        gap:5px; transition:all .15s ease; text-decoration:none;
        letter-spacing:.1px; border:none;
    }
    .btn-edit   { background:#EFF6FF; color:#2D4DA3; box-shadow:inset 0 0 0 1px rgba(45,77,163,.15); }
    .btn-edit:hover { background:#2D4DA3; color:#FFF; transform:translateY(-1px); box-shadow:0 4px 10px rgba(45,77,163,.3); }
    .btn-delete { background:#FEF2F2; color:#DC2626; box-shadow:inset 0 0 0 1px rgba(220,38,38,.15); }
    .btn-delete:hover { background:#DC2626; color:#FFF; transform:translateY(-1px); box-shadow:0 4px 10px rgba(220,38,38,.3); }
    .action-btn svg { width:13px; height:13px; }

    /* ── Empty State ── */
    .empty-state { text-align:center; padding:64px 0; color:var(--gray-400); }
    .empty-state svg { width:48px; height:48px; margin-bottom:14px; opacity:.4; }
    .empty-state p { font-size:14px; margin:0; font-family:Inter,sans-serif; font-weight:500; }

    /* ═══════════════════════════════════════════════════════════════════════
       IMAGE MODAL — unchanged
    ═══════════════════════════════════════════════════════════════════════ */
    .img-modal-overlay { display:none; position:fixed; inset:0; z-index:9999; align-items:center; justify-content:center; padding:20px; }
    .img-modal-overlay.show { display:flex; animation:fadeIn .25s ease; }
    .img-modal-backdrop { position:fixed; inset:0; background:rgba(15,23,42,.8); backdrop-filter:blur(8px); }
    .img-modal-box { position:relative; z-index:1; background:#FFF; border-radius:20px; width:100%; max-width:600px; box-shadow:0 25px 60px rgba(0,0,0,.3); animation:zoomIn .3s cubic-bezier(0.34,1.56,0.64,1); overflow:hidden; }
    @keyframes zoomIn { from{transform:scale(0.8);opacity:0;} to{transform:scale(1);opacity:1;} }
    .img-modal-header { padding:20px 24px; border-bottom:1px solid var(--gray-100); display:flex; align-items:center; justify-content:space-between; }
    .img-modal-header h3 { font-family:Inter,sans-serif; font-size:16px; font-weight:700; color:var(--gray-900); margin:0; }
    .img-modal-close { width:36px; height:36px; background:var(--gray-100); border:none; border-radius:10px; cursor:pointer; display:flex; align-items:center; justify-content:center; color:var(--gray-500); font-size:18px; transition:all .15s ease; }
    .img-modal-close:hover { background:var(--gray-200); color:var(--gray-700); transform:rotate(90deg); }
    .img-modal-image-wrap { width:100%; aspect-ratio:4/3; background:var(--gray-50); display:flex; align-items:center; justify-content:center; overflow:hidden; }
    .img-modal-image { width:100%; height:100%; object-fit:contain; padding:20px; }
    .img-modal-content { padding:24px; }
    .img-modal-product-name { font-family:Inter,sans-serif; font-size:18px; font-weight:700; color:var(--gray-900); margin:0 0 8px 0; }
    .img-modal-product-desc { font-family:Inter,sans-serif; font-size:14px; color:var(--gray-600); line-height:1.6; margin:0; }
    .img-modal-product-desc.empty { color:var(--gray-400); font-style:italic; }
    .img-modal-meta { display:grid; grid-template-columns:repeat(2,1fr); gap:12px; margin-top:16px; padding-top:16px; border-top:1px solid var(--gray-100); }
    .img-modal-meta-item { display:flex; flex-direction:column; gap:4px; }
    .img-modal-meta-label { font-family:Inter,sans-serif; font-size:11px; font-weight:700; color:var(--gray-400); text-transform:uppercase; letter-spacing:.08em; }
    .img-modal-meta-value { font-family:Inter,sans-serif; font-size:13px; font-weight:600; color:var(--gray-900); }
    .stock-badge-modal { display:inline-flex; align-items:center; justify-content:center; padding:4px 10px; border-radius:7px; font-size:13px; font-weight:700; font-family:Inter,sans-serif; }
    .stock-badge-modal.safe { background:linear-gradient(135deg,#ECFDF5,#D1FAE5); color:#059669; box-shadow:inset 0 0 0 1px rgba(5,150,105,.2); }
    .stock-badge-modal.warning { background:linear-gradient(135deg,#FEF3C7,#FDE68A); color:#D97706; box-shadow:inset 0 0 0 1px rgba(217,119,6,.2); }
    .stock-badge-modal.empty { background:linear-gradient(135deg,#FEF2F2,#FEE2E2); color:#DC2626; box-shadow:inset 0 0 0 1px rgba(220,38,38,.2); }
    .kondisi-badge-modal { display:inline-flex; align-items:center; gap:5px; padding:4px 10px; border-radius:7px; font-size:13px; font-weight:700; font-family:Inter,sans-serif; }
    .kondisi-badge-modal .dot { width:6px; height:6px; border-radius:50%; }
    .kondisi-badge-modal.new { background:linear-gradient(135deg,#EFF6FF,#DBEAFE); color:#1D4ED8; box-shadow:inset 0 0 0 1px rgba(37,99,235,.2); }
    .kondisi-badge-modal.new .dot { background:#2563EB; }
    .kondisi-badge-modal.excellent { background:linear-gradient(135deg,#ECFDF5,#D1FAE5); color:#047857; box-shadow:inset 0 0 0 1px rgba(5,150,105,.2); }
    .kondisi-badge-modal.excellent .dot { background:#10B981; }
    .kondisi-badge-modal.good { background:linear-gradient(135deg,#F0FDF4,#DCFCE7); color:#15803D; box-shadow:inset 0 0 0 1px rgba(22,163,74,.2); }
    .kondisi-badge-modal.good .dot { background:#22C55E; }
    .kondisi-badge-modal.fair { background:linear-gradient(135deg,#FEF3C7,#FDE68A); color:#B45309; box-shadow:inset 0 0 0 1px rgba(245,158,11,.2); }
    .kondisi-badge-modal.fair .dot { background:#F59E0B; }
    .kondici-badge-modal.poor { background:linear-gradient(135deg,#FEF2F2,#FEE2E2); color:#B91C1C; box-shadow:inset 0 0 0 1px rgba(220,38,38,.2); }
    .kondisi-badge-modal.poor .dot { background:#EF4444; }

    /* ═══════════════════════════════════════════════════════════════════════
       MODAL OVERLAY & ANIMATION — shared
    ═══════════════════════════════════════════════════════════════════════ */
    .modal-overlay { display:none; position:fixed; inset:0; z-index:999; align-items:center; justify-content:center; padding:20px; }
    .modal-overlay.show { display:flex; animation:fadeIn .2s ease; }
    @keyframes fadeIn { from{opacity:0;} to{opacity:1;} }
    @keyframes slideUp { from{transform:translateY(24px);opacity:0;} to{transform:translateY(0);opacity:1;} }
    .modal-backdrop { position:fixed; inset:0; background:rgba(15,23,42,.55); backdrop-filter:blur(6px); }

    /* ═══════════════════════════════════════════════════════════════════════
       MODAL ADD — redesigned
    ═══════════════════════════════════════════════════════════════════════ */
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

    .modal-box {
        position:relative; z-index:1; background:#FFF; border-radius:20px;
        width:100%; max-width:660px; max-height:90vh; overflow-y:auto;
        box-shadow:0 25px 60px rgba(0,0,0,.18), 0 1px 0 rgba(255,255,255,.1) inset;
        animation:slideUp .28s cubic-bezier(0.22,1,0.36,1);
        font-family:'Plus Jakarta Sans',Inter,sans-serif;
    }
    .modal-box::-webkit-scrollbar { width:6px; }
    .modal-box::-webkit-scrollbar-track { background:transparent; }
    .modal-box::-webkit-scrollbar-thumb { background:var(--gray-200); border-radius:6px; }

    /* gradient header */
    .modal-header {
        padding:0;
        position:sticky; top:0; z-index:2; border-radius:20px 20px 0 0;
        overflow:hidden;
    }
    .modal-header-inner {
        background:linear-gradient(135deg, #1e3a5f 0%, #2563eb 60%, #3b82f6 100%);
        padding:22px 26px 20px;
        position:relative; overflow:hidden;
        display:flex; align-items:center; gap:14px;
    }
    .modal-header-inner::before {
        content:''; position:absolute; top:-40px; right:-40px;
        width:150px; height:150px; border-radius:50%;
        background:rgba(255,255,255,0.06);
    }
    .modal-header-inner::after {
        content:''; position:absolute; bottom:-60px; left:35%;
        width:200px; height:200px; border-radius:50%;
        background:rgba(255,255,255,0.04);
    }
    .modal-hicon {
        width:42px; height:42px; background:rgba(255,255,255,0.15);
        border-radius:12px; display:flex; align-items:center; justify-content:center;
        border:1px solid rgba(255,255,255,0.2); flex-shrink:0; position:relative; z-index:1;
    }
    .modal-htitle { position:relative; z-index:1; }
    .modal-htitle h2 { font-size:1.1rem; font-weight:700; color:#fff; margin:0; letter-spacing:-0.01em; }
    .modal-htitle p  { font-size:0.78rem; color:rgba(255,255,255,0.7); margin:3px 0 0; }
    .modal-hclose {
        position:absolute; top:16px; right:16px; z-index:3;
        width:32px; height:32px; background:rgba(255,255,255,0.15);
        border:1px solid rgba(255,255,255,0.2); border-radius:9px;
        display:flex; align-items:center; justify-content:center;
        color:rgba(255,255,255,0.85); font-size:14px; cursor:pointer;
        transition:background .2s; line-height:1;
    }
    .modal-hclose:hover { background:rgba(255,255,255,0.28); color:#fff; }

    /* body */
    .modal-body { padding:24px 26px; }

    /* section labels */
    .modal-section {
        display:flex; align-items:center; gap:7px;
        font-size:0.65rem; font-weight:700; letter-spacing:0.08em;
        text-transform:uppercase; color:var(--gray-400);
        margin:20px 0 14px;
    }
    .modal-section:first-child { margin-top:0; }
    .modal-section::after { content:''; flex:1; height:1px; background:var(--gray-100); }

    .form-divider { height:1px; background:var(--gray-100); margin:18px 0; }
    .form-grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
    .form-group { margin-bottom:14px; }
    .form-group:last-child { margin-bottom:0; }

    /* labels */
    .form-group label {
        display:flex; align-items:center; gap:5px;
        font-size:0.78rem; font-weight:600;
        color:var(--gray-700); margin-bottom:7px;
    }
    .form-group label .req { color:#ef4444; font-size:0.72rem; }

    /* input wrapper with icon */
    .fg-wrap { position:relative; }
    .fg-ico {
        position:absolute; left:11px; top:50%; transform:translateY(-50%);
        color:var(--gray-400); pointer-events:none; display:flex; align-items:center;
    }
    .fg-ico-top { position:absolute; left:11px; top:11px; color:var(--gray-400); pointer-events:none; display:flex; align-items:center; }

    /* inputs */
    .form-group input,
    .form-group select {
        width:100%; height:42px;
        background:#f8fafc; border:1.5px solid #e8edf5; border-radius:10px;
        padding:0 12px 0 34px;
        font-family:'Plus Jakarta Sans',Inter,sans-serif; font-size:0.85rem;
        color:var(--gray-900); outline:none; box-sizing:border-box;
        transition:all .18s ease; font-weight:500;
        appearance:none;
    }
    .form-group textarea {
        width:100%; background:#f8fafc; border:1.5px solid #e8edf5; border-radius:10px;
        padding:10px 12px 10px 34px;
        font-family:'Plus Jakarta Sans',Inter,sans-serif; font-size:0.85rem;
        color:var(--gray-900); outline:none; box-sizing:border-box;
        transition:all .18s ease; font-weight:500; resize:none; line-height:1.6;
    }
    .form-group input::placeholder,
    .form-group textarea::placeholder { color:#c0cad9; font-weight:400; }
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color:#3b82f6; background:#fff;
        box-shadow:0 0 0 3px rgba(59,130,246,.1);
    }
    /* file input special */
    .form-group input[type="file"] {
        padding:0 12px; cursor:pointer;
        display:flex; align-items:center;
    }
    .form-hint { font-size:0.72rem; color:var(--gray-400); margin-top:5px; padding-left:2px; }

    /* select arrow */
    .select-wrap { position:relative; }
    .select-arrow {
        position:absolute; right:11px; top:50%; transform:translateY(-50%);
        color:var(--gray-400); pointer-events:none; font-size:0.72rem;
    }

    /* footer */
    .modal-footer {
        padding:16px 26px 22px;
        display:flex; gap:10px; justify-content:flex-end;
        position:sticky; bottom:0; background:white;
        border-top:1px solid var(--gray-100);
        border-radius:0 0 20px 20px;
    }
    .btn-cancel {
        height:42px; padding:0 20px;
        background:#f1f5f9; border:1.5px solid #e2e8f0; border-radius:10px;
        font-family:'Plus Jakarta Sans',Inter,sans-serif; font-size:0.85rem; font-weight:600;
        color:var(--gray-600); cursor:pointer; display:inline-flex; align-items:center; gap:6px;
        transition:all .15s ease;
    }
    .btn-cancel:hover { background:#e2e8f0; border-color:#cbd5e1; }
    .btn-save {
        height:42px; padding:0 26px;
        background:linear-gradient(135deg, #2563eb, #3b82f6); border:none; border-radius:10px;
        font-family:'Plus Jakarta Sans',Inter,sans-serif; font-size:0.85rem; font-weight:700;
        color:#fff; cursor:pointer; display:inline-flex; align-items:center; gap:7px;
        box-shadow:0 4px 14px rgba(37,99,235,.35); transition:all .2s ease;
    }
    .btn-save:hover { background:linear-gradient(135deg,#1d4ed8,#2563eb); box-shadow:0 6px 20px rgba(37,99,235,.45); transform:translateY(-1px); }
    .btn-save:active { transform:translateY(0); }

    /* ═══════════════════════════════════════════════════════════════════════
       CONFIRM / DELETE MODAL — redesigned
    ═══════════════════════════════════════════════════════════════════════ */
    .confirm-box {
        position:relative; z-index:1; background:#FFF; border-radius:20px;
        width:100%; max-width:400px;
        box-shadow:0 25px 60px rgba(0,0,0,.18);
        overflow:hidden; animation:slideUp .25s cubic-bezier(0.22,1,0.36,1);
        font-family:'Plus Jakarta Sans',Inter,sans-serif;
    }
    .confirm-header {
        background:linear-gradient(135deg, #7f1d1d 0%, #dc2626 60%, #ef4444 100%);
        padding:22px 24px 18px;
        position:relative; overflow:hidden;
    }
    .confirm-header::before {
        content:''; position:absolute; top:-30px; right:-30px;
        width:120px; height:120px; border-radius:50%;
        background:rgba(255,255,255,0.07);
    }
    .confirm-hclose {
        position:absolute; top:14px; right:14px;
        width:30px; height:30px; background:rgba(255,255,255,0.15);
        border:1px solid rgba(255,255,255,0.2); border-radius:8px;
        display:flex; align-items:center; justify-content:center;
        color:rgba(255,255,255,0.85); font-size:13px; cursor:pointer;
        transition:background .2s; z-index:2;
    }
    .confirm-hclose:hover { background:rgba(255,255,255,0.28); }
    .confirm-hicon {
        width:44px; height:44px; background:rgba(255,255,255,0.15);
        border-radius:12px; display:flex; align-items:center; justify-content:center;
        border:1px solid rgba(255,255,255,0.2); margin-bottom:12px; position:relative; z-index:1;
    }
    .confirm-htitle { position:relative; z-index:1; }
    .confirm-htitle h3 { font-size:1.05rem; font-weight:700; color:#fff; margin:0; }
    .confirm-htitle p  { font-size:0.78rem; color:rgba(255,255,255,0.7); margin:3px 0 0; }

    .confirm-body { padding:22px 24px 18px; }
    .confirm-desc {
        font-size:0.875rem; color:var(--gray-600); line-height:1.65;
        background:#fef2f2; border:1px solid #fecaca; border-radius:10px;
        padding:13px 15px;
    }
    .confirm-desc strong { color:var(--gray-900); }

    .confirm-footer { padding:4px 24px 22px; display:flex; gap:10px; }
    .btn-confirm-cancel {
        flex:1; height:42px; border:1.5px solid var(--gray-200); border-radius:10px;
        font-family:'Plus Jakarta Sans',Inter,sans-serif; font-size:0.85rem; font-weight:600;
        color:var(--gray-600); background:white; cursor:pointer; transition:all .15s ease;
    }
    .btn-confirm-cancel:hover { background:var(--gray-50); border-color:var(--gray-300); }
    .btn-confirm-delete {
        flex:1; height:42px; border:none; border-radius:10px;
        font-family:'Plus Jakarta Sans',Inter,sans-serif; font-size:0.85rem; font-weight:700;
        color:white; background:linear-gradient(135deg,#dc2626,#ef4444); cursor:pointer;
        box-shadow:0 4px 14px rgba(220,38,38,.35); transition:all .2s ease;
        display:inline-flex; align-items:center; justify-content:center; gap:7px;
    }
    .btn-confirm-delete:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(220,38,38,.45); }
    .btn-confirm-delete:active { transform:translateY(0); }
</style>

{{-- ═══ HEADER ═══ --}}
<div class="page-header">
    <div class="page-title">
        <h1>Kelola Produk</h1>
        <p>Atur inventaris, harga sewa, dan kondisi produk.</p>
    </div>
    @php
        $currentAdmin = \App\Models\Admin::where('email', Auth::user()->email)
                        ->where('status', 1)->where('is_deleted', 0)->first();
        $isSuperadmin = $currentAdmin && $currentAdmin->role === 'superadmin';
        $isAdminStaff = $currentAdmin && in_array($currentAdmin->role, ['admin','staff']);
        $canEdit = $currentAdmin && ($isSuperadmin || $isAdminStaff || $currentAdmin->can_edit == 1);
    @endphp
    @if($canEdit)
    <button class="btn-add" onclick="openAddModal()">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Tambah Produk
    </button>
    @endif
</div>

@if (session('success'))
<div class="alert-success">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22,4 12,14.01 9,11.01"/>
    </svg>
    {{ session('success') }}
</div>
@endif

{{-- ═══ STAT CARDS ═══ --}}
@php
    $totalProduk  = $produks->count();
    $stockOk      = $produks->where('stock', '>', 10)->count();
    $stockMenipis = $produks->where('stock', '>', 0)->where('stock', '<=', 10)->count();
    $stockHabis   = $produks->where('stock', '<=', 0)->count();
@endphp
<div class="stats-grid">
    <div class="stat-card blue">
        <div class="stat-icon blue">
            <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                <line x1="3" y1="6" x2="21" y2="6"/>
                <path d="M16 10a4 4 0 0 1-8 0"/>
            </svg>
        </div>
        <div>
            <div class="stat-label">Total Produk</div>
            <div class="stat-value">{{ $totalProduk }}</div>
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
            <div class="stat-label">Stok Aman</div>
            <div class="stat-value" style="color:#059669;">{{ $stockOk }}</div>
        </div>
    </div>
    <div class="stat-card amber">
        <div class="stat-icon amber">
            <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>
        </div>
        <div>
            <div class="stat-label">Stok Menipis</div>
            <div class="stat-value" style="color:#D97706;">{{ $stockMenipis }}</div>
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
            <div class="stat-label">Stok Habis</div>
            <div class="stat-value" style="color:#DC2626;">{{ $stockHabis }}</div>
        </div>
    </div>
</div>

{{-- ═══ TABLE ═══ --}}
<div class="table-container">
    <div class="table-toolbar">
        <div style="display:flex;align-items:center;">
            <span class="table-label">Inventaris Produk</span>
            <span class="count-badge">{{ $totalProduk }}</span>
        </div>
        <div class="search-wrap">
            <svg width="14" height="14" fill="none" stroke="#9CA3AF" stroke-width="2.2" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="text" id="searchInput" placeholder="Cari produk..." onkeyup="filterTable()">
        </div>
    </div>

    <div class="table-scroll">
    <table id="productTable">
        <thead>
            <tr>
                <th class="freeze-1">Aksi</th>
                <th class="freeze-2 sortable" data-col="1" data-type="text">
                    <span class="th-inner">Foto &amp; Nama Produk
                        <span class="sort-icon">
                            <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                            <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                        </span>
                        <span class="sort-badge" id="badge-1"></span>
                    </span>
                </th>
                <th class="sortable" data-col="2" data-type="text">
                    <span class="th-inner">Kategori
                        <span class="sort-icon">
                            <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                            <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                        </span>
                        <span class="sort-badge" id="badge-2"></span>
                    </span>
                </th>
                <th class="sortable" data-col="3" data-type="number">
                    <span class="th-inner">Harga / Hari
                        <span class="sort-icon">
                            <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                            <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                        </span>
                        <span class="sort-badge" id="badge-3"></span>
                    </span>
                </th>
                <th class="sortable" data-col="4" data-type="number" style="min-width:80px">
                    <span class="th-inner">Stok
                        <span class="sort-icon">
                            <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                            <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                        </span>
                        <span class="sort-badge" id="badge-4"></span>
                    </span>
                </th>
                <th class="sortable" data-col="5" data-type="text">
                    <span class="th-inner">Kondisi
                        <span class="sort-icon">
                            <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                            <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                        </span>
                        <span class="sort-badge" id="badge-5"></span>
                    </span>
                </th>
                <th class="sortable" data-col="6" data-type="number" style="min-width:110px">
                    <span class="th-inner">Min. Sewa
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
            @forelse ($produks as $produk)
            @php
                $sc = $produk->stock <= 0 ? 'stock-low' : ($produk->stock <= 10 ? 'stock-med' : 'stock-high');
                $cc = match(strtolower($produk->condition ?? '')) {
                    'new'       => 'cond-new',
                    'excellent' => 'cond-excellent',
                    'good'      => 'cond-good',
                    'fair'      => 'cond-fair',
                    'poor'      => 'cond-poor',
                    default     => 'cond-good',
                };
            @endphp
            <tr class="product-row">
                <td class="freeze-1">
                    <div class="action-wrap">
                        @if($canEdit)
                        <a href="{{ route('produks.edit', $produk->id) }}" class="action-btn btn-edit">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                            Edit
                        </a>
                        <button class="action-btn btn-delete"
                            onclick="openDeleteModal({{ $produk->id }}, '{{ addslashes($produk->product_name) }}')">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <polyline points="3,6 5,6 21,6"/>
                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                <path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/>
                            </svg>
                            Hapus
                        </button>
                        @else
                        <span style="font-size:12px;color:var(--gray-400);font-family:Inter,sans-serif;font-style:italic;">View Only</span>
                        @endif
                    </div>
                </td>
                <td class="freeze-2">
                    <div class="product-cell">
                        @if($produk->photo)
                            <div class="product-img-wrap" onclick="openImageModal(
                                '{{ asset('products/' . $produk->photo) }}',
                                '{{ addslashes($produk->product_name) }}',
                                '{{ addslashes($produk->description ?? '') }}',
                                {{ $produk->stock }},
                                '{{ $produk->condition }}',
                                '{{ $produk->kategori->category_name ?? 'Tanpa Kategori' }}'
                            )">
                                <img src="{{ asset('products/' . $produk->photo) }}" alt="{{ $produk->product_name }}" class="product-img">
                            </div>
                        @else
                            <div class="product-img-placeholder">
                                <svg width="18" height="18" fill="none" stroke="#9CA3AF" stroke-width="1.5" viewBox="0 0 24 24">
                                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                                    <circle cx="8.5" cy="8.5" r="1.5"/>
                                    <polyline points="21,15 16,10 5,21"/>
                                </svg>
                            </div>
                        @endif
                        <span class="product-name">{{ $produk->product_name }}</span>
                    </div>
                </td>
                <td>
                    <span class="cat-pill">
                        <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/>
                            <line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/>
                        </svg>
                        {{ $produk->kategori->category_name ?? '-' }}
                    </span>
                </td>
                <td>
                    <div class="price-text">
                        Rp {{ number_format($produk->rental_price, 0, ',', '.') }}
                        <span class="price-unit">/hari</span>
                    </div>
                </td>
                <td><span class="stock-badge {{ $sc }}">{{ $produk->stock }}</span></td>
                <td>
                    <span class="cond-badge {{ $cc }}">
                        <span class="cond-badge-dot"></span>
                        {{ $produk->condition }}
                    </span>
                </td>
                <td>
                    <span class="min-sewa-badge">
                        <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        {{ $produk->min_rental_days }} hari
                    </span>
                </td>
                <td class="audit-cell">
                    @if($produk->created_by)
                        <div class="audit-name">{{ $produk->created_by }}</div>
                    @else
                        <span class="audit-empty">—</span>
                    @endif
                </td>
                <td class="audit-cell">
                    @if($produk->created_date)
                        <div class="audit-name">{{ \Carbon\Carbon::parse($produk->created_date)->format('d M Y') }}</div>
                        <div class="audit-date">{{ \Carbon\Carbon::parse($produk->created_date)->format('H:i') }}</div>
                    @else
                        <span class="audit-empty">—</span>
                    @endif
                </td>
                <td class="audit-cell">
                    @if($produk->last_updated_by)
                        <div class="audit-name">{{ $produk->last_updated_by }}</div>
                    @else
                        <span class="audit-empty">—</span>
                    @endif
                </td>
                <td class="audit-cell">
                    @if($produk->last_updated_date)
                        <div class="audit-name">{{ \Carbon\Carbon::parse($produk->last_updated_date)->format('d M Y') }}</div>
                        <div class="audit-date">{{ \Carbon\Carbon::parse($produk->last_updated_date)->format('H:i') }}</div>
                    @else
                        <span class="audit-empty">—</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="11">
                <div class="empty-state">
                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <path d="M16 10a4 4 0 0 1-8 0"/>
                    </svg>
                    <p>Belum ada produk.</p>
                </div>
            </td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>

{{-- ═══ MODAL IMAGE POPUP — unchanged ═══ --}}
<div id="imageModal" class="img-modal-overlay">
    <div class="img-modal-backdrop" onclick="closeImageModal()"></div>
    <div class="img-modal-box">
        <div class="img-modal-header">
            <h3>Detail Produk</h3>
            <button class="img-modal-close" onclick="closeImageModal()">✕</button>
        </div>
        <div class="img-modal-body">
            <div class="img-modal-image-wrap">
                <img id="modalImage" src="" alt="" class="img-modal-image">
            </div>
            <div class="img-modal-content">
                <h4 class="img-modal-product-name" id="modalProductName"></h4>
                <p class="img-modal-product-desc" id="modalProductDesc"></p>
                <div class="img-modal-meta">
                    <div class="img-modal-meta-item">
                        <span class="img-modal-meta-label">Kategori</span>
                        <span class="img-modal-meta-value" id="modalCategory"></span>
                    </div>
                    <div class="img-modal-meta-item">
                        <span class="img-modal-meta-label">Stok Tersedia</span>
                        <span id="modalStock"></span>
                    </div>
                    <div class="img-modal-meta-item" style="grid-column:span 2;">
                        <span class="img-modal-meta-label">Kondisi Produk</span>
                        <span id="modalKondisi"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ═══ MODAL TAMBAH — redesigned ═══ --}}
<div class="modal-overlay" id="modalAdd">
    <div class="modal-backdrop" onclick="closeModal('modalAdd')"></div>
    <div class="modal-box">

        {{-- Header gradient --}}
        <div class="modal-header">
            <div class="modal-header-inner">
                <button class="modal-hclose" onclick="closeModal('modalAdd')">✕</button>
                <div class="modal-hicon">
                    <svg width="20" height="20" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/>
                        <path d="M16 3H8a2 2 0 00-2 2v2h12V5a2 2 0 00-2-2z"/>
                    </svg>
                </div>
                <div class="modal-htitle">
                    <h2>Tambah Produk Baru</h2>
                    <p>Tambahkan item baru ke katalog rental. Kolom bertanda * wajib diisi.</p>
                </div>
            </div>
        </div>

        <form action="{{ route('produks.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">

                {{-- Informasi Produk --}}
                <div class="modal-section">
                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                    Informasi Produk
                </div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label>Nama Produk <span class="req">*</span></label>
                        <div class="fg-wrap">
                            <span class="fg-ico"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/></svg></span>
                            <input type="text" name="product_name" placeholder="cth. Yamaha NMAX" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Kategori <span class="req">*</span></label>
                        <div class="fg-wrap select-wrap">
                            <span class="fg-ico"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h8M4 18h16"/></svg></span>
                            <select name="category_id" required>
                                <option value="" disabled selected>Pilih Kategori</option>
                                @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}">{{ $kategori->category_name }}</option>
                                @endforeach
                            </select>
                            <span class="select-arrow">▼</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <div class="fg-wrap">
                        <span class="fg-ico-top"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg></span>
                        <textarea name="description" rows="3" placeholder="Deskripsi detail produk..."></textarea>
                    </div>
                </div>

                <div class="form-divider"></div>

                {{-- Harga & Stok --}}
                <div class="modal-section">
                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                    Harga &amp; Stok
                </div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label>Stok Tersedia <span class="req">*</span></label>
                        <div class="fg-wrap">
                            <span class="fg-ico"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16"/><path d="M1 21h22"/></svg></span>
                            <input type="number" name="stock" placeholder="10" required min="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Harga Sewa / Hari <span class="req">*</span></label>
                        <div class="fg-wrap">
                            <span class="fg-ico"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg></span>
                            <input type="number" name="rental_price" placeholder="200000" required min="0">
                        </div>
                        <div class="form-hint">dalam Rupiah (Rp)</div>
                    </div>
                    <div class="form-group">
                        <label>Kondisi <span class="req">*</span></label>
                        <div class="fg-wrap select-wrap">
                            <span class="fg-ico"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></span>
                            <select name="condition" required>
                                <option value="" disabled selected>Pilih Kondisi</option>
                                <option value="New">New</option>
                                <option value="Excellent">Excellent</option>
                                <option value="Good">Good</option>
                                <option value="Fair">Fair</option>
                                <option value="Poor">Poor</option>
                            </select>
                            <span class="select-arrow">▼</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Minimal Hari Sewa</label>
                        <div class="fg-wrap">
                            <span class="fg-ico"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg></span>
                            <input type="number" name="min_rental_days" placeholder="1" value="1" min="1">
                        </div>
                        <div class="form-hint">hari minimum peminjaman</div>
                    </div>
                </div>

                <div class="form-divider"></div>

                {{-- Foto & Status --}}
                <div class="modal-section">
                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21,15 16,10 5,21"/></svg>
                    Foto &amp; Status
                </div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label>Foto Produk</label>
                        <div class="fg-wrap">
                            <span class="fg-ico"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg></span>
                            <input type="file" name="photo" accept="image/*">
                        </div>
                        <div class="form-hint">Format: JPG, PNG. Maks 2MB.</div>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <div class="fg-wrap select-wrap">
                            <span class="fg-ico"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg></span>
                            <select name="status">
                                <option value="1">✓ Aktif</option>
                                <option value="0">✗ Nonaktif</option>
                            </select>
                            <span class="select-arrow">▼</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal('modalAdd')">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
                    Batal
                </button>
                <button type="submit" class="btn-save">
                    <svg width="14" height="14" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Simpan Produk
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ═══ MODAL DELETE — redesigned ═══ --}}
<div class="modal-overlay" id="modalDelete">
    <div class="modal-backdrop" onclick="closeModal('modalDelete')"></div>
    <div class="confirm-box">

        {{-- Red gradient header --}}
        <div class="confirm-header">
            <button class="confirm-hclose" onclick="closeModal('modalDelete')">✕</button>
            <div class="confirm-hicon">
                <svg width="22" height="22" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                    <polyline points="3,6 5,6 21,6"/>
                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                    <path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/>
                </svg>
            </div>
            <div class="confirm-htitle">
                <h3>Hapus Produk</h3>
                <p>Tindakan ini tidak dapat dibatalkan</p>
            </div>
        </div>

        <div class="confirm-body">
            <p class="confirm-desc">
                Produk <strong id="deleteProductName"></strong> akan dihapus secara permanen dari sistem dan tidak dapat dipulihkan kembali.
            </p>
        </div>

        <div class="confirm-footer">
            <button class="btn-confirm-cancel" onclick="closeModal('modalDelete')">Batal</button>
            <button class="btn-confirm-delete" onclick="executeDelete()">
                <svg width="14" height="14" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24">
                    <polyline points="3,6 5,6 21,6"/>
                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                    <path d="M10 11v6"/><path d="M14 11v6"/>
                </svg>
                Ya, Hapus
            </button>
        </div>
    </div>
</div>

<form id="deleteForm" method="POST" action="" style="display:none">
    @csrf @method('DELETE')
</form>

<script>
    /* ── Search ──────────────────────────────────────── */
    function filterTable() {
        const q = document.getElementById('searchInput').value.toLowerCase();
        document.querySelectorAll('.product-row').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(q) ? '' : 'none';
        });
    }

    /* ── Sort ────────────────────────────────────────── */
    const COL_TYPES = {
        1:'text', 2:'text', 3:'number', 4:'number',
        5:'text', 6:'number', 7:'text', 8:'date', 9:'text', 10:'date'
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
                th.classList.remove('sort-active','asc','desc');
                if (badge) badge.textContent = '';
            }
        });
    }

    function parseDate(str) {
        if (!str || str === '—' || str === '-') return 0;
        const months = {Jan:0,Feb:1,Mar:2,Apr:3,May:4,Jun:5,Jul:6,Aug:7,Sep:8,Oct:9,Nov:10,Dec:11};
        const m = str.match(/(\d{1,2})\s+(\w{3})\s+(\d{4})/);
        if (!m) return 0;
        return new Date(+m[3], months[m[2]] || 0, +m[1]).getTime();
    }

    function parseVal(text, type) {
        if (type === 'number') {
            const clean = text.replace(/Rp/gi,'').replace(/\./g,'').replace(/hari/gi,'').replace(/,/g,'.').trim();
            return parseFloat(clean) || 0;
        }
        if (type === 'date') return parseDate(text.trim());
        return text.trim().toLowerCase();
    }

    function sortTable(col, dir) {
        const tbody = document.querySelector('#productTable tbody');
        const rows  = Array.from(tbody.querySelectorAll('tr')).filter(r => r.cells.length > 1);
        const type  = COL_TYPES[col] || 'text';
        rows.sort((a, b) => {
            const aV = parseVal(a.cells[col]?.innerText ?? '', type);
            const bV = parseVal(b.cells[col]?.innerText ?? '', type);
            if (aV < bV) return dir === 'asc' ? -1 : 1;
            if (aV > bV) return dir === 'asc' ?  1 : -1;
            return 0;
        });
        rows.forEach(row => tbody.appendChild(row));
    }

    /* ── Image Modal ─────────────────────────────────── */
    function openImageModal(imgSrc, name, desc, stock, kondisi, category) {
        document.getElementById('modalImage').src = imgSrc;
        document.getElementById('modalImage').alt = name;
        document.getElementById('modalProductName').textContent = name;
        const descEl = document.getElementById('modalProductDesc');
        if (desc && desc.trim() !== '') {
            descEl.textContent = desc;
            descEl.classList.remove('empty');
        } else {
            descEl.textContent = 'Tidak ada deskripsi untuk produk ini.';
            descEl.classList.add('empty');
        }
        document.getElementById('modalCategory').textContent = category;
        const stockEl = document.getElementById('modalStock');
        let stockClass = 'safe';
        if (stock === 0) stockClass = 'empty';
        else if (stock <= 10) stockClass = 'warning';
        stockEl.innerHTML = `<span class="stock-badge-modal ${stockClass}">${stock} Unit</span>`;
        const kondisiEl = document.getElementById('modalKondisi');
        const kondisiMap = {
            'New': { class: 'new', label: 'New' },
            'Excellent': { class: 'excellent', label: 'Excellent' },
            'Good': { class: 'good', label: 'Good' },
            'Fair': { class: 'fair', label: 'Fair' },
            'Poor': { class: 'poor', label: 'Poor' }
        };
        const kondisiData = kondisiMap[kondisi] || { class: 'good', label: kondisi };
        kondisiEl.innerHTML = `<span class="kondisi-badge-modal ${kondisiData.class}"><span class="dot"></span>${kondisiData.label}</span>`;
        document.getElementById('imageModal').classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.remove('show');
        document.body.style.overflow = '';
    }

    /* ── Modals ──────────────────────────────────────── */
    function openAddModal()  { document.getElementById('modalAdd').classList.add('show'); document.body.style.overflow = 'hidden'; }
    function closeModal(id)  { document.getElementById(id).classList.remove('show'); document.body.style.overflow = ''; }

    function openDeleteModal(id, name) {
        document.getElementById('deleteProductName').textContent = name;
        document.getElementById('deleteForm').action = '/produk/delete/' + id;
        document.getElementById('modalDelete').classList.add('show');
        document.body.style.overflow = 'hidden';
    }
    function executeDelete() { document.getElementById('deleteForm').submit(); }

    /* ── Close on Escape ─────────────────────────────── */
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            closeImageModal();
            ['modalAdd','modalDelete'].forEach(id => closeModal(id));
        }
    });

    document.querySelector('.img-modal-box')?.addEventListener('click', function(e) {
        e.stopPropagation();
    });
</script>

@endsection