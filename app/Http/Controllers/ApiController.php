<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SearchHistory;

class ApiController extends Controller
{
    public function index()
    {
        $histories = SearchHistory::orderBy('created_at', 'desc')->get();

        return response()->json([
            'status'  => 'success',
            'message' => 'Data berhasil diambil',
            'data'    => $histories,
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'city_name'   => 'required|string|max:255',
            'temperature' => 'required|numeric',
        ]);

        $history = SearchHistory::create([
            'city_name'   => $request->city_name,
            'temperature' => $request->temperature,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Riwayat berhasil ditambahkan',
            'data'    => $history,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $history = SearchHistory::find($id);

        if (!$history) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        $request->validate([
            'city_name' => 'required|string|max:255',
        ]);

        $history->update([
            'city_name' => $request->city_name,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Riwayat berhasil diperbarui',
            'data'    => $history,
        ], 200);
    }

    public function destroy($id)
    {
        $history = SearchHistory::find($id);

        if (!$history) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        $history->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Riwayat berhasil dihapus',
        ], 200);
    }
}
