<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\JenisPenilaian;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\NilaiAkhir;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Support\NilaiAkhirCalculator;
use App\Support\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class NilaiController extends Controller
{
    public function index(Request $request): View
    {
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $mapelList = $this->mapelListForGuru($request);
        $jenisList = JenisPenilaian::orderBy('nama_jenis')->get();
        $tahunAjaran = TahunAjaran::where('status_aktif', true)->first() ?? TahunAjaran::orderByDesc('id')->first();

        $kelasId = $request->integer('kelas_id') ?: $kelasList->first()?->id;
        $requestedMapelId = $request->integer('mata_pelajaran_id') ?: null;

        $this->authorizeMapelAccess($request, $requestedMapelId);

        $mapelId = $requestedMapelId ?: $mapelList->first()?->id;
        $jenisPenilaianId = $request->integer('jenis_penilaian_id') ?: $jenisList->first()?->id;
        $tanggal = $request->input('tanggal', now('Asia/Jakarta')->toDateString());

        $selectedKelas = $kelasId ? Kelas::find($kelasId) : null;
        $students = collect();
        $existingNilai = collect();

        if ($selectedKelas) {
            $students = Siswa::with('kelas')
                ->where('kelas_id', $selectedKelas->id)
                ->orderBy('nama_siswa')
                ->get();

            if ($mapelId && $jenisPenilaianId) {
                $existingNilai = Nilai::where('kelas_id', $selectedKelas->id)
                    ->where('mata_pelajaran_id', $mapelId)
                    ->where('jenis_penilaian_id', $jenisPenilaianId)
                    ->whereDate('tanggal_nilai', $tanggal)
                    ->get()
                    ->keyBy('siswa_id');
            }
        }

        return view('nilai.index', [
            'kelasList' => $kelasList,
            'mapelList' => $mapelList,
            'jenisList' => $jenisList,
            'selectedKelas' => $selectedKelas,
            'students' => $students,
            'existingNilai' => $existingNilai,
            'tanggal' => $tanggal,
            'tahunAjaran' => $tahunAjaran,
            'selectedMapelId' => $mapelId,
            'selectedJenisPenilaianId' => $jenisPenilaianId,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'kelas_id' => ['required', 'exists:kelas,id'],
            'mata_pelajaran_id' => ['required', 'exists:mata_pelajaran,id'],
            'jenis_penilaian_id' => ['required', 'exists:jenis_penilaian,id'],
            'tanggal' => ['required', 'date'],
            'nilai' => ['required', 'array'],
            'nilai.*.angka' => ['required', 'numeric', 'min:0', 'max:100'],
            'nilai.*.keterangan' => ['nullable', 'string'],
        ]);

        $this->authorizeMapelAccess($request, (int) $data['mata_pelajaran_id']);

        $guru = Guru::where('user_id', $request->user()->id)->first();
        $tahunAjaran = TahunAjaran::where('status_aktif', true)->first() ?? TahunAjaran::orderByDesc('id')->first();
        $kelas = Kelas::findOrFail($data['kelas_id']);
        $mataPelajaran = MataPelajaran::find($data['mata_pelajaran_id']);
        $students = Siswa::where('kelas_id', $kelas->id)->pluck('id')->all();
        $jenisPenilaian = JenisPenilaian::findOrFail($data['jenis_penilaian_id']);

        DB::transaction(function () use ($data, $guru, $tahunAjaran, $kelas, $students): void {
            foreach ($students as $studentId) {
                $row = $data['nilai'][$studentId] ?? null;
                if (! $row) {
                    continue;
                }

                Nilai::updateOrCreate(
                    [
                        'siswa_id' => $studentId,
                        'kelas_id' => $kelas->id,
                        'mata_pelajaran_id' => $data['mata_pelajaran_id'],
                        'jenis_penilaian_id' => $data['jenis_penilaian_id'],
                        'tanggal_nilai' => $data['tanggal'],
                    ],
                    [
                        'guru_id' => $guru?->id,
                        'tahun_ajaran_id' => $tahunAjaran?->id,
                        'nilai' => $row['angka'],
                        'keterangan' => $row['keterangan'] ?? null,
                    ]
                );
            }
        });

        ActivityLogger::record(
            $request->user(),
            'nilai',
            'Input nilai kelas '.$kelas->nama_kelas,
            ($mataPelajaran?->nama_mapel ?? 'Mapel').', '.$jenisPenilaian->nama_jenis.' pada '.$data['tanggal'].'.',
            route('nilai.index', [
                'kelas_id' => $kelas->id,
                'mata_pelajaran_id' => $data['mata_pelajaran_id'],
                'jenis_penilaian_id' => $data['jenis_penilaian_id'],
                'tanggal' => $data['tanggal'],
            ])
        );

        $this->recalculateNilaiAkhir(
            $kelas->id,
            $data['mata_pelajaran_id'],
            $tahunAjaran?->id,
            $jenisPenilaian
        );

        return redirect()
            ->route('nilai.index', [
                'kelas_id' => $kelas->id,
                'mata_pelajaran_id' => $data['mata_pelajaran_id'],
                'jenis_penilaian_id' => $data['jenis_penilaian_id'],
                'tanggal' => $data['tanggal'],
            ])
            ->with('success', 'Nilai berhasil disimpan.');
    }

    private function recalculateNilaiAkhir(int $kelasId, int $mataPelajaranId, ?int $tahunAjaranId, JenisPenilaian $jenisPenilaian): void
    {
        app(NilaiAkhirCalculator::class)->recalculateForClassMapel($kelasId, $mataPelajaranId, $tahunAjaranId);
    }

    private function mapelListForGuru(Request $request): Collection
    {
        $guru = $this->currentGuru($request);

        if (! $guru) {
            return collect();
        }

        return MataPelajaran::query()
            ->whereHas('jadwal', fn ($query) => $query->where('guru_id', $guru->id))
            ->orderBy('nama_mapel')
            ->get();
    }

    private function currentGuru(Request $request): ?Guru
    {
        return Guru::where('user_id', $request->user()->id)->first();
    }

    private function authorizeMapelAccess(Request $request, ?int $mapelId): void
    {
        if (! $mapelId) {
            return;
        }

        $guru = $this->currentGuru($request);

        abort_unless(
            $guru && MataPelajaran::query()
                ->whereKey($mapelId)
                ->whereHas('jadwal', fn ($query) => $query->where('guru_id', $guru->id))
                ->exists(),
            403,
            'Anda tidak memiliki akses ke mata pelajaran tersebut.'
        );
    }
}
