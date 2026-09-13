<?php

namespace App\Exports;

use App\Models\Absensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AbsensiExport implements FromCollection, WithHeadings, WithMapping
{
    protected $kelasId;
    protected $jadwalId;
    protected $mapelId;
    protected $tanggalDari;
    protected $tanggalSampai;

    public function __construct($kelasId = null, $jadwalId = null, $mapelId = null, $tanggalDari = null, $tanggalSampai = null)
    {
        $this->kelasId = $kelasId;
        $this->jadwalId = $jadwalId;
        $this->mapelId = $mapelId;
        $this->tanggalDari = $tanggalDari;
        $this->tanggalSampai = $tanggalSampai;
    }

    public function collection()
    {
        $query = Absensi::with(['siswa.kelas', 'siswa.user', 'jadwal.mataPelajaran', 'jadwal.guru']);

        if ($this->kelasId) {
            $query->where('kelas_id', $this->kelasId);
        }

        if ($this->jadwalId) {
            $query->where('jadwal_id', $this->jadwalId);
        } elseif ($this->mapelId) {
            $query->whereHas('jadwal', fn ($jadwal) => $jadwal->where('mata_pelajaran_id', $this->mapelId));
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
            'Mata Pelajaran',
            'Jadwal',
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
            $absensi->jadwal?->mataPelajaran?->nama_mapel ?? '-',
            $absensi->jadwal ? ($absensi->jadwal->hari.' '.($absensi->jadwal->jam_mulai ? substr((string) $absensi->jadwal->jam_mulai, 0, 5) : '-')) : '-',
            ucfirst($absensi->status_kehadiran),
            $absensi->keterangan ?? '-',
        ];
    }
}
