<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\SearchHistory;

class WeatherController extends Controller
{
    //Menampilkan halaman utama sekaligus daftar riwayat pencarian (READ)

    public function index()
    {
        // Ambil semua riwayat, diurutkan dari yang terbaru
        $histories = SearchHistory::latest()->get();
        return view('weather.index', compact('histories'));
    }

    
    //Mengambil data cuaca dari API dan menyimpan riwayat pencarian (CREATE).
     
    public function check(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'city' => 'required|string|max:100',
        ], [
            'city.required' => 'Nama kota tidak boleh kosong.',
        ]);

        $city   = $request->input('city');
        $apiKey = env('OPENWEATHER_API_KEY');

        // 2. Kirim request ke OpenWeatherMap
        $response = Http::get('https://api.openweathermap.org/data/2.5/weather', [
            'q'     => $city,
            'appid' => $apiKey,
            'units' => 'metric',
            'lang'  => 'id',
        ]);

        // 3. Ambil riwayat 
        $histories = SearchHistory::latest()->get();

        if ($response->successful()) {
            $data = $response->json();

            $weather = [
                'city'        => $data['name'],
                'country'     => $data['sys']['country'],
                'temperature' => round($data['main']['temp']),
                'description' => ucfirst($data['weather'][0]['description']),
                'icon'        => $data['weather'][0]['icon'],
            ];

            // 4. Simpan ke database (CREATE)
            SearchHistory::create([
                'city_name'   => $weather['city'],
                'temperature' => $weather['temperature'],
                'description' => $weather['description'],
            ]);

            // Refresh riwayat setelah data baru disimpa
            $histories = SearchHistory::latest()->get();

            return view('weather.index', compact('weather', 'histories'));
        }

        // Jika kota tidak ditemukan
        $errorMessage = 'Kota tidak ditemukan. Periksa kembali nama kota Anda.';

        return view('weather.index', compact('errorMessage', 'histories'));
    }

    //Menghapus satu data riwayat pencarian berdasarkan id (DELETE)

    public function destroy($id)
    {
        $history = SearchHistory::findOrFail($id);
        $history->delete();

        // Redirect kembali ke halaman utama dengan pesan sukses
        return redirect()->route('weather.index')
                         ->with('success', 'Riwayat pencarian berhasil dihapus.');
    }
}
