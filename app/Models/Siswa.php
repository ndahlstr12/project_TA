<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['nisn', 'nama', 'kelas', 'jenis_kelamin'])]
class Siswa extends Model
{
    public function orangtua()
    {
        return $this->hasMany(User::class, 'siswa_id')->where('role', 'orangtua');
    }
}
