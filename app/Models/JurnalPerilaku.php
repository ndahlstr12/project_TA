<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalPerilaku extends Model
{
    use HasFactory;

    protected $fillable = ['siswa_id', 'guru_id', 'catatan', 'rekomendasi', 'poin', 'tipe', 'tanggal'];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
}
