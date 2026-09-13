<?php

namespace Tests\Feature;

use App\Models\Absensi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AbsensiJadwalGuruValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guru_hanya_bisa_menyimpan_absensi_ke_jadwal_miliknya(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-07-13 08:00:00', 'Asia/Jakarta'));

        $fixture = $this->createFixture();

        $wrongResponse = $this->actingAs(User::query()->findOrFail($fixture['guru_user_id']))
            ->post(route('absensi.store'), [
                'kelas_id' => $fixture['kelas_id'],
                'jadwal_id' => $fixture['jadwal_bahasa_id'],
                'tanggal' => '2026-07-13',
                'absensi' => [
                    $fixture['siswa_id'] => [
                        'status' => 'hadir',
                        'keterangan' => 'Hadir',
                    ],
                ],
            ]);

        $wrongResponse->assertSessionHasErrors('jadwal_id');

        $correctResponse = $this->actingAs(User::query()->findOrFail($fixture['guru_user_id']))
            ->post(route('absensi.store'), [
                'kelas_id' => $fixture['kelas_id'],
                'jadwal_id' => $fixture['jadwal_sejarah_id'],
                'tanggal' => '2026-07-13',
                'absensi' => [
                    $fixture['siswa_id'] => [
                        'status' => 'hadir',
                        'keterangan' => 'Hadir tepat waktu',
                    ],
                ],
            ]);

        $correctResponse->assertRedirect();

        $this->assertDatabaseHas('absensi', [
            'siswa_id' => $fixture['siswa_id'],
            'kelas_id' => $fixture['kelas_id'],
            'jadwal_id' => $fixture['jadwal_sejarah_id'],
            'guru_id' => $fixture['guru_id'],
            'status_kehadiran' => 'hadir',
            'keterangan' => 'Hadir tepat waktu',
        ]);
    }

    private function createFixture(): array
    {
        $now = now('Asia/Jakarta');

        $guruUserId = DB::table('users')->insertGetId([
            'name' => 'Samuel Hutabarat',
            'email' => 'samuel.absensi@example.test',
            'email_verified_at' => $now,
            'password' => Hash::make('password'),
            'remember_token' => null,
            'role' => 'guru',
            'phone' => '081200000021',
            'status_aktif' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $guruLainUserId = DB::table('users')->insertGetId([
            'name' => 'Guru Lain',
            'email' => 'guru.lain@example.test',
            'email_verified_at' => $now,
            'password' => Hash::make('password'),
            'remember_token' => null,
            'role' => 'guru',
            'phone' => '081200000022',
            'status_aktif' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $siswaUserId = DB::table('users')->insertGetId([
            'name' => 'Siswa Uji',
            'email' => 'siswa.absensi@example.test',
            'email_verified_at' => $now,
            'password' => Hash::make('password'),
            'remember_token' => null,
            'role' => 'siswa',
            'phone' => '081200000023',
            'status_aktif' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $tahunAjaranId = DB::table('tahun_ajaran')->insertGetId([
            'nama_tahun_ajaran' => '2025/2026',
            'semester' => 'Ganjil',
            'tanggal_mulai' => '2025-07-01',
            'tanggal_selesai' => '2026-06-30',
            'status_aktif' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $guruId = DB::table('guru')->insertGetId([
            'user_id' => $guruUserId,
            'nip' => '198700000021',
            'nama_guru' => 'Samuel Hutabarat',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_lahir' => '1987-01-01',
            'alamat' => 'Jl. Samuel 1',
            'no_hp' => '081200000021',
            'foto' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $guruLainId = DB::table('guru')->insertGetId([
            'user_id' => $guruLainUserId,
            'nip' => '198700000022',
            'nama_guru' => 'Guru Lain',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_lahir' => '1988-01-01',
            'alamat' => 'Jl. Guru 2',
            'no_hp' => '081200000022',
            'foto' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $kelasId = DB::table('kelas')->insertGetId([
            'nama_kelas' => 'X',
            'tingkat' => 'X',
            'jurusan' => null,
            'kapasitas' => 36,
            'wali_guru_id' => $guruId,
            'tahun_ajaran_id' => $tahunAjaranId,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $siswaId = DB::table('siswa')->insertGetId([
            'user_id' => $siswaUserId,
            'nis' => '2026000021',
            'nisn' => '0012345688',
            'nama_siswa' => 'Siswa Uji',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_lahir' => '2010-01-01',
            'alamat' => 'Jl. Siswa 21',
            'no_hp' => '081200000023',
            'foto' => null,
            'kelas_id' => $kelasId,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $sejarahId = DB::table('mata_pelajaran')->insertGetId([
            'kode_mapel' => 'SEJ-001',
            'nama_mapel' => 'Sejarah',
            'kelompok_mapel' => 'A',
            'jam_mingguan' => 4,
            'kkm' => 75,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $bahasaId = DB::table('mata_pelajaran')->insertGetId([
            'kode_mapel' => 'BIN-002',
            'nama_mapel' => 'Bahasa Indonesia',
            'kelompok_mapel' => 'A',
            'jam_mingguan' => 4,
            'kkm' => 75,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $jadwalSejarahId = DB::table('jadwal')->insertGetId([
            'kelas_id' => $kelasId,
            'guru_id' => $guruId,
            'mata_pelajaran_id' => $sejarahId,
            'tahun_ajaran_id' => $tahunAjaranId,
            'hari' => 'Senin',
            'jam_mulai' => '07:30:00',
            'jam_selesai' => '08:50:00',
            'ruang' => 'R-101',
            'status_aktif' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $jadwalBahasaId = DB::table('jadwal')->insertGetId([
            'kelas_id' => $kelasId,
            'guru_id' => $guruLainId,
            'mata_pelajaran_id' => $bahasaId,
            'tahun_ajaran_id' => $tahunAjaranId,
            'hari' => 'Senin',
            'jam_mulai' => '09:00:00',
            'jam_selesai' => '10:20:00',
            'ruang' => 'R-102',
            'status_aktif' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return [
            'guru_user_id' => $guruUserId,
            'guru_id' => $guruId,
            'kelas_id' => $kelasId,
            'siswa_id' => $siswaId,
            'jadwal_sejarah_id' => $jadwalSejarahId,
            'jadwal_bahasa_id' => $jadwalBahasaId,
        ];
    }
}
