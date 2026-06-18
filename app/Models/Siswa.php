<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Siswa extends Model
{
    protected $fillable = [
        'nisn', 
        'nis',
        'nama', 
        'orang_tua_id', 
        'kelas_id', 
        'jenis_kelamin',
        'nama_ayah', 
        'nama_ibu', 
        'email_orang_tua'
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function orangTua(): BelongsTo
    {
        return $this->belongsTo(OrangTua::class, 'orang_tua_id');
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function raports(): HasMany
    {
        return $this->hasMany(Raport::class);
    }

    public function rankings(): HasMany
    {
        return $this->hasMany(SpkRanking::class);
    }

    public function ekstrakurikulers(): HasMany
    {
        return $this->hasMany(Ekstrakurikuler::class);
    }
}
