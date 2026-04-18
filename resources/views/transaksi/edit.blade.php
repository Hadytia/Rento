@extends('layouts.app')

@section('page_title', 'Edit Transaksi')

@section('content')

<style>
    .page-header { margin-bottom: 24px; }
    .page-header h1 { font-size: 24px; font-weight: 700; color: #1E1E1E; margin: 0 0 4px 0; }
    .page-header p { font-size: 14px; color: #6B6B6B; margin: 0; }

    .form-card { background: white; border-radius: 12px; padding: 28px; box-shadow: 0px 2px 8px rgba(0,0,0,0.06); max-width: 680px; }

    .trx-badge { display: inline-flex; align-items: center; gap: 6px; background: #F0F4FF; border: 1px solid #BFDBFE; border-radius: 6px; padding: 4px 12px; font-size: 13px; font-weight: 700; color: #2D4DA3; margin-bottom: 20px; }

    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .form-group { display: flex; flex-direction: column; gap: 6px; }
    .form-group.full { grid-column: 1 / -1; }

    label { font-size: 13px; font-weight: 600; color: #374151; }
    .form-control {
        height: 42px; border: 1px solid #E5E5E5; border-radius: 8px;
        padding: 0 14px; font-family: Inter, sans-serif; font-size: 14px;
        color: #1E1E1E; background: white; outline: none; width: 100%;
        box-sizing: border-box;
    }
    .form-control:focus { border-color: #2D4DA3; box-shadow: 0 0 0 3px rgba(45,77,163,0.08); }
    textarea.form-control { height: 90px; padding: 10px 14px; resize: vertical; }
    select.form-control { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%236B6B6B' stroke-width='2' viewBox='0 0 24 24'%3E%3Cpolyline points='6,9 12,15 18,9'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 12px center; padding-right: 36px; }

    .total-box { background: #F0F4FF; border: 1px solid #BFDBFE; border-radius: 8px; padding: 14px 18px; display: flex; justify-content: space-between; align-items: center; }
    .total-box .total-label { font-size: 14px; color: #374151; font-weight: 600; }
    .total-box .total-val { font-size: 18px; font-weight: 700; color: #2D4DA3; }

    .form-footer { display: flex; gap: 12px; margin-top: 24px; }
    .btn-submit {
        height: 42px; padding: 0 28px; background: #2D4DA3; border: none;
        border-radius: 8px; font-family: Inter, sans-serif; font-size: 14px;
        font-weight: 600; color: white; cursor: pointer;
    }
    .btn-submit:hover { background: #243F8F; }
    .btn-cancel {
        height: 42px; padding: 0 20px; background: white;
        border: 1px solid #E5E5E5; border-radius: 8px;
        font-family: Inter, sans-serif; font-size: 14px; color: #1E1E1E;
        cursor: pointer; text-decoration: none; display: flex; align-items: center;
    }
    .btn-cancel:hover { background: #F5F5F5; }

    .alert-error { background: #FEF2F2; border: 1px solid #FECACA; border-radius: 8px; padding: 10px 16px; font-size: 13px; color: #DC2626; margin-bottom: 16px; }
    .field-error { font-size: 12px; color: #DC2626; margin-top: 2px; }
</style>

<div class="page-header">
    <h1>Edit Transaksi</h1>
    <p>Perbarui data transaksi di bawah ini.</p>
</div>

@if ($errors->any())
    <div class="alert-error">
        @foreach ($errors->all() as $err)
            <div>• {{ $err }}</div>
        @endforeach
    </div>
@endif

<div class="form-card">

    <div class="trx-badge">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14,2 14,8 20,8"/>
        </svg>
        {{ $trx->trx_code }}
    </div>

    <form method="POST" action="{{ route('transaksi.update', $trx->id) }}">
        @csrf
        @method('PUT')

        <div class="form-grid">

            {{-- Customer --}}
            <div class="form-group">
                <label for="user_id">Customer <span style="color:#DC2626">*</span></label>
                <select name="user_id" id="user_id" class="form-control" required>
                    <option value="">-- Pilih Customer --</option>
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
                <select name="product_id" id="product_id" class="form-control" required onchange="calcTotal()">
                    <option value="">-- Pilih Produk --</option>
                    @foreach ($produks as $produk)
                        <option value="{{ $produk->id }}"
                            data-price="{{ $produk->rental_price }}"
                            {{ old('product_id', $trx->product_id) == $produk->id ? 'selected' : '' }}>
                            {{ $produk->product_name }} — Rp {{ number_format($produk->rental_price, 0, ',', '.') }}/hari
                        </option>
                    @endforeach
                </select>
                @error('product_id')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            {{-- Rental Start --}}
            <div class="form-group">
                <label for="rental_start">Tanggal Mulai <span style="color:#DC2626">*</span></label>
                <input type="date" name="rental_start" id="rental_start" class="form-control"
                    value="{{ old('rental_start', $trx->rental_start) }}" required onchange="calcTotal()">
                @error('rental_start')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            {{-- Rental End --}}
            <div class="form-group">
                <label for="rental_end">Tanggal Selesai <span style="color:#DC2626">*</span></label>
                <input type="date" name="rental_end" id="rental_end" class="form-control"
                    value="{{ old('rental_end', $trx->rental_end) }}" required onchange="calcTotal()">
                @error('rental_end')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            {{-- Status --}}
            <div class="form-group">
                <label for="trx_status">Status <span style="color:#DC2626">*</span></label>
                <select name="trx_status" id="trx_status" class="form-control" required>
                    @foreach (['Active','Completed','Overdue','Cancelled'] as $s)
                        <option value="{{ $s }}" {{ old('trx_status', $trx->trx_status) == $s ? 'selected' : '' }}>
                            {{ $s }}
                        </option>
                    @endforeach
                </select>
                @error('trx_status')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            {{-- Payment Method --}}
            <div class="form-group">
                <label for="payment_method">Metode Pembayaran</label>
                <select name="payment_method" id="payment_method" class="form-control">
                    <option value="">-- Pilih --</option>
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
                <label for="paid_amount">Jumlah Dibayar</label>
                <input type="number" name="paid_amount" id="paid_amount" class="form-control"
                    placeholder="0" min="0" value="{{ old('paid_amount', $trx->paid_amount) }}">
                @error('paid_amount')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            {{-- Notes --}}
            <div class="form-group full">
                <label for="notes">Catatan</label>
                <textarea name="notes" id="notes" class="form-control">{{ old('notes', $trx->notes) }}</textarea>
                @error('notes')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            {{-- Total Preview --}}
            <div class="form-group full">
                <div class="total-box" id="totalBox">
                    <div class="total-label" id="totalDesc">Total</div>
                    <div class="total-val" id="totalVal">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</div>
                </div>
            </div>

        </div>

        <div class="form-footer">
            <button type="submit" class="btn-submit">Update Transaksi</button>
            <a href="{{ route('reports.index') }}" class="btn-cancel">Batal</a>
        </div>
    </form>
</div>

<script>
    function calcTotal() {
        const sel   = document.getElementById('product_id');
        const opt   = sel.options[sel.selectedIndex];
        const price = opt ? parseFloat(opt.dataset.price) : 0;

        const start = document.getElementById('rental_start').value;
        const end   = document.getElementById('rental_end').value;

        if (!price || !start || !end) return;

        const sDate = new Date(start);
        const eDate = new Date(end);
        const diff  = Math.floor((eDate - sDate) / 86400000) + 1;

        if (diff < 1) return;

        const total = price * diff;
        document.getElementById('totalDesc').textContent = diff + ' hari × Rp ' + price.toLocaleString('id-ID');
        document.getElementById('totalVal').textContent  = 'Rp ' + total.toLocaleString('id-ID');
    }

    // Init on load
    calcTotal();
</script>

@endsection