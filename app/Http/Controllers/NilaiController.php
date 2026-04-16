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
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class NilaiController extends Controller
{
    public function index(Request $request): View
    {
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $mapelList = MataPelajaran::orderBy('nama_mapel')->get();
        $jenisList = JenisPenilaian::orderBy('nama_jenis')->get();
        $tahunAjaran = TahunAjaran::where('status_aktif', true)->first() ?? TahunAjaran::orderByDesc('id')->first();

        $kelasId = $request->integer('kelas_id') ?: $kelasList->first()?->id;
        $mapelId = $request->integer('mata_pelajaran_id') ?: $mapelList->first()?->id;
        $jenisPenilaianId = $request->integer('jenis_penilaian_id') ?: $jenisList->first()?->id;
        $tanggal = $request->input('tanggal', now()->toDateString());

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

        $guru = Guru::where('user_id', $request->user()->id)->first();
        $tahunAjaran = TahunAjaran::where('status_aktif', true)->first() ?? TahunAjaran::orderByDesc('id')->first();
        $kelas = Kelas::findOrFail($data['kelas_id']);
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
        if (! $tahunAjaranId) {
            return;
        }

        $students = Siswa::where('kelas_id', $kelasId)->get();

        foreach ($students as $student) {
            $nilaiRecords = Nilai::with('siswa')
                ->where('siswa_id', $student->id)
                ->where('kelas_id', $kelasId)
                ->where('mata_pelajaran_id', $mataPelajaranId)
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->get();

            if ($nilaiRecords->isEmpty()) {
                continue;
            }

            $bobotMap = JenisPenilaian::pluck('bobot', 'id');
            $totalBobot = 0.0;
            $totalNilai = 0.0;

            foreach ($nilaiRecords as $nilaiRecord) {
                $bobot = (float) ($bobotMap[$nilaiRecord->jenis_penilaian_id] ?? 0);
                $totalBobot += $bobot;
                $totalNilai += ((float) $nilaiRecord->nilai) * $bobot;
            }

            if ($totalBobot <= 0) {
                continue;
            }

            $finalScore = round($totalNilai / $totalBobot, 2);
            $predikat = $this->predikatFromScore($finalScore);
            $statusLulus = $finalScore >= 75;
            $semester = TahunAjaran::find($tahunAjaranId)?->semester ?? 'Ganjil';

            NilaiAkhir::updateOrCreate(
                [
                    'siswa_id' => $student->id,
                    'kelas_id' => $kelasId,
                    'mata_pelajaran_id' => $mataPelajaranId,
                    'tahun_ajaran_id' => $tahunAjaranId,
                    'semester' => $semester,
                ],
                [
                    'nilai_akhir' => $finalScore,
                    'predikat' => $predikat,
                    'status_lulus' => $statusLulus,
                    'catatan' => $statusLulus ? 'Tuntas' : 'Perlu remedial',
                ]
            );
        }
    }

    private function predikatFromScore(float $score): string
    {
        return match (true) {
            $score >= 90 => 'A',
            $score >= 80 => 'B',
            $score >= 70 => 'C',
            default => 'D',
        };
    }
}
