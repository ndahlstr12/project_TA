<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Raport extends Model
{
    use HasFactory;

    protected $fillable = [
        'siswa_id',
        'wali_id',
        'catatan_wali',
        'status',
        'semester',
        'tahun_ajaran'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function wali()
    {
        return $this->belongsTo(Guru::class, 'wali_id');
    }
}
