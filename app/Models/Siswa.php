<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $fillable = [
        'nisn', 
        'nama', 
        'kelas', 
        'jenis_kelamin', 
        'nama_ayah', 
        'nama_ibu', 
        'email_orang_tua'
    ];

    public function orangtua()
    {
        return $this->hasMany(User::class, 'siswa_id')->where('role', 'orangtua');
    }
}
