<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\CheckEditAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use CheckEditAccess;

    public function index()
    {
        $users = User::where('is_deleted', 0)
                    ->latest('created_date')
                    ->get();
        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        if ($redirect = $this->checkEditAccess()) return $redirect;

        $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email',
            'password'          => 'required|min:6|confirmed',
            'phone'             => 'required|string|max:20',
            'address'           => 'nullable|string',
            'id_card_number'    => 'nullable|string|max:16',
            'emergency_contact' => 'nullable|string|max:255',
            'company_code'      => 'required|string|max:50',
        ]);

        User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            'phone'             => $request->phone,
            'address'           => $request->address,
            'id_card_number'    => $request->id_card_number,
            'emergency_contact' => $request->emergency_contact,
            'company_code'      => $request->company_code,
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
            'company_code'      => 'nullable|string|max:50',
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'name'              => $request->name,
            'email'             => $request->email,
            'phone'             => $request->phone,
            'address'           => $request->address,
            'id_card_number'    => $request->id_card_number,
            'emergency_contact' => $request->emergency_contact,
            'company_code'      => $request->company_code,
            'status'            => $request->has('status') ? 1 : 0,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil diupdate.');
    }

    public function destroy($id)
    {
        if ($redirect = $this->checkEditAccess()) return $redirect;

        $user = User::findOrFail($id);
        $user->update([
            'is_deleted' => 1,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}