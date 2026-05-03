<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\Admin;
use App\Models\OtpCode;
use App\Mail\OtpMail;
use Exception;
use PragmaRX\Google2FA\Google2FA;

class LoginController extends Controller
{
    private function getGoogle2FA(): Google2FA
    {
        $g = new Google2FA();
        $g->setEnforceGoogleAuthenticatorCompatibility(true);
        return $g;
    }

    public function showLoginForm()
    {
        if (Auth::check() && !session('2fa_admin_id')) {
            return redirect()->intended('/dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();

            $admin = Admin::where('email', Auth::user()->email)
                         ->where('status', 1)
                         ->where('is_deleted', 0)
                         ->first();

            if (!$admin) {
                Auth::logout();
                return back()->withErrors(['email' => 'Akun Anda tidak memiliki akses ke sistem ini.']);
            }

            Auth::logout();
            session(['2fa_admin_id' => $admin->id]);

            if (!$admin->two_factor_secret) {
                return redirect()->route('2fa.setup');
            }

            return redirect()->route('2fa.choose');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Email atau password salah.']);
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Exception $e) {
            return redirect('/login')->with('error', 'Login Google gagal, silakan coba lagi.');
        }

        // ✅ Cek semua kondisi termasuk is_deleted
        $admin = Admin::where('email', $googleUser->getEmail())->first();

        // ✅ Akun ditemukan tapi is_deleted = 1
        if ($admin && $admin->is_deleted == 1) {

            // ✅ Kalau role superadmin/admin/staff → restore dengan role asli
            if (in_array($admin->role, ['superadmin', 'admin', 'staff'])) {
                $admin->update([
                    'is_deleted'   => 0,
                    'status'       => 1,
                    'updated_date' => now(),
                ]);
                // Lanjut ke proses 2FA
            } else {
                // ✅ Role dosen yang ditolak → restore sebagai pending
                $admin->update([
                    'is_deleted'   => 0,
                    'status'       => 0,
                    'role'         => 'dosen',
                    'can_edit'     => 0,
                    'created_date' => now(),
                ]);
                return redirect()->route('auth.pending');
            }
        }

        // ✅ Belum terdaftar sama sekali → auto register sebagai dosen pending
        if (!$admin) {
            $admin = Admin::create([
                'name'         => $googleUser->getName(),
                'email'        => $googleUser->getEmail(),
                'password'     => '',
                'role'         => 'dosen',
                'can_edit'     => 0,
                'status'       => 0,
                'is_deleted'   => 0,
                'created_by'   => 'google',
                'created_date' => now(),
            ]);

            $user = User::where('google_id', $googleUser->getId())->first();
            if (!$user) {
                $user = User::where('email', $googleUser->getEmail())->first();
                if ($user) {
                    $user->update(['google_id' => $googleUser->getId()]);
                } else {
                    $user = User::create([
                        'name'         => $googleUser->getName(),
                        'email'        => $googleUser->getEmail(),
                        'google_id'    => $googleUser->getId(),
                        'password'     => bcrypt(str()->random(24)),
                        'company_code' => 'RENT-01',
                        'status'       => 1,
                        'is_deleted'   => 0,
                        'created_date' => now(),
                    ]);
                }
            }

            return redirect()->route('auth.pending');
        }

        // ✅ Status 0 → cek rolenya
        if ($admin->status == 0) {
            if ($admin->role === 'dosen') {
                return redirect()->route('auth.pending');
            }
            return redirect('/login')->with('error', 'Akun Anda sedang dinonaktifkan. Hubungi Superadmin.');
        }

        // ✅ Terdaftar dan aktif → lanjut 2FA
        $user = User::where('google_id', $googleUser->getId())->first();
        if (!$user) {
            $user = User::where('email', $googleUser->getEmail())->first();
            if ($user) {
                $user->update(['google_id' => $googleUser->getId()]);
            } else {
                $user = User::create([
                    'name'         => $googleUser->getName(),
                    'email'        => $googleUser->getEmail(),
                    'google_id'    => $googleUser->getId(),
                    'password'     => bcrypt(str()->random(24)),
                    'company_code' => 'RENT-01',
                    'status'       => 1,
                    'is_deleted'   => 0,
                    'created_date' => now(),
                ]);
            }
        }

        session([
            '2fa_admin_id' => $admin->id,
            '2fa_user_id'  => $user->id,
        ]);

        if (!$admin->two_factor_secret) {
            return redirect()->route('2fa.setup');
        }

        return redirect()->route('2fa.choose');
    }

    public function show2FAChoose()
    {
        if (!session('2fa_admin_id')) return redirect('/login');
        return view('auth.2fa-choose');
    }

    public function show2FASetup()
    {
        $adminId = session('2fa_admin_id');
        if (!$adminId) return redirect('/login');

        $admin     = Admin::findOrFail($adminId);
        $google2fa = $this->getGoogle2FA();

        if (!$admin->two_factor_secret) {
            $secret = $google2fa->generateSecretKey(16);
            $admin->two_factor_secret = $secret;
            $admin->save();
        }

        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $admin->email,
            $admin->two_factor_secret
        );

        return view('auth.2fa-setup', compact('qrCodeUrl', 'admin'));
    }

