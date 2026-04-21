<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeatherController;

/*
|--------------------------------------------------------------------------
| Web Routes - Aplikasi Pengecek Cuaca dengan Riwayat Pencarian
|--------------------------------------------------------------------------
*/

// Halaman utama: menampilkan form + daftar riwayat pencarian
Route::get('/', [WeatherController::class, 'index'])->name('weather.index');

// Proses pengecekan cuaca (POST dari form)
Route::post('/check', [WeatherController::class, 'check'])->name('weather.check');

// Hapus satu data riwayat pencarian berdasarkan ID
// Menggunakan method DELETE sesuai konvensi RESTful Laravel
Route::delete('/history/{id}', [WeatherController::class, 'destroy'])->name('weather.destroy');

Route::post('/', [WeatherController::class, 'index']);

