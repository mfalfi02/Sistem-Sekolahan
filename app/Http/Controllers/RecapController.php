<?php

namespace App\Http\Controllers;

use App\Exports\AbsensiExport;
use App\Exports\NilaiExport;
use App\Models\Absensi;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Jadwal;
use App\Models\NilaiAkhir;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Dompdf\Dompdf;

class RecapController extends Controller
{
    public function absensi(Request $request): View
    {
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $kelasId = $request->integer('kelas_id') ?: $kelasList->first()?->id;
        $jadwalId = $request->integer('jadwal_id') ?: null;
        $mataPelajaranId = $request->integer('mata_pelajaran_id') ?: null;
        $tanggalDari = $request->input('tanggal_dari', now('Asia/Jakarta')->startOfMonth()->toDateString());
        $tanggalSampai = $request->input('tanggal_sampai', now('Asia/Jakarta')->toDateString());
        $selectedKelas = $kelasId ? Kelas::find($kelasId) : null;
        $jadwalList = collect();
        $mapelList = collect();
        $selectedJadwal = null;
        $selectedMapel = null;

        if ($selectedKelas) {
            $jadwalList = Jadwal::with(['mataPelajaran', 'guru'])
                ->where('kelas_id', $selectedKelas->id)
                ->orderBy('hari')
                ->orderBy('jam_mulai')
                ->get();

            $mapelList = $jadwalList
                ->pluck('mataPelajaran')
                ->filter()
                ->unique('id')
                ->values();

            $selectedJadwal = $jadwalId
                ? $jadwalList->firstWhere('id', $jadwalId)
                : null;

            $selectedMapel = $mataPelajaranId
                ? $mapelList->firstWhere('id', $mataPelajaranId)
                : null;

            if (! $selectedMapel && $selectedJadwal) {
                $selectedMapel = $selectedJadwal->mataPelajaran;
            }
        }

        $query = Absensi::with(['siswa', 'kelas', 'tahunAjaran', 'jadwal.mataPelajaran', 'jadwal.guru'])
            ->whereBetween('tanggal_absen', [$tanggalDari, $tanggalSampai]);

        if ($selectedKelas) {
            $query->where('kelas_id', $selectedKelas->id);
        }

        if ($jadwalId) {
            $query->where('jadwal_id', $jadwalId);
        } elseif ($mataPelajaranId) {
            $query->whereHas('jadwal', fn ($jadwal) => $jadwal->where('mata_pelajaran_id', $mataPelajaranId));
        }

        $records = $query->orderBy('tanggal_absen', 'desc')->orderBy('id', 'desc')->get();
        $summary = [
            'hadir' => (clone $query)->where('status_kehadiran', 'hadir')->count(),
            'sakit' => (clone $query)->where('status_kehadiran', 'sakit')->count(),
            'izin' => (clone $query)->where('status_kehadiran', 'izin')->count(),
            'alfa' => (clone $query)->where('status_kehadiran', 'alfa')->count(),
            'terlambat' => (clone $query)->where('status_kehadiran', 'terlambat')->count(),
        ];

        $jadwalSummary = $records
            ->groupBy('jadwal_id')
            ->map(function ($group) {
                $first = $group->first();
                $jadwal = $first?->jadwal;

                return [
                    'jadwal' => $jadwal,
                    'mapel' => $jadwal?->mataPelajaran,
                    'guru' => $jadwal?->guru,
                    'total' => $group->count(),
                    'hadir' => $group->where('status_kehadiran', 'hadir')->count(),
                    'sakit' => $group->where('status_kehadiran', 'sakit')->count(),
                    'izin' => $group->where('status_kehadiran', 'izin')->count(),
                    'alfa' => $group->where('status_kehadiran', 'alfa')->count(),
                    'terlambat' => $group->where('status_kehadiran', 'terlambat')->count(),
                ];
            })
            ->values();

        return view('rekap.absensi', compact(
            'kelasList',
            'selectedKelas',
            'tanggalDari',
            'tanggalSampai',
            'records',
            'summary',
            'jadwalList',
            'mapelList',
            'selectedJadwal',
            'selectedMapel',
            'jadwalSummary'
        ));
    }

