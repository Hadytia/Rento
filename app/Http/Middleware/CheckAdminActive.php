<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdminActive
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $admin = Admin::where('email', Auth::user()->email)
                         ->first();

            // Akun dihapus atau dinonaktifkan → force logout
            if (!$admin || $admin->is_deleted == 1 || $admin->status == 0) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect('/login')->with('error', 'Sesi Anda telah berakhir karena akun dinonaktifkan atau dihapus.');
            }
        }

        return $next($request);
    }
}