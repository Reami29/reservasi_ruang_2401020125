<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; 

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Master: ruang
        Schema::create('ruang', function (Blueprint $table) {
            $table->id();
            $table->string('nama_ruang', 100)->unique();
            $table->timestamps();
        });

        // 2. Tabel Utama: reservasi_ruang
        Schema::create('reservasi_ruang', function (Blueprint $table) {
            $table->id();
            $table->char('kode_reservasi', 10)->unique();
            $table->string('nama_peminjam', 100);
            $table->string('email', 100)->unique();
            $table->unsignedTinyInteger('durasi_jam');
            $table->foreignId('ruang_id')->constrained('ruang')->onUpdate('cascade')->onDelete('restrict');
            $table->timestamps();
        });

        // Tambahkan constraint CHECK menggunakan SQL murni
        DB::statement('ALTER TABLE reservasi_ruang ADD CONSTRAINT check_durasi_jam CHECK (durasi_jam BETWEEN 1 AND 8)');
    }

    public function down(): void
    {
        Schema::dropIfExists('reservasi_ruang');
        Schema::dropIfExists('ruang');
    }
};