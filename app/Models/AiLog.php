<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiLog extends Model
{
    protected $fillable = [
        'raport_id', 
        'prompt_payload', 
        'hasil_ai', 
        'api_model'
    ];

    public function raport(): BelongsTo
    {
        return $this->belongsTo(Raport::class);
    }
}
