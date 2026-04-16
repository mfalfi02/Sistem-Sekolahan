<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absensi extends Model
{
    protected $table = 'absensi';

    protected $fillable = [
        'siswa_id',
        'kelas_id',
        'guru_id',
        'jadwal_id',
        'tahun_ajaran_id',
        'tanggal_absen',
        'pertemuan_ke',
        'status_kehadiran',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_absen' => 'date',
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

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class);
    }

    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(Jadwal::class);
    }
}
