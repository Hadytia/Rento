<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Traits\CheckEditAccess;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    use CheckEditAccess;

    public function index()
    {
        $kategoris = Kategori::where('is_deleted', 0)
                        ->latest('created_date')
                        ->get();
        return view('kategoris.index', compact('kategoris'));
    }

    public function store(Request $request)
    {
        if ($redirect = $this->checkEditAccess()) return $redirect;

        $request->validate([
            'category_name' => 'required|string|max:255',
            'description'   => 'nullable|string',
            'status'        => 'required|in:1,0',
        ]);

        Kategori::create([
            'category_name' => $request->category_name,
            'description'   => $request->description,
            'status'        => $request->status,
            'is_deleted'    => 0,
            'created_by'    => auth()->user()->name ?? 'system',
            'created_date'  => now(),
        ]);

        return redirect()->route('kategoris.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        if ($redirect = $this->checkEditAccess()) return $redirect;

        $request->validate([
            'category_name' => 'required|string|max:255',
            'description'   => 'nullable|string',
            'status'        => 'required|in:1,0',
        ]);

        $kategori = Kategori::findOrFail($id);
        $kategori->update([
            'category_name'     => $request->category_name,
            'description'       => $request->description,
            'status'            => $request->status,
            'last_updated_by'   => auth()->user()->name ?? 'system',
            'last_updated_date' => now(),
        ]);

        return redirect()->route('kategoris.index')->with('success', 'Kategori berhasil diupdate.');
    }

    public function destroy($id)
    {
        if ($redirect = $this->checkEditAccess()) return $redirect;

        $kategori = Kategori::findOrFail($id);
        $kategori->update([
            'is_deleted'        => 1,
            'last_updated_by'   => auth()->user()->name ?? 'system',
            'last_updated_date' => now(),
        ]);

        return redirect()->route('kategoris.index')->with('success', 'Kategori berhasil dihapus.');
    }

        public function apiIndex(Request $request)
    {
        $kategoris = Kategori::where('is_deleted', 0)
            ->where('status', 1)
            ->orderBy('category_name')
            ->get()
            ->map(function ($k) {
                return [
                    'id'            => $k->id,
                    'category_name' => $k->category_name,
                    'description'   => $k->description,
                    'status'        => $k->status,
                ];
            });

        return response()->json([
            'success' => true,
            'data'    => $kategoris,
        ]);
    }

    public function apiStore(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
            'description'   => 'nullable|string',
            'status'        => 'nullable|in:1,0',
        ]);

        $kategori = Kategori::create([
            'category_name' => $request->category_name,
            'description'   => $request->description,
            'status'        => $request->status ?? 1,
            'is_deleted'    => 0,
            'created_by'    => 'api',
            'created_date'  => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil ditambahkan.',
            'data'    => [
                'id'            => $kategori->id,
                'category_name' => $kategori->category_name,
                'description'   => $kategori->description,
                'status'        => $kategori->status,
            ],
        ], 201);
    }
}