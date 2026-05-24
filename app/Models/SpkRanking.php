<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpkRanking extends Model
{
    protected $fillable = [
        'siswa_id', 
        'skor_spk', 
        'ranking', 
        'tahun_ajaran'
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }
}
