<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CbtUjian extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_ujian',
        'mapel',
        'kelas',
        'level',
        'durasi',
        'acak_soal',
        'acak_jawaban',
        'jumlah_soal',
        'status',
        'evaluasi_ujian'
    ];

    protected $casts = [
        'acak_soal' => 'boolean',
        'acak_jawaban' => 'boolean',
        'status' => 'boolean',
    ];
}
