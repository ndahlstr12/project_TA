<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model SpkRanking — Hasil perankingan metode SAW
 *
 * Menyimpan output akhir perhitungan SPK (Simple Additive Weighting):
 *   - siswa_id    : siswa yang dievaluasi
 *   - skor_spk    : nilai preferensi Vi = Σ(Wj × Rij), range 0.0–1.0
 *   - ranking     : urutan dari Vi tertinggi (1 = terbaik)
 *   - tahun_ajaran: periode penilaian
 *
 * Interpretasi hasil:
 *   - Ranking tinggi (skor besar) → siswa berprestasi baik
 *   - Ranking rendah (skor kecil) → siswa perlu perhatian & intervensi
 *
 * Hasil ranking ini menjadi dasar rekomendasi penanganan masalah siswa
 * yang kemudian diperkuat dengan analisis Generative AI (Google Gemini).
 */
class SpkRanking extends Model
{
    protected $fillable = [
        'siswa_id',
        'skor_spk',
        'ranking',
        'tahun_ajaran',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }
}