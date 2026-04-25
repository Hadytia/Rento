<?php

namespace App\Http\Controllers;

use App\Mail\DosenApproved;
use App\Mail\DosenRejected;
use App\Models\Admin;
use App\Traits\CheckEditAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    use CheckEditAccess;

    public function index()
    {
        $currentAdmin = Admin::where('email', Auth::user()->email)
                            ->where('status', 1)
                            ->where('is_deleted', 0)
                            ->first();

        if ($currentAdmin && $currentAdmin->role === 'dosen') {
            return redirect('/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $admins = Admin::where('is_deleted', 0)
                    ->where('status', 1)
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
            'status'       => $request->input('status') == 1 ? 1 : 0,
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

        if ($targetAdmin->role === 'superadmin' && $currentAdmin->role !== 'superadmin') {
            return redirect()->route('admins.index')->with('error', 'Anda tidak memiliki akses untuk mengedit akun Superadmin.');
        }

        if ($request->role === 'superadmin' && $currentAdmin->role !== 'superadmin') {
            return redirect()->route('admins.index')->with('error', 'Hanya Superadmin yang boleh mengatur role Superadmin.');
        }

        $data = [
            'name'              => $request->name,
            'email'             => $request->email,
            'role'              => $request->role,
            'status'            => $request->input('status') == 1 ? 1 : 0,
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

        if ($targetAdmin->role === 'superadmin' && $currentAdmin->role !== 'superadmin') {
            return redirect()->route('admins.index')->with('error', 'Anda tidak memiliki akses untuk menghapus akun Superadmin.');
        }

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

    // ✅ ACC dosen pending + kirim email notifikasi
    public function approve($id)
    {
        $admin = Admin::findOrFail($id);
        $admin->update([
            'status'            => 1,
            'last_updated_by'   => Auth::user()->name ?? 'system',
            'last_updated_date' => now(),
        ]);

        // Kirim email notifikasi ke dosen
        try {
            Mail::to($admin->email)->send(new DosenApproved(
                dosenName:  $admin->name,
                dosenEmail: $admin->email,
                approvedBy: Auth::user()->name ?? 'Administrator',
            ));
        } catch (\Exception $e) {
            // Kalau email gagal, tetap redirect sukses
            return redirect()->route('admins.index')
                ->with('success', "Akun {$admin->name} berhasil disetujui. (Email gagal terkirim: {$e->getMessage()})");
        }

        return redirect()->route('admins.index')
            ->with('success', "Akun {$admin->name} berhasil disetujui dan email notifikasi telah dikirim.");
    }

    // ✅ Reject dosen pending + kirim email notifikasi
    public function reject($id)
    {
        $admin = Admin::findOrFail($id);

        // Simpan data sebelum dihapus
        $dosenName  = $admin->name;
        $dosenEmail = $admin->email;

        $admin->update([
            'is_deleted'        => 1,
            'last_updated_by'   => Auth::user()->name ?? 'system',
            'last_updated_date' => now(),
        ]);

        // Kirim email notifikasi penolakan
        try {
            Mail::to($dosenEmail)->send(new DosenRejected(
                dosenName:  $dosenName,
                dosenEmail: $dosenEmail,
                rejectedBy: Auth::user()->name ?? 'Administrator',
            ));
        } catch (\Exception $e) {
            return redirect()->route('admins.index')
                ->with('success', "Akun {$dosenName} berhasil ditolak. (Email gagal terkirim: {$e->getMessage()})");
        }

        return redirect()->route('admins.index')
            ->with('success', "Akun {$dosenName} berhasil ditolak dan email notifikasi telah dikirim.");
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