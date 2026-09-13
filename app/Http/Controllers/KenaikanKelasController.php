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
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class KenaikanKelasController extends Controller
{
    private const ALLOWED_PROMOTION = [
        'X' => 'XI',
        'XI' => 'XII',
    ];

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
        $kkmAverage = 0.0;
        $nextLevel = $selectedKelas ? $this->nextPromotionLevel($selectedKelas) : null;
        $targetClassList = $nextLevel
            ? $this->classesByLevel($kelasList, $nextLevel)
            : collect();
        $promotionBlockedMessage = $selectedKelas && $this->classLevel($selectedKelas) === 'XII'
            ? 'Kelas XII tidak dapat dinaikkan lagi'
            : 'Kelas ini tidak memiliki jenjang berikutnya';

        if ($selectedKelas && ! empty($tahunAjaranIds)) {
            $students = Siswa::with(['user', 'kelas'])
                ->where('kelas_id', $selectedKelas->id)
                ->orderBy('nama_siswa')
                ->get()
                ->map(function (Siswa $siswa) use ($tahunAjaranIds): array {
                    $nilaiQuery = NilaiAkhir::with('mataPelajaran')
                        ->where('siswa_id', $siswa->id)
                        ->whereIn('tahun_ajaran_id', $tahunAjaranIds);

                    $avg = (float) ($nilaiQuery->avg('nilai_akhir') ?: 0);
                    $total = (clone $nilaiQuery)->count();
                    $tuntas = (clone $nilaiQuery)->where('status_lulus', true)->count();
                    $kkmValues = (clone $nilaiQuery)
                        ->get()
                        ->pluck('mataPelajaran.kkm')
                        ->filter(fn ($value) => is_numeric($value) && (int) $value > 0)
                        ->map(fn ($value) => (int) $value);
                    $kkmThreshold = $kkmValues->isNotEmpty()
                        ? round((float) $kkmValues->avg(), 2)
                        : 75.0;

                    return [
                        'siswa' => $siswa,
                        'rata_rata' => $avg,
                        'kkm' => $kkmThreshold,
                        'total_mapel' => $total,
                        'tuntas' => $tuntas,
                        'rekomendasi_naik' => $avg >= $kkmThreshold && $avg > 0,
                    ];
                });

            $kkmAverage = $students->count() > 0 ? round((float) $students->avg('kkm'), 2) : 75.0;
        }

        return view('masters.kenaikan-kelas', [
            'kelasList' => $kelasList,
            'selectedKelas' => $selectedKelas,
            'targetClassList' => $targetClassList,
            'hasPromotionTarget' => $targetClassList->isNotEmpty(),
            'promotionBlockedMessage' => $promotionBlockedMessage,
            'students' => $students,
            'tahunAjaranAktif' => $tahunAjaranAktif,
            'kkmAverage' => $kkmAverage,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'kelas_id' => ['required', 'exists:kelas,id'],
            'naik_ids' => ['nullable', 'array'],
            'naik_ids.*' => ['integer', 'exists:siswa,id'],
        ]);

        $kelasAsal = Kelas::findOrFail($data['kelas_id']);
        $allowedTargetIds = $this->allowedTargetClassIds($kelasAsal);
        $selectedIds = collect($data['naik_ids'] ?? [])->map(fn ($value) => (int) $value)->all();

        if ($allowedTargetIds->isEmpty()) {
            throw ValidationException::withMessages([
                'kelas_id' => 'Kelas '.$kelasAsal->nama_kelas.' tidak memiliki jenjang berikutnya.',
            ]);
        }

        $defaultTargetClassId = (int) $allowedTargetIds->first();

        DB::transaction(function () use ($data, $selectedIds, $defaultTargetClassId): void {
            $targetKelasId = $defaultTargetClassId;

            foreach ($selectedIds as $siswaId) {
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

    private function allowedTargetClassIds(Kelas $kelasAsal): Collection
    {
        $nextLevel = $this->nextPromotionLevel($kelasAsal);

        if (! $nextLevel) {
            return collect();
        }

        return $this->classesByLevel(Kelas::orderBy('nama_kelas')->get(), $nextLevel)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->values();
    }

    private function classesByLevel(Collection $kelasList, string $level): Collection
    {
        return $kelasList
            ->filter(fn (Kelas $kelas) => $this->classLevel($kelas) === $level)
            ->values();
    }

    private function nextPromotionLevel(Kelas $kelas): ?string
    {
        $level = $this->classLevel($kelas);

        return $level ? (self::ALLOWED_PROMOTION[$level] ?? null) : null;
    }

    private function classLevel(Kelas $kelas): ?string
    {
        foreach ([$kelas->tingkat, $kelas->nama_kelas] as $value) {
            $normalized = $this->normalizeLevel($value);

            if ($normalized) {
                return $normalized;
            }
        }

        return null;
    }

    private function normalizeLevel(?string $value): ?string
    {
        $value = strtoupper(trim((string) $value));

        if ($value === '') {
            return null;
        }

        if (preg_match('/^(XII|XI|X)(\b|[^A-Z])/', $value, $matches) === 1) {
            return $matches[1];
        }

        return null;
    }
}
