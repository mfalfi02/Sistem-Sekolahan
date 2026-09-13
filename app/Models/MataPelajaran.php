<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MataPelajaran extends Model
{
    protected $table = 'mata_pelajaran';

    protected $fillable = [
        'kode_mapel',
        'nama_mapel',
        'kelompok_mapel',
        'jam_mingguan',
        'kkm',
    ];

    public function jadwal(): HasMany
    {
        return $this->hasMany(Jadwal::class);
    }
}
