<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\NilaiAkhir;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiswaPortalController extends Controller
{
    public function nilai(Request $request): View
    {
        $siswa = Siswa::with('kelas')
            ->where('user_id', $request->user()->id)
            ->first();

        if (! $siswa) {
            abort(404);
        }

        $tahunAjaranMap = TahunAjaran::get()
            ->mapWithKeys(fn (TahunAjaran $tahunAjaran) => [
                $tahunAjaran->id => [
                    'label' => $tahunAjaran->nama_tahun_ajaran,
                    'caption' => $tahunAjaran->semester,
                ],
            ]);
        $nilaiAkhir = NilaiAkhir::with(['kelas', 'mataPelajaran', 'tahunAjaran'])
            ->where('siswa_id', $siswa->id)
            ->get();
        $periods = $nilaiAkhir
            ->groupBy(fn (NilaiAkhir $nilai) => $nilai->kelas_id.'|'.$nilai->tahun_ajaran_id.'|'.$nilai->semester)
            ->map(function ($records, string $key) use ($tahunAjaranMap) {
                $nilai = $records->first();
                $tahunAjaran = $tahunAjaranMap->get($nilai->tahun_ajaran_id, ['label' => '-', 'caption' => '-']);
                $semesterLabel = $tahunAjaran['caption'] ?? $nilai->semester;

                return [
                    'key' => $key,
                    'label' => trim(($nilai->kelas?->nama_kelas ?? 'Kelas').' Semester '.$semesterLabel),
                    'caption' => trim($tahunAjaran['label'].' - '.($tahunAjaran['caption'] ?? '-')),
                    'average' => round((float) $records->avg('nilai_akhir'), 2),
                    'tuntas' => $records->where('status_lulus', true)->count(),
                    'remedial' => $records->where('status_lulus', false)->count(),
                    'records' => $records->sortBy(fn (NilaiAkhir $item) => $item->mataPelajaran?->nama_mapel ?? '')->values(),
                ];
            })
            ->sortBy('label')
            ->values();

        $selectedPeriodKey = $request->input('periode');
        $selectedPeriod = $selectedPeriodKey
            ? $periods->firstWhere('key', $selectedPeriodKey)
            : null;
        $periodFilters = $periods->map(fn (array $period) => [
            'key' => $period['key'],
            'label' => $period['label'],
            'caption' => $period['caption'],
        ])->values();

        $visiblePeriods = $selectedPeriod
            ? collect([$selectedPeriod])
            : $periods;

        return view('siswa.nilai-index', compact('siswa', 'visiblePeriods', 'periodFilters', 'selectedPeriodKey'));
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
        $nilaiDetail = Nilai::with(['jenisPenilaian', 'guru', 'tahunAjaran'])
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

}
