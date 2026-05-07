<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CbtSoal extends Model
{
    use HasFactory;

    protected $fillable = [
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
}
