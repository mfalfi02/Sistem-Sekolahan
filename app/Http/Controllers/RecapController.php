<?php

namespace App\Http\Controllers;

use App\Exports\AbsensiExport;
use App\Exports\NilaiExport;
use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\NilaiAkhir;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Dompdf\Dompdf;

class RecapController extends Controller
{
    public function absensi(Request $request): View
    {
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $kelasId = $request->integer('kelas_id') ?: $kelasList->first()?->id;
        $tanggalDari = $request->input('tanggal_dari', now()->startOfMonth()->toDateString());
        $tanggalSampai = $request->input('tanggal_sampai', now()->toDateString());
        $selectedKelas = $kelasId ? Kelas::find($kelasId) : null;

        $query = Absensi::with(['siswa', 'kelas'])
            ->whereBetween('tanggal_absen', [$tanggalDari, $tanggalSampai]);

        if ($selectedKelas) {
            $query->where('kelas_id', $selectedKelas->id);
        }

        $records = $query->orderBy('tanggal_absen', 'desc')->orderBy('id', 'desc')->get();
        $summary = [
            'hadir' => (clone $query)->where('status_kehadiran', 'hadir')->count(),
            'sakit' => (clone $query)->where('status_kehadiran', 'sakit')->count(),
            'izin' => (clone $query)->where('status_kehadiran', 'izin')->count(),
            'alfa' => (clone $query)->where('status_kehadiran', 'alfa')->count(),
            'terlambat' => (clone $query)->where('status_kehadiran', 'terlambat')->count(),
        ];

        return view('rekap.absensi', compact('kelasList', 'selectedKelas', 'tanggalDari', 'tanggalSampai', 'records', 'summary'));
    }

    public function exportAbsensi(Request $request)
    {
        $kelasId = $request->integer('kelas_id');
        $tanggalDari = $request->input('tanggal_dari');
        $tanggalSampai = $request->input('tanggal_sampai');

        return Excel::download(new AbsensiExport($kelasId, $tanggalDari, $tanggalSampai), 'rekap-absensi.xlsx');
    }

    public function nilai(Request $request): View
    {
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $mapelList = MataPelajaran::orderBy('nama_mapel')->get();
        $kelasId = $request->integer('kelas_id') ?: $kelasList->first()?->id;
        $tanggalDari = $request->input('tanggal_dari', now()->startOfYear()->toDateString());
        $tanggalSampai = $request->input('tanggal_sampai', now()->toDateString());
        $mapelId = $request->integer('mata_pelajaran_id') ?: $mapelList->first()?->id;
        $selectedKelas = $kelasId ? Kelas::find($kelasId) : null;
        $selectedMapel = $mapelId ? MataPelajaran::find($mapelId) : null;

        $query = NilaiAkhir::with(['siswa', 'kelas', 'mataPelajaran']);

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
        $mapelId = $request->integer('mata_pelajaran_id');

        return Excel::download(new NilaiExport($kelasId, $mapelId), 'rekap-nilai.xlsx');
    }

    public function exportAbsensiPdf(Request $request)
    {
        $kelasId = $request->integer('kelas_id');
        $tanggalDari = $request->input('tanggal_dari');
        $tanggalSampai = $request->input('tanggal_sampai');

        $query = Absensi::with(['siswa.kelas', 'siswa.user']);

        if ($kelasId) {
            $query->where('kelas_id', $kelasId);
        }

        if ($tanggalDari && $tanggalSampai) {
            $query->whereBetween('tanggal_absen', [$tanggalDari, $tanggalSampai]);
        }

        $records = $query->orderBy('tanggal_absen', 'desc')->get();
        $selectedKelas = $kelasId ? Kelas::find($kelasId) : null;

        $html = view('exports.absensi-pdf', compact('records', 'selectedKelas', 'tanggalDari', 'tanggalSampai'))->render();

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
        $mapelId = $request->integer('mata_pelajaran_id');

        $query = NilaiAkhir::with(['siswa', 'kelas', 'mataPelajaran']);

        if ($kelasId) {
            $query->where('kelas_id', $kelasId);
        }

        if ($mapelId) {
            $query->where('mata_pelajaran_id', $mapelId);
        }

        $records = $query->orderByDesc('nilai_akhir')->get();
        $selectedKelas = $kelasId ? Kelas::find($kelasId) : null;
        $selectedMapel = $mapelId ? MataPelajaran::find($mapelId) : null;

        $html = view('exports.nilai-pdf', compact('records', 'selectedKelas', 'selectedMapel'))->render();

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
}
