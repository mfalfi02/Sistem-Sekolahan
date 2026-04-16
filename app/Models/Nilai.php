<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Nilai extends Model
{
    protected $table = 'nilai';

    protected $fillable = [
        'siswa_id',
        'guru_id',
        'kelas_id',
        'mata_pelajaran_id',
        'tahun_ajaran_id',
        'jenis_penilaian_id',
        'tanggal_nilai',
        'nilai',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_nilai' => 'date',
            'nilai' => 'decimal:2',
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
}
