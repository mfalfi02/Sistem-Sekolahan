<?php

namespace Tests\Unit;

use App\Models\NilaiAkhir;
use App\Support\NilaiAkhirCalculator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class NilaiAkhirCalculatorTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_calculates_final_score_from_attendance_and_grade_weights(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-07-15 08:00:00', 'Asia/Jakarta'));

        $fixture = $this->createAcademicFixture();

        app(NilaiAkhirCalculator::class)->recalculateForClassMapel(
            $fixture['kelas_id'],
            $fixture['mata_pelajaran_id'],
            $fixture['tahun_ajaran_id']
        );

        $nilaiAkhir = NilaiAkhir::query()
            ->where('siswa_id', $fixture['siswa_id'])
            ->where('kelas_id', $fixture['kelas_id'])
            ->where('mata_pelajaran_id', $fixture['mata_pelajaran_id'])
            ->where('tahun_ajaran_id', $fixture['tahun_ajaran_id'])
            ->firstOrFail();

        $this->assertSame(4, (int) $nilaiAkhir->absensi_total);
        $this->assertSame(2, (int) $nilaiAkhir->absensi_hadir);
        $this->assertSame(1, (int) $nilaiAkhir->absensi_terlambat);
        $this->assertSame(1, (int) $nilaiAkhir->absensi_alfa);
        $this->assertSame(50.00, (float) $nilaiAkhir->persentase_absensi);
        $this->assertSame(50.00, (float) $nilaiAkhir->nilai_absensi);
        $this->assertSame(79.00, (float) $nilaiAkhir->nilai_akhir);
        $this->assertSame('C', $nilaiAkhir->predikat);
        $this->assertFalse((bool) $nilaiAkhir->status_lulus);
    }

    private function createAcademicFixture(): array
    {
        $now = now('Asia/Jakarta');

        $guruUserId = DB::table('users')->insertGetId([
            'name' => 'Guru Penguji',
            'email' => 'guru.penguji@example.test',
            'email_verified_at' => $now,
            'password' => Hash::make('password'),
            'remember_token' => null,
            'role' => 'guru',
            'phone' => '081200000001',
            'status_aktif' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $siswaUserId = DB::table('users')->insertGetId([
            'name' => 'Siswa Penguji',
            'email' => 'siswa.penguji@example.test',
            'email_verified_at' => $now,
            'password' => Hash::make('password'),
            'remember_token' => null,
            'role' => 'siswa',
            'phone' => '081200000002',
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
            'nip' => '198700000001',
            'nama_guru' => 'Guru Penguji',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_lahir' => '1987-01-01',
            'alamat' => 'Jl. Penguji 1',
            'no_hp' => '081200000001',
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
            'nis' => '2026000001',
            'nisn' => '0012345678',
            'nama_siswa' => 'Siswa Penguji',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_lahir' => '2010-01-01',
            'alamat' => 'Jl. Siswa 1',
            'no_hp' => '081200000002',
            'foto' => null,
            'kelas_id' => $kelasId,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $mataPelajaranId = DB::table('mata_pelajaran')->insertGetId([
            'kode_mapel' => 'MTK-001',
            'nama_mapel' => 'Matematika',
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
            'hari' => 'Rabu',
            'jam_mulai' => '07:30:00',
            'jam_selesai' => '08:50:00',
            'ruang' => 'R-101',
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

        DB::table('absensi')->insert([
            [
                'siswa_id' => $siswaId,
                'kelas_id' => $kelasId,
                'guru_id' => $guruId,
                'jadwal_id' => $jadwalId,
                'tahun_ajaran_id' => $tahunAjaranId,
                'tanggal_absen' => '2026-07-01',
                'pertemuan_ke' => 1,
                'status_kehadiran' => 'hadir',
                'keterangan' => 'Hadir',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'siswa_id' => $siswaId,
                'kelas_id' => $kelasId,
                'guru_id' => $guruId,
                'jadwal_id' => $jadwalId,
                'tahun_ajaran_id' => $tahunAjaranId,
                'tanggal_absen' => '2026-07-02',
                'pertemuan_ke' => 2,
                'status_kehadiran' => 'terlambat',
                'keterangan' => 'Terlambat',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'siswa_id' => $siswaId,
                'kelas_id' => $kelasId,
                'guru_id' => $guruId,
                'jadwal_id' => $jadwalId,
                'tahun_ajaran_id' => $tahunAjaranId,
                'tanggal_absen' => '2026-07-03',
                'pertemuan_ke' => 3,
                'status_kehadiran' => 'alfa',
                'keterangan' => 'Alfa',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'siswa_id' => $siswaId,
                'kelas_id' => $kelasId,
                'guru_id' => $guruId,
                'jadwal_id' => $jadwalId,
                'tahun_ajaran_id' => $tahunAjaranId,
                'tanggal_absen' => '2026-07-04',
                'pertemuan_ke' => 4,
                'status_kehadiran' => 'hadir',
                'keterangan' => 'Hadir',
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
            'mata_pelajaran_id' => $mataPelajaranId,
            'tahun_ajaran_id' => $tahunAjaranId,
        ];
    }
}
