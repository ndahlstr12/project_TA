<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CbtHasil extends Model
{
    use HasFactory;

    protected $table = 'cbt_hasils';

    protected $fillable = [
        'cbt_ujian_id',
        'siswa_id',
        'skor',
        'jumlah_benar',
        'jumlah_salah',
        'status',
        'rekomendasi_ai'
    ];

    public function ujian()
    {
        return $this->belongsTo(CbtUjian::class, 'cbt_ujian_id');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
