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

    public function apiIndex(Request $request)
    {
        $query = Produk::with('kategori')
            ->where('is_deleted', 0)
            ->where('status', 1);

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        $produks = $query->get()->map(function ($produk) {
            return [
                'id'              => $produk->id,
                'product_name'    => $produk->product_name,
                'description'     => $produk->description,
                'rental_price'    => $produk->rental_price,
                'stock'           => $produk->stock,
                'condition'       => $produk->condition,
                'min_rental_days' => $produk->min_rental_days,
                'category'        => $produk->kategori?->category_name,
                'photo'           => $produk->photo
                    ? asset('products/' . $produk->photo)
                    : null,
            ];
        });

        return response()->json([
            'success' => true,
            'data'    => $produks,
        ]);
    }

    public function apiStore(Request $request)
    {
        $request->validate([
            'product_name'    => 'required|string|max:255',
            'description'     => 'nullable|string',
            'rental_price'    => 'required|numeric|min:0',
            'stock'           => 'required|integer|min:0',
            'condition'       => 'required|in:New,Excellent,Good,Fair,Poor',
            'category_id'     => 'required|exists:categories,id',
            'min_rental_days' => 'nullable|integer|min:1',
        ]);

        $produk = Produk::create([
            'product_name'    => $request->product_name,
            'description'     => $request->description,
            'rental_price'    => $request->rental_price,
            'stock'           => $request->stock,
            'condition'       => $request->condition,
            'category_id'     => $request->category_id,
            'min_rental_days' => $request->min_rental_days ?? 1,
            'photo'           => null,
            'status'          => 1,
            'is_deleted'      => 0,
            'created_by'      => 'api',
            'created_date'    => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan.',
            'data'    => [
                'id'           => $produk->id,
                'product_name' => $produk->product_name,
                'rental_price' => $produk->rental_price,
                'stock'        => $produk->stock,
                'condition'    => $produk->condition,
            ],
        ], 201);
    }
}