<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PenaltyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransaksiController;

/*
|--------------------------------------------------------------------------
| WEB ROUTES
|--------------------------------------------------------------------------
*/

// =========================
// HOME
// =========================
Route::get('/', function () {
    return view('welcome');
    // return redirect('https://rento-gules.vercel.app/');
});

// =========================
// LOGIN (PUBLIC)
// =========================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::get('/login/google', [LoginController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/login/google/callback', [LoginController::class, 'handleGoogleCallback']);

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Pending approval page
Route::get('/auth/pending', function () {
    return view('auth.pending');
})->name('auth.pending');

// =========================
// 2FA (PUBLIC — belum login)
// =========================
Route::get('/2fa/setup',         [LoginController::class, 'show2FASetup'])->name('2fa.setup');
Route::get('/2fa/choose',        [LoginController::class, 'show2FAChoose'])->name('2fa.choose');
Route::get('/2fa/verify',        [LoginController::class, 'show2FAVerify'])->name('2fa.verify');
Route::post('/2fa/verify',       [LoginController::class, 'verify2FA'])->name('2fa.verify.post');
Route::get('/2fa/email/send',    [LoginController::class, 'sendEmailOtp'])->name('2fa.email.send');
Route::get('/2fa/email/verify',  [LoginController::class, 'showEmailOtpVerify'])->name('2fa.email.verify');
Route::post('/2fa/email/verify', [LoginController::class, 'verifyEmailOtp'])->name('2fa.email.verify.post');

// =========================
// PROTECTED ROUTES (AUTH)
// =========================
Route::middleware(['auth'])->group(function () {

    // ── Dashboard ──
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Kategori ──
    Route::resource('kategoris', KategoriController::class)->only([
        'index', 'store', 'update', 'destroy'
    ]);

    // ── Produk ──
    Route::get('/produk', [ProdukController::class, 'index'])->name('produks.index');
    Route::get('/produk/create', [ProdukController::class, 'create'])->name('produks.create');
    Route::post('/produk/store', [ProdukController::class, 'store'])->name('produks.store');
    Route::get('/produk/edit/{id}', [ProdukController::class, 'edit'])->name('produks.edit');
    Route::put('/produk/update/{id}', [ProdukController::class, 'update'])->name('produks.update');
    Route::delete('/produk/delete/{id}', [ProdukController::class, 'destroy'])->name('produks.destroy');

    // ── Users ──
    Route::resource('users', UserController::class)->only([
        'index', 'store', 'update', 'destroy'
    ]);

    // ── Reports ──
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
    Route::get('/reports/download/{id}', [ReportController::class, 'download'])->name('reports.download');

    // ── Transaksi ──
    Route::get('/transaksi/create',        [TransaksiController::class, 'create'])->name('transaksi.create');
    Route::post('/transaksi/store',        [TransaksiController::class, 'store'])->name('transaksi.store');
    Route::get('/transaksi/edit/{id}',     [TransaksiController::class, 'edit'])->name('transaksi.edit');
    Route::put('/transaksi/update/{id}',   [TransaksiController::class, 'update'])->name('transaksi.update');
    Route::patch('/transaksi/{id}/return', [TransaksiController::class, 'returnItem'])->name('transaksi.return');
    Route::delete('/transaksi/{id}',       [TransaksiController::class, 'destroy'])->name('transaksi.destroy');

    // ── Penalties ──
    Route::get('/penalties', [PenaltyController::class, 'index'])->name('penalties.index');
    Route::patch('/penalties/{id}/resolve', [PenaltyController::class, 'markResolved'])->name('penalties.resolve');
    Route::patch('/penalties/{id}/finish', [PenaltyController::class, 'markFinished'])->name('penalties.finish');
    Route::post('/penalties/send-reminder', [PenaltyController::class, 'sendReminder'])->name('penalties.send-reminder');

    // ── Admin ──
    Route::resource('admins', AdminController::class)->only([
        'index', 'store', 'update', 'destroy'
    ]);

    // ── Admin approval & toggle ──
    Route::patch('/admins/{id}/approve', [AdminController::class, 'approve'])->name('admins.approve');
    Route::patch('/admins/{id}/reject', [AdminController::class, 'reject'])->name('admins.reject');
    Route::patch('/admins/{id}/toggle-edit', [AdminController::class, 'toggleEdit'])->name('admins.toggleEdit');

});