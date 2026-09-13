<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MataPelajaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mata_pelajaran')->delete();

        $now = now('Asia/Jakarta');

        DB::table('mata_pelajaran')->insert([
            [
                'kode_mapel' => 'PAK-001',
                'nama_mapel' => 'Pendidikan Agama Kristen',
                'kelompok_mapel' => 'Keagamaan',
                'jam_mingguan' => null,
                'kkm' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kode_mapel' => 'PKN-002',
                'nama_mapel' => 'Pendidikan Pancasila dan Kewarganegaraan',
                'kelompok_mapel' => 'Umum',
                'jam_mingguan' => null,
                'kkm' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kode_mapel' => 'BIN-003',
                'nama_mapel' => 'Bahasa Indonesia',
                'kelompok_mapel' => 'Umum',
                'jam_mingguan' => null,
                'kkm' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kode_mapel' => 'BIG-004',
                'nama_mapel' => 'Bahasa Inggris',
                'kelompok_mapel' => 'Umum',
                'jam_mingguan' => null,
                'kkm' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kode_mapel' => 'MTK-005',
                'nama_mapel' => 'Matematika',
                'kelompok_mapel' => 'Umum',
                'jam_mingguan' => null,
                'kkm' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kode_mapel' => 'IPA-006',
                'nama_mapel' => 'Ilmu Pengetahuan Alam',
                'kelompok_mapel' => 'Umum',
                'jam_mingguan' => null,
                'kkm' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kode_mapel' => 'IPS-007',
                'nama_mapel' => 'Ilmu Pengetahuan Sosial',
                'kelompok_mapel' => 'Umum',
                'jam_mingguan' => null,
                'kkm' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kode_mapel' => 'SJR-008',
                'nama_mapel' => 'Sejarah Indonesia',
                'kelompok_mapel' => 'Umum',
                'jam_mingguan' => null,
                'kkm' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kode_mapel' => 'SBD-009',
                'nama_mapel' => 'Seni Budaya',
                'kelompok_mapel' => 'Umum',
                'jam_mingguan' => null,
                'kkm' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kode_mapel' => 'PJOK-010',
                'nama_mapel' => 'Pendidikan Jasmani, Olahraga, dan Kesehatan',
                'kelompok_mapel' => 'Umum',
                'jam_mingguan' => null,
                'kkm' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kode_mapel' => 'INF-011',
                'nama_mapel' => 'Informatika',
                'kelompok_mapel' => 'Umum',
                'jam_mingguan' => null,
                'kkm' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kode_mapel' => 'IPAK-012',
                'nama_mapel' => 'Ilmu Pengetahuan Alkitab',
                'kelompok_mapel' => 'Keagamaan',
                'jam_mingguan' => null,
                'kkm' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kode_mapel' => 'DOG-013',
                'nama_mapel' => 'Dogmatika',
                'kelompok_mapel' => 'Keagamaan',
                'jam_mingguan' => null,
                'kkm' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kode_mapel' => 'ETK-014',
                'nama_mapel' => 'Etika Kristen',
                'kelompok_mapel' => 'Keagamaan',
                'jam_mingguan' => null,
                'kkm' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kode_mapel' => 'SGJ-015',
                'nama_mapel' => 'Sejarah Gereja',
                'kelompok_mapel' => 'Keagamaan',
                'jam_mingguan' => null,
                'kkm' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
