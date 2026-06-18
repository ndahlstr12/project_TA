<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    use HasFactory;

    protected $fillable = ['nama_mapel', 'kode_mapel', 'kategori', 'kkm'];

    public function guruMapels()
    {
        return $this->hasMany(GuruMapel::class);
    }
}
