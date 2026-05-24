<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalPerilaku extends Model
{
    use HasFactory;

    protected $fillable = ['siswa_id', 'guru_id', 'catatan', 'poin', 'tipe', 'tanggal'];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
}
