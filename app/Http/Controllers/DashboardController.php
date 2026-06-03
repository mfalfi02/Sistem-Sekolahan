<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\NilaiAkhir;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();
        $role = $user?->role;
        $today = now()->toDateString();
        $selectedKelasId = $request->integer('kelas_id');

        $data = [];

        match ($role) {
            'admin', 'tu' => $this->adminData($data, $today, $selectedKelasId),
            'guru' => $this->guruData($data, $user, $today),
            'siswa' => $this->siswaData($data, $user),
            default => $this->defaultData($data),
        };

        return view('dashboard.index', array_merge(['user' => $user, 'role' => $role], $data));
    }

    private function adminData(array &$data, string $today, ?int $selectedKelasId = null): void
    {
        $hariIni = $this->indonesianDay(now()->dayOfWeekIso);
        $kelasFilterList = Kelas::orderBy('nama_kelas')->get();
        $kelasList = Kelas::with(['waliGuru.user', 'tahunAjaran'])->orderBy('nama_kelas')->get();

        if ($selectedKelasId) {
            $kelasList = $kelasList->where('id', $selectedKelasId)->values();
        }

        $jadwalHariIni = Jadwal::with(['kelas.waliGuru.user', 'guru.user', 'mataPelajaran'])
            ->where('hari', $hariIni)
            ->where('status_aktif', true)
            ->get()
            ->groupBy('kelas_id');

        $data['cards'] = [
            ['label' => 'Total User', 'value' => (string) User::count(), 'icon' => '👥'],
            ['label' => 'Siswa Aktif', 'value' => (string) Siswa::count(), 'icon' => '👦'],
            ['label' => 'Guru', 'value' => (string) Guru::count(), 'icon' => '👩‍🏫'],
            ['label' => 'Nilai Akhir', 'value' => (string) NilaiAkhir::count(), 'icon' => '📊'],
        ];

        $data['classActivity'] = $kelasList->map(function (Kelas $kelas) use ($today, $jadwalHariIni) {
            $totalSiswa = Siswa::where('kelas_id', $kelas->id)->count();
            $hadir = Absensi::where('kelas_id', $kelas->id)
                ->whereDate('tanggal_absen', $today)
                ->where('status_kehadiran', 'hadir')
                ->count();
            $persentaseHadir = $totalSiswa > 0 ? round(($hadir / $totalSiswa) * 100, 1) : 0;
            $jadwalKelas = $jadwalHariIni->get($kelas->id, collect());
            $jadwalUtama = $jadwalKelas->first();

            return [
                'kelas' => $kelas,
                'total_siswa' => $totalSiswa,
                'hadir' => $hadir,
                'persentase_hadir' => $persentaseHadir,
                'guru' => $jadwalUtama?->guru?->nama_guru ?? $kelas->waliGuru?->nama_guru ?? '-',
                'mata_pelajaran' => $jadwalUtama?->mataPelajaran?->nama_mapel ?? '-',
                'jam' => $jadwalUtama ? trim(($jadwalUtama->jam_mulai ? substr((string) $jadwalUtama->jam_mulai, 0, 5) : '-') . ' - ' . ($jadwalUtama->jam_selesai ? substr((string) $jadwalUtama->jam_selesai, 0, 5) : '-')) : '-',
                'jumlah_jadwal' => $jadwalKelas->count(),
            ];
        })->values();

        $data['kelasFilterList'] = $kelasFilterList;
        $data['selectedKelasId'] = $selectedKelasId;
        $data['selectedKelasLabel'] = $selectedKelasId
            ? $kelasFilterList->firstWhere('id', $selectedKelasId)?->nama_kelas
            : 'Semua Kelas';
    }

    private function guruData(array &$data, $user, string $today): void
    {
        $guru = Guru::where('user_id', $user->id)->first();
        $hariIni = $this->indonesianDay(now()->dayOfWeekIso);
        $jadwalHariIni = Jadwal::with(['kelas', 'mataPelajaran'])
            ->where('guru_id', $guru?->id)
            ->where('hari', $hariIni)
            ->where('status_aktif', true)
            ->orderBy('jam_mulai')
            ->get();

        $data['cards'] = [
            ['label' => 'Absensi Hari Ini', 'value' => (string) Absensi::whereDate('tanggal_absen', $today)->count(), 'icon' => '📅'],
            ['label' => 'Nilai Masuk Hari Ini', 'value' => (string) Nilai::whereDate('tanggal_nilai', $today)->count(), 'icon' => '📝'],
            ['label' => 'Kelas Diajar', 'value' => (string) Kelas::count(), 'icon' => '🏫'],
            ['label' => 'Mapel', 'value' => (string) MataPelajaran::count(), 'icon' => '📚'],
        ];

        $data['recentNilai'] = Nilai::where('guru_id', $guru?->id)->latest()->limit(5)->get();
        $data['jadwalHariIni'] = $jadwalHariIni;
        $data['hariIni'] = $hariIni;
    }

    private function siswaData(array &$data, $user): void
    {
        $siswa = Siswa::with('kelas')->where('user_id', $user->id)->first();
        $hariIni = $this->indonesianDay(now()->dayOfWeekIso);
        $jadwalHariIni = Jadwal::with(['guru', 'mataPelajaran'])
            ->where('kelas_id', $siswa?->kelas_id)
            ->where('hari', $hariIni)
            ->where('status_aktif', true)
            ->orderBy('jam_mulai')
            ->get();
        $absensiThisMonth = Absensi::where('siswa_id', $siswa?->id)
            ->whereMonth('tanggal_absen', now()->month)
            ->count();
        $totalHadir = Absensi::where('siswa_id', $siswa?->id)
            ->where('status_kehadiran', 'hadir')
            ->whereMonth('tanggal_absen', now()->month)
            ->count();
        $kehadiran = $absensiThisMonth > 0 ? round(($totalHadir / $absensiThisMonth) * 100, 1) : 0;
        $tahunAjaranMap = TahunAjaran::pluck('nama_tahun_ajaran', 'id');
        $nilaiAkhir = NilaiAkhir::with(['kelas', 'mataPelajaran'])
            ->where('siswa_id', $siswa?->id)
            ->get();
        $gradePeriods = $nilaiAkhir
            ->groupBy(fn (NilaiAkhir $nilai) => $nilai->kelas_id.'|'.$nilai->tahun_ajaran_id.'|'.$nilai->semester)
            ->map(function ($records, string $key) use ($tahunAjaranMap) {
                $nilai = $records->first();

                return [
                    'key' => $key,
                    'kelas_id' => $nilai->kelas_id,
                    'tahun_ajaran_id' => $nilai->tahun_ajaran_id,
                    'semester' => $nilai->semester,
                    'label' => trim(($nilai->kelas?->nama_kelas ?? 'Kelas').' Semester '.$nilai->semester),
                    'caption' => $tahunAjaranMap->get($nilai->tahun_ajaran_id, '-'),
                    'average' => round((float) $records->avg('nilai_akhir'), 2),
                    'total' => $records->count(),
                ];
            })
            ->sortBy('label')
            ->values();
        $gradeChartData = $gradePeriods
            ->map(fn (array $period) => [
                'label' => $this->shortPeriodName($period['label']),
                'full_label' => trim($period['label'].' '.$period['caption']),
                'score' => $period['average'],
                'total' => $period['total'],
            ])
            ->values();
        $absensiSummary = Absensi::where('siswa_id', $siswa?->id)
            ->whereMonth('tanggal_absen', now()->month)
            ->get()
            ->countBy('status_kehadiran');

        $data['cards'] = [
            ['label' => 'Kehadiran Bulan Ini', 'value' => $kehadiran . '%', 'icon' => '✅'],
            ['label' => 'Nilai Tersedia', 'value' => (string) Nilai::where('siswa_id', $siswa?->id)->count(), 'icon' => '⭐'],
            ['label' => 'Nilai Akhir', 'value' => (string) $nilaiAkhir->count(), 'icon' => '🏆'],
            ['label' => 'Peringkat Kelas', 'value' => 'Top 10%', 'icon' => '🥇'],
        ];

        $data['siswa'] = $siswa;
        $data['avgGrade'] = $nilaiAkhir->avg('nilai_akhir') ?: 0;
        $data['gradePeriods'] = $gradePeriods;
        $data['gradeChartData'] = $gradeChartData;
        $data['absensiSummary'] = $absensiSummary;
        $data['jadwalHariIni'] = $jadwalHariIni;
        $data['hariIni'] = $hariIni;
    }

    private function defaultData(array &$data): void
    {
        $data['cards'] = [
            ['label' => 'Total Guru', 'value' => (string) Guru::count(), 'icon' => '👩‍🏫'],
            ['label' => 'Total Siswa', 'value' => (string) Siswa::count(), 'icon' => '👦'],
            ['label' => 'Kelas', 'value' => (string) Kelas::count(), 'icon' => '🏫'],
            ['label' => 'Mapel', 'value' => (string) MataPelajaran::count(), 'icon' => '📚'],
        ];
    }

    private function indonesianDay(int $dayOfWeekIso): string
    {
        return match ($dayOfWeekIso) {
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu',
        };
    }

    private function shortPeriodName(string $name): string
    {
        return str_replace(['Semester Ganjil', 'Semester Genap'], ['Ganjil', 'Genap'], $name);
    }
}
