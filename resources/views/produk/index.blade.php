@extends('layouts.app')

@section('content')

<style>
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

    .alert-success {
        background:#ECFDF5; border:1px solid #6EE7B7; border-radius:10px;
        padding:11px 16px; font-family:Inter,sans-serif; font-size:13px; color:#065F46;
        margin-bottom:18px; display:flex; align-items:center; gap:8px;
    }

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

    table { width:100%; border-collapse:collapse; }
    thead tr { background:#F8FAFC; }
    thead th {
        font-family:Inter,sans-serif; font-size:11px; font-weight:700; color:#94A3B8;
        letter-spacing:.06em; text-transform:uppercase; padding:11px 16px; text-align:left; white-space:nowrap;
    }
    tbody tr { border-bottom:1px solid #F1F5F9; transition:background .1s; }
    tbody tr:last-child { border-bottom:none; }
    tbody tr:hover { background:#F8FAFC; }
    tbody td { font-family:Inter,sans-serif; font-size:13.5px; color:#1E1E1E; padding:12px 16px; vertical-align:middle; }

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

    .product-cell { display:flex; align-items:center; gap:12px; min-width:200px; }
    .product-img { width:46px; height:46px; border-radius:10px; object-fit:cover; border:1px solid #E5E7EB; flex-shrink:0; }
    .product-img-placeholder {
        width:46px; height:46px; border-radius:10px; background:#F3F4F6;
        border:1px solid #E5E7EB; display:flex; align-items:center; justify-content:center; flex-shrink:0;
    }
    .product-name { font-weight:600; color:#0F172A; font-size:13.5px; }

    .cat-pill {
        display:inline-flex; align-items:center; gap:5px; background:#EFF6FF; color:#2D4DA3;
        border-radius:20px; padding:3px 10px; font-size:12px; font-weight:600;
        font-family:Inter,sans-serif; white-space:nowrap;
    }

    .price-text { font-weight:700; color:#0F172A; font-size:13px; white-space:nowrap; }
    .price-unit { font-size:11px; color:#94A3B8; font-weight:400; }

    .stock-badge {
        display:inline-flex; align-items:center; justify-content:center;
        width:30px; height:30px; border-radius:50%;
        font-family:Inter,sans-serif; font-size:12px; font-weight:700; color:white;
    }
    .stock-high   { background:#22C55E; }
    .stock-medium { background:#F59E0B; }
    .stock-low    { background:#EF4444; }

    .cond-badge {
        display:inline-flex; align-items:center; border-radius:20px; padding:3px 10px;
        font-size:12px; font-weight:600; font-family:Inter,sans-serif; border:1px solid; white-space:nowrap;
    }
    .cond-new       { color:#2563EB; border-color:#93C5FD; background:#EFF6FF; }
    .cond-excellent { color:#059669; border-color:#6EE7B7; background:#ECFDF5; }
    .cond-good      { color:#16A34A; border-color:#86EFAC; background:#F0FDF4; }
    .cond-fair      { color:#D97706; border-color:#FCD34D; background:#FFFBEB; }
    .cond-poor      { color:#DC2626; border-color:#FCA5A5; background:#FEF2F2; }

    .action-wrap { display:flex; gap:6px; }
    .action-btn {
        height:32px; padding:0 12px; border-radius:8px; font-family:Inter,sans-serif;
        font-size:12px; font-weight:600; cursor:pointer; display:inline-flex; align-items:center;
        gap:5px; transition:all .15s; text-decoration:none;
    }
    .btn-edit   { background:#EFF6FF; color:#2D4DA3; border:1px solid #BFDBFE; }
    .btn-edit:hover { background:#DBEAFE; color:#2D4DA3; }
    .btn-delete { background:#FEF2F2; color:#DC2626; border:1px solid #FECACA; }
    .btn-delete:hover { background:#FEE2E2; }
    .action-btn svg { width:13px; height:13px; }

    .empty-state { text-align:center; padding:56px 0; color:#94A3B8; }
    .empty-state svg { width:44px; height:44px; margin-bottom:12px; opacity:.3; }
    .empty-state p { font-size:14px; margin:0; font-family:Inter,sans-serif; }

    /* ── Modals ── */
    .modal-overlay { display:none; position:fixed; inset:0; z-index:999; align-items:center; justify-content:center; padding:20px; }
    .modal-overlay.show { display:flex; }
    .modal-backdrop { position:fixed; inset:0; background:rgba(15,23,42,.5); backdrop-filter:blur(3px); }

    .form-modal {
        position:relative; z-index:1; background:#FFF; border-radius:16px;
        width:100%; max-width:640px; max-height:90vh; overflow-y:auto;
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
    .modal-section-title {
        font-family:Inter,sans-serif; font-size:11px; font-weight:700; color:#94A3B8;
        text-transform:uppercase; letter-spacing:.06em; margin:18px 0 12px;
    }
    .modal-section-title:first-child { margin-top:0; }
    .form-divider { height:1px; background:#F1F5F9; margin:16px 0; }
    .form-grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
    .form-group { margin-bottom:14px; }
    .form-group:last-child { margin-bottom:0; }
    .form-group label {
        display:block; font-family:Inter,sans-serif; font-size:12px; font-weight:600;
        color:#374151; margin-bottom:6px; text-transform:uppercase; letter-spacing:.04em;
    }
    .form-group input,.form-group textarea,.form-group select {
        width:100%; border:1px solid #E2E8F0; border-radius:9px; padding:10px 13px;
        font-family:Inter,sans-serif; font-size:13.5px; color:#0F172A; outline:none;
        box-sizing:border-box; background:#FAFAFA; transition:border-color .15s,background .15s;
    }
    .form-group input:focus,.form-group textarea:focus,.form-group select:focus {
        border-color:#2D4DA3; background:#fff; box-shadow:0 0 0 3px rgba(45,77,163,.08);
    }
    .form-group textarea { resize:vertical; min-height:80px; }
    .form-hint { font-family:Inter,sans-serif; font-size:11px; color:#94A3B8; margin-top:4px; }
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
        cursor:pointer; transition:background .15s; box-shadow:0 2px 6px rgba(45,77,163,.2);
    }
    .btn-save:hover { background:#253f8a; }

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
    .confirm-title { font-family:Inter,sans-serif; font-size:16px; font-weight:700; color:#0F172A; margin-bottom:8px; }
    .confirm-desc { font-family:Inter,sans-serif; font-size:13px; color:#64748B; line-height:1.6; }
    .confirm-footer { padding:14px 24px 20px; display:flex; gap:10px; }
    .btn-confirm-cancel {
        flex:1; height:40px; border:1.5px solid #E2E8F0; border-radius:11px;
        font-family:Inter,sans-serif; font-size:13px; font-weight:600; color:#64748B;
        background:white; cursor:pointer; transition:background .15s;
    }
    .btn-confirm-cancel:hover { background:#F8FAFC; }
    .btn-confirm-delete {
        flex:1; height:40px; border:none; border-radius:11px;
        font-family:Inter,sans-serif; font-size:13px; font-weight:700; color:white;
        background:#EF4444; cursor:pointer; transition:background .15s;
        box-shadow:0 2px 8px rgba(239,68,68,.25);
    }
    .btn-confirm-delete:hover { background:#DC2626; }
</style>

{{-- Header --}}
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
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22,4 12,14.01 9,11.01"/>
        </svg>
        {{ session('success') }}
    </div>
@endif

{{-- Table --}}
<div class="table-container">
    <div class="table-toolbar">
        <div style="display:flex;align-items:center;">
            <span class="table-label">Inventaris Produk</span>
            <span class="count-badge">{{ $produks->count() }}</span>
        </div>
        <div class="search-wrap">
            <svg width="14" height="14" fill="none" stroke="#9E9E9E" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="text" id="searchInput" placeholder="Cari produk...">
        </div>
    </div>

    <table id="productTable">
        <thead>
            <tr>
                <th style="width:140px">Aksi</th>
                <th class="sortable" data-col="1" data-type="text">
                    <span class="th-inner">Foto & Nama Produk
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
                <th class="sortable" data-col="4" data-type="number" style="width:70px">
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
                <th class="sortable" data-col="6" data-type="number" style="width:100px">
                    <span class="th-inner">Min. Sewa
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
            @forelse ($produks as $produk)
            <tr class="product-row">
                <td>
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
                                <path d="M10 11v6"/><path d="M14 11v6"/>
                                <path d="M9 6V4h6v2"/>
                            </svg>
                            Hapus
                        </button>
                        @else
                        <span style="font-size:12px;color:#94A3B8;font-family:Inter,sans-serif;">View Only</span>
                        @endif
                    </div>
                </td>
                <td>
                    <div class="product-cell">
                        @if($produk->photo)
                            <img src="{{ asset('products/' . $produk->photo) }}" alt="{{ $produk->product_name }}" class="product-img">
                        @else
                            <div class="product-img-placeholder">
                                <svg width="20" height="20" fill="none" stroke="#9CA3AF" stroke-width="1.5" viewBox="0 0 24 24">
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
                        <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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
                <td>
                    @php $sc = $produk->stock <= 5 ? 'stock-low' : ($produk->stock <= 10 ? 'stock-medium' : 'stock-high'); @endphp
                    <span class="stock-badge {{ $sc }}">{{ $produk->stock }}</span>
                </td>
                <td>
                    @php
                        $cc = match(strtolower($produk->condition)) {
                            'new'       => 'cond-new',
                            'excellent' => 'cond-excellent',
                            'good'      => 'cond-good',
                            'fair'      => 'cond-fair',
                            'poor'      => 'cond-poor',
                            default     => 'cond-good',
                        };
                    @endphp
                    <span class="cond-badge {{ $cc }}">{{ $produk->condition }}</span>
                </td>
                <td><span style="font-family:Inter,sans-serif;font-size:13px;color:#64748B;">{{ $produk->min_rental_days }} hari</span></td>
            </tr>
            @empty
            <tr><td colspan="7">
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

{{-- ── MODAL TAMBAH ── --}}
<div class="modal-overlay" id="modalAdd">
    <div class="modal-backdrop" onclick="closeModal('modalAdd')"></div>
    <div class="form-modal">
        <div class="modal-header">
            <div>
                <h2>Tambah Produk Baru</h2>
                <p>Kolom bertanda * wajib diisi.</p>
            </div>
            <button class="modal-close" onclick="closeModal('modalAdd')">✕</button>
        </div>
        <form action="{{ route('produks.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="modal-section-title">Informasi Produk</div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label>Nama Produk <span style="color:#EF4444">*</span></label>
                        <input type="text" name="product_name" placeholder="cth. Yamaha NMAX" required>
                    </div>
                    <div class="form-group">
                        <label>Kategori <span style="color:#EF4444">*</span></label>
                        <select name="category_id" required>
                            <option value="" disabled selected>Pilih Kategori</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id }}">{{ $kategori->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="description" placeholder="Deskripsi detail produk..."></textarea>
                </div>

                <div class="form-divider"></div>
                <div class="modal-section-title">Harga & Stok</div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label>Stok Tersedia <span style="color:#EF4444">*</span></label>
                        <input type="number" name="stock" placeholder="10" required min="0">
                    </div>
                    <div class="form-group">
                        <label>Harga Sewa / Hari <span style="color:#EF4444">*</span></label>
                        <input type="number" name="rental_price" placeholder="200000" required min="0">
                    </div>
                </div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label>Kondisi <span style="color:#EF4444">*</span></label>
                        <select name="condition" required>
                            <option value="" disabled selected>Pilih Kondisi</option>
                            <option value="New">New</option>
                            <option value="Excellent">Excellent</option>
                            <option value="Good">Good</option>
                            <option value="Fair">Fair</option>
                            <option value="Poor">Poor</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Minimal Hari Sewa</label>
                        <input type="number" name="min_rental_days" placeholder="1" value="1" min="1">
                    </div>
                </div>

                <div class="form-divider"></div>
                <div class="modal-section-title">Foto & Status</div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label>Foto Produk</label>
                        <input type="file" name="photo" accept="image/*">
                        <div class="form-hint">Format: JPG, PNG. Maks 2MB.</div>
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
                <button type="submit" class="btn-save">Simpan Produk</button>
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
            <div class="confirm-subtitle">Hapus Produk</div>
            <div class="confirm-title">Yakin ingin menghapus?</div>
            <p class="confirm-desc">
                Produk <strong id="deleteProductName" style="color:#0F172A"></strong> akan dihapus dan tidak bisa dipulihkan.
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
    // ── Search ──────────────────────────────────────────────────────────────
    document.getElementById('searchInput').addEventListener('keyup', function () {
        const q = this.value.toLowerCase();
        document.querySelectorAll('.product-row').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(q) ? '' : 'none';
        });
    });

    // ── Sort ────────────────────────────────────────────────────────────────
    const COL_TYPES = {
        1: 'text',    // Foto & Nama Produk
        2: 'text',    // Kategori
        3: 'number',  // Harga / Hari  → strip "Rp" dan titik
        4: 'number',  // Stok
        5: 'text',    // Kondisi
        6: 'number',  // Min. Sewa → strip " hari"
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
                    if (type === 'number') {
                        badge.textContent = sortDir === 'asc' ? '0→9' : '9→0';
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

    function parseVal(text, type) {
        if (type === 'number') {
            // Strip "Rp", titik ribuan, " hari", spasi → ambil angka
            const clean = text.replace(/Rp/gi, '').replace(/\./g, '').replace(/hari/gi, '').replace(/,/g, '.').trim();
            return parseFloat(clean) || 0;
        }
        return text.trim().toLowerCase();
    }

    function sortTable(col, dir) {
        const tbody = document.querySelector('#productTable tbody');
        const rows  = Array.from(tbody.querySelectorAll('tr')).filter(r => r.cells.length > 1);
        const type  = COL_TYPES[col] || 'text';

        rows.sort((a, b) => {
            const aVal = parseVal(a.cells[col]?.innerText ?? '', type);
            const bVal = parseVal(b.cells[col]?.innerText ?? '', type);
            if (aVal < bVal) return dir === 'asc' ? -1 : 1;
            if (aVal > bVal) return dir === 'asc' ?  1 : -1;
            return 0;
        });

        rows.forEach(row => tbody.appendChild(row));
    }

    // ── Modals ──────────────────────────────────────────────────────────────
    function openAddModal()  { document.getElementById('modalAdd').classList.add('show'); }
    function closeModal(id)  { document.getElementById(id).classList.remove('show'); }

    function openDeleteModal(id, name) {
        document.getElementById('deleteProductName').textContent = name;
        document.getElementById('deleteForm').action = '/produk/delete/' + id;
        document.getElementById('modalDelete').classList.add('show');
    }
    function executeDelete() { document.getElementById('deleteForm').submit(); }
</script>

@endsection