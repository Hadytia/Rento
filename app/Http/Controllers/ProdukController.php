<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use App\Traits\CheckEditAccess;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    use CheckEditAccess;

    public function index()
    {
        $produks = Produk::with('kategori')
                    ->where('is_deleted', 0)
                    ->get();
        $kategoris = Kategori::where('is_deleted', 0)->get();
        return view('produk.index', compact('produks', 'kategoris'));
    }

    public function create()
    {
        $kategoris = Kategori::where('is_deleted', 0)->get();
        return view('produk.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        if ($redirect = $this->checkEditAccess()) return $redirect;

        $request->validate([
            'product_name'    => 'required',
            'rental_price'    => 'required|numeric',
            'stock'           => 'required|integer',
            'condition'       => 'required',
            'category_id'     => 'required|exists:categories,id',
            'photo'           => 'nullable|image|max:2048',
            'created_date'    => 'nullable|date',
            'min_rental_days' => 'nullable|integer|min:1',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('products', 'public');
        }

        Produk::create([
            'product_name'    => $request->product_name,
            'description'     => $request->description,
            'rental_price'    => $request->rental_price,
            'stock'           => $request->stock,
            'condition'       => $request->condition,
            'category_id'     => $request->category_id,
            'photo'           => $photoPath,
            'created_date'    => $request->created_date ?? now(),
            'min_rental_days' => $request->min_rental_days ?? 1,
            'status'          => 1,
            'is_deleted'      => 0,
            'created_by'      => auth()->user()->name ?? 'system',
        ]);

        return redirect()->route('produks.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $produk    = Produk::findOrFail($id);
        $kategoris = Kategori::where('is_deleted', 0)->get();
        return view('produk.edit', compact('produk', 'kategoris'));
    }

    public function update(Request $request, $id)
    {
        if ($redirect = $this->checkEditAccess()) return $redirect;

        $request->validate([
            'product_name' => 'required',
            'rental_price' => 'required|numeric',
            'stock'        => 'required|integer',
            'condition'    => 'required',
            'category_id'  => 'required|exists:categories,id',
            'photo'        => 'nullable|image|max:2048',
        ]);

        $produk = Produk::findOrFail($id);

        $photoPath = $produk->photo;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('products', 'public');
        }

        $produk->update([
            'product_name'      => $request->product_name,
            'description'       => $request->description,
            'rental_price'      => $request->rental_price,
            'stock'             => $request->stock,
            'condition'         => $request->condition,
            'category_id'       => $request->category_id,
            'photo'             => $photoPath,
            'last_updated_by'   => auth()->user()->name ?? 'system',
            'last_updated_date' => now(),
        ]);

        return redirect()->route('produks.index')->with('success', 'Produk berhasil diupdate.');
    }

    public function destroy($id)
    {
        if ($redirect = $this->checkEditAccess()) return $redirect;

        $produk = Produk::findOrFail($id);
        $produk->update([
            'is_deleted'        => 1,
            'last_updated_by'   => auth()->user()->name ?? 'system',
            'last_updated_date' => now(),
        ]);

        return redirect()->route('produks.index')->with('success', 'Produk berhasil dihapus.');
    }
}