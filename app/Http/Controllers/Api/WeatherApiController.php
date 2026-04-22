<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SearchHistoryResource;
use App\Models\SearchHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class WeatherApiController extends Controller
{
    /**
     * INDEX — Tampilkan semua riwayat pencarian.
     * METHOD : GET
     * URL    : /api/weather
     */
    public function index()
    {
        $histories = SearchHistory::latest()->get();

        // Membungkus koleksi dengan SearchHistoryResource
        return SearchHistoryResource::collection($histories);
    }

    /**
     * STORE — Cek cuaca dari OpenWeatherMap dan simpan ke database.
     * METHOD : POST
     * URL    : /api/weather
     * BODY   : { "city": "Jakarta" }
     */
    public function store(Request $request)
    {
        // 1. Validasi input dari body request
        $validator = Validator::make($request->all(), [
            'city' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422); // 422 Unprocessable Entity
        }

        // 2. Panggil OpenWeatherMap API menggunakan Laravel HTTP Client
        $response = Http::get('https://api.openweathermap.org/data/2.5/weather', [
            'q'     => $request->city,
            'appid' => env('OPENWEATHER_API_KEY'),
            'units' => 'metric',
            'lang'  => 'id',
        ]);

        // 3. Tangani jika kota tidak ditemukan (API mengembalikan 404)
        if ($response->failed()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Kota tidak ditemukan. Periksa kembali nama kota.',
            ], 404);
        }

        // 4. Ekstrak data yang dibutuhkan dari respons API
        $data = $response->json();

        // 5. Simpan ke database menggunakan mass assignment
        $history = SearchHistory::create([
            'city_name'   => $data['name'],
            'temperature' => round($data['main']['temp']),
            'description' => ucfirst($data['weather'][0]['description']),
        ]);

        // 6. Kembalikan data yang baru dibuat dengan HTTP status 201 Created
        return (new SearchHistoryResource($history))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * UPDATE — Edit data riwayat pencarian yang sudah ada.
     * METHOD : PUT
     * URL    : /api/weather/{id}
     * BODY   : { "city_name": "Surabaya", "temperature": 32, "description": "Cerah berawan" }
     *
     * Fungsi ini berguna jika ada koreksi data manual tanpa re-call ke API.
     */
    public function update(Request $request, $id)
    {
        // 1. Cari data berdasarkan ID, otomatis return 404 jika tidak ada
        $history = SearchHistory::findOrFail($id);

        // 2. Validasi input (semua field bersifat opsional saat update)
        $validator = Validator::make($request->all(), [
            'city_name'   => 'sometimes|required|string|max:100',
            'temperature' => 'sometimes|required|integer',
            'description' => 'sometimes|required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // 3. Update hanya field yang dikirim (tidak mengganti field lain)
        $history->update($request->only(['city_name', 'temperature', 'description']));

        // 4. Kembalikan data yang sudah diperbarui
        return response()->json([
            'status'  => 'success',
            'message' => 'Riwayat berhasil diperbarui.',
            'data'    => new SearchHistoryResource($history),
        ]);
    }

    /**
     * DESTROY — Hapus satu data riwayat pencarian.
     * METHOD : DELETE
     * URL    : /api/weather/{id}
     */
    public function destroy($id)
    {
        $history = SearchHistory::findOrFail($id);
        $history->delete();

        return response()->json([
            'status'  => 'success',
            'message' => "Riwayat pencarian kota '{$history->city_name}' berhasil dihapus.",
        ]);
    }
}