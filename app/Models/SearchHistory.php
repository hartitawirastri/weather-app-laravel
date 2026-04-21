<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SearchHistory extends Model
{
    /**
     * Kolom yang boleh diisi secara massal (mass assignment).
     * Ini wajib diatur untuk keamanan aplikasi Laravel.
     */
    protected $fillable = [
        'city_name',
        'temperature',
        'description',
    ];
}
