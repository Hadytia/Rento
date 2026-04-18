@extends('layouts.app')

@section('content')

<div class="p-6 w-full">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Kelola Produk</h1>
            <p class="text-sm text-gray-500">Atur inventaris, harga sewa, dan kondisi produk.</p>
        </div>
        <button onclick="openAddModal()"
            class="bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium px-4 py-2 rounded-lg flex items-center gap-2">
            + Tambah Produk
        </button>
    </div>

    {{-- Card --}}
    <div class="bg-white rounded-xl shadow p-6 w-full">

        {{-- Subheader + Search --}}
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-semibold text-gray-700">Inventaris Produk ({{ $produks->count() }})</h2>
            <input type="text" id="searchInput" placeholder="Cari produk..."
                class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 w-52" />
        </div>

        {{-- Table --}}
        <table class="w-full text-sm text-left" id="productTable">
            <thead>
                <tr class="border-b text-gray-500 text-xs uppercase">
                    <th class="py-3 pr-4 w-24">Aksi</th>
                    <th class="py-3 pr-4">Foto & Nama Produk</th>
                    <th class="py-3 pr-4">Kategori</th>
                    <th class="py-3 pr-4">Harga / Hari</th>
                    <th class="py-3 pr-4">Stok</th>
                    <th class="py-3 pr-4">Kondisi</th>
                    <th class="py-3">Min. Sewa</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($produks as $produk)
                <tr class="hover:bg-gray-50 product-row">
                    {{-- Actions --}}
                    <td class="py-3 pr-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('produks.edit', $produk->id) }}"
                                class="flex items-center gap-1 text-blue-600 hover:text-blue-800 text-xs font-medium">
                                ✏️ Edit
                            </a>
                            <form action="{{ route('produks.destroy', $produk->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="flex items-center gap-1 text-red-500 hover:text-red-700 text-xs font-medium"
                                    onclick="return confirm('Yakin hapus produk ini?')">
                                    🗑️
                                </button>
                            </form>
                        </div>
                    </td>

                    {{-- Foto + Nama Produk --}}
                    <td class="py-3 pr-4" style="min-width:220px">
                        <div class="flex items-center" style="gap:14px">
                            @if($produk->photo)
                                <img src="{{ asset('products/' . $produk->photo) }}"
                                     alt="{{ $produk->product_name }}"
                                     style="width:48px;height:48px;border-radius:8px;object-fit:cover;border:1px solid #E5E7EB;flex-shrink:0;">
                            @else
                                <div style="width:48px;height:48px;border-radius:8px;background:#F3F4F6;border:1px solid #E5E7EB;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <svg width="20" height="20" fill="none" stroke="#9CA3AF" stroke-width="1.5" viewBox="0 0 24 24">
                                        <rect x="3" y="3" width="18" height="18" rx="2"/>
                                        <circle cx="8.5" cy="8.5" r="1.5"/>
                                        <polyline points="21,15 16,10 5,21"/>
                                    </svg>
                                </div>
                            @endif
                            <span class="font-medium text-gray-800 product-name">{{ $produk->product_name }}</span>
                        </div>
                    </td>

                    {{-- Category --}}
                    <td class="py-3 pr-4 text-blue-500">
                        {{ $produk->kategori->category_name ?? '-' }}
                    </td>

                    {{-- Price --}}
                    <td class="py-3 pr-4 font-medium text-gray-800">
                        Rp {{ number_format($produk->rental_price, 0, ',', '.') }}
                    </td>

                    {{-- Stock Badge --}}
                    <td class="py-3 pr-4">
                        @php
                            $stock = $produk->stock;
                            $badgeColor = $stock <= 3 ? 'bg-red-500' : ($stock <= 7 ? 'bg-yellow-400' : 'bg-green-500');
                        @endphp
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-white text-xs font-bold {{ $badgeColor }}">
                            {{ $stock }}
                        </span>
                    </td>

                    {{-- Condition Badge --}}
                    <td class="py-3 pr-4">
                        @php
                            $condition = $produk->condition;
                            $conditionColor = match(strtolower($condition)) {
                                'excellent' => 'text-green-600 border-green-400',
                                'good'      => 'text-green-500 border-green-300',
                                'fair'      => 'text-yellow-500 border-yellow-400',
                                'poor'      => 'text-red-500 border-red-400',
                                'new'       => 'text-blue-500 border-blue-400',
                                default     => 'text-gray-500 border-gray-300',
                            };
                        @endphp
                        <span class="border rounded-full px-3 py-0.5 text-xs font-medium {{ $conditionColor }}">
                            {{ $condition }}
                        </span>
                    </td>

                    {{-- Min Rental Days --}}
                    <td class="py-3 text-gray-600">
                        {{ $produk->min_rental_days }} hari
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Search Script --}}
<script>
    document.getElementById('searchInput').addEventListener('keyup', function () {
        const query = this.value.toLowerCase();
        document.querySelectorAll('.product-row').forEach(row => {
            const name = row.querySelector('.product-name').textContent.toLowerCase();
            row.style.display = name.includes(query) ? '' : 'none';
        });
    });
</script>

{{-- Modal Add Product --}}
<div id="addProductModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">
    <div class="bg-white rounded-xl shadow-xl w-[680px] max-h-[90vh] overflow-y-auto p-6 relative">

        {{-- Header --}}
        <div class="flex items-start justify-between mb-1">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Tambah Produk Baru</h2>
                <p class="text-sm text-gray-500 mt-1">Tambahkan item baru ke katalog rental. Kolom bertanda * wajib diisi.</p>
            </div>
            <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600 text-xl leading-none mt-1">✕</button>
        </div>

        <hr class="my-4 border-gray-200">

        <form id="addProductForm" action="{{ route('produks.store') }}" method="POST" enctype="multipart/form-data">
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

            {{-- Foto Produk --}}
            <div class="mb-4">
                <label class="block text-sm text-gray-800 mb-1">Foto Produk</label>
                <input type="file" name="photo" accept="image/*"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800 outline-none focus:border-blue-500">
                <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG. Maks 2MB.</p>
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
                <button type="button" onclick="closeAddModal()"
                    class="h-10 px-4 bg-gray-100 hover:bg-gray-200 border border-gray-200 rounded-lg text-sm text-gray-700">
                    Batal
                </button>
                <button type="submit"
                    class="h-10 px-6 bg-blue-700 hover:bg-blue-800 text-white rounded-lg text-sm font-medium">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddModal() {
        const modal = document.getElementById('addProductModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeAddModal() {
        const modal = document.getElementById('addProductModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.getElementById('addProductModal').addEventListener('click', function (e) {
        if (e.target === this) closeAddModal();
    });
</script>

@endsection