<?php

namespace App\Support;

use App\Models\Absensi;
use App\Models\JenisPenilaian;
use App\Models\Nilai;
use App\Models\NilaiAkhir;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Support\Collection;

class NilaiAkhirCalculator
{
    private const ABSENSI_WEIGHT = 10;
    private const AKADEMIK_WEIGHT = 90;

    public function recalculateForClassMapel(int $kelasId, int $mataPelajaranId, ?int $tahunAjaranId): void
    {
        if (! $tahunAjaranId) {
            return;
        }

        $students = Siswa::where('kelas_id', $kelasId)->get();
        $bobotMap = JenisPenilaian::pluck('bobot', 'id');

        $nilaiGroups = Nilai::where('kelas_id', $kelasId)
            ->where('mata_pelajaran_id', $mataPelajaranId)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->get()
            ->groupBy('siswa_id');

        $absensiGroups = Absensi::with('jadwal')
            ->where('kelas_id', $kelasId)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->whereHas('jadwal', fn ($query) => $query->where('mata_pelajaran_id', $mataPelajaranId))
            ->get()
            ->groupBy('siswa_id');

        $semester = TahunAjaran::find($tahunAjaranId)?->semester ?? 'Ganjil';

        foreach ($students as $student) {
            $nilaiRecords = $nilaiGroups->get($student->id, collect());
            if ($nilaiRecords->isEmpty()) {
                continue;
            }

            [$nilaiAkademik, $totalBobot] = $this->academicScore($nilaiRecords, $bobotMap);
            if ($totalBobot <= 0) {
                continue;
            }

            $attendanceSummary = $this->attendanceSummary($absensiGroups->get($student->id, collect()));
            $finalScore = round(
                ($nilaiAkademik * (self::AKADEMIK_WEIGHT / 100)) +
                ($attendanceSummary['nilai_absensi'] * (self::ABSENSI_WEIGHT / 100)),
                2
            );
            $predikat = $this->predikatFromScore($finalScore);
            $statusLulus = $finalScore >= 75;

            NilaiAkhir::updateOrCreate(
                [
                    'siswa_id' => $student->id,
                    'kelas_id' => $kelasId,
                    'mata_pelajaran_id' => $mataPelajaranId,
                    'tahun_ajaran_id' => $tahunAjaranId,
                    'semester' => $semester,
                ],
                array_merge([
                    'nilai_akhir' => $finalScore,
                    'predikat' => $predikat,
                    'status_lulus' => $statusLulus,
                    'catatan' => $statusLulus ? 'Tuntas' : 'Perlu remedial',
                ], $attendanceSummary)
            );
        }
    }

    private function academicScore(Collection $nilaiRecords, Collection $bobotMap): array
    {
        $totalBobot = 0.0;
        $totalNilai = 0.0;

        foreach ($nilaiRecords as $nilaiRecord) {
            $bobot = (float) ($bobotMap[$nilaiRecord->jenis_penilaian_id] ?? 0);
            $totalBobot += $bobot;
            $totalNilai += ((float) $nilaiRecord->nilai) * $bobot;
        }

        if ($totalBobot <= 0) {
            return [0.0, 0.0];
        }

        return [round($totalNilai / $totalBobot, 2), $totalBobot];
    }

    private function attendanceSummary(Collection $records): array
    {
        $counts = $records->countBy('status_kehadiran');
        $total = $records->count();
        $hadir = (int) ($counts->get('hadir', 0));
        $terlambat = (int) ($counts->get('terlambat', 0));
        $sakit = (int) ($counts->get('sakit', 0));
        $izin = (int) ($counts->get('izin', 0));
        $alfa = (int) ($counts->get('alfa', 0));
        $presentCount = $hadir + $terlambat;
        $percentage = $total > 0 ? round(($presentCount / $total) * 100, 2) : 0.0;
        $attendanceScore = $percentage;

        return [
            'absensi_total' => $total,
            'absensi_hadir' => $hadir,
            'absensi_terlambat' => $terlambat,
            'absensi_sakit' => $sakit,
            'absensi_izin' => $izin,
            'absensi_alfa' => $alfa,
            'persentase_absensi' => $percentage,
            'nilai_absensi' => $attendanceScore,
        ];
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