    public function exportAbsensi(Request $request)
    {
        $kelasId = $request->integer('kelas_id');
        $jadwalId = $request->integer('jadwal_id') ?: null;
        $mapelId = $request->integer('mata_pelajaran_id') ?: null;
        $tanggalDari = $request->input('tanggal_dari');
        $tanggalSampai = $request->input('tanggal_sampai');

        return Excel::download(new AbsensiExport($kelasId, $jadwalId, $mapelId, $tanggalDari, $tanggalSampai), 'rekap-absensi.xlsx');
    }

    public function nilai(Request $request): View
    {
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $mapelList = $this->mapelListForRequest($request);
        $allowedMapelIds = $this->allowedMapelIdsForRequest($request);
        $kelasId = $request->integer('kelas_id') ?: $kelasList->first()?->id;
        $tanggalDari = $request->input('tanggal_dari', now('Asia/Jakarta')->startOfYear()->toDateString());
        $tanggalSampai = $request->input('tanggal_sampai', now('Asia/Jakarta')->toDateString());
        $requestedMapelId = $request->integer('mata_pelajaran_id') ?: null;

        $this->authorizeMapelAccess($request, $requestedMapelId, $allowedMapelIds);

        $mapelId = $requestedMapelId ?: $mapelList->first()?->id;
        $selectedKelas = $kelasId ? Kelas::find($kelasId) : null;
        $selectedMapel = $mapelId ? $mapelList->firstWhere('id', $mapelId) : null;

        $query = NilaiAkhir::with(['siswa', 'kelas', 'mataPelajaran', 'tahunAjaran']);

        $this->applyMapelAccess($query, $allowedMapelIds);

        if ($selectedKelas) {
            $query->where('kelas_id', $selectedKelas->id);
        }

        if ($selectedMapel) {
            $query->where('mata_pelajaran_id', $selectedMapel->id);
        }

        $records = $query->orderByDesc('nilai_akhir')->get();
        $average = $records->avg('nilai_akhir') ? round((float) $records->avg('nilai_akhir'), 2) : 0;

        return view('rekap.nilai', compact('kelasList', 'mapelList', 'selectedKelas', 'selectedMapel', 'records', 'average', 'tanggalDari', 'tanggalSampai'));
    }

    public function exportNilai(Request $request)
    {
        $kelasId = $request->integer('kelas_id');
        $mapelId = $request->integer('mata_pelajaran_id') ?: null;
        $allowedMapelIds = $this->allowedMapelIdsForRequest($request);

        $this->authorizeMapelAccess($request, $mapelId, $allowedMapelIds);

        return Excel::download(new NilaiExport($kelasId, $mapelId, $allowedMapelIds), 'rekap-nilai.xlsx');
    }

