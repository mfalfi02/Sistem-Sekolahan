<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\NilaiAkhir;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Support\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class KenaikanKelasController extends Controller
{
    public function index(Request $request): View
    {
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $selectedKelasId = $request->integer('kelas_id') ?: $kelasList->first()?->id;
        $tahunAjaranAktif = TahunAjaran::where('status_aktif', true)->first() ?? TahunAjaran::orderByDesc('id')->first();
        $tahunAjaranIds = $tahunAjaranAktif
            ? TahunAjaran::where('nama_tahun_ajaran', $tahunAjaranAktif->nama_tahun_ajaran)->pluck('id')->all()
            : [];

        $selectedKelas = $selectedKelasId ? Kelas::find($selectedKelasId) : null;
        $students = collect();
        $classAverage = 0.0;
        $targetClassList = $kelasList->when(
            $selectedKelasId,
            fn (Collection $collection) => $collection->where('id', '!=', $selectedKelasId)->values()
        );

        if ($selectedKelas && ! empty($tahunAjaranIds)) {
            $students = Siswa::with(['user', 'kelas'])
                ->where('kelas_id', $selectedKelas->id)
                ->orderBy('nama_siswa')
                ->get()
                ->map(function (Siswa $siswa) use ($tahunAjaranIds): array {
                    $nilaiQuery = NilaiAkhir::where('siswa_id', $siswa->id)
                        ->whereIn('tahun_ajaran_id', $tahunAjaranIds);

                    $avg = (float) ($nilaiQuery->avg('nilai_akhir') ?: 0);
                    $total = (clone $nilaiQuery)->count();
                    $tuntas = (clone $nilaiQuery)->where('status_lulus', true)->count();

                    return [
                        'siswa' => $siswa,
                        'rata_rata' => $avg,
                        'total_mapel' => $total,
                        'tuntas' => $tuntas,
                        'rekomendasi_naik' => $avg > 0,
                    ];
                });

            $classAverage = $students->count() > 0 ? round($students->avg('rata_rata'), 2) : 0;
            $students = $students->map(function (array $item) use ($classAverage): array {
                $item['rekomendasi_naik'] = $item['rata_rata'] >= $classAverage && $item['rata_rata'] > 0;

                return $item;
            });
        }

        return view('masters.kenaikan-kelas', [
            'kelasList' => $kelasList,
            'selectedKelas' => $selectedKelas,
            'targetClassList' => $targetClassList,
            'students' => $students,
            'tahunAjaranAktif' => $tahunAjaranAktif,
            'classAverage' => $classAverage,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'kelas_id' => ['required', 'exists:kelas,id'],
            'kelas_tujuan_default_id' => ['required', 'exists:kelas,id'],
            'naik_ids' => ['nullable', 'array'],
            'naik_ids.*' => ['integer', 'exists:siswa,id'],
            'target_kelas_id' => ['nullable', 'array'],
            'target_kelas_id.*' => ['nullable', 'exists:kelas,id'],
        ]);

        $kelasAsal = Kelas::findOrFail($data['kelas_id']);
        $selectedIds = collect($data['naik_ids'] ?? [])->map(fn ($value) => (int) $value)->all();

        DB::transaction(function () use ($data, $selectedIds): void {
            foreach ($selectedIds as $siswaId) {
                $targetKelasId = $data['target_kelas_id'][$siswaId] ?? $data['kelas_tujuan_default_id'];

                Siswa::whereKey($siswaId)
                    ->where('kelas_id', $data['kelas_id'])
                    ->update([
                        'kelas_id' => $targetKelasId,
                    ]);
            }
        });

        ActivityLogger::record(
            $request->user(),
            'kenaikan_kelas',
            'Proses kenaikan kelas',
            count($selectedIds).' siswa dipindahkan dari kelas '.$kelasAsal->nama_kelas.'.',
            route('kenaikan-kelas.index', ['kelas_id' => $data['kelas_id']])
        );

        return redirect()
            ->route('kenaikan-kelas.index', ['kelas_id' => $data['kelas_id']])
            ->with('success', 'Kenaikan kelas berhasil diproses.');
    }
}
