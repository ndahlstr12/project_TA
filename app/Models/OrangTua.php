<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrangTua extends Model
{
    protected $table = 'orang_tuas';
    protected $fillable = [
        'user_id', 
        'nama_wali', 
        'no_telp', 
        'alamat', 
        'email_notifikasi'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function siswas(): HasMany
    {
        return $this->hasMany(Siswa::class, 'orang_tua_id');
    }
}
