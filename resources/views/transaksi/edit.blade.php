@extends('layouts.app')

@section('page_title', 'Edit Transaksi')

@section('content')

<style>
    /* ── Layout ── */
    .trx-wrapper { max-width: 780px; }

    /* ── Page Header ── */
    .page-header { margin-bottom: 24px; }
    .page-header h1 { font-size: 18px; font-weight: 700; color: #0B1A2B; margin: 0 0 4px 0; }
    .page-header p  { font-size: 13px; color: #6B7280; margin: 0; }

    /* ── TRX Badge ── */
    .trx-badge {
        display: inline-flex; align-items: center; gap: 6px;
        background: #EEF2FF; border: 1px solid #C7D2FE; border-radius: 7px;
        padding: 5px 12px; font-size: 12px; font-weight: 700; color: #2D4DA3;
        margin-bottom: 20px; font-family: 'Consolas', monospace;
    }

    /* ── Alert ── */
    .alert-error {
        background: #FEF2F2; border: 1px solid #FECACA; border-radius: 8px;
        padding: 10px 16px; font-size: 13px; color: #DC2626; margin-bottom: 16px;
        display: flex; flex-direction: column; gap: 3px;
    }

    /* ── Card ── */
    .form-card {
        background: white; border-radius: 14px; border: 1px solid #E5E7EB;
        overflow: hidden;
    }
    .form-section {
        padding: 20px 24px;
        border-bottom: 1px solid #F3F4F6;
    }
    .form-section:last-child { border-bottom: none; }
    .section-title {
        font-size: 11px; font-weight: 700; color: #6B7280;
        text-transform: uppercase; letter-spacing: .5px;
        margin-bottom: 16px;
    }

    /* ── Grid ── */
    .form-grid   { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    .form-group  { display: flex; flex-direction: column; gap: 5px; }
    .form-group.full { grid-column: 1 / -1; }

    /* ── Labels & Inputs ── */
    label { font-size: 12px; font-weight: 600; color: #374151; }
    .form-control {
        height: 38px; border: 1px solid #E5E7EB; border-radius: 8px;
        padding: 0 12px; font-family: Inter, sans-serif; font-size: 13px;
        color: #1E1E1E; background: white; outline: none; width: 100%;
        box-sizing: border-box; transition: border-color .15s, box-shadow .15s;
    }
    .form-control:focus {
        border-color: #2D4DA3;
        box-shadow: 0 0 0 3px rgba(45,77,163,0.08);
    }
    textarea.form-control {
        height: 80px; padding: 10px 12px; resize: vertical; line-height: 1.5;
    }
    select.form-control {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%236B6B6B' stroke-width='2' viewBox='0 0 24 24'%3E%3Cpolyline points='6,9 12,15 18,9'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 12px center; padding-right: 36px;
    }
    .field-error { font-size: 11px; color: #DC2626; margin-top: 2px; }

    /* ── Status badge colors on select ── */
    .status-active    { color: #2D4DA3; }
    .status-completed { color: #059669; }
    .status-overdue   { color: #EA580C; }
    .status-cancelled { color: #DC2626; }

    /* ── Total box ── */
    .total-box {
        background: #EEF2FF; border: 1px solid #C7D2FE;
        border-radius: 10px; padding: 14px 18px;
        display: flex; justify-content: space-between; align-items: center;
    }
    .total-left  { font-size: 13px; color: #374151; }
    .total-days  { font-size: 11px; color: #6B7280; margin-top: 2px; }
    .total-right { font-size: 20px; font-weight: 700; color: #2D4DA3; }

    /* ── Footer ── */
    .form-footer {
        display: flex; align-items: center; justify-content: flex-end;
        gap: 10px; padding: 16px 24px; background: #F9FAFB;
        border-top: 1px solid #E5E7EB;
    }
    .btn-submit {
        height: 38px; padding: 0 24px; background: #2D4DA3; border: none;
        border-radius: 8px; font-family: Inter, sans-serif; font-size: 13px;
        font-weight: 600; color: white; cursor: pointer; display: flex;
        align-items: center; gap: 7px;
    }
    .btn-submit:hover { background: #243F8F; }
    .btn-cancel {
        height: 38px; padding: 0 18px; background: white;
        border: 1px solid #E5E7EB; border-radius: 8px;
        font-family: Inter, sans-serif; font-size: 13px; color: #374151;
        cursor: pointer; text-decoration: none; display: flex; align-items: center;
    }
    .btn-cancel:hover { background: #F5F5F5; }
</style>

<div class="trx-wrapper">

    {{-- Header --}}
    <div class="page-header">
        <h1>Edit Transaksi</h1>
        <p>Perbarui data transaksi penyewaan.</p>
    </div>

    {{-- TRX Badge --}}
    <div class="trx-badge">
        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14,2 14,8 20,8"/>
        </svg>
        {{ $trx->trx_code }}
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

        {{-- Section 1: Data Pelanggan & Produk --}}
        <div class="form-section">
            <div class="section-title">Data Pelanggan &amp; Produk</div>
            <div class="form-grid">

                {{-- Customer --}}
                <div class="form-group">
                    <label for="user_id">Pelanggan <span style="color:#DC2626">*</span></label>
                    <select name="user_id" id="user_id" class="form-control" required form="formEdit">
                        <option value="">— Pilih Pelanggan —</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}"
                                {{ old('user_id', $trx->user_id) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                {{-- Produk --}}
                <div class="form-group">
                    <label for="product_id">Produk <span style="color:#DC2626">*</span></label>
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
                    @error('product_id')<div class="field-error">{{ $message }}</div>@enderror
                </div>

            </div>
        </div>

        {{-- Section 2: Periode & Status --}}
        <div class="form-section">
            <div class="section-title">Periode Sewa &amp; Status</div>
            <div class="form-grid">

                {{-- Rental Start --}}
                <div class="form-group">
                    <label for="rental_start">Tanggal Mulai <span style="color:#DC2626">*</span></label>
                    <input type="date" name="rental_start" id="rental_start" class="form-control"
                        value="{{ old('rental_start', $trx->rental_start) }}" required onchange="calcTotal()" form="formEdit">
                    @error('rental_start')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                {{-- Rental End --}}
                <div class="form-group">
                    <label for="rental_end">Tanggal Selesai <span style="color:#DC2626">*</span></label>
                    <input type="date" name="rental_end" id="rental_end" class="form-control"
                        value="{{ old('rental_end', $trx->rental_end) }}" required onchange="calcTotal()" form="formEdit">
                    @error('rental_end')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                {{-- Status --}}
                <div class="form-group">
                    <label for="trx_status">Status Transaksi <span style="color:#DC2626">*</span></label>
                    <select name="trx_status" id="trx_status" class="form-control" required form="formEdit">
                        @php
                            $statusOptions = [
                                'Active'    => ['label' => 'Aktif',      'color' => '#2D4DA3'],
                                'Completed' => ['label' => 'Selesai',    'color' => '#059669'],
                                'Overdue'   => ['label' => 'Terlambat',  'color' => '#EA580C'],
                                'Cancelled' => ['label' => 'Dibatalkan', 'color' => '#DC2626'],
                            ];
                        @endphp
                        @foreach ($statusOptions as $val => $opt)
                            <option value="{{ $val }}" {{ old('trx_status', $trx->trx_status) == $val ? 'selected' : '' }}>
                                {{ $opt['label'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('trx_status')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                {{-- Total Preview --}}
                <div class="form-group full">
                    <div class="total-box" id="totalBox">
                        <div class="total-left">
                            <div style="font-weight:600; color:#0B1A2B; font-size:13px;">Total Biaya Sewa</div>
                            <div class="total-days" id="totalDesc">-</div>
                        </div>
                        <div class="total-right" id="totalVal">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Section 3: Pembayaran --}}
        <div class="form-section">
            <div class="section-title">Informasi Pembayaran</div>
            <div class="form-grid">

                {{-- Payment Method --}}
                <div class="form-group">
                    <label for="payment_method">Metode Pembayaran</label>
                    <select name="payment_method" id="payment_method" class="form-control" form="formEdit">
                        <option value="">— Pilih Metode —</option>
                        @foreach (['Cash','Transfer','QRIS','Debit/Kredit'] as $pm)
                            <option value="{{ $pm }}" {{ old('payment_method', $trx->payment_method) == $pm ? 'selected' : '' }}>
                                {{ $pm }}
                            </option>
                        @endforeach
                    </select>
                    @error('payment_method')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                {{-- Paid Amount --}}
                <div class="form-group">
                    <label for="paid_amount">Jumlah Dibayar (Rp)</label>
                    <input type="number" name="paid_amount" id="paid_amount" class="form-control"
                        placeholder="0" min="0" value="{{ old('paid_amount', $trx->paid_amount) }}" form="formEdit">
                    @error('paid_amount')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                {{-- Notes --}}
                <div class="form-group full">
                    <label for="notes">Catatan</label>
                    <textarea name="notes" id="notes" class="form-control"
                        placeholder="Catatan tambahan (opsional)..." form="formEdit">{{ old('notes', $trx->notes) }}</textarea>
                    @error('notes')<div class="field-error">{{ $message }}</div>@enderror
                </div>

            </div>
        </div>

        {{-- Footer --}}
        <div class="form-footer">
            <a href="{{ route('reports.index') }}" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-submit" form="formEdit">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <polyline points="20,6 9,17 4,12"/>
                </svg>
                Simpan Perubahan
            </button>
        </div>

    </div>{{-- /form-card --}}

</div>{{-- /trx-wrapper --}}

{{-- Form di luar card --}}
<form id="formEdit" method="POST" action="{{ route('transaksi.update', $trx->id) }}" style="display:none">
    @csrf
    @method('PUT')
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
</script>

@endsection