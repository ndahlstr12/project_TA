<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Raport extends Model
{
    use HasFactory;

    protected $fillable = [
        'siswa_id',
        'wali_id',
        'catatan_wali',
        'kokurikuler',
        'sakit',
        'izin',
        'alpa',
        'status',
        'saran_ai',
        'rekomendasi_ai',
        'semester',
        'tahun_ajaran',
    ];

    // =========================================================================
    // RELASI
    // =========================================================================

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function wali()
    {
        return $this->belongsTo(Guru::class, 'wali_id');
    }

    // =========================================================================
    // ACCESSOR — dihitung otomatis, tidak perlu kolom tambahan di database
    // =========================================================================

    /**
     * Persentase kehadiran siswa.
     * Rumus: (jumlah hadir / total hari) × 100
     */
    public function getKehadiranPresentaseAttribute(): string
    {
        if (!$this->siswa_id) return '0';

        $total  = Kehadiran::where('siswa_id', $this->siswa_id)->count();
        $hadir  = Kehadiran::where('siswa_id', $this->siswa_id)
                            ->where('status', 'Hadir')
                            ->count();

        if ($total === 0) return '0';

        return number_format(($hadir / $total) * 100, 1);
    }

    /**
     * Rata-rata nilai akademik semester ini.
     */
    public function getRataRataNilaiAttribute(): string
    {
        if (!$this->siswa_id) return '0';

        $avg = Nilai::where('siswa_id', $this->siswa_id)
                    ->where('semester', $this->semester)
                    ->where('tahun_ajaran', $this->tahun_ajaran)
                    ->avg('nilai_angka');

        return $avg ? number_format($avg, 1) : '0';
    }
}