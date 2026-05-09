@extends('layouts.app')

@section('page_title', 'Edit Transaksi')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    :root {
        --primary: #2D4DA3;
        --primary-dark: #253f8a;
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

    .trx-wrapper {
        max-width:780px;
        font-family:'Plus Jakarta Sans',sans-serif;
    }

    /* ── Page Header ── */
    .page-header { margin-bottom:20px; }
    .page-header h1 {
        font-size:26px; font-weight:700; margin:0 0 6px 0; letter-spacing:-0.5px;
        background:linear-gradient(135deg, #111827 0%, #2D4DA3 100%);
        -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent;
    }
    .page-header p { font-size:14px; color:var(--gray-500); margin:0; }

    /* ── TRX Badge ── */
    .trx-badge {
        display:inline-flex; align-items:center; gap:7px;
        background:linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%);
        border:1.5px solid #bfdbfe; border-radius:8px;
        padding:6px 14px; font-size:12px; font-weight:700; color:#2D4DA3;
        margin-bottom:20px; font-family:'JetBrains Mono','Consolas',monospace;
        box-shadow:inset 0 0 0 1px rgba(45,77,163,.1);
    }

    /* ── Alert Error ── */
    .alert-error {
        background:#FEF2F2; border:1px solid #FECACA; border-radius:12px;
        padding:13px 18px; font-size:13px; color:#DC2626; margin-bottom:20px;
        display:flex; flex-direction:column; gap:4px;
        box-shadow:0 1px 2px rgba(220,38,38,.06);
    }

    /* ── Card ── */
    .form-card {
        background:#FFF; border-radius:18px;
        border:1px solid var(--gray-200);
        box-shadow:0 1px 2px rgba(15,23,42,.04), 0 4px 12px rgba(15,23,42,.04);
        overflow:hidden;
    }

    /* ── Card Header Gradient ── */
    .card-header {
        position:relative;
        background:linear-gradient(135deg, #1e3a5f 0%, #2563eb 60%, #3b82f6 100%);
        padding:24px 28px; overflow:hidden;
    }
    .card-header::before {
        content:''; position:absolute; top:-40px; right:-40px;
        width:180px; height:180px; border-radius:50%;
        background:rgba(255,255,255,0.06); pointer-events:none;
    }
    .card-header::after {
        content:''; position:absolute; bottom:-50px; left:-30px;
        width:200px; height:200px; border-radius:50%;
        background:rgba(255,255,255,0.04); pointer-events:none;
    }
    .card-header-content {
        position:relative; z-index:1;
        display:flex; align-items:center; gap:14px;
    }
    .card-header-icon {
        width:44px; height:44px; border-radius:12px; flex-shrink:0;
        background:rgba(255,255,255,0.15); backdrop-filter:blur(10px);
        border:1px solid rgba(255,255,255,0.2);
        display:flex; align-items:center; justify-content:center;
    }
    .card-header-icon svg { width:22px; height:22px; color:#fff; }
    .card-header h2 {
        font-size:1.05rem; font-weight:700; color:#fff; margin:0 0 3px 0;
        letter-spacing:-0.01em;
    }
    .card-header p {
        font-size:0.78rem; color:rgba(255,255,255,0.7); margin:0; font-weight:500;
    }
    /* TRX code chip in header */
    .header-trx-chip {
        position:relative; z-index:1;
        margin-left:auto; flex-shrink:0;
        background:rgba(255,255,255,0.15); backdrop-filter:blur(10px);
        border:1px solid rgba(255,255,255,0.2); border-radius:8px;
        padding:5px 12px; font-size:12px; font-weight:700;
        color:#fff; font-family:'JetBrains Mono','Consolas',monospace;
        letter-spacing:.3px;
    }

    /* ── Form Section ── */
    .form-section {
        padding:24px 28px;
        border-bottom:1px solid var(--gray-100);
    }
    .form-section:last-of-type { border-bottom:none; }

    .section-label {
        display:flex; align-items:center; gap:8px;
        font-size:0.65rem; font-weight:700; letter-spacing:.08em;
        text-transform:uppercase; color:#94a3b8;
        margin-bottom:18px;
    }
    .section-label svg { width:13px; height:13px; flex-shrink:0; }
    .section-label::after { content:''; flex:1; height:1px; background:var(--gray-100); }

    /* ── Grid ── */
    .form-grid  { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
    .form-group { display:flex; flex-direction:column; gap:7px; }
    .form-group.full { grid-column:1 / -1; }

    /* ── Labels ── */
    .form-group label {
        font-size:0.7rem; font-weight:700; color:#64748b;
        text-transform:uppercase; letter-spacing:.06em;
    }
    .form-group label .req { color:#ef4444; margin-left:2px; }

    /* ── Input wrapper with icon ── */
    .fg-wrap { position:relative; }
    .fg-ico {
        position:absolute; left:11px; top:50%; transform:translateY(-50%);
        color:var(--gray-400); pointer-events:none; display:flex; align-items:center;
    }
    .fg-ico-top {
        position:absolute; left:11px; top:11px;
        color:var(--gray-400); pointer-events:none; display:flex; align-items:center;
    }

    /* ── Inputs ── */
    .form-control {
        width:100%; height:42px;
        background:#f8fafc; border:1.5px solid #e8edf5; border-radius:10px;
        padding:0 12px 0 34px;
        font-family:'Plus Jakarta Sans',sans-serif; font-size:0.875rem;
        color:var(--gray-900); outline:none; box-sizing:border-box;
        transition:all .18s ease; font-weight:500; appearance:none;
    }
    textarea.form-control {
        height:85px; padding:10px 12px 10px 34px; resize:vertical; line-height:1.6;
    }
    .form-control::placeholder { color:#c0cad9; font-weight:400; }
    .form-control:focus {
        border-color:#3b82f6; background:#fff;
        box-shadow:0 0 0 3px rgba(59,130,246,.1);
    }
    select.form-control {
        background-image:url("data:image/svg+xml,%3Csvg width='12' height='8' viewBox='0 0 12 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1.5L6 6.5L11 1.5' stroke='%2394a3b8' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
        background-repeat:no-repeat;
        background-position:right 12px center;
        padding-right:36px;
    }

    /* ── Field error ── */
    .field-error { font-size:11px; color:#ef4444; margin-top:2px; }

    /* ── Total box ── */
    .total-box {
        background:linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border:1.5px solid #bfdbfe; border-radius:12px;
        padding:16px 20px;
        display:flex; justify-content:space-between; align-items:center;
        box-shadow:inset 0 0 0 1px rgba(45,77,163,.08);
    }
    .total-left { display:flex; flex-direction:column; gap:3px; }
    .total-label { font-size:0.78rem; font-weight:700; color:var(--gray-700); }
    .total-days  { font-size:0.72rem; color:var(--gray-500); font-weight:500; }
    .total-right {
        font-size:1.4rem; font-weight:800; color:#2D4DA3;
        letter-spacing:-0.5px;
    }

    /* ── Status select colors ── */
    .status-active    { color:#2D4DA3; }
    .status-completed { color:#059669; }
    .status-overdue   { color:#EA580C; }
    .status-cancelled { color:#DC2626; }

    /* ── Footer ── */
    .form-footer {
        display:flex; align-items:center; justify-content:flex-end;
        gap:12px; padding:20px 28px;
        background:#fafbfc; border-top:1px solid var(--gray-100);
    }
    .btn-submit {
        height:42px; padding:0 28px;
        background:linear-gradient(135deg, #2563eb, #3b82f6);
        border:none; border-radius:10px;
        font-family:'Plus Jakarta Sans',sans-serif; font-size:0.875rem; font-weight:700;
        color:#fff; cursor:pointer; display:inline-flex; align-items:center; gap:7px;
        box-shadow:0 4px 14px rgba(37,99,235,.35); transition:all .2s ease;
    }
    .btn-submit:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(37,99,235,.45); }
    .btn-submit:active { transform:translateY(0); }
    .btn-cancel {
        height:42px; padding:0 22px;
        background:#f1f5f9; border:1.5px solid #e2e8f0; border-radius:10px;
        font-family:'Plus Jakarta Sans',sans-serif; font-size:0.875rem; font-weight:700;
        color:#64748b; cursor:pointer; text-decoration:none;
        display:inline-flex; align-items:center; gap:6px;
        transition:all .15s ease;
    }
    .btn-cancel:hover { background:#e2e8f0; border-color:#cbd5e1; color:#475569; }
</style>

<div class="trx-wrapper">

    {{-- Page Header --}}
    <div class="page-header">
        <h1>Edit Transaksi</h1>
        <p>Perbarui data transaksi penyewaan.</p>
    </div>

    {{-- Alert Error --}}
    @if ($errors->any())
        <div class="alert-error">
            @foreach ($errors->all() as $err)
                <div>• {{ $err }}</div>
            @endforeach
        </div>
    @endif

    <div class="form-card">

        {{-- Card Header --}}
        <div class="card-header">
            <div class="card-header-content">
                <div class="card-header-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                </div>
                <div>
                    <h2>Edit Transaksi Rental</h2>
                    <p>Perbarui informasi transaksi penyewaan.</p>
                </div>
                <div class="header-trx-chip">{{ $trx->trx_code }}</div>
            </div>
        </div>

        {{-- Section 1: Data Pelanggan & Produk --}}
        <div class="form-section">
            <div class="section-label">
                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
                Data Pelanggan &amp; Produk
            </div>
            <div class="form-grid">

                {{-- Customer --}}
                <div class="form-group">
                    <label for="user_id">Pelanggan <span class="req">*</span></label>
                    <div class="fg-wrap">
                        <span class="fg-ico">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                        </span>
                        <select name="user_id" id="user_id" class="form-control" required form="formEdit">
                            <option value="">— Pilih Pelanggan —</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ old('user_id', $trx->user_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('user_id')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                {{-- Produk --}}
                <div class="form-group">
                    <label for="product_id">Produk <span class="req">*</span></label>
                    <div class="fg-wrap">
                        <span class="fg-ico">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                                <line x1="3" y1="6" x2="21" y2="6"/>
                                <path d="M16 10a4 4 0 0 1-8 0"/>
                            </svg>
                        </span>
                        <select name="product_id" id="product_id" class="form-control" required onchange="calcTotal()" form="formEdit">
                            <option value="">— Pilih Produk —</option>
                            @foreach ($produks as $produk)
                                <option value="{{ $produk->id }}"
                                    data-price="{{ $produk->rental_price }}"
                                    {{ old('product_id', $trx->product_id) == $produk->id ? 'selected' : '' }}>
                                    {{ $produk->product_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('product_id')<div class="field-error">{{ $message }}</div>@enderror
                </div>

            </div>
        </div>

        {{-- Section 2: Periode & Status --}}
        <div class="form-section">
            <div class="section-label">
                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
                Periode Sewa &amp; Status
            </div>
            <div class="form-grid">

                {{-- Rental Start --}}
                <div class="form-group">
                    <label for="rental_start">Tanggal Mulai <span class="req">*</span></label>
                    <div class="fg-wrap">
                        <span class="fg-ico">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8" y1="2" x2="8" y2="6"/>
                                <line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                        </span>
                        <input type="date" name="rental_start" id="rental_start" class="form-control"
                            value="{{ old('rental_start', $trx->rental_start) }}" required onchange="calcTotal()" form="formEdit">
                    </div>
                    @error('rental_start')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                {{-- Rental End --}}
                <div class="form-group">
                    <label for="rental_end">Tanggal Selesai <span class="req">*</span></label>
                    <div class="fg-wrap">
                        <span class="fg-ico">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8" y1="2" x2="8" y2="6"/>
                                <line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                        </span>
                        <input type="date" name="rental_end" id="rental_end" class="form-control"
                            value="{{ old('rental_end', $trx->rental_end) }}" required onchange="calcTotal()" form="formEdit">
                    </div>
                    @error('rental_end')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                {{-- Status --}}
                <div class="form-group">
                    <label for="trx_status">Status Transaksi <span class="req">*</span></label>
                    <div class="fg-wrap">
                        <span class="fg-ico">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M12 8v4M12 16h.01"/>
                            </svg>
                        </span>
                        <select name="trx_status" id="trx_status" class="form-control" required form="formEdit">
                            @php
                                $statusOptions = [
                                    'Active'    => 'Aktif',
                                    'Completed' => 'Selesai',
                                    'Overdue'   => 'Terlambat',
                                    'Cancelled' => 'Dibatalkan',
                                ];
                            @endphp
                            @foreach ($statusOptions as $val => $label)
                                <option value="{{ $val }}" {{ old('trx_status', $trx->trx_status) == $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('trx_status')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                {{-- Total Preview --}}
                <div class="form-group full">
                    <div class="total-box" id="totalBox">
                        <div class="total-left">
                            <div class="total-label">Total Biaya Sewa</div>
                            <div class="total-days" id="totalDesc">-</div>
                        </div>
                        <div class="total-right" id="totalVal">
                            Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Section 3: Pembayaran --}}
        <div class="form-section">
            <div class="section-label">
                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
                    <line x1="1" y1="10" x2="23" y2="10"/>
                </svg>
                Informasi Pembayaran
            </div>
            <div class="form-grid">

                {{-- Delivery Method --}}
                <div class="form-group">
                    <label for="delivery_method">Metode Pengiriman</label>
                    <div class="fg-wrap">
                        <span class="fg-ico">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect x="1" y="3" width="15" height="13" rx="1"/>
                                <path d="M16 8h4l3 3v5h-7V8z"/>
                                <circle cx="5.5" cy="18.5" r="2.5"/>
                                <circle cx="18.5" cy="18.5" r="2.5"/>
                            </svg>
                        </span>
                        <select name="delivery_method" id="delivery_method" class="form-control" form="formEdit">
                            <option value="">— Pilih Metode —</option>
                            <option value="Pickup"   {{ old('delivery_method', $trx->delivery_method) == 'Pickup'   ? 'selected' : '' }}>Pickup — Ambil Sendiri</option>
                            <option value="Delivery" {{ old('delivery_method', $trx->delivery_method) == 'Delivery' ? 'selected' : '' }}>Delivery — Diantar Kurir</option>
                            <option value="COD"      {{ old('delivery_method', $trx->delivery_method) == 'COD'      ? 'selected' : '' }}>COD — Cash on Delivery</option>
                        </select>
                    </div>
                    @error('delivery_method')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                {{-- Payment Method --}}
                <div class="form-group">
                    <label for="payment_method">Metode Pembayaran</label>
                    <div class="fg-wrap">
                        <span class="fg-ico">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
                                <line x1="1" y1="10" x2="23" y2="10"/>
                            </svg>
                        </span>
                        <select name="payment_method" id="payment_method" class="form-control" form="formEdit">
                            <option value="">— Pilih Metode —</option>
                            @foreach (['Cash','Transfer','QRIS','Debit/Kredit'] as $pm)
                                <option value="{{ $pm }}" {{ old('payment_method', $trx->payment_method) == $pm ? 'selected' : '' }}>
                                    {{ $pm }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('payment_method')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                {{-- Paid Amount --}}
                <div class="form-group">
                    <label for="paid_amount">Jumlah Dibayar (Rp)</label>
                    <div class="fg-wrap">
                        <span class="fg-ico">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <line x1="12" y1="1" x2="12" y2="23"/>
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                            </svg>
                        </span>
                        <input type="text" name="paid_amount" id="paid_amount" class="form-control"
                            placeholder="0" value="{{ old('paid_amount', $trx->paid_amount) }}" form="formEdit"
                            oninput="formatPaidAmount(this)" autocomplete="off">
                        <input type="hidden" name="paid_amount_raw" id="paid_amount_raw" value="{{ old('paid_amount', $trx->paid_amount) }}">
                    </div>
                    @error('paid_amount')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                {{-- Notes --}}
                <div class="form-group full">
                    <label for="notes">Catatan</label>
                    <div class="fg-wrap">
                        <span class="fg-ico-top">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14,2 14,8 20,8"/>
                                <line x1="16" y1="13" x2="8" y2="13"/>
                                <line x1="16" y1="17" x2="8" y2="17"/>
                            </svg>
                        </span>
                        <textarea name="notes" id="notes" class="form-control"
                            placeholder="Catatan tambahan (opsional)..." form="formEdit">{{ old('notes', $trx->notes) }}</textarea>
                    </div>
                    @error('notes')<div class="field-error">{{ $message }}</div>@enderror
                </div>

            </div>
        </div>

        {{-- Footer --}}
        <div class="form-footer">
            <a href="{{ route('reports.index') }}" class="btn-cancel">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M18 6L6 18M6 6l12 12"/>
                </svg>
                Batal
            </a>
            <button type="submit" class="btn-submit" form="formEdit">
                <svg width="14" height="14" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24">
                    <polyline points="20,6 9,17 4,12"/>
                </svg>
                Simpan Perubahan
            </button>
        </div>

    </div>

</div>

{{-- Form di luar card --}}
<form id="formEdit" method="POST" action="{{ route('transaksi.update', $trx->id) }}" style="display:none" onsubmit="syncPaidAmount()">
    @csrf
    @method('PUT')
    <input type="hidden" name="paid_amount" id="paid_amount_submit" value="{{ old('paid_amount', $trx->paid_amount) }}">
</form>

<script>
    function calcTotal() {
        const sel   = document.getElementById('product_id');
        const opt   = sel.options[sel.selectedIndex];
        const price = opt ? parseFloat(opt.dataset.price) : 0;
        const start = document.getElementById('rental_start').value;
        const end   = document.getElementById('rental_end').value;
        const box   = document.getElementById('totalBox');

        if (!price || !start || !end) return;

        const diff = Math.floor((new Date(end) - new Date(start)) / 86400000) + 1;
        if (diff < 1) return;

        const total = price * diff;
        box.style.display = 'flex';
        document.getElementById('totalDesc').textContent = diff + ' hari × Rp ' + price.toLocaleString('id-ID');
        document.getElementById('totalVal').textContent  = 'Rp ' + total.toLocaleString('id-ID');
    }

    calcTotal();

    function formatPaidAmount(input) {
        // Ambil angka saja
        let raw = input.value.replace(/[^0-9]/g, '');
        // Format dengan separator titik
        let formatted = raw ? parseInt(raw).toLocaleString('id-ID') : '';
        input.value = formatted;
        // Simpan nilai asli ke hidden input
        document.getElementById('paid_amount_raw').value = raw;
    }

    // Format saat halaman load
    window.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('paid_amount');
        if (input.value) {
            // Parse float dulu untuk handle "350000.00", lalu bulatkan
            let raw = Math.round(parseFloat(input.value));
            if (!isNaN(raw) && raw > 0) {
                input.value = raw.toLocaleString('id-ID');
                document.getElementById('paid_amount_raw').value = raw;
            }
        }
    });

    function syncPaidAmount() {
    const raw = document.getElementById('paid_amount_raw').value;
    document.getElementById('paid_amount_submit').value = raw;
    }
</script>

@endsection