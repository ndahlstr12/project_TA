<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Guru extends Model
{
    protected $fillable = [
        'nip', 
        'nama', 
        'gelar', 
        'spesialisasi',
        'ttd_digital',
        'is_walikelas',
        'kelas_ampu'
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function waliKelas(): HasOne
    {
        return $this->hasOne(Kelas::class, 'wali_id');
    }

    public function mapels(): HasMany
    {
        return $this->hasMany(GuruMapel::class);
    }

    public function jadwals(): HasMany
    {
        return $this->hasMany(Jadwal::class);
    }
}
