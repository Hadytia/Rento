<?php

namespace App\Traits;

use App\Models\Admin;
use Illuminate\Support\Facades\Auth;

trait CheckEditAccess
{
    /**
     * Cek apakah user yang sedang login punya akses edit/hapus.
     * Kalau tidak punya akses → redirect balik dengan pesan error.
     */
    protected function checkEditAccess()
    {
        $user  = Auth::user();
        $admin = Admin::where('email', $user->email)
                     ->where('status', 1)
                     ->where('is_deleted', 0)
                     ->first();

        if (!$admin) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk melakukan tindakan ini.');
        }

        // Superadmin & admin → selalu bisa
        if (in_array($admin->role, ['superadmin', 'admin', 'staff'])) {
            return null; // null = boleh lanjut
        }

        // Dosen → cek can_edit
        if ($admin->role === 'dosen' && !$admin->can_edit) {
            return redirect()->back()->with('error', '⚠️ Anda tidak memiliki akses untuk melakukan tindakan ini. Hubungi Admin untuk mendapatkan akses edit.');
        }

        return null; // null = boleh lanjut
    }
}