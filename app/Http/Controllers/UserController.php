<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\CheckEditAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    use CheckEditAccess;

    public function index()
    {
        $userIds = DB::table('transactions')
            ->whereNotNull('user_id')
            ->where('is_deleted', 0)
            ->distinct()
            ->pluck('user_id');

        $users = User::whereIn('id', $userIds)
            ->where('is_deleted', 0)
            ->orderBy('id', 'asc')
            ->get();

        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        if ($redirect = $this->checkEditAccess()) return $redirect;

        $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email',
            'phone'             => 'required|string|max:20',
            'address'           => 'nullable|string',
            'id_card_number'    => 'nullable|string|max:16',
            'emergency_contact' => 'nullable|string|max:255',
            'company_code'      => 'nullable|string|max:50',
        ]);

        User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => bcrypt(str()->random(16)),
            'phone'             => $request->phone,
            'address'           => $request->address,
            'id_card_number'    => $request->id_card_number,
            'emergency_contact' => $request->emergency_contact,
            'company_code'      => null, // akan diisi otomatis saat transaksi pertama
            'status'            => $request->has('status') ? 1 : 0,
            'is_deleted'        => 0,
            'created_by'        => auth()->user()->name ?? 'system',
            'created_date'      => now(),
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        if ($redirect = $this->checkEditAccess()) return $redirect;

        $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email,' . $id,
            'phone'             => 'nullable|string|max:20',
            'address'           => 'nullable|string',
            'id_card_number'    => 'nullable|string|max:16',
            'emergency_contact' => 'nullable|string|max:255',
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'name'              => $request->name,
            'email'             => $request->email,
            'phone'             => $request->phone,
            'address'           => $request->address,
            'id_card_number'    => $request->id_card_number,
            'emergency_contact' => $request->emergency_contact,
            // company_code tidak diupdate dari form, dikelola otomatis
            'status'            => $request->has('status') ? 1 : 0,
            'last_updated_date' => now(),
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil diupdate.');
    }

    public function destroy($id)
    {
        if ($redirect = $this->checkEditAccess()) return $redirect;

        $user = User::findOrFail($id);
        $user->update([
            'is_deleted'        => 1,
            'last_updated_date' => now(),
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}