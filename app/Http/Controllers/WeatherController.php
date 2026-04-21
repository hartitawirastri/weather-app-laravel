<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    /**
     * Menampilkan halaman utama aplikasi cuaca.
     */
    public function index()
    {
        return view('weather.index');
    }

    /**
     * Mengambil data cuaca dari OpenWeatherMap API
     * berdasarkan nama kota yang dikirim oleh user.
     */
    public function check(Request $request)
    {
        // 1. Validasi input: pastikan nama kota tidak kosong
        $request->validate([
            'city' => 'required|string|max:100',
        ], [
            'city.required' => 'Nama kota tidak boleh kosong.',
        ]);

        $city   = $request->input('city');
        $apiKey = env('OPENWEATHER_API_KEY');

        // 2. Kirim request ke OpenWeatherMap menggunakan Laravel HTTP Client
        $response = Http::get('https://api.openweathermap.org/data/2.5/weather', [
            'q'     => $city,
            'appid' => $apiKey,
            'units' => 'metric',  // Suhu dalam Celsius
            'lang'  => 'id',      // Deskripsi cuaca dalam Bahasa Indonesia
        ]);

        // 3. Tangani respons: sukses atau gagal
        if ($response->successful()) {
            $data = $response->json();

            $weather = [
                'city'        => $data['name'],
                'country'     => $data['sys']['country'],
                'temperature' => round($data['main']['temp']),
                'description' => ucfirst($data['weather'][0]['description']),
                'icon'        => $data['weather'][0]['icon'],
            ];

            return view('weather.index', compact('weather'));
        }

        // Jika kota tidak ditemukan atau terjadi error lain
        $errorMessage = 'Kota tidak ditemukan. Periksa kembali nama kota Anda.';

        return view('weather.index', compact('errorMessage'))->with('city', $city);
    }
}
