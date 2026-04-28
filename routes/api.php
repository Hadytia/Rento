<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PenaltyController;
use App\Http\Controllers\TransaksiController;

Route::get('/products',     [ProdukController::class,   'apiIndex']);
Route::get('/users',        [UserController::class,     'apiIndex']);
Route::get('/penalties',    [PenaltyController::class,  'apiIndex']);
Route::get('/transactions', [TransaksiController::class,'apiIndex']);