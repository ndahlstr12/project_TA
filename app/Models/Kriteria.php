<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

/**
 * Model Kriteria — Komponen bobot dalam metode SAW (Simple Additive Weighting)
 *
 * Setiap kriteria merepresentasikan satu dimensi penilaian siswa:
 *   - kode  : identifier kriteria (C1, C2, C3, dst.)
 *   - nama  : nama kriteria (mis. "Nilai Akademik")
 *   - bobot : bobot kepentingan dalam persen (total semua kriteria = 100%)
 *   - jenis : 'benefit' (semakin besar semakin baik)
 *             'cost'    (semakin kecil semakin baik, mis. jumlah alpa)
 *
 * Rumus normalisasi SAW:
 *   Benefit → Rij = Xij / max(Xj)
 *   Cost    → Rij = min(Xj) / Xij
 */
#[Fillable(['kode', 'nama', 'bobot', 'jenis'])]
class Kriteria extends Model
{
    //
}