<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Absensi;
use App\Models\JenisPenilaian;
use App\Models\Jadwal;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\NilaiAkhir;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [
            [
                'email' => 'tu@sekolah.test',
                'name' => 'Admin Sekolah',
                'role' => 'admin',
                'phone' => '081234567890',
                'password' => 'AdminSekolah123!',
            ],
            [
                'email' => 'guru@sekolah.test',
                'name' => 'Guru Demo',
                'role' => 'guru',
                'phone' => '081234567891',
                'password' => 'GuruDemo123!',
            ],
            [
                'email' => 'siswa@sekolah.test',
                'name' => 'Siswa Demo',
                'role' => 'siswa',
                'phone' => '081234567892',
                'password' => 'SiswaDemo123!',
            ],
            [
                'email' => 'admin@sekolah.test',
                'name' => 'Admin Sistem',
                'role' => 'admin',
                'phone' => '081234567899',
                'password' => 'AdminSistem123!',
            ],
            [
                'email' => 'guru2@sekolah.test',
                'name' => 'Guru Matematika',
                'role' => 'guru',
                'phone' => '081234567893',
                'password' => 'GuruMatematika123!',
            ],
            [
                'email' => 'guru3@sekolah.test',
                'name' => 'Guru Bahasa',
                'role' => 'guru',
                'phone' => '081234567894',
                'password' => 'GuruBahasa123!',
            ],
            [
                'email' => 'siswa2@sekolah.test',
                'name' => 'Siswa Dua',
                'role' => 'siswa',
                'phone' => '081234567895',
                'password' => 'SiswaDua123!',
            ],
            [
                'email' => 'siswa3@sekolah.test',
                'name' => 'Siswa Tiga',
                'role' => 'siswa',
                'phone' => '081234567896',
                'password' => 'SiswaTiga123!',
            ],
            [
                'email' => 'siswa4@sekolah.test',
                'name' => 'Siswa Empat',
                'role' => 'siswa',
                'phone' => '081234567897',
                'password' => 'SiswaEmpat123!',
            ],
            [
                'email' => 'siswa5@sekolah.test',
                'name' => 'Siswa Lima',
                'role' => 'siswa',
                'phone' => '081234567898',
                'password' => 'SiswaLima123!',
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => $userData['password'],
                    'role' => $userData['role'],
                    'phone' => $userData['phone'],
                    'status_aktif' => true,
                ]
            );
        }

        $tahunAjaran = TahunAjaran::updateOrCreate(
            ['nama_tahun_ajaran' => '2025/2026'],
            [
                'semester' => 'Ganjil',
                'tanggal_mulai' => now()->startOfMonth(),
                'tanggal_selesai' => now()->addMonths(5)->endOfMonth(),
                'status_aktif' => true,
            ]
        );

        $guruUser = User::where('email', 'guru@sekolah.test')->first();
        $guruUser2 = User::where('email', 'guru2@sekolah.test')->first();
        $guruUser3 = User::where('email', 'guru3@sekolah.test')->first();

        $guru = Guru::updateOrCreate(
            ['nip' => '198812312024011001'],
            [
                'user_id' => $guruUser?->id,
                'nama_guru' => 'Guru Demo',
                'jenis_kelamin' => 'Laki-laki',
                'tanggal_lahir' => '1988-12-31',
                'alamat' => 'Jl. Pendidikan No. 1',
                'no_hp' => '081234567891',
            ]
        );

        $guru2 = Guru::updateOrCreate(
            ['nip' => '198901152024011002'],
            [
                'user_id' => $guruUser2?->id,
                'nama_guru' => 'Guru Matematika',
                'jenis_kelamin' => 'Perempuan',
                'tanggal_lahir' => '1989-01-15',
                'alamat' => 'Jl. Pendidikan No. 2',
                'no_hp' => '081234567893',
            ]
        );

        $guru3 = Guru::updateOrCreate(
            ['nip' => '199002202024011003'],
            [
                'user_id' => $guruUser3?->id,
                'nama_guru' => 'Guru Bahasa',
                'jenis_kelamin' => 'Perempuan',
                'tanggal_lahir' => '1990-02-20',
                'alamat' => 'Jl. Pendidikan No. 3',
                'no_hp' => '081234567894',
            ]
        );

        $kelasA = Kelas::updateOrCreate(
            ['nama_kelas' => 'X IPA 1'],
            [
                'tingkat' => 'X',
                'jurusan' => 'IPA',
                'kapasitas' => 36,
                'wali_guru_id' => $guru->id,
                'tahun_ajaran_id' => $tahunAjaran->id,
            ]
        );

        $kelasB = Kelas::updateOrCreate(
            ['nama_kelas' => 'X IPA 2'],
            [
                'tingkat' => 'X',
                'jurusan' => 'IPA',
                'kapasitas' => 36,
                'wali_guru_id' => $guru2->id,
                'tahun_ajaran_id' => $tahunAjaran->id,
            ]
        );

        $kelasC = Kelas::updateOrCreate(
            ['nama_kelas' => 'X IPS 1'],
            [
                'tingkat' => 'X',
                'jurusan' => 'IPS',
                'kapasitas' => 36,
                'wali_guru_id' => $guru3->id,
                'tahun_ajaran_id' => $tahunAjaran->id,
            ]
        );

        $siswaUser = User::where('email', 'siswa@sekolah.test')->first();
        $siswaA = Siswa::updateOrCreate(
            ['nis' => '20250001'],
            [
                'user_id' => $siswaUser?->id,
                'nisn' => '9988776655',
                'nama_siswa' => 'Siswa Demo',
                'jenis_kelamin' => 'Perempuan',
                'tanggal_lahir' => '2008-07-15',
                'alamat' => 'Jl. Pelajar No. 12',
                'no_hp' => '081234567892',
                'kelas_id' => $kelasA->id,
            ]
        );

        $studentSeed = [
            ['label' => 'B1', 'email' => 'siswa2@sekolah.test', 'nis' => '20250002', 'nisn' => '9988776656', 'name' => 'Siswa Dua', 'gender' => 'Laki-laki', 'birth' => '2008-03-10', 'kelas_id' => $kelasB->id, 'phone' => '081234567895'],
            ['label' => 'B2', 'email' => 'siswa3@sekolah.test', 'nis' => '20250003', 'nisn' => '9988776657', 'name' => 'Siswa Tiga', 'gender' => 'Perempuan', 'birth' => '2008-05-21', 'kelas_id' => $kelasB->id, 'phone' => '081234567896'],
            ['label' => 'C1', 'email' => 'siswa4@sekolah.test', 'nis' => '20250004', 'nisn' => '9988776658', 'name' => 'Siswa Empat', 'gender' => 'Laki-laki', 'birth' => '2008-08-11', 'kelas_id' => $kelasC->id, 'phone' => '081234567897'],
            ['label' => 'C2', 'email' => 'siswa5@sekolah.test', 'nis' => '20250005', 'nisn' => '9988776659', 'name' => 'Siswa Lima', 'gender' => 'Perempuan', 'birth' => '2008-11-02', 'kelas_id' => $kelasC->id, 'phone' => '081234567898'],
        ];

        foreach ($studentSeed as $seed) {
            $studentUser = User::where('email', $seed['email'])->first();
            Siswa::updateOrCreate(
                ['nis' => $seed['nis']],
                [
                    'user_id' => $studentUser?->id,
                    'nisn' => $seed['nisn'],
                    'nama_siswa' => $seed['name'],
                    'jenis_kelamin' => $seed['gender'],
                    'tanggal_lahir' => $seed['birth'],
                    'alamat' => 'Jl. Pelajar ' . $seed['nis'],
                    'no_hp' => $seed['phone'],
                    'kelas_id' => $seed['kelas_id'],
                ]
            );
        }

        $mapels = [
            ['kode_mapel' => 'MTK-01', 'nama_mapel' => 'Matematika', 'kelompok_mapel' => 'Wajib', 'jam_mingguan' => 5, 'kkm' => 75],
            ['kode_mapel' => 'BIN-01', 'nama_mapel' => 'Bahasa Indonesia', 'kelompok_mapel' => 'Wajib', 'jam_mingguan' => 4, 'kkm' => 75],
            ['kode_mapel' => 'BING-01', 'nama_mapel' => 'Bahasa Inggris', 'kelompok_mapel' => 'Wajib', 'jam_mingguan' => 4, 'kkm' => 75],
            ['kode_mapel' => 'IPA-01', 'nama_mapel' => 'IPA', 'kelompok_mapel' => 'Wajib', 'jam_mingguan' => 4, 'kkm' => 75],
            ['kode_mapel' => 'IPS-01', 'nama_mapel' => 'IPS', 'kelompok_mapel' => 'Wajib', 'jam_mingguan' => 4, 'kkm' => 75],
        ];

        foreach ($mapels as $mapelData) {
            MataPelajaran::updateOrCreate(
                ['kode_mapel' => $mapelData['kode_mapel']],
                [
                    'nama_mapel' => $mapelData['nama_mapel'],
                    'kelompok_mapel' => $mapelData['kelompok_mapel'],
                    'jam_mingguan' => $mapelData['jam_mingguan'],
                    'kkm' => $mapelData['kkm'],
                ]
            );
        }

        JenisPenilaian::updateOrCreate(
            ['nama_jenis' => 'Tugas'],
            [
                'bobot' => 20,
                'keterangan' => 'Penilaian tugas harian',
            ]
        );

        JenisPenilaian::updateOrCreate(
            ['nama_jenis' => 'UTS'],
            [
                'bobot' => 30,
                'keterangan' => 'Penilaian tengah semester',
            ]
        );

        JenisPenilaian::updateOrCreate(
            ['nama_jenis' => 'UAS'],
            [
                'bobot' => 50,
                'keterangan' => 'Penilaian akhir semester',
            ]
        );

        $mapel = MataPelajaran::where('kode_mapel', 'MTK-01')->first();
        $mapelBin = MataPelajaran::where('kode_mapel', 'BIN-01')->first();
        $mapelBing = MataPelajaran::where('kode_mapel', 'BING-01')->first();
        $mapelIpa = MataPelajaran::where('kode_mapel', 'IPA-01')->first();
        $mapelIps = MataPelajaran::where('kode_mapel', 'IPS-01')->first();
        $jenisTugas = JenisPenilaian::where('nama_jenis', 'Tugas')->first();
        $jenisUTS = JenisPenilaian::where('nama_jenis', 'UTS')->first();
        $jenisUAS = JenisPenilaian::where('nama_jenis', 'UAS')->first();

        $todayDay = match (now()->dayOfWeekIso) {
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu',
        };

        $jadwalSeed = [
            ['kelas' => $kelasA, 'guru' => $guru, 'mapel' => $mapel, 'mulai' => '07:30', 'selesai' => '08:50', 'ruang' => 'R-101'],
            ['kelas' => $kelasB, 'guru' => $guru2, 'mapel' => $mapelIpa, 'mulai' => '09:00', 'selesai' => '10:20', 'ruang' => 'R-102'],
            ['kelas' => $kelasC, 'guru' => $guru3, 'mapel' => $mapelBin, 'mulai' => '10:30', 'selesai' => '11:50', 'ruang' => 'R-103'],
        ];

        foreach ($jadwalSeed as $seed) {
            if (! $seed['kelas'] || ! $seed['guru'] || ! $seed['mapel']) {
                continue;
            }

            Jadwal::updateOrCreate(
                [
                    'kelas_id' => $seed['kelas']->id,
                    'guru_id' => $seed['guru']->id,
                    'mata_pelajaran_id' => $seed['mapel']->id,
                    'tahun_ajaran_id' => $tahunAjaran->id,
                    'hari' => $todayDay,
                    'jam_mulai' => $seed['mulai'],
                ],
                [
                    'jam_selesai' => $seed['selesai'],
                    'ruang' => $seed['ruang'],
                    'status_aktif' => true,
                ]
            );
        }

        $students = Siswa::with('kelas')->get();
        $subjects = [
            ['mapel' => $mapel, 'guru' => $guru, 'base' => 85],
            ['mapel' => $mapelBin, 'guru' => $guru3, 'base' => 82],
            ['mapel' => $mapelBing, 'guru' => $guru3, 'base' => 84],
            ['mapel' => $mapelIpa, 'guru' => $guru2, 'base' => 86],
            ['mapel' => $mapelIps, 'guru' => $guru3, 'base' => 83],
        ];

        foreach ($students as $index => $student) {
            $dateOffset = $index + 1;

            Absensi::updateOrCreate(
                [
                    'siswa_id' => $student->id,
                    'kelas_id' => $student->kelas_id,
                    'tanggal_absen' => now()->subDays($dateOffset)->toDateString(),
                ],
                [
                    'guru_id' => $student->kelas?->wali_guru_id ?? $guru->id,
                    'tahun_ajaran_id' => $tahunAjaran->id,
                    'pertemuan_ke' => $dateOffset,
                    'status_kehadiran' => $index % 4 === 0 ? 'izin' : 'hadir',
                    'keterangan' => $index % 4 === 0 ? 'Izin untuk keperluan keluarga' : 'Hadir tepat waktu',
                ]
            );

            foreach ($subjects as $subjectIndex => $subject) {
                if (! $subject['mapel'] || ! $subject['guru']) {
                    continue;
                }

                $nilaiDasar = $subject['base'] - ($index * 2);
                $kelasId = $student->kelas_id;

                Nilai::updateOrCreate(
                    [
                        'siswa_id' => $student->id,
                        'kelas_id' => $kelasId,
                        'mata_pelajaran_id' => $subject['mapel']->id,
                        'jenis_penilaian_id' => $jenisTugas->id,
                        'tanggal_nilai' => now()->subDays($dateOffset + $subjectIndex + 2)->toDateString(),
                    ],
                    [
                        'guru_id' => $subject['guru']->id,
                        'tahun_ajaran_id' => $tahunAjaran->id,
                        'nilai' => $nilaiDasar,
                        'keterangan' => 'Tugas ' . ($subjectIndex + 1),
                    ]
                );

                Nilai::updateOrCreate(
                    [
                        'siswa_id' => $student->id,
                        'kelas_id' => $kelasId,
                        'mata_pelajaran_id' => $subject['mapel']->id,
                        'jenis_penilaian_id' => $jenisUTS->id,
                        'tanggal_nilai' => now()->subDays($dateOffset + $subjectIndex + 1)->toDateString(),
                    ],
                    [
                        'guru_id' => $subject['guru']->id,
                        'tahun_ajaran_id' => $tahunAjaran->id,
                        'nilai' => $nilaiDasar + 3,
                        'keterangan' => 'UTS',
                    ]
                );

                Nilai::updateOrCreate(
                    [
                        'siswa_id' => $student->id,
                        'kelas_id' => $kelasId,
                        'mata_pelajaran_id' => $subject['mapel']->id,
                        'jenis_penilaian_id' => $jenisUAS->id,
                        'tanggal_nilai' => now()->subDays($dateOffset)->toDateString(),
                    ],
                    [
                        'guru_id' => $subject['guru']->id,
                        'tahun_ajaran_id' => $tahunAjaran->id,
                        'nilai' => $nilaiDasar + 5,
                        'keterangan' => 'UAS',
                    ]
                );

                NilaiAkhir::updateOrCreate(
                    [
                        'siswa_id' => $student->id,
                        'kelas_id' => $kelasId,
                        'mata_pelajaran_id' => $subject['mapel']->id,
                        'tahun_ajaran_id' => $tahunAjaran->id,
                        'semester' => $tahunAjaran->semester,
                    ],
                    [
                        'nilai_akhir' => $nilaiDasar + 4,
                        'predikat' => $nilaiDasar + 4 >= 85 ? 'A' : (($nilaiDasar + 4 >= 75) ? 'B' : 'C'),
                        'ranking' => $index + 1,
                        'status_lulus' => true,
                        'catatan' => 'Data seed testing',
                    ]
                );
            }
        }
    }
}
