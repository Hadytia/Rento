<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Traits\CheckEditAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    use CheckEditAccess;

    public function index()
    {
        // ✅ Dosen tidak boleh akses halaman admin
        $currentAdmin = Admin::where('email', Auth::user()->email)
                            ->where('status', 1)
                            ->where('is_deleted', 0)
                            ->first();

        if ($currentAdmin && $currentAdmin->role === 'dosen') {
            return redirect('/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $admins = Admin::where('is_deleted', 0)
                    ->latest('created_date')
                    ->get();

        $pendingDosens = Admin::where('is_deleted', 0)
                    ->where('status', 0)
                    ->where('role', 'dosen')
                    ->latest('created_date')
                    ->get();

        return view('admins.index', compact('admins', 'pendingDosens'));
    }

    public function store(Request $request)
    {
        if ($redirect = $this->checkEditAccess()) return $redirect;

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:admins,email',
            'password' => 'required|string|min:6',
            'role'     => 'required|in:superadmin,admin,staff,dosen',
            'status'   => 'nullable|in:1,0',
        ]);

        // ✅ Hanya superadmin yang boleh tambah superadmin
        $currentAdmin = Admin::where('email', Auth::user()->email)->first();
        if ($request->role === 'superadmin' && $currentAdmin->role !== 'superadmin') {
            return redirect()->route('admins.index')->with('error', 'Hanya Superadmin yang boleh menambah akun Superadmin.');
        }

        Admin::create([
            'name'         => $request->name,
            'email'        => $request->email,
            'password'     => bcrypt($request->password),
            'role'         => $request->role,
            'can_edit'     => 0,
            'status'       => $request->input('status') == 1 ? 1 : 0, // ✅ Fix
            'is_deleted'   => 0,
            'created_by'   => Auth::user()->name ?? 'system',
            'created_date' => now(),
        ]);

        return redirect()->route('admins.index')->with('success', 'Admin berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        if ($redirect = $this->checkEditAccess()) return $redirect;

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:admins,email,' . $id,
            'password' => 'nullable|string|min:6',
            'role'     => 'required|in:superadmin,admin,staff,dosen',
            'status'   => 'nullable|in:1,0',
        ]);

        $targetAdmin  = Admin::findOrFail($id);
        $currentAdmin = Admin::where('email', Auth::user()->email)->first();

        // ✅ Hanya superadmin yang boleh edit superadmin
        if ($targetAdmin->role === 'superadmin' && $currentAdmin->role !== 'superadmin') {
            return redirect()->route('admins.index')->with('error', 'Anda tidak memiliki akses untuk mengedit akun Superadmin.');
        }

        // ✅ Hanya superadmin yang boleh upgrade role ke superadmin
        if ($request->role === 'superadmin' && $currentAdmin->role !== 'superadmin') {
            return redirect()->route('admins.index')->with('error', 'Hanya Superadmin yang boleh mengatur role Superadmin.');
        }

        $data = [
            'name'              => $request->name,
            'email'             => $request->email,
            'role'              => $request->role,
            'status'            => $request->input('status') == 1 ? 1 : 0, // ✅ Fix
            'last_updated_by'   => Auth::user()->name ?? 'system',
            'last_updated_date' => now(),
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $targetAdmin->update($data);

        return redirect()->route('admins.index')->with('success', 'Admin berhasil diupdate.');
    }

    public function destroy($id)
    {
        if ($redirect = $this->checkEditAccess()) return $redirect;

        $targetAdmin  = Admin::findOrFail($id);
        $currentAdmin = Admin::where('email', Auth::user()->email)->first();

        // ✅ Hanya superadmin yang boleh hapus superadmin
        if ($targetAdmin->role === 'superadmin' && $currentAdmin->role !== 'superadmin') {
            return redirect()->route('admins.index')->with('error', 'Anda tidak memiliki akses untuk menghapus akun Superadmin.');
        }

        // ✅ Tidak boleh hapus diri sendiri
        if ($targetAdmin->email === Auth::user()->email) {
            return redirect()->route('admins.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $targetAdmin->update([
            'is_deleted'        => 1,
            'last_updated_by'   => Auth::user()->name ?? 'system',
            'last_updated_date' => now(),
        ]);

        return redirect()->route('admins.index')->with('success', 'Admin berhasil dihapus.');
    }

    // ✅ ACC dosen pending
    public function approve($id)
    {
        $admin = Admin::findOrFail($id);
        $admin->update([
            'status'            => 1,
            'last_updated_by'   => Auth::user()->name ?? 'system',
            'last_updated_date' => now(),
        ]);

        return redirect()->route('admins.index')->with('success', "Akun {$admin->name} berhasil disetujui.");
    }

    // ✅ Reject dosen pending
    public function reject($id)
    {
        $admin = Admin::findOrFail($id);
        $admin->update([
            'is_deleted'        => 1,
            'last_updated_by'   => Auth::user()->name ?? 'system',
            'last_updated_date' => now(),
        ]);

        return redirect()->route('admins.index')->with('success', "Akun {$admin->name} berhasil ditolak.");
    }

    // ✅ Toggle akses edit/hapus untuk dosen
    public function toggleEdit($id)
    {
        $admin    = Admin::findOrFail($id);
        $newValue = $admin->can_edit ? 0 : 1;
        $admin->update([
            'can_edit'          => $newValue,
            'last_updated_by'   => Auth::user()->name ?? 'system',
            'last_updated_date' => now(),
        ]);

        $status = $newValue ? 'diberikan' : 'dicabut';
        return redirect()->route('admins.index')->with('success', "Akses edit {$admin->name} berhasil {$status}.");
    }
}
