<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Jadwal extends Model
{
    use HasFactory;

    protected $fillable = [
        'hari',
        'jam_mulai',
        'jam_selesai',
        'mapel',
        'guru',
        'kelas'
    ];
}
