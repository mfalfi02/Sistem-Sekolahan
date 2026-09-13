<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisPenilaianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('jenis_penilaian')->delete();

        $now = now('Asia/Jakarta');

        DB::table('jenis_penilaian')->insert([
            [
                'nama_jenis' => 'Tugas',
                'bobot' => 20.00,
                'keterangan' => 'Bobot penilaian tugas sebesar 20%.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_jenis' => 'UAS',
                'bobot' => 40.00,
                'keterangan' => 'Bobot penilaian ujian akhir semester sebesar 40%.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_jenis' => 'UTS',
                'bobot' => 30.00,
                'keterangan' => 'Bobot penilaian ujian tengah semester sebesar 30%.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
