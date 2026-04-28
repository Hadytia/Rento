<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PenaltyController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\KategoriController;

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
Route::post('/categories',   [KategoriController::class,  'apiStore']);