    public function exportAbsensiPdf(Request $request)
    {
        $kelasId = $request->integer('kelas_id');
        $jadwalId = $request->integer('jadwal_id') ?: null;
        $mapelId = $request->integer('mata_pelajaran_id') ?: null;
        $tanggalDari = $request->input('tanggal_dari');
        $tanggalSampai = $request->input('tanggal_sampai');

        $query = Absensi::with(['siswa.kelas', 'siswa.user', 'tahunAjaran', 'jadwal.mataPelajaran', 'jadwal.guru']);

        if ($kelasId) {
            $query->where('kelas_id', $kelasId);
        }

        if ($jadwalId) {
            $query->where('jadwal_id', $jadwalId);
        } elseif ($mapelId) {
            $query->whereHas('jadwal', fn ($jadwal) => $jadwal->where('mata_pelajaran_id', $mapelId));
        }

        if ($tanggalDari && $tanggalSampai) {
            $query->whereBetween('tanggal_absen', [$tanggalDari, $tanggalSampai]);
        }

        $records = $query->orderBy('tanggal_absen', 'desc')->get();
        $selectedKelas = $kelasId ? Kelas::find($kelasId) : null;
        $selectedJadwal = $jadwalId ? Jadwal::with(['mataPelajaran', 'guru'])->find($jadwalId) : null;
        $selectedMapel = $mapelId ? MataPelajaran::find($mapelId) : $selectedJadwal?->mataPelajaran;
        $tahunAjaranAktif = TahunAjaran::where('status_aktif', true)->first() ?? TahunAjaran::orderByDesc('id')->first();

        $html = view('exports.absensi-pdf', compact('records', 'selectedKelas', 'selectedJadwal', 'selectedMapel', 'tanggalDari', 'tanggalSampai', 'tahunAjaranAktif'))->render();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $pdf = $dompdf->output();

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="rekap-absensi.pdf"',
        ]);
    }

    public function exportNilaiPdf(Request $request)
    {
        $kelasId = $request->integer('kelas_id');
        $mapelId = $request->integer('mata_pelajaran_id') ?: null;
        $allowedMapelIds = $this->allowedMapelIdsForRequest($request);

        $this->authorizeMapelAccess($request, $mapelId, $allowedMapelIds);

        $query = NilaiAkhir::with(['siswa', 'kelas', 'mataPelajaran', 'tahunAjaran']);

        $this->applyMapelAccess($query, $allowedMapelIds);

        if ($kelasId) {
            $query->where('kelas_id', $kelasId);
        }

        if ($mapelId) {
            $query->where('mata_pelajaran_id', $mapelId);
        }

        $records = $query->orderByDesc('nilai_akhir')->get();
        $selectedKelas = $kelasId ? Kelas::find($kelasId) : null;
        $selectedMapel = $mapelId ? MataPelajaran::find($mapelId) : null;
        $tahunAjaranAktif = TahunAjaran::where('status_aktif', true)->first() ?? TahunAjaran::orderByDesc('id')->first();

        $html = view('exports.nilai-pdf', compact('records', 'selectedKelas', 'selectedMapel', 'tahunAjaranAktif'))->render();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $pdf = $dompdf->output();

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="rekap-nilai.pdf"',
        ]);
    }

    private function mapelListForRequest(Request $request): Collection
    {
        $query = MataPelajaran::query()->orderBy('nama_mapel');
        $guru = $this->currentGuru($request);

        if ($request->user()?->role === 'guru' && ! $guru) {
            $query->whereRaw('1 = 0');
        } elseif ($guru) {
            $query->whereHas('jadwal', fn ($jadwal) => $jadwal->where('guru_id', $guru->id));
        }

        return $query->get();
    }

    private function allowedMapelIdsForRequest(Request $request): ?array
    {
        $guru = $this->currentGuru($request);

        if ($request->user()?->role !== 'guru') {
            return null;
        }

        if (! $guru) {
            return [];
        }

        return MataPelajaran::query()
            ->whereHas('jadwal', fn ($jadwal) => $jadwal->where('guru_id', $guru->id))
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    private function currentGuru(Request $request): ?Guru
    {
        if ($request->user()?->role !== 'guru') {
            return null;
        }

        return Guru::where('user_id', $request->user()->id)->first();
    }

    private function authorizeMapelAccess(Request $request, ?int $mapelId, ?array $allowedMapelIds): void
    {
        if ($request->user()?->role !== 'guru' || ! $mapelId) {
            return;
        }

        abort_unless(
            in_array($mapelId, $allowedMapelIds ?? [], true),
            403,
            'Anda tidak memiliki akses ke mata pelajaran tersebut.'
        );
    }

    private function applyMapelAccess($query, ?array $allowedMapelIds): void
    {
        if ($allowedMapelIds !== null) {
            $query->whereIn('mata_pelajaran_id', $allowedMapelIds);
        }
    }
}
