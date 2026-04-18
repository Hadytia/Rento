@extends('layouts.app')

@section('page_title', 'Buat Transaksi')

@section('content')

<style>
    .page-header { margin-bottom: 24px; }
    .page-header h1 { font-size: 24px; font-weight: 700; color: #1E1E1E; margin: 0 0 4px 0; }
    .page-header p { font-size: 14px; color: #6B6B6B; margin: 0; }

    .form-card { background: white; border-radius: 12px; padding: 28px; box-shadow: 0px 2px 8px rgba(0,0,0,0.06); max-width: 680px; }

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

    .price-preview { margin-top: 6px; font-size: 12px; color: #6B6B6B; }
    .price-preview span { font-weight: 700; color: #2D4DA3; }

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
    <h1>Buat Transaksi Baru</h1>
    <p>Isi data rental di bawah ini.</p>
</div>

@if ($errors->any())
    <div class="alert-error">
        @foreach ($errors->all() as $err)
            <div>• {{ $err }}</div>
        @endforeach
    </div>
@endif

<div class="form-card">
    <form method="POST" action="{{ route('transaksi.store') }}">
        @csrf

        <div class="form-grid">

            {{-- Customer --}}
            <div class="form-group">
                <label for="user_id">Customer <span style="color:#DC2626">*</span></label>
                <select name="user_id" id="user_id" class="form-control" required>
                    <option value="">-- Pilih Customer --</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
                @error('user_id')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            {{-- Produk --}}
            <div class="form-group">
                <label for="product_id">Produk <span style="color:#DC2626">*</span></label>
                <select name="product_id" id="product_id" class="form-control" required onchange="updatePrice()">
                    <option value="">-- Pilih Produk --</option>
                    @foreach ($produks as $produk)
                        <option value="{{ $produk->id }}"
                            data-price="{{ $produk->rental_price }}"
                            {{ old('product_id') == $produk->id ? 'selected' : '' }}>
                            {{ $produk->product_name }} — Rp {{ number_format($produk->rental_price, 0, ',', '.') }}/hari
                        </option>
                    @endforeach
                </select>
                <div class="price-preview" id="pricePreview" style="display:none">
                    Harga sewa: <span id="priceVal">-</span> / hari
                </div>
                @error('product_id')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            {{-- Rental Start --}}
            <div class="form-group">
                <label for="rental_start">Tanggal Mulai <span style="color:#DC2626">*</span></label>
                <input type="date" name="rental_start" id="rental_start" class="form-control"
                    value="{{ old('rental_start') }}" required onchange="calcTotal()">
                @error('rental_start')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            {{-- Rental End --}}
            <div class="form-group">
                <label for="rental_end">Tanggal Selesai <span style="color:#DC2626">*</span></label>
                <input type="date" name="rental_end" id="rental_end" class="form-control"
                    value="{{ old('rental_end') }}" required onchange="calcTotal()">
                @error('rental_end')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            {{-- Payment Method --}}
            <div class="form-group">
                <label for="payment_method">Metode Pembayaran</label>
                <select name="payment_method" id="payment_method" class="form-control">
                    <option value="">-- Pilih --</option>
                    <option value="Cash"        {{ old('payment_method') == 'Cash'        ? 'selected' : '' }}>Cash</option>
                    <option value="Transfer"    {{ old('payment_method') == 'Transfer'    ? 'selected' : '' }}>Transfer</option>
                    <option value="QRIS"        {{ old('payment_method') == 'QRIS'        ? 'selected' : '' }}>QRIS</option>
                    <option value="Debit/Kredit"{{ old('payment_method') == 'Debit/Kredit'? 'selected' : '' }}>Debit / Kredit</option>
                </select>
                @error('payment_method')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            {{-- Paid Amount --}}
            <div class="form-group">
                <label for="paid_amount">Jumlah Dibayar</label>
                <input type="number" name="paid_amount" id="paid_amount" class="form-control"
                    placeholder="0" min="0" value="{{ old('paid_amount', 0) }}">
                @error('paid_amount')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            {{-- Notes --}}
            <div class="form-group full">
                <label for="notes">Catatan</label>
                <textarea name="notes" id="notes" class="form-control" placeholder="Catatan tambahan...">{{ old('notes') }}</textarea>
                @error('notes')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            {{-- Total Preview --}}
            <div class="form-group full">
                <div class="total-box" id="totalBox" style="display:none">
                    <div class="total-label" id="totalDesc">Total</div>
                    <div class="total-val" id="totalVal">Rp 0</div>
                </div>
            </div>

        </div>

        <div class="form-footer">
            <button type="submit" class="btn-submit">Simpan Transaksi</button>
            <a href="{{ route('reports.index') }}" class="btn-cancel">Batal</a>
        </div>
    </form>
</div>

<script>
    function updatePrice() {
        const sel   = document.getElementById('product_id');
        const opt   = sel.options[sel.selectedIndex];
        const price = opt ? opt.dataset.price : null;

        if (price) {
            document.getElementById('pricePreview').style.display = '';
            document.getElementById('priceVal').textContent = 'Rp ' + parseInt(price).toLocaleString('id-ID');
        } else {
            document.getElementById('pricePreview').style.display = 'none';
        }
        calcTotal();
    }

    function calcTotal() {
        const sel   = document.getElementById('product_id');
        const opt   = sel.options[sel.selectedIndex];
        const price = opt ? parseFloat(opt.dataset.price) : 0;

        const start = document.getElementById('rental_start').value;
        const end   = document.getElementById('rental_end').value;

        if (!price || !start || !end) {
            document.getElementById('totalBox').style.display = 'none';
            return;
        }

        const sDate  = new Date(start);
        const eDate  = new Date(end);
        const diff   = Math.floor((eDate - sDate) / 86400000) + 1;

        if (diff < 1) {
            document.getElementById('totalBox').style.display = 'none';
            return;
        }

        const total  = price * diff;
        document.getElementById('totalBox').style.display  = '';
        document.getElementById('totalDesc').textContent   = diff + ' hari × Rp ' + price.toLocaleString('id-ID');
        document.getElementById('totalVal').textContent    = 'Rp ' + total.toLocaleString('id-ID');
    }

    // Init jika ada old value
    updatePrice();
</script>

@endsection