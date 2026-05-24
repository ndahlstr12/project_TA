<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RaportFile extends Model
{
    protected $fillable = [
        'raport_id', 
        'template_name', 
        'file_path_pdf', 
        'generated_at'
    ];

    protected $casts = [
        'generated_at' => 'datetime',
    ];

    public function raport(): BelongsTo
    {
        return $this->belongsTo(Raport::class);
    }
}