    public function show2FAVerify()
    {
        if (!session('2fa_admin_id')) return redirect('/login');
        return view('auth.2fa-verify');
    }

    public function verify2FA(Request $request)
    {
        $request->validate(['one_time_password' => 'required|digits:6']);

        $adminId = session('2fa_admin_id');
        $userId  = session('2fa_user_id');
        if (!$adminId) return redirect('/login');

        $admin     = Admin::findOrFail($adminId);
        $google2fa = $this->getGoogle2FA();

        if (!$google2fa->verifyKey($admin->two_factor_secret, $request->one_time_password)) {
            return back()->withErrors(['one_time_password' => 'Kode OTP salah atau sudah kadaluarsa.']);
        }

        // ✅ Cek status setelah 2FA berhasil
        if ($redirect = $this->checkAdminStatus($admin, $request)) {
            return $redirect;
        }

        $this->loginUser($adminId, $userId, $request);
        return redirect()->intended('/dashboard');
    }

    public function sendEmailOtp()
    {
        $adminId = session('2fa_admin_id');
        if (!$adminId) return redirect('/login');

        $admin = Admin::findOrFail($adminId);

        OtpCode::where('admin_id', $adminId)->delete();

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        OtpCode::create([
            'admin_id'   => $adminId,
            'code'       => $code,
            'expires_at' => now()->addMinutes(5),
            'used'       => false,
            'created_at' => now(),
        ]);

        Mail::to($admin->email)->send(new OtpMail($code, $admin->name));

        return redirect()->route('2fa.email.verify');
    }

    public function showEmailOtpVerify()
    {
        if (!session('2fa_admin_id')) return redirect('/login');
        return view('auth.2fa-email-verify');
    }

    public function verifyEmailOtp(Request $request)
    {
        $request->validate(['otp_code' => 'required|digits:6']);

        $adminId = session('2fa_admin_id');
        $userId  = session('2fa_user_id');
        if (!$adminId) return redirect('/login');

        $otp = OtpCode::where('admin_id', $adminId)
                      ->where('code', $request->otp_code)
                      ->where('used', false)
                      ->where('expires_at', '>', now())
                      ->first();

        if (!$otp) {
            return back()->withErrors(['otp_code' => 'Kode OTP salah atau sudah kadaluarsa.']);
        }

        $otp->update(['used' => true]);

        $admin = Admin::findOrFail($adminId);

        // ✅ Cek status setelah 2FA berhasil
        if ($redirect = $this->checkAdminStatus($admin, $request)) {
            return $redirect;
        }

        $this->loginUser($adminId, $userId, $request);
        return redirect()->intended('/dashboard');
    }

    // ✅ Cek status admin setelah 2FA berhasil
    private function checkAdminStatus(Admin $admin, Request $request)
    {
        // Fresh dari DB supaya status selalu terbaru
        $admin = $admin->fresh();

        if ($admin->status == 0) {
            session()->forget(['2fa_admin_id', '2fa_user_id']);
            $request->session()->regenerate();

            if ($admin->role === 'dosen') {
                return redirect()->route('auth.pending');
            }
            return redirect('/login')->with('error', 'Akun Anda sedang dinonaktifkan. Hubungi Superadmin.');
        }
        return null;
    }

    private function loginUser($adminId, $userId, $request)
    {
        $admin = Admin::findOrFail($adminId);

        if ($userId) {
            $user = User::findOrFail($userId);
        } else {
            $user = User::where('email', $admin->email)->firstOrFail();
        }

        Auth::login($user, false);
        session()->forget(['2fa_admin_id', '2fa_user_id']);
        $request->session()->regenerate();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}