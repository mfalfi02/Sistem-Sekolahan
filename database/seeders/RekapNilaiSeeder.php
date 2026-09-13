<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RekapNilaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('nilai_akhir')->delete();

        $now = now('Asia/Jakarta');
        $tahunAjaranId = DB::table('tahun_ajaran')->where('status_aktif', true)->value('id');

        $siswaIds = DB::table('siswa')->pluck('id', 'nis')->all();
        $kelasIds = DB::table('kelas')->pluck('id', 'nama_kelas')->all();
        $mapelIds = DB::table('mata_pelajaran')->pluck('id', 'kode_mapel')->all();

        $kelasId = $kelasIds['X'] ?? null;
        $mapelId = $mapelIds['BIN-003'] ?? null;

        $rows = [
            ['siswa_nis' => '2026100001', 'semester' => 'Ganjil', 'nilai_akhir' => 88.50, 'predikat' => 'A', 'ranking' => 1, 'status_lulus' => true, 'catatan' => 'Sangat baik'],
            ['siswa_nis' => '2026100002', 'semester' => 'Ganjil', 'nilai_akhir' => 81.00, 'predikat' => 'A', 'ranking' => 2, 'status_lulus' => true, 'catatan' => 'Baik'],
            ['siswa_nis' => '2026100003', 'semester' => 'Ganjil', 'nilai_akhir' => 74.00, 'predikat' => 'B', 'ranking' => 3, 'status_lulus' => false, 'catatan' => 'Perlu peningkatan'],
            ['siswa_nis' => '2026100004', 'semester' => 'Ganjil', 'nilai_akhir' => 92.00, 'predikat' => 'A', 'ranking' => 4, 'status_lulus' => true, 'catatan' => 'Unggul'],
            ['siswa_nis' => '2026100005', 'semester' => 'Ganjil', 'nilai_akhir' => 85.00, 'predikat' => 'A', 'ranking' => 5, 'status_lulus' => true, 'catatan' => 'Stabil'],
            ['siswa_nis' => '2026100006', 'semester' => 'Ganjil', 'nilai_akhir' => 79.25, 'predikat' => 'B', 'ranking' => 6, 'status_lulus' => true, 'catatan' => 'Baik'],
            ['siswa_nis' => '2026100007', 'semester' => 'Ganjil', 'nilai_akhir' => 67.75, 'predikat' => 'C', 'ranking' => 7, 'status_lulus' => false, 'catatan' => 'Remedial disarankan'],
            ['siswa_nis' => '2026100008', 'semester' => 'Ganjil', 'nilai_akhir' => 90.00, 'predikat' => 'A', 'ranking' => 8, 'status_lulus' => true, 'catatan' => 'Sangat baik'],
            ['siswa_nis' => '2026100009', 'semester' => 'Ganjil', 'nilai_akhir' => 76.50, 'predikat' => 'B', 'ranking' => 9, 'status_lulus' => true, 'catatan' => 'Baik'],
            ['siswa_nis' => '2026100010', 'semester' => 'Ganjil', 'nilai_akhir' => 83.00, 'predikat' => 'A', 'ranking' => 10, 'status_lulus' => true, 'catatan' => 'Bagus'],
        ];

        $payload = [];

        foreach ($rows as $row) {
            $payload[] = [
                'siswa_id' => $siswaIds[$row['siswa_nis']] ?? null,
                'kelas_id' => $kelasId,
                'mata_pelajaran_id' => $mapelId,
                'tahun_ajaran_id' => $tahunAjaranId,
                'semester' => $row['semester'],
                'nilai_akhir' => $row['nilai_akhir'],
                'predikat' => $row['predikat'],
                'ranking' => $row['ranking'],
                'status_lulus' => $row['status_lulus'],
                'catatan' => $row['catatan'],
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('nilai_akhir')->insert($payload);
    }
}
