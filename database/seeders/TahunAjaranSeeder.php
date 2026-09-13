<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TahunAjaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tahun_ajaran')->delete();

        $now = now('Asia/Jakarta');

        DB::table('tahun_ajaran')->insert([
            [
                'nama_tahun_ajaran' => '2025/2026',
                'semester' => 'Ganjil',
                'tanggal_mulai' => '2025-07-01',
                'tanggal_selesai' => '2026-06-30',
                'status_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
