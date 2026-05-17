<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PenaltyController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PaymentController;

// ── GET ──────────────────────────────────────────────────
Route::get('/products',     [ProdukController::class,    'apiIndex']);
Route::get('/users',        [UserController::class,      'apiIndex']);
Route::get('/penalties',    [PenaltyController::class,   'apiIndex']);
Route::get('/transactions', [TransaksiController::class, 'apiIndex']);
Route::get('/categories',   [KategoriController::class,  'apiIndex']);

// ── POST ─────────────────────────────────────────────────
Route::post('/products',     [ProdukController::class,    'apiStore']);
Route::post('/users',        [UserController::class,      'apiStore']);
Route::post('/penalties',    [PenaltyController::class,   'apiStore']);
Route::post('/transactions', [TransaksiController::class, 'apiStore']);
Route::put('/transactions/{id}', [TransaksiController::class, 'apiUpdate']);
Route::post('/categories',   [KategoriController::class,  'apiStore']);

// ── PAYMENT GATEWAY ─────────────────────────────────────────────────
Route::post('/payment/create',               [PaymentController::class, 'createSnapToken']);
Route::post('/payment/notification',         [PaymentController::class, 'notification']);
Route::get('/payment/status/{order_id}',     [PaymentController::class, 'checkStatus']);