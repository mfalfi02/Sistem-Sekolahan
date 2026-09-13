<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NilaiAkhir extends Model
{
    protected $table = 'nilai_akhir';

    protected $fillable = [
        'siswa_id',
        'kelas_id',
        'mata_pelajaran_id',
        'tahun_ajaran_id',
        'semester',
        'nilai_akhir',
        'absensi_total',
        'absensi_hadir',
        'absensi_terlambat',
        'absensi_sakit',
        'absensi_izin',
        'absensi_alfa',
        'persentase_absensi',
        'nilai_absensi',
        'predikat',
        'ranking',
        'status_lulus',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'nilai_akhir' => 'decimal:2',
            'absensi_total' => 'integer',
            'absensi_hadir' => 'integer',
            'absensi_terlambat' => 'integer',
            'absensi_sakit' => 'integer',
            'absensi_izin' => 'integer',
            'absensi_alfa' => 'integer',
            'persentase_absensi' => 'decimal:2',
            'nilai_absensi' => 'decimal:2',
            'status_lulus' => 'boolean',
        ];
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function mataPelajaran(): BelongsTo
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class);
    }
}
