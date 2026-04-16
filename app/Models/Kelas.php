<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kelas extends Model
{
    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas',
        'tingkat',
        'jurusan',
        'kapasitas',
        'wali_guru_id',
        'tahun_ajaran_id',
    ];

    public function waliGuru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'wali_guru_id');
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }
}
