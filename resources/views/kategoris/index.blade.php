@extends('layouts.app')

@section('content')

<style>
    /* ── Page Header ── */
    .page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 24px;
    }
    .page-title h1 {
        font-family: Inter, sans-serif;
        font-size: 22px;
        font-weight: 700;
        color: #1E1E1E;
        margin: 0;
    }
    .page-title p {
        font-family: Inter, sans-serif;
        font-size: 13px;
        color: #6B6B6B;
        margin: 4px 0 0 0;
    }
    .btn-add {
        height: 40px;
        padding: 0 18px;
        background: #2D4DA3;
        color: #FFFFFF;
        border: none;
        border-radius: 10px;
        font-family: Inter, sans-serif;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 7px;
        text-decoration: none;
        white-space: nowrap;
        box-shadow: 0 2px 8px rgba(45,77,163,0.18);
        transition: background 0.15s;
    }
    .btn-add:hover { background: #253f8a; color: #fff; }

    /* ── Alert ── */
    .alert-success {
        background: #ECFDF5;
        border: 1px solid #6EE7B7;
        border-radius: 10px;
        padding: 11px 16px;
        font-family: Inter, sans-serif;
        font-size: 13px;
        color: #065F46;
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* ── Table Container ── */
    .table-container {
        background: #FFFFFF;
        border-radius: 14px;
        padding: 22px;
        box-shadow: 0px 2px 12px rgba(0,0,0,0.07);
    }
    .table-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 18px;
    }
    .table-label {
        font-family: Inter, sans-serif;
        font-size: 14px;
        font-weight: 600;
        color: #1E1E1E;
    }
    .count-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #EFF6FF;
        color: #2D4DA3;
        border-radius: 20px;
        padding: 2px 10px;
        font-size: 12px;
        font-weight: 700;
        margin-left: 8px;
        font-family: Inter, sans-serif;
    }
    .search-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
        border: 1px solid #E5E5E5;
        border-radius: 9px;
        padding: 0 12px;
        height: 38px;
        background: #FAFAFA;
        width: 220px;
        transition: border-color 0.15s;
    }
    .search-wrap:focus-within { border-color: #2D4DA3; background: #fff; }
    .search-wrap svg { flex-shrink: 0; color: #9E9E9E; }
    .search-wrap input {
        border: none;
        outline: none;
        font-family: Inter, sans-serif;
        font-size: 13px;
        color: #1E1E1E;
        width: 100%;
        background: transparent;
    }
    .search-wrap input::placeholder { color: #B0B0B0; }

    /* ── Table ── */
    table { width: 100%; border-collapse: collapse; }
    thead tr { background: #F8FAFC; }
    thead th {
        font-family: Inter, sans-serif;
        font-size: 11px;
        font-weight: 700;
        color: #94A3B8;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        padding: 11px 16px;
        text-align: left;
        white-space: nowrap;
    }

    /* ── Sort Header ── */
    .sortable {
        cursor: pointer;
        user-select: none;
        transition: color 0.15s, background 0.15s;
    }
    .sortable:hover {
        color: #2D4DA3;
        background: #EFF6FF;
    }

    .th-inner {
        display: inline-flex;
        align-items: center;
        gap: 7px;
    }

    /* Sort icon container */
    .sort-icon {
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        gap: 2px;
        flex-shrink: 0;
    }
    .sort-icon svg {
        width: 9px;
        height: 6px;
        display: block;
        transition: fill 0.15s;
    }

    /* Default: both arrows grey */
    .sortable:not(.sort-active) .tri-up   { fill: #CBD5E1; }
    .sortable:not(.sort-active) .tri-down { fill: #CBD5E1; }
    .sortable:hover:not(.sort-active) .tri-up   { fill: #94A3B8; }
    .sortable:hover:not(.sort-active) .tri-down { fill: #94A3B8; }

    /* Active sort column */
    th.sort-active {
        color: #2D4DA3;
        background: #EFF6FF;
    }
    /* ASC: up arrow blue, down arrow light */
    th.sort-active.asc  .tri-up   { fill: #2D4DA3; }
    th.sort-active.asc  .tri-down { fill: #BFDBFE; }
    /* DESC: down arrow blue, up arrow light */
    th.sort-active.desc .tri-up   { fill: #BFDBFE; }
    th.sort-active.desc .tri-down { fill: #2D4DA3; }

    /* Sort label badge */
    .sort-badge {
        display: inline-flex;
        align-items: center;
        background: #2D4DA3;
        color: white;
        font-size: 9px;
        font-weight: 700;
        padding: 1px 5px;
        border-radius: 4px;
        letter-spacing: .5px;
        margin-left: 2px;
        opacity: 0;
        transition: opacity 0.15s;
    }
    th.sort-active .sort-badge { opacity: 1; }

    tbody tr {
        border-bottom: 1px solid #F1F5F9;
        transition: background 0.1s;
    }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: #F8FAFC; }
    tbody td {
        font-family: Inter, sans-serif;
        font-size: 13.5px;
        color: #1E1E1E;
        padding: 14px 16px;
        vertical-align: middle;
    }

    /* ── Category name cell ── */
    .cat-name {
        font-weight: 600;
        color: #0F172A;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .cat-icon {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        background: #EFF6FF;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .cat-icon svg { width: 16px; height: 16px; color: #2D4DA3; }

    /* ── Badge ── */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        border-radius: 20px;
        padding: 4px 12px;
        font-family: Inter, sans-serif;
        font-size: 12px;
        font-weight: 600;
    }
    .badge.active   { background: #ECFDF5; color: #059669; border: 1px solid #6EE7B7; }
    .badge.inactive { background: #FEF2F2; color: #DC2626; border: 1px solid #FECACA; }
    .badge-dot { width: 6px; height: 6px; border-radius: 50%; display: inline-block; }
    .badge.active .badge-dot   { background: #059669; }
    .badge.inactive .badge-dot { background: #DC2626; }

    /* ── Action Buttons ── */
    .action-wrap { display: flex; gap: 6px; }
    .action-btn {
        height: 32px;
        padding: 0 12px;
        border-radius: 8px;
        font-family: Inter, sans-serif;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        border: none;
        transition: all 0.15s;
        text-decoration: none;
    }
    .btn-edit   { background: #EFF6FF; color: #2D4DA3; border: 1px solid #BFDBFE; }
    .btn-edit:hover   { background: #DBEAFE; }
    .btn-delete { background: #FEF2F2; color: #DC2626; border: 1px solid #FECACA; }
    .btn-delete:hover { background: #FEE2E2; }
    .action-btn svg { width: 13px; height: 13px; }

    /* ── Empty state ── */
    .empty-state { text-align: center; padding: 48px 0; color: #94A3B8; }
    .empty-state svg { width: 44px; height: 44px; margin-bottom: 10px; opacity: 0.35; }
    .empty-state p { font-size: 14px; margin: 0; font-family: Inter, sans-serif; }

    /* ── Modal Overlay ── */
    .modal-overlay { display: none; position: fixed; inset: 0; z-index: 999; align-items: center; justify-content: center; padding: 20px; }
    .modal-overlay.show { display: flex; }
    .modal-backdrop { position: fixed; inset: 0; background: rgba(15,23,42,0.5); backdrop-filter: blur(3px); }
    .modal-box { position: relative; z-index: 1; background: #FFFFFF; border-radius: 16px; width: 100%; max-width: 460px; box-shadow: 0px 20px 50px rgba(0,0,0,0.18); overflow: hidden; }
    .modal-header { padding: 20px 24px 16px; border-bottom: 1px solid #F1F5F9; display: flex; align-items: center; justify-content: space-between; }
    .modal-header h2 { font-family: Inter, sans-serif; font-size: 16px; font-weight: 700; color: #0F172A; margin: 0; }
    .modal-close { width: 28px; height: 28px; background: #F1F5F9; border: none; border-radius: 7px; cursor: pointer; display: flex; align-items: center; justify-content: center; color: #64748B; font-size: 14px; }
    .modal-close:hover { background: #E2E8F0; }
    .modal-body { padding: 20px 24px; }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; font-family: Inter, sans-serif; font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.04em; }
    .form-group input, .form-group textarea, .form-group select { width: 100%; border: 1px solid #E2E8F0; border-radius: 9px; padding: 10px 13px; font-family: Inter, sans-serif; font-size: 13.5px; color: #0F172A; outline: none; box-sizing: border-box; background: #FAFAFA; transition: border-color 0.15s, background 0.15s; }
    .form-group input:focus, .form-group textarea:focus, .form-group select:focus { border-color: #2D4DA3; background: #fff; box-shadow: 0 0 0 3px rgba(45,77,163,0.08); }
    .form-group textarea { resize: vertical; min-height: 80px; }
    .modal-footer { padding: 16px 24px; border-top: 1px solid #F1F5F9; display: flex; gap: 10px; justify-content: flex-end; }
    .btn-cancel { height: 38px; padding: 0 18px; background: white; border: 1px solid #E2E8F0; border-radius: 9px; font-family: Inter, sans-serif; font-size: 13px; font-weight: 500; cursor: pointer; color: #374151; }
    .btn-cancel:hover { background: #F8FAFC; }
    .btn-save { height: 38px; padding: 0 20px; background: #2D4DA3; border: none; border-radius: 9px; font-family: Inter, sans-serif; font-size: 13px; font-weight: 600; color: #FFFFFF; cursor: pointer; box-shadow: 0 2px 6px rgba(45,77,163,0.2); }
    .btn-save:hover { background: #253f8a; }

    /* ── Confirm Modal ── */
    .confirm-box { position: relative; z-index: 1; background: #FFFFFF; border-radius: 18px; width: 100%; max-width: 380px; box-shadow: 0px 25px 60px rgba(0,0,0,0.18); overflow: hidden; }
    .confirm-accent { height: 4px; width: 100%; background: #EF4444; }
    .confirm-body { padding: 24px 24px 20px; }
    .confirm-icon-wrap { width: 52px; height: 52px; border-radius: 14px; background: #FEF2F2; display: flex; align-items: center; justify-content: center; margin-bottom: 14px; }
    .confirm-icon-wrap svg { width: 24px; height: 24px; color: #EF4444; }
    .confirm-subtitle { font-family: Inter, sans-serif; font-size: 11px; font-weight: 700; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 4px; }
    .confirm-title { font-family: Inter, sans-serif; font-size: 16px; font-weight: 700; color: #0F172A; margin-bottom: 8px; }
    .confirm-desc { font-family: Inter, sans-serif; font-size: 13px; color: #64748B; line-height: 1.6; margin-bottom: 0; }
    .confirm-footer { padding: 14px 24px 20px; display: flex; gap: 10px; }
    .btn-confirm-cancel { flex: 1; height: 40px; border: 1.5px solid #E2E8F0; border-radius: 11px; font-family: Inter, sans-serif; font-size: 13px; font-weight: 600; color: #64748B; background: white; cursor: pointer; }
    .btn-confirm-cancel:hover { background: #F8FAFC; }
    .btn-confirm-delete { flex: 1; height: 40px; border: none; border-radius: 11px; font-family: Inter, sans-serif; font-size: 13px; font-weight: 700; color: white; background: #EF4444; cursor: pointer; box-shadow: 0 2px 8px rgba(239,68,68,0.25); }
    .btn-confirm-delete:hover { background: #DC2626; }
</style>

{{-- Sort icon template (reusable) --}}
<template id="sortIconTpl">
    <span class="sort-icon">
        <svg class="tri-up" viewBox="0 0 8 5" fill="none" xmlns="http://www.w3.org/2000/svg">
            <polygon points="4,0 8,5 0,5" fill="#64748B"/>
        </svg>
        <svg class="tri-down" viewBox="0 0 8 5" fill="none" xmlns="http://www.w3.org/2000/svg">
            <polygon points="0,0 8,0 4,5" fill="#64748B"/>
        </svg>
    </span>
</template>

{{-- Page Header --}}
<div class="page-header">
    <div class="page-title">
        <h1>Kelola Kategori</h1>
        <p>Tambah, edit, atau hapus kategori produk.</p>
    </div>
    @php
        $currentAdmin = \App\Models\Admin::where('email', Auth::user()->email)
                        ->where('status', 1)->where('is_deleted', 0)->first();
        $isSuperadmin = $currentAdmin && $currentAdmin->role === 'superadmin';
        $isAdminStaff = $currentAdmin && in_array($currentAdmin->role, ['admin', 'staff']);
        $canEdit = $currentAdmin && ($isSuperadmin || $isAdminStaff || $currentAdmin->can_edit == 1);
    @endphp
    @if($canEdit)
    <button class="btn-add" onclick="openAddModal()">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Tambah Kategori
    </button>
    @endif
</div>

{{-- Alert --}}
@if (session('success'))
    <div class="alert-success">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
            <polyline points="22,4 12,14.01 9,11.01"/>
        </svg>
        {{ session('success') }}
    </div>
@endif

{{-- Table --}}
<div class="table-container">
    <div class="table-toolbar">
        <div style="display:flex;align-items:center;gap:0">
            <span class="table-label">Daftar Kategori</span>
            <span class="count-badge">{{ $kategoris->count() }}</span>
        </div>
        <div class="search-wrap">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="text" placeholder="Cari kategori..." id="searchInput" onkeyup="filterTable()">
        </div>
    </div>

    <table id="kategoriTable">
        <thead>
            <tr>
                <th style="width:130px">Aksi</th>
                <th class="sortable" data-col="1">
                    <span class="th-inner">
                        Nama Kategori
                        <span class="sort-icon">
                            <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                            <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                        </span>
                        <span class="sort-badge" id="badge-1"></span>
                    </span>
                </th>
                <th class="sortable" data-col="2">
                    <span class="th-inner">
                        Deskripsi
                        <span class="sort-icon">
                            <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                            <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                        </span>
                        <span class="sort-badge" id="badge-2"></span>
                    </span>
                </th>
                <th class="sortable" data-col="3" style="width:110px">
                    <span class="th-inner">
                        Status
                        <span class="sort-icon">
                            <svg class="tri-up" viewBox="0 0 9 6"><polygon points="4.5,0 9,6 0,6"/></svg>
                            <svg class="tri-down" viewBox="0 0 9 6"><polygon points="0,0 9,0 4.5,6"/></svg>
                        </span>
                        <span class="sort-badge" id="badge-3"></span>
                    </span>
                </th>
            </tr>
        </thead>
        <tbody>
            @forelse ($kategoris as $kategori)
            <tr>
                <td>
                    <div class="action-wrap">
                        @if($canEdit)
                        <button class="action-btn btn-edit"
                            onclick="openEditModal(
                                {{ $kategori->id }},
                                '{{ addslashes($kategori->category_name) }}',
                                '{{ addslashes($kategori->description) }}',
                                '{{ $kategori->status }}'
                            )">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                            Edit
                        </button>
                        <button class="action-btn btn-delete"
                            onclick="openDeleteModal({{ $kategori->id }}, '{{ addslashes($kategori->category_name) }}')">
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
                    <div class="cat-name">
                        @php
                            $iconMap = [
                                'outdoor gear'  => 'cat_outdoor_gear.png',
                                'vehicles'      => 'cat_vehicles.png',
                                'cooking tools' => 'cat_cooking_tools.png',
                                'electronic'    => 'cat_electronic.png',
                            ];
                            $key      = strtolower(trim($kategori->category_name));
                            $iconFile = $iconMap[$key] ?? null;
                        @endphp
                        <div class="cat-icon" style="{{ $iconFile ? 'background:transparent;padding:0;overflow:hidden;' : '' }}">
                            @if($iconFile)
                                <img src="{{ asset('categories/' . $iconFile) }}"
                                     alt="{{ $kategori->category_name }}"
                                     style="width:34px;height:34px;border-radius:9px;object-fit:cover;">
                            @else
                                <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <line x1="8" y1="6" x2="21" y2="6"/>
                                    <line x1="8" y1="12" x2="21" y2="12"/>
                                    <line x1="8" y1="18" x2="21" y2="18"/>
                                    <line x1="3" y1="6" x2="3.01" y2="6"/>
                                    <line x1="3" y1="12" x2="3.01" y2="12"/>
                                    <line x1="3" y1="18" x2="3.01" y2="18"/>
                                </svg>
                            @endif
                        </div>
                        <div>{{ $kategori->category_name }}</div>
                    </div>
                </td>
                <td style="color:#64748B;font-size:13px;">{{ $kategori->description ?: '-' }}</td>
                <td>
                    <span class="badge {{ $kategori->status ? 'active' : 'inactive' }}">
                        <span class="badge-dot"></span>
                        {{ $kategori->status ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4">
                    <div class="empty-state">
                        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <line x1="8" y1="6" x2="21" y2="6"/>
                            <line x1="8" y1="12" x2="21" y2="12"/>
                            <line x1="8" y1="18" x2="21" y2="18"/>
                        </svg>
                        <p>Belum ada kategori.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- ── MODAL ADD ── --}}
<div class="modal-overlay" id="modalAdd">
    <div class="modal-backdrop" onclick="closeModal('modalAdd')"></div>
    <div class="modal-box">
        <div class="modal-header">
            <h2>Tambah Kategori</h2>
            <button class="modal-close" onclick="closeModal('modalAdd')">✕</button>
        </div>
        <form method="POST" action="{{ route('kategoris.store') }}">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Kategori <span style="color:#EF4444">*</span></label>
                    <input type="text" name="category_name" placeholder="cth. Outdoor Gear" required>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="description" placeholder="Deskripsi singkat kategori..."></textarea>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal('modalAdd')">Batal</button>
                <button type="submit" class="btn-save">Simpan Kategori</button>
            </div>
        </form>
    </div>
</div>

{{-- ── MODAL EDIT ── --}}
<div class="modal-overlay" id="modalEdit">
    <div class="modal-backdrop" onclick="closeModal('modalEdit')"></div>
    <div class="modal-box">
        <div class="modal-header">
            <h2>Edit Kategori</h2>
            <button class="modal-close" onclick="closeModal('modalEdit')">✕</button>
        </div>
        <form method="POST" id="editForm" action="">
            @csrf @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Kategori <span style="color:#EF4444">*</span></label>
                    <input type="text" name="category_name" id="editCategoryName" required>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="description" id="editDescription"></textarea>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" id="editStatus">
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal('modalEdit')">Batal</button>
                <button type="submit" class="btn-save">Update Kategori</button>
            </div>
        </form>
    </div>
</div>

{{-- ── MODAL KONFIRMASI DELETE ── --}}
<div class="modal-overlay" id="modalDelete">
    <div class="modal-backdrop" onclick="closeModal('modalDelete')"></div>
    <div class="confirm-box">
        <div class="confirm-accent"></div>
        <div class="confirm-body">
            <div class="confirm-icon-wrap">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <polyline points="3,6 5,6 21,6"/>
                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                    <path d="M10 11v6"/><path d="M14 11v6"/>
                    <path d="M9 6V4h6v2"/>
                </svg>
            </div>
            <div class="confirm-subtitle">Hapus Data</div>
            <div class="confirm-title">Hapus Kategori</div>
            <p class="confirm-desc">
                Kategori <strong id="deleteCategoryName" style="color:#0F172A"></strong> akan dihapus secara permanen dan tidak dapat dipulihkan.
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
    // ── Sort ───────────────────────────────────────────────────────────────
    let sortCol = -1, sortDir = 'asc';

    document.querySelectorAll('th.sortable').forEach(th => {
        th.addEventListener('click', () => {
            const col = parseInt(th.dataset.col);
            if (sortCol === col) {
                sortDir = sortDir === 'asc' ? 'desc' : 'asc';
            } else {
                sortCol = col;
                sortDir = 'asc';
            }
            updateSortIcons();
            sortTable(col, sortDir);
        });
    });

    function updateSortIcons() {
        document.querySelectorAll('th.sortable').forEach(th => {
            const col   = parseInt(th.dataset.col);
            const badge = document.getElementById('badge-' + col);

            if (col === sortCol) {
                th.classList.add('sort-active');
                th.classList.remove('asc', 'desc');
                th.classList.add(sortDir);
                if (badge) badge.textContent = sortDir === 'asc' ? 'A-Z' : 'Z-A';
            } else {
                th.classList.remove('sort-active', 'asc', 'desc');
                if (badge) badge.textContent = '';
            }
        });
    }

    function sortTable(col, dir) {
        const tbody = document.querySelector('#kategoriTable tbody');
        const rows  = Array.from(tbody.querySelectorAll('tr')).filter(r => r.cells.length > 1);

        rows.sort((a, b) => {
            const aText = a.cells[col]?.innerText.trim().toLowerCase() ?? '';
            const bText = b.cells[col]?.innerText.trim().toLowerCase() ?? '';
            if (aText < bText) return dir === 'asc' ? -1 : 1;
            if (aText > bText) return dir === 'asc' ?  1 : -1;
            return 0;
        });

        rows.forEach(row => tbody.appendChild(row));
    }

    // ── Add Modal ──────────────────────────────────────────────────────────
    function openAddModal() {
        document.getElementById('modalAdd').classList.add('show');
    }

    // ── Edit Modal ─────────────────────────────────────────────────────────
    function openEditModal(id, categoryName, description, status) {
        document.getElementById('editForm').action         = '/kategoris/' + id;
        document.getElementById('editCategoryName').value  = categoryName;
        document.getElementById('editDescription').value   = description;
        document.getElementById('editStatus').value        = status;
        document.getElementById('modalEdit').classList.add('show');
    }

    // ── Delete Modal ───────────────────────────────────────────────────────
    function openDeleteModal(id, name) {
        document.getElementById('deleteCategoryName').textContent = name;
        document.getElementById('deleteForm').action = '/kategoris/' + id;
        document.getElementById('modalDelete').classList.add('show');
    }

    function executeDelete() {
        document.getElementById('deleteForm').submit();
    }

    // ── Close ──────────────────────────────────────────────────────────────
    function closeModal(id) {
        document.getElementById(id).classList.remove('show');
    }

    // ── Search ─────────────────────────────────────────────────────────────
    function filterTable() {
        const input = document.getElementById('searchInput').value.toLowerCase();
        document.querySelectorAll('#kategoriTable tbody tr').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(input) ? '' : 'none';
        });
    }
</script>

@endsection