<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SearchHistoryResource extends JsonResource
{
    
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'city_name'   => $this->city_name,
            'temperature' => $this->temperature . '°C',   // Tambah satuan langsung di resource
            'description' => $this->description,
            'searched_at' => $this->created_at->format('d M Y, H:i:s'), // Format tanggal yang ramah
        ];
    }
}