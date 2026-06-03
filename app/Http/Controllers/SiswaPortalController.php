<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Notifikasi;
use App\Models\MataPelajaran;
use App\Models\Jadwal;
use App\Models\Nilai;
use App\Models\NilaiAkhir;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiswaPortalController extends Controller
{
    public function index(Request $request): View
    {
        $siswa = Siswa::with('kelas')
            ->where('user_id', $request->user()->id)
            ->first();
        $hariIni = $this->indonesianDay(now()->dayOfWeekIso);

        $absensi = collect();
        $nilaiAkhir = collect();
        $jadwalHariIni = collect();
        $notifikasi = collect();

        if ($siswa) {
            $absensi = Absensi::with(['kelas', 'guru'])
                ->where('siswa_id', $siswa->id)
                ->latest('tanggal_absen')
                ->limit(10)
                ->get();

            $nilaiAkhir = NilaiAkhir::with(['kelas', 'mataPelajaran'])
                ->where('siswa_id', $siswa->id)
                ->orderByDesc('nilai_akhir')
                ->get();

            $jadwalHariIni = Jadwal::with(['guru', 'mataPelajaran'])
                ->where('kelas_id', $siswa->kelas_id)
                ->where('hari', $hariIni)
                ->where('status_aktif', true)
                ->orderBy('jam_mulai')
                ->get();

            $notifikasi = Notifikasi::where('user_id', $request->user()->id)
                ->latest()
                ->limit(5)
                ->get();
        }

        return view('siswa.portal', compact('siswa', 'absensi', 'nilaiAkhir', 'jadwalHariIni', 'hariIni', 'notifikasi'));
    }

    public function nilai(Request $request): View
    {
        $siswa = Siswa::with('kelas')
            ->where('user_id', $request->user()->id)
            ->first();

        if (! $siswa) {
            abort(404);
        }

        $tahunAjaranMap = TahunAjaran::pluck('nama_tahun_ajaran', 'id');
        $nilaiAkhir = NilaiAkhir::with(['kelas', 'mataPelajaran'])
            ->where('siswa_id', $siswa->id)
            ->get();
        $periods = $nilaiAkhir
            ->groupBy(fn (NilaiAkhir $nilai) => $nilai->kelas_id.'|'.$nilai->tahun_ajaran_id.'|'.$nilai->semester)
            ->map(function ($records, string $key) use ($tahunAjaranMap) {
                $nilai = $records->first();

                return [
                    'key' => $key,
                    'label' => trim(($nilai->kelas?->nama_kelas ?? 'Kelas').' Semester '.$nilai->semester),
                    'caption' => $tahunAjaranMap->get($nilai->tahun_ajaran_id, '-'),
                    'average' => round((float) $records->avg('nilai_akhir'), 2),
                    'tuntas' => $records->where('status_lulus', true)->count(),
                    'remedial' => $records->where('status_lulus', false)->count(),
                    'records' => $records->sortBy(fn (NilaiAkhir $item) => $item->mataPelajaran?->nama_mapel ?? '')->values(),
                ];
            })
            ->sortBy('label')
            ->values();

        return view('siswa.nilai-index', compact('siswa', 'periods'));
    }

    public function absensi(Request $request): View
    {
        $siswa = Siswa::with('kelas')
            ->where('user_id', $request->user()->id)
            ->first();

        if (! $siswa) {
            abort(404);
        }

        $tahunAjaranMap = TahunAjaran::get()
            ->mapWithKeys(fn (TahunAjaran $tahunAjaran) => [
                $tahunAjaran->id => trim($tahunAjaran->nama_tahun_ajaran.' Semester '.$tahunAjaran->semester),
            ]);
        $periods = Absensi::with(['kelas', 'guru'])
            ->where('siswa_id', $siswa->id)
            ->latest('tanggal_absen')
            ->get()
            ->groupBy('tahun_ajaran_id')
            ->map(function ($records, $tahunAjaranId) use ($tahunAjaranMap) {
                return [
                    'key' => (string) $tahunAjaranId,
                    'label' => $tahunAjaranMap->get($tahunAjaranId, 'Periode Lain'),
                    'summary' => $records->countBy('status_kehadiran'),
                    'records' => $records->values(),
                    'total' => $records->count(),
                ];
            })
            ->sortBy('label')
            ->values();

        return view('siswa.absensi-index', compact('siswa', 'periods'));
    }

    public function detailNilai($mapelId): View
    {
        $siswa = Siswa::with('kelas')
            ->where('user_id', auth()->id())
            ->first();

        if (! $siswa) {
            abort(404);
        }

        $mapel = MataPelajaran::findOrFail($mapelId);
        $nilaiDetail = Nilai::with(['jenisPenilaian', 'guru'])
            ->where('siswa_id', $siswa->id)
            ->where('mata_pelajaran_id', $mapelId)
            ->orderBy('tanggal_nilai', 'desc')
            ->get();

        $nilaiAkhirList = NilaiAkhir::with(['kelas', 'tahunAjaran'])
            ->where('siswa_id', $siswa->id)
            ->where('mata_pelajaran_id', $mapelId)
            ->orderBy('tahun_ajaran_id')
            ->orderBy('semester')
            ->get();

        return view('siswa.nilai-detail', compact('siswa', 'mapel', 'nilaiDetail', 'nilaiAkhirList'));
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
}
