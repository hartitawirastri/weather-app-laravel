<?php
// routes/api.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

// GET - Ambil semua riwayat
Route::get('/histories', [ApiController::class, 'index']);

// POST - Tambah riwayat baru
Route::post('/histories', [ApiController::class, 'store']);

// PUT - Edit nama kota di riwayat
Route::put('/histories/{id}', [ApiController::class, 'update']);

// DELETE - Hapus riwayat
Route::delete('/histories/{id}', [ApiController::class, 'destroy']);
