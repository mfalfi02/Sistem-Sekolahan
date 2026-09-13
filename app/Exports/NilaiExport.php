<?php

namespace App\Exports;

use App\Models\NilaiAkhir;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class NilaiExport implements FromCollection, WithHeadings, WithMapping
{
    protected $kelasId;
    protected $mapelId;
    protected $allowedMapelIds;

    public function __construct($kelasId = null, $mapelId = null, ?array $allowedMapelIds = null)
    {
        $this->kelasId = $kelasId;
        $this->mapelId = $mapelId;
        $this->allowedMapelIds = $allowedMapelIds;
    }

    public function collection()
    {
        $query = NilaiAkhir::with(['siswa', 'mataPelajaran', 'kelas']);

        if ($this->kelasId) {
            $query->where('kelas_id', $this->kelasId);
        }

        if ($this->mapelId) {
            $query->where('mata_pelajaran_id', $this->mapelId);
        }

        if ($this->allowedMapelIds !== null) {
            $query->whereIn('mata_pelajaran_id', $this->allowedMapelIds);
        }

        return $query->orderByDesc('nilai_akhir')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Siswa',
            'Kelas',
            'Mata Pelajaran',
            'Nilai Akhir',
            'Absensi',
            'Predikat',
            'Status',
        ];
    }

    public function map($nilai): array
    {
        return [
            $nilai->id,
            $nilai->siswa->nama_siswa,
            $nilai->kelas->nama_kelas ?? '-',
            $nilai->mataPelajaran->nama_mapel ?? '-',
            number_format((float) $nilai->nilai_akhir, 2),
            number_format((float) ($nilai->persentase_absensi ?? 0), 2).'%',
            $nilai->predikat ?? '-',
            $nilai->status_lulus ? 'Tuntas' : 'Belum Tuntas',
        ];
    }
}
