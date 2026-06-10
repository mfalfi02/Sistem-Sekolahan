<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Jadwal;
use App\Models\Notifikasi;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Support\ActivityLogger;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AbsensiController extends Controller
{
    public function index(Request $request): View
    {
        $kelasId = $request->integer('kelas_id');
        $jadwalId = $request->integer('jadwal_id');
        $tanggal = $request->input('tanggal', now()->toDateString());
        $tahunAjaran = TahunAjaran::where('status_aktif', true)->first() ?? TahunAjaran::orderByDesc('id')->first();
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $hariIni = $this->indonesianDay(Carbon::parse($tanggal)->dayOfWeekIso);

        $selectedKelas = $kelasId ? Kelas::find($kelasId) : $kelasList->first();
        $students = collect();
        $existingAbsensi = collect();
        $jadwalAktif = collect();
        $selectedJadwal = null;

        if ($selectedKelas) {
            $students = Siswa::with('kelas')
                ->where('kelas_id', $selectedKelas->id)
                ->orderBy('nama_siswa')
                ->get();

            $jadwalAktif = Jadwal::with(['guru', 'mataPelajaran'])
                ->where('kelas_id', $selectedKelas->id)
                ->where('hari', $hariIni)
                ->where('status_aktif', true)
                ->orderBy('jam_mulai')
                ->get();

            $selectedJadwal = $jadwalId
                ? $jadwalAktif->firstWhere('id', $jadwalId)
                : $jadwalAktif->first();

            if (! $selectedJadwal) {
                $selectedJadwal = $jadwalAktif->first();
            }

            $existingAbsensi = Absensi::where('kelas_id', $selectedKelas->id)
                ->whereDate('tanggal_absen', $tanggal)
                ->get()
                ->keyBy('siswa_id');
        }

        return view('absensi.index', [
            'kelasList' => $kelasList,
            'selectedKelas' => $selectedKelas,
            'students' => $students,
            'existingAbsensi' => $existingAbsensi,
            'jadwalAktif' => $jadwalAktif,
            'selectedJadwal' => $selectedJadwal,
            'hariIni' => $hariIni,
            'tanggal' => Carbon::parse($tanggal)->toDateString(),
            'tahunAjaran' => $tahunAjaran,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'kelas_id' => ['required', 'exists:kelas,id'],
            'jadwal_id' => ['nullable', 'exists:jadwal,id'],
            'tanggal' => ['required', 'date'],
            'absensi' => ['required', 'array'],
            'absensi.*.status' => ['required', 'in:hadir,sakit,izin,alfa,terlambat'],
            'absensi.*.keterangan' => ['nullable', 'string'],
            'pertemuan_ke' => ['nullable', 'integer', 'min:1'],
        ]);

        $tahunAjaran = TahunAjaran::where('status_aktif', true)->first() ?? TahunAjaran::orderByDesc('id')->first();
        $kelas = Kelas::findOrFail($data['kelas_id']);
        $siswaIds = Siswa::where('kelas_id', $kelas->id)->pluck('id')->all();
        $hari = $this->indonesianDay(Carbon::parse($data['tanggal'])->dayOfWeekIso);
        $jadwalAktif = null;

        if (! empty($data['jadwal_id'])) {
            $jadwalAktif = Jadwal::where('id', $data['jadwal_id'])
                ->where('kelas_id', $kelas->id)
                ->where('status_aktif', true)
                ->first();
        }

        if (! $jadwalAktif) {
            $jadwalAktif = Jadwal::where('kelas_id', $kelas->id)
                ->where('hari', $hari)
                ->where('status_aktif', true)
                ->orderBy('jam_mulai')
                ->first();
        }
        $guru = $jadwalAktif?->guru ?? Guru::where('user_id', $request->user()->id)->first();

        $siswaList = Siswa::with('user')
            ->where('kelas_id', $kelas->id)
            ->get();

        DB::transaction(function () use ($data, $guru, $tahunAjaran, $kelas, $siswaIds, $jadwalAktif, $siswaList): void {
            foreach ($siswaIds as $siswaId) {
                $row = $data['absensi'][$siswaId] ?? ['status' => 'hadir', 'keterangan' => null];

                Absensi::updateOrCreate(
                    [
                        'siswa_id' => $siswaId,
                        'kelas_id' => $kelas->id,
                        'tanggal_absen' => $data['tanggal'],
                    ],
                    [
                        'guru_id' => $guru?->id,
                        'jadwal_id' => $jadwalAktif?->id,
                        'tahun_ajaran_id' => $tahunAjaran?->id,
                        'pertemuan_ke' => $data['pertemuan_ke'] ?? null,
                        'status_kehadiran' => $row['status'],
                        'keterangan' => $row['keterangan'] ?? null,
                    ]
                );
            }

            $judul = 'Absensi kelas '.$kelas->nama_kelas.' pada '.Carbon::parse($data['tanggal'])->format('d M Y');
            foreach ($siswaList as $siswa) {
                $status = $data['absensi'][$siswa->id]['status'] ?? 'hadir';
                $pesan = 'Absensi Anda untuk kelas '.$kelas->nama_kelas.' sudah diinput dengan status '.strtoupper($status).'.';

                Notifikasi::updateOrCreate(
                    [
                        'user_id' => $siswa->user_id,
                        'judul' => $judul,
                        'link' => route('siswa.portal'),
                    ],
                    [
                        'pesan' => $pesan,
                        'tipe' => $status === 'hadir' ? 'success' : 'info',
                        'is_read' => false,
                        'read_at' => null,
                    ]
                );
            }
        });

        ActivityLogger::record(
            $request->user(),
            'absensi',
            'Input absensi kelas '.$kelas->nama_kelas,
            'Tanggal '.Carbon::parse($data['tanggal'])->format('d M Y').' untuk '.count($siswaIds).' siswa.',
            route('absensi.index', ['kelas_id' => $kelas->id, 'tanggal' => $data['tanggal']])
        );

        return redirect()
            ->route('absensi.index', ['kelas_id' => $kelas->id, 'tanggal' => $data['tanggal']])
            ->with('success', 'Absensi berhasil disimpan.');
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
