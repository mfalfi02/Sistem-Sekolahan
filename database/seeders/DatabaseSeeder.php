<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        DB::table('notifikasi')->delete();
        DB::table('activity_logs')->delete();
        DB::table('log_aktivitas')->delete();
        DB::table('nilai_akhir')->delete();
        DB::table('nilai')->delete();
        DB::table('absensi')->delete();
        DB::table('jadwal')->delete();
        DB::table('jenis_penilaian')->delete();
        DB::table('siswa')->delete();
        DB::table('guru')->delete();
        DB::table('mata_pelajaran')->delete();
        DB::table('kelas')->delete();
        DB::table('tahun_ajaran')->delete();
        DB::table('users')->where('role', '!=', 'admin')->delete();

        Schema::enableForeignKeyConstraints();

        $now = now('Asia/Jakarta');

        DB::table('users')->insertOrIgnore([
            [
                'name' => 'Admin Sekolah',
                'email' => 'tu@sekolah.test',
                'email_verified_at' => $now,
                'password' => Hash::make('AdminSekolah123!'),
                'remember_token' => null,
                'role' => 'admin',
                'phone' => '081234567890',
                'status_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Admin Sistem',
                'email' => 'admin@sekolah.test',
                'email_verified_at' => $now,
                'password' => Hash::make('AdminSistem123'),
                'remember_token' => null,
                'role' => 'admin',
                'phone' => '081234567899',
                'status_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        $this->call([
            TahunAjaranSeeder::class,
            KepalaSekolahSeeder::class,
        ]);

        $tahunAjaranId = DB::table('tahun_ajaran')->where('status_aktif', true)->value('id');

        DB::table('kelas')->insert([
            [
                'nama_kelas' => 'X',
                'tingkat' => 'X',
                'jurusan' => null,
                'kapasitas' => null,
                'wali_guru_id' => null,
                'tahun_ajaran_id' => $tahunAjaranId,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_kelas' => 'XI',
                'tingkat' => 'XI',
                'jurusan' => null,
                'kapasitas' => null,
                'wali_guru_id' => null,
                'tahun_ajaran_id' => $tahunAjaranId,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_kelas' => 'XII',
                'tingkat' => 'XII',
                'jurusan' => null,
                'kapasitas' => null,
                'wali_guru_id' => null,
                'tahun_ajaran_id' => $tahunAjaranId,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        $this->call([
            JenisPenilaianSeeder::class,
            MataPelajaranSeeder::class,
            GuruSeeder::class,
            SiswaSeeder::class,
            RekapAbsensiSeeder::class,
            RekapNilaiSeeder::class,
        ]);
    }
}
