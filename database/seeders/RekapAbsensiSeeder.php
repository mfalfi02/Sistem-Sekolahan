<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RekapAbsensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('absensi')->delete();
        DB::table('jadwal')->delete();

        $now = now('Asia/Jakarta');
        $tahunAjaranId = DB::table('tahun_ajaran')->where('status_aktif', true)->value('id');

        $siswaIds = DB::table('siswa')->pluck('id', 'nis')->all();
        $kelasIds = DB::table('kelas')->pluck('id', 'nama_kelas')->all();
        $guruIds = DB::table('guru')->pluck('id', 'nama_guru')->all();
        $mapelIds = DB::table('mata_pelajaran')->pluck('id', 'kode_mapel')->all();

        $kelasId = $kelasIds['X'] ?? null;
        $guruId = $guruIds['Yohanes Prasetyo'] ?? null;
        $mapelId = $mapelIds['BIN-003'] ?? null;
        $hari = 'Senin';
        $tanggalAbsensi = $now->copy()->startOfWeek()->toDateString();

        $jadwalId = DB::table('jadwal')->insertGetId([
            'kelas_id' => $kelasId,
            'guru_id' => $guruId,
            'mata_pelajaran_id' => $mapelId,
            'tahun_ajaran_id' => $tahunAjaranId,
            'hari' => $hari,
            'jam_mulai' => '07:30:00',
            'jam_selesai' => '08:50:00',
            'ruang' => 'R-101',
            'status_aktif' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $rows = [
            ['siswa_nis' => '2026100001', 'status_kehadiran' => 'hadir', 'keterangan' => 'Hadir tepat waktu'],
            ['siswa_nis' => '2026100002', 'status_kehadiran' => 'izin', 'keterangan' => 'Izin keperluan keluarga'],
            ['siswa_nis' => '2026100003', 'status_kehadiran' => 'sakit', 'keterangan' => 'Sakit'],
            ['siswa_nis' => '2026100004', 'status_kehadiran' => 'hadir', 'keterangan' => 'Terlambat 10 menit'],
            ['siswa_nis' => '2026100005', 'status_kehadiran' => 'hadir', 'keterangan' => 'Hadir'],
            ['siswa_nis' => '2026100006', 'status_kehadiran' => 'hadir', 'keterangan' => 'Hadir'],
            ['siswa_nis' => '2026100007', 'status_kehadiran' => 'izin', 'keterangan' => 'Izin acara keluarga'],
            ['siswa_nis' => '2026100008', 'status_kehadiran' => 'hadir', 'keterangan' => 'Hadir tepat waktu'],
            ['siswa_nis' => '2026100009', 'status_kehadiran' => 'alfa', 'keterangan' => 'Tanpa keterangan'],
            ['siswa_nis' => '2026100010', 'status_kehadiran' => 'hadir', 'keterangan' => 'Hadir'],
        ];

        $payload = [];

        foreach ($rows as $row) {
            $payload[] = [
                'siswa_id' => $siswaIds[$row['siswa_nis']] ?? null,
                'kelas_id' => $kelasId,
                'guru_id' => $guruId,
                'jadwal_id' => $jadwalId,
                'tahun_ajaran_id' => $tahunAjaranId,
                'tanggal_absen' => $tanggalAbsensi,
                'pertemuan_ke' => null,
                'status_kehadiran' => $row['status_kehadiran'],
                'keterangan' => $row['keterangan'],
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('absensi')->insert($payload);
    }
}
