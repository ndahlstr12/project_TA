<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Guru extends Model
{
    use HasFactory;

    protected $fillable = [
        'nip',
        'nama',
        'gelar',
        'spesialisasi',
        'is_walikelas',
        'kelas_ampu'
    ];

    public function kelas()
    {
        return $this->hasOne(Kelas::class, 'nama_kelas', 'kelas_ampu');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'guru_id');
    }
}
