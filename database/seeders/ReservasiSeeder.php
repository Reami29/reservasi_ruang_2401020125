<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReservasiSeeder extends Seeder
{
    public function run(): void
    {
        $ruangRapatA = DB::table('ruang')->insertGetId([
            'nama_ruang' => 'Ruang Rapat A',
            'created_at' => now(), 'updated_at' => now()
        ]);
        $ruangSeminar = DB::table('ruang')->insertGetId([
            'nama_ruang' => 'Ruang Seminar',
            'created_at' => now(), 'updated_at' => now()
        ]);

        DB::table('reservasi_ruang')->insert([
            [
                'kode_reservasi' => 'RS00000001',
                'nama_peminjam' => 'Andi Saputra',
                'email' => 'andi.r@example.com',
                'durasi_jam' => 2,
                'ruang_id' => $ruangRapatA,
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'kode_reservasi' => 'RS00000002',
                'nama_peminjam' => 'Siti Rahma',
                'email' => 'siti.r@example.com',
                'durasi_jam' => 3,
                'ruang_id' => $ruangSeminar,
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'kode_reservasi' => 'RS00000003',
                'nama_peminjam' => 'Budi Pratama',
                'email' => 'budi.r@example.com',
                'durasi_jam' => 1,
                'ruang_id' => $ruangRapatA,
                'created_at' => now(), 'updated_at' => now()
            ],
        ]);
    }
}