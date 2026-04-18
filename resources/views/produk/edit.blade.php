@extends('layouts.app')

@section('content')

<div class="p-6 w-full">

    {{-- Form Container --}}
    <div class="bg-white rounded-xl shadow p-6 w-[600px] mx-auto">

        {{-- Header --}}
        <div class="flex items-start justify-between mb-1">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Edit Produk</h2>
                <p class="text-sm text-gray-500 mt-1">Perbarui informasi produk. Kolom bertanda * wajib diisi.</p>
            </div>
            <a href="{{ route('produks.index') }}" class="text-gray-400 hover:text-gray-600 text-xl leading-none mt-1">✕</a>
        </div>

        <hr class="my-4 border-gray-200">

        <form action="{{ route('produks.update', $produk->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Product Name & Category --}}
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm text-gray-800 mb-1">Nama Produk*</label>
                    <input type="text" name="product_name" value="{{ $produk->product_name }}" required
                        class="w-full h-10 border border-gray-200 rounded-lg px-3 text-sm text-gray-800 outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm text-gray-800 mb-1">Kategori*</label>
                    <select name="category_id" required
                        class="w-full h-10 border border-gray-200 rounded-lg px-3 text-sm text-gray-800 outline-none focus:border-blue-500 bg-white">
                        <option value="" disabled>Pilih Kategori</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}"
                                {{ $produk->category_id == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Description --}}
            <div class="mb-4">
                <label class="block text-sm text-gray-800 mb-1">Deskripsi Produk</label>
                <textarea name="description" rows="3"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 outline-none focus:border-blue-500 resize-none">{{ $produk->description }}</textarea>
            </div>

            <hr class="my-4 border-gray-200">

            {{-- Stock & Price --}}
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm text-gray-800 mb-1">Stok Tersedia*</label>
                    <input type="number" name="stock" value="{{ $produk->stock }}" required min="0"
                        class="w-full h-10 border border-gray-200 rounded-lg px-3 text-sm text-gray-800 outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm text-gray-800 mb-1">Harga Sewa (per hari)*</label>
                    <input type="number" name="rental_price" value="{{ $produk->rental_price }}" required min="0"
                        class="w-full h-10 border border-gray-200 rounded-lg px-3 text-sm text-gray-800 outline-none focus:border-blue-500">
                </div>
            </div>

            {{-- Condition & Min Rental Days --}}
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm text-gray-800 mb-1">Kondisi*</label>
                    <select name="condition" required
                        class="w-full h-10 border border-gray-200 rounded-lg px-3 text-sm text-gray-800 outline-none focus:border-blue-500 bg-white">
                        <option value="" disabled>Pilih Kondisi</option>
                        @foreach(['New', 'Excellent', 'Good', 'Fair', 'Poor'] as $cond)
                            <option value="{{ $cond }}"
                                {{ $produk->condition == $cond ? 'selected' : '' }}>
                                {{ $cond }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-800 mb-1">Minimal Hari Sewa</label>
                    <input type="number" name="min_rental_days" value="{{ $produk->min_rental_days }}" min="1"
                        class="w-full h-10 border border-gray-200 rounded-lg px-3 text-sm text-gray-800 outline-none focus:border-blue-500">
                </div>
            </div>

            <hr class="my-4 border-gray-200">

            {{-- Status --}}
            <div class="mb-6">
                <label class="block text-sm text-gray-800 mb-1">Status</label>
                <select name="status"
                    class="w-full h-10 border border-gray-200 rounded-lg px-3 text-sm text-gray-800 outline-none focus:border-blue-500 bg-white">
                    <option value="1" {{ $produk->status == 1 ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ $produk->status == 0 ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            {{-- Buttons --}}
            <div class="flex justify-end gap-3">
                <a href="{{ route('produks.index') }}"
                    class="h-10 px-4 bg-gray-100 hover:bg-gray-200 border border-gray-200 rounded-lg text-sm text-gray-700 flex items-center">
                    Batal
                </a>
                <button type="submit"
                    class="h-10 px-6 bg-blue-700 hover:bg-blue-800 text-white rounded-lg text-sm font-medium">
                    Update
                </button>
            </div>

        </form>
    </div>
</div>

@endsection