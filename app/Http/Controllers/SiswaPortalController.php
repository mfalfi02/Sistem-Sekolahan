<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Notifikasi;
use App\Models\MataPelajaran;
use App\Models\Jadwal;
use App\Models\Nilai;
use App\Models\NilaiAkhir;
use App\Models\Siswa;
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

        $nilaiAkhir = NilaiAkhir::where('siswa_id', $siswa->id)
            ->where('mata_pelajaran_id', $mapelId)
            ->first();

        return view('siswa.nilai-detail', compact('siswa', 'mapel', 'nilaiDetail', 'nilaiAkhir'));
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
