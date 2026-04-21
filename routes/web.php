<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeatherController;

/*
|--------------------------------------------------------------------------
| Web Routes - Aplikasi Pengecek Cuaca
|--------------------------------------------------------------------------
*/

// Halaman utama: menampilkan form pencarian
Route::get('/', [WeatherController::class, 'index'])->name('weather.index');

// Proses pengecekan cuaca setelah form disubmit
Route::post('/check', [WeatherController::class, 'check'])->name('weather.check');
