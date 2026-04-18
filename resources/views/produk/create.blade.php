@extends('layouts.app')

@section('content')

<div class="p-6 w-full">

    {{-- Form Container --}}
    <div class="bg-white rounded-xl shadow p-6 w-[600px] mx-auto">

        {{-- Header --}}
        <div class="flex items-start justify-between mb-1">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Tambah Produk Baru</h2>
                <p class="text-sm text-gray-500 mt-1">Tambahkan item baru ke katalog rental. Kolom bertanda * wajib diisi.</p>
            </div>
            <a href="{{ route('produks.index') }}" class="text-gray-400 hover:text-gray-600 text-xl leading-none mt-1">✕</a>
        </div>

        <hr class="my-4 border-gray-200">

        <form action="{{ route('produks.store') }}" method="POST">
            @csrf

            {{-- Product Name & Category --}}
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm text-gray-800 mb-1">Nama Produk*</label>
                    <input type="text" name="product_name" placeholder="cth. Yamaha NMAX" required
                        class="w-full h-10 border border-gray-200 rounded-lg px-3 text-sm text-gray-800 outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm text-gray-800 mb-1">Kategori*</label>
                    <select name="category_id" required
                        class="w-full h-10 border border-gray-200 rounded-lg px-3 text-sm text-gray-800 outline-none focus:border-blue-500 bg-white">
                        <option value="" disabled selected>Pilih Kategori</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}">{{ $kategori->category_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Description --}}
            <div class="mb-4">
                <label class="block text-sm text-gray-800 mb-1">Deskripsi Produk</label>
                <textarea name="description" placeholder="Deskripsi detail produk" rows="3"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 outline-none focus:border-blue-500 resize-none"></textarea>
            </div>

            <hr class="my-4 border-gray-200">

            {{-- Stock & Price --}}
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm text-gray-800 mb-1">Stok Tersedia*</label>
                    <input type="number" name="stock" placeholder="10" required min="0"
                        class="w-full h-10 border border-gray-200 rounded-lg px-3 text-sm text-gray-800 outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm text-gray-800 mb-1">Harga Sewa (per hari)*</label>
                    <input type="number" name="rental_price" placeholder="200000" required min="0"
                        class="w-full h-10 border border-gray-200 rounded-lg px-3 text-sm text-gray-800 outline-none focus:border-blue-500">
                </div>
            </div>

            {{-- Condition & Min Rental Days --}}
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm text-gray-800 mb-1">Kondisi*</label>
                    <select name="condition" required
                        class="w-full h-10 border border-gray-200 rounded-lg px-3 text-sm text-gray-800 outline-none focus:border-blue-500 bg-white">
                        <option value="" disabled selected>Pilih Kondisi</option>
                        <option value="New">New</option>
                        <option value="Excellent">Excellent</option>
                        <option value="Good">Good</option>
                        <option value="Fair">Fair</option>
                        <option value="Poor">Poor</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-800 mb-1">Minimal Hari Sewa</label>
                    <input type="number" name="min_rental_days" placeholder="1" value="1" min="1"
                        class="w-full h-10 border border-gray-200 rounded-lg px-3 text-sm text-gray-800 outline-none focus:border-blue-500">
                </div>
            </div>

            <hr class="my-4 border-gray-200">

            {{-- Status --}}
            <div class="mb-6">
                <label class="block text-sm text-gray-800 mb-1">Status</label>
                <select name="status"
                    class="w-full h-10 border border-gray-200 rounded-lg px-3 text-sm text-gray-800 outline-none focus:border-blue-500 bg-white">
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
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
                    Simpan
                </button>
            </div>

        </form>
    </div>
</div>

@endsection