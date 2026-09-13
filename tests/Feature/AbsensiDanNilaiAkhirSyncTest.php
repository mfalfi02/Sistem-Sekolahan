<?php

namespace Tests\Feature;

use App\Models\NilaiAkhir;
use App\Models\User;
use App\Support\NilaiAkhirCalculator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AbsensiDanNilaiAkhirSyncTest extends TestCase
{
    use RefreshDatabase;

    public function test_menyimpan_absensi_memicu_hitung_ulang_nilai_akhir(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-07-15 08:00:00', 'Asia/Jakarta'));

        $fixture = $this->createAcademicFixture();

        app(NilaiAkhirCalculator::class)->recalculateForClassMapel(
            $fixture['kelas_id'],
            $fixture['mata_pelajaran_id'],
            $fixture['tahun_ajaran_id']
        );

        $before = NilaiAkhir::query()
            ->where('siswa_id', $fixture['siswa_id'])
            ->firstOrFail();

        $this->assertSame(74.00, (float) $before->nilai_akhir);
        $this->assertSame(0.00, (float) $before->nilai_absensi);

        $guruUser = User::query()->findOrFail($fixture['guru_user_id']);
        $tanggalAbsensi = '2026-07-10';

        $response = $this->actingAs($guruUser)->post(route('absensi.store'), [
            'kelas_id' => $fixture['kelas_id'],
            'jadwal_id' => $fixture['jadwal_id'],
            'tanggal' => $tanggalAbsensi,
            'pertemuan_ke' => 5,
            'absensi' => [
                $fixture['siswa_id'] => [
                    'status' => 'hadir',
                    'keterangan' => 'Hadir tepat waktu',
                ],
            ],
        ]);

        $response->assertRedirect(route('absensi.index', [
            'kelas_id' => $fixture['kelas_id'],
            'tanggal' => $tanggalAbsensi,
        ]));

        $after = NilaiAkhir::query()
            ->where('siswa_id', $fixture['siswa_id'])
            ->where('kelas_id', $fixture['kelas_id'])
            ->where('mata_pelajaran_id', $fixture['mata_pelajaran_id'])
            ->where('tahun_ajaran_id', $fixture['tahun_ajaran_id'])
            ->firstOrFail();

        $this->assertSame(82.00, (float) $after->nilai_akhir);
        $this->assertSame(80.00, (float) $after->nilai_absensi);
        $this->assertTrue((bool) $after->status_lulus);
    }

    private function createAcademicFixture(): array
    {
        $now = now('Asia/Jakarta');

        $guruUserId = DB::table('users')->insertGetId([
            'name' => 'Guru Penguji',
            'email' => 'guru.sync@example.test',
            'email_verified_at' => $now,
            'password' => Hash::make('password'),
            'remember_token' => null,
            'role' => 'guru',
            'phone' => '081200000011',
            'status_aktif' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $siswaUserId = DB::table('users')->insertGetId([
            'name' => 'Siswa Sync',
            'email' => 'siswa.sync@example.test',
            'email_verified_at' => $now,
            'password' => Hash::make('password'),
            'remember_token' => null,
            'role' => 'siswa',
            'phone' => '081200000012',
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
            'nip' => '198700000011',
            'nama_guru' => 'Guru Sync',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_lahir' => '1987-01-01',
            'alamat' => 'Jl. Sinkron 1',
            'no_hp' => '081200000011',
            'foto' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $kelasId = DB::table('kelas')->insertGetId([
            'nama_kelas' => 'XI',
            'tingkat' => 'XI',
            'jurusan' => null,
            'kapasitas' => 36,
            'wali_guru_id' => $guruId,
            'tahun_ajaran_id' => $tahunAjaranId,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $siswaId = DB::table('siswa')->insertGetId([
            'user_id' => $siswaUserId,
            'nis' => '2026000011',
            'nisn' => '0012345679',
            'nama_siswa' => 'Siswa Sync',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_lahir' => '2010-01-01',
            'alamat' => 'Jl. Siswa 11',
            'no_hp' => '081200000012',
            'foto' => null,
            'kelas_id' => $kelasId,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $mataPelajaranId = DB::table('mata_pelajaran')->insertGetId([
            'kode_mapel' => 'BIN-001',
            'nama_mapel' => 'Bahasa Indonesia',
            'kelompok_mapel' => 'A',
            'jam_mingguan' => 4,
            'kkm' => 75,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $jadwalId = DB::table('jadwal')->insertGetId([
            'kelas_id' => $kelasId,
            'guru_id' => $guruId,
            'mata_pelajaran_id' => $mataPelajaranId,
            'tahun_ajaran_id' => $tahunAjaranId,
            'hari' => 'Jumat',
            'jam_mulai' => '07:30:00',
            'jam_selesai' => '08:50:00',
            'ruang' => 'R-202',
            'status_aktif' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $jenisPenilaianIds = [
            'tugas' => DB::table('jenis_penilaian')->insertGetId([
                'nama_jenis' => 'Tugas',
                'bobot' => 20.00,
                'keterangan' => 'Bobot tugas 20 persen.',
                'created_at' => $now,
                'updated_at' => $now,
            ]),
            'uts' => DB::table('jenis_penilaian')->insertGetId([
                'nama_jenis' => 'UTS',
                'bobot' => 30.00,
                'keterangan' => 'Bobot UTS 30 persen.',
                'created_at' => $now,
                'updated_at' => $now,
            ]),
            'uas' => DB::table('jenis_penilaian')->insertGetId([
                'nama_jenis' => 'UAS',
                'bobot' => 40.00,
                'keterangan' => 'Bobot UAS 40 persen.',
                'created_at' => $now,
                'updated_at' => $now,
            ]),
        ];

        $nilaiTanggal = '2026-07-01';

        DB::table('nilai')->insert([
            [
                'siswa_id' => $siswaId,
                'guru_id' => $guruId,
                'kelas_id' => $kelasId,
                'mata_pelajaran_id' => $mataPelajaranId,
                'tahun_ajaran_id' => $tahunAjaranId,
                'jenis_penilaian_id' => $jenisPenilaianIds['tugas'],
                'tanggal_nilai' => $nilaiTanggal,
                'nilai' => 80.00,
                'keterangan' => 'Tugas 1',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'siswa_id' => $siswaId,
                'guru_id' => $guruId,
                'kelas_id' => $kelasId,
                'mata_pelajaran_id' => $mataPelajaranId,
                'tahun_ajaran_id' => $tahunAjaranId,
                'jenis_penilaian_id' => $jenisPenilaianIds['uts'],
                'tanggal_nilai' => $nilaiTanggal,
                'nilai' => 70.00,
                'keterangan' => 'UTS',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'siswa_id' => $siswaId,
                'guru_id' => $guruId,
                'kelas_id' => $kelasId,
                'mata_pelajaran_id' => $mataPelajaranId,
                'tahun_ajaran_id' => $tahunAjaranId,
                'jenis_penilaian_id' => $jenisPenilaianIds['uas'],
                'tanggal_nilai' => $nilaiTanggal,
                'nilai' => 90.00,
                'keterangan' => 'UAS',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        return [
            'guru_user_id' => $guruUserId,
            'siswa_user_id' => $siswaUserId,
            'siswa_id' => $siswaId,
            'guru_id' => $guruId,
            'kelas_id' => $kelasId,
            'jadwal_id' => $jadwalId,
            'mata_pelajaran_id' => $mataPelajaranId,
            'tahun_ajaran_id' => $tahunAjaranId,
        ];
    }
}
