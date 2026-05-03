@extends('layouts.app')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

    .form-wrap * {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .form-card {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.04), 0 20px 60px -10px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    .form-header {
        background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 60%, #3b82f6 100%);
        padding: 28px 32px 24px;
        position: relative;
        overflow: hidden;
    }

    .form-header::before {
        content: '';
        position: absolute;
        top: -40px; right: -40px;
        width: 160px; height: 160px;
        border-radius: 50%;
        background: rgba(255,255,255,0.06);
    }

    .form-header::after {
        content: '';
        position: absolute;
        bottom: -60px; left: 30%;
        width: 220px; height: 220px;
        border-radius: 50%;
        background: rgba(255,255,255,0.04);
    }

    .form-header h2 {
        color: #fff;
        font-size: 1.25rem;
        font-weight: 700;
        letter-spacing: -0.01em;
        margin: 0;
    }

    .form-header p {
        color: rgba(255,255,255,0.7);
        font-size: 0.8rem;
        margin-top: 4px;
    }

    .header-icon {
        width: 42px; height: 42px;
        background: rgba(255,255,255,0.15);
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255,255,255,0.2);
        flex-shrink: 0;
    }

    .close-btn {
        position: absolute;
        top: 20px; right: 20px;
        width: 32px; height: 32px;
        background: rgba(255,255,255,0.15);
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        color: rgba(255,255,255,0.85);
        text-decoration: none;
        font-size: 0.85rem;
        transition: background 0.2s;
        border: 1px solid rgba(255,255,255,0.2);
        z-index: 2;
    }

    .close-btn:hover { background: rgba(255,255,255,0.25); color: #fff; }

    .form-body { padding: 28px 32px 32px; }

    .section-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #94a3b8;
        margin-bottom: 16px;
    }

    .section-label::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #f1f5f9;
    }

    .field-group { margin-bottom: 20px; }

    .field-label {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.8rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 7px;
    }

    .field-label .req {
        color: #ef4444;
        font-size: 0.75rem;
    }

    .input-wrap { position: relative; }

    .input-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 0.9rem;
        pointer-events: none;
        line-height: 1;
    }

    .field-input,
    .field-select,
    .field-textarea {
        width: 100%;
        background: #f8fafc;
        border: 1.5px solid #e8edf5;
        border-radius: 10px;
        color: #1e293b;
        font-size: 0.875rem;
        font-family: 'Plus Jakarta Sans', sans-serif;
        transition: all 0.2s ease;
        outline: none;
        box-sizing: border-box;
    }

    .field-input,
    .field-select {
        height: 42px;
        padding: 0 12px 0 36px;
    }

    .field-textarea {
        padding: 11px 12px 11px 36px;
        resize: none;
        line-height: 1.6;
    }

    .field-input::placeholder,
    .field-textarea::placeholder { color: #c0cad9; }

    .field-input:focus,
    .field-select:focus,
    .field-textarea:focus {
        border-color: #3b82f6;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .field-select { cursor: pointer; appearance: none; }

    .select-wrap { position: relative; }

    .select-arrow {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        pointer-events: none;
        font-size: 0.75rem;
    }

    .field-hint {
        font-size: 0.72rem;
        color: #94a3b8;
        margin-top: 5px;
        padding-left: 2px;
    }

    .divider {
        height: 1px;
        background: linear-gradient(to right, transparent, #e8edf5, transparent);
        margin: 24px 0;
    }

    .btn-cancel {
        height: 42px;
        padding: 0 20px;
        background: #f1f5f9;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 600;
        color: #64748b;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
        cursor: pointer;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .btn-cancel:hover {
        background: #e2e8f0;
        color: #475569;
        border-color: #cbd5e1;
    }

    .btn-submit {
        height: 42px;
        padding: 0 28px;
        background: linear-gradient(135deg, #2563eb, #3b82f6);
        border: none;
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 700;
        color: #fff;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35);
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .btn-submit:hover {
        background: linear-gradient(135deg, #1d4ed8, #2563eb);
        box-shadow: 0 6px 20px rgba(37, 99, 235, 0.45);
        transform: translateY(-1px);
    }

    .btn-submit:active { transform: translateY(0); }
</style>

<div class="form-wrap p-6 w-full">
    <div class="form-card w-[620px] mx-auto">

        {{-- Header --}}
        <div class="form-header">
            <a href="{{ route('produks.index') }}" class="close-btn">✕</a>
            <div class="flex items-center gap-3">
                <div class="header-icon">
                    <svg width="20" height="20" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/>
                        <path d="M16 3H8a2 2 0 00-2 2v2h12V5a2 2 0 00-2-2z"/>
                    </svg>
                </div>
                <div>
                    <h2>Tambah Produk Baru</h2>
                    <p>Tambahkan item baru ke katalog rental. Kolom bertanda * wajib diisi.</p>
                </div>
            </div>
        </div>

        {{-- Body --}}
        <div class="form-body">
            <form action="{{ route('produks.store') }}" method="POST">
                @csrf

                {{-- Section: Informasi Produk --}}
                <div class="section-label">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                    Informasi Produk
                </div>

                <div class="grid grid-cols-2 gap-4">
                    {{-- Nama Produk --}}
                    <div class="field-group">
                        <label class="field-label">
                            Nama Produk <span class="req">*</span>
                        </label>
                        <div class="input-wrap">
                            <span class="input-icon">
                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/></svg>
                            </span>
                            <input type="text" name="product_name" placeholder="cth. Yamaha NMAX" required class="field-input">
                        </div>
                    </div>

                    {{-- Kategori --}}
                    <div class="field-group">
                        <label class="field-label">
                            Kategori <span class="req">*</span>
                        </label>
                        <div class="input-wrap select-wrap">
                            <span class="input-icon">
                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h8M4 18h16"/></svg>
                            </span>
                            <select name="category_id" required class="field-select">
                                <option value="" disabled selected>Pilih Kategori</option>
                                @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}">{{ $kategori->category_name }}</option>
                                @endforeach
                            </select>
                            <span class="select-arrow">▼</span>
                        </div>
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div class="field-group">
                    <label class="field-label">Deskripsi Produk</label>
                    <div class="input-wrap">
                        <span class="input-icon" style="top: 14px; transform: none;">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                        </span>
                        <textarea name="description" placeholder="Deskripsi detail produk, kondisi, spesifikasi, dll..." rows="3" class="field-textarea"></textarea>
                    </div>
                </div>

                <div class="divider"></div>

                {{-- Section: Harga & Stok --}}
                <div class="section-label">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                    Harga & Stok
                </div>

                <div class="grid grid-cols-2 gap-4">
                    {{-- Stok --}}
                    <div class="field-group">
                        <label class="field-label">Stok Tersedia <span class="req">*</span></label>
                        <div class="input-wrap">
                            <span class="input-icon">
                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16"/><path d="M1 21h22"/><path d="M9 21V9h6v12"/></svg>
                            </span>
                            <input type="number" name="stock" placeholder="10" required min="0" class="field-input">
                        </div>
                    </div>

                    {{-- Harga Sewa --}}
                    <div class="field-group">
                        <label class="field-label">Harga Sewa / Hari <span class="req">*</span></label>
                        <div class="input-wrap">
                            <span class="input-icon">
                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                            </span>
                            <input type="number" name="rental_price" placeholder="200000" required min="0" class="field-input">
                        </div>
                        <p class="field-hint">dalam Rupiah (Rp)</p>
                    </div>

                    {{-- Kondisi --}}
                    <div class="field-group">
                        <label class="field-label">Kondisi <span class="req">*</span></label>
                        <div class="input-wrap select-wrap">
                            <span class="input-icon">
                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            </span>
                            <select name="condition" required class="field-select">
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

                    {{-- Min Hari Sewa --}}
                    <div class="field-group">
                        <label class="field-label">Minimal Hari Sewa</label>
                        <div class="input-wrap">
                            <span class="input-icon">
                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            </span>
                            <input type="number" name="min_rental_days" placeholder="1" value="1" min="1" class="field-input">
                        </div>
                        <p class="field-hint">hari minimum peminjaman</p>
                    </div>
                </div>

                <div class="divider"></div>

                {{-- Section: Status --}}
                <div class="section-label">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83"/></svg>
                    Status Produk
                </div>

                <div class="field-group" style="margin-bottom: 28px;">
                    <label class="field-label">Status Ketersediaan</label>
                    <div class="input-wrap select-wrap">
                        <span class="input-icon">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                        </span>
                        <select name="status" class="field-select">
                            <option value="1">✓ Aktif</option>
                            <option value="0">✗ Nonaktif</option>
                        </select>
                        <span class="select-arrow">▼</span>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="flex justify-end items-center gap-3">
                    <a href="{{ route('produks.index') }}" class="btn-cancel">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
                        Batal
                    </a>
                    <button type="submit" class="btn-submit">
                        <svg width="15" height="15" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Simpan Produk
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection