<?php

namespace App\Exports;

use App\Models\Absensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AbsensiExport implements FromCollection, WithHeadings, WithMapping
{
    protected $kelasId;
    protected $tanggalDari;
    protected $tanggalSampai;

    public function __construct($kelasId = null, $tanggalDari = null, $tanggalSampai = null)
    {
        $this->kelasId = $kelasId;
        $this->tanggalDari = $tanggalDari;
        $this->tanggalSampai = $tanggalSampai;
    }

    public function collection()
    {
        $query = Absensi::with(['siswa.kelas', 'siswa.user']);

        if ($this->kelasId) {
            $query->where('kelas_id', $this->kelasId);
        }

        if ($this->tanggalDari && $this->tanggalSampai) {
            $query->whereBetween('tanggal_absen', [$this->tanggalDari, $this->tanggalSampai]);
        }

        return $query->orderBy('tanggal_absen', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Siswa',
            'Kelas',
            'Status',
            'Keterangan',
        ];
    }

    public function map($absensi): array
    {
        return [
            $absensi->id,
            $absensi->tanggal_absen->format('d/m/Y'),
            $absensi->siswa->user->name ?? $absensi->siswa->nama_siswa,
            $absensi->siswa->kelas->nama_kelas ?? '-',
            ucfirst($absensi->status_kehadiran),
            $absensi->keterangan ?? '-',
        ];
    }
}
