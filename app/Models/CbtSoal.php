<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CbtSoal extends Model
{
    use HasFactory;

    protected $fillable = [
        'ujian_id',
        'pertanyaan',
        'opsi_a',
        'opsi_b',
        'opsi_c',
        'opsi_d',
        'opsi_e',
        'jawaban_benar',
        'mapel',
        'kelas'
    ];

    public function ujian()
    {
        return $this->belongsTo(CbtUjian::class, 'ujian_id');
    }
}
