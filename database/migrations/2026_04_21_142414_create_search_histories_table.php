<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration: membuat tabel search_histories.
     */
    public function up(): void
    {
        Schema::create('search_histories', function (Blueprint $table) {
            $table->id();                          // Primary key auto-increment
            $table->string('city_name');           // Nama kota yang dicari
            $table->integer('temperature');        // Suhu dalam Celsius
            $table->string('description');         // Deskripsi cuaca (misal: "berawan")
            $table->timestamps();                  // created_at & updated_at otomatis
        });
    }

    /**
     * Batalkan migration: hapus tabel search_histories.
     */
    public function down(): void
    {
        Schema::dropIfExists('search_histories');
    }
};
