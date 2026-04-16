<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Siswa extends Model
{
    protected $table = 'siswa';

    protected $fillable = [
        'user_id',
        'nis',
        'nisn',
        'nama_siswa',
        'jenis_kelamin',
        'tanggal_lahir',
        'alamat',
        'no_hp',
        'foto',
        'kelas_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }
}
