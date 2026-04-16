<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisPenilaian extends Model
{
    protected $table = 'jenis_penilaian';

    protected $fillable = [
        'nama_jenis',
        'bobot',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'bobot' => 'decimal:2',
        ];
    }
}
