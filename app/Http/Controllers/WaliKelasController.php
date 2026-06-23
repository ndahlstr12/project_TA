<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kelas;
use App\Models\Jadwal;
use App\Models\Siswa;
use App\Models\SpkRanking;
use App\Models\Kriteria;
use App\Models\Nilai;
use App\Models\Kehadiran;
use App\Models\JurnalPerilaku;
use App\Models\Setting;
use App\Models\Raport;

/**
 * WaliKelasController
 *
 * Mengelola fitur Wali Kelas, termasuk:
 * - Dashboard monitoring kelas
 * - Sistem Pendukung Keputusan (SPK) menggunakan metode SAW
 *   (Simple Additive Weighting) untuk perankingan siswa
 *
 * Metode SAW dipilih karena sederhana, transparan, dan mudah
 * dijelaskan kepada stakeholder non-teknis (guru, orang tua).
 *
 * Referensi: Fishburn, P.C. (1967). Additive Utilities with
 * Incomplete Product Set: Applications to Priorities and Assignments.
 */
class WaliKelasController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $guru = $user->guru;

        if (!$guru) {
            return redirect()->route('login')->with('error', 'Data guru tidak ditemukan.');
        }

        $semester = Setting::get('semester', 'Ganjil');
        $tahunAjaran = Setting::get('tahun_ajaran', '2025/2026');

        // 1. Ambil kelas yang diwali
        $kelas = Kelas::where('wali_id', $guru->id)->first();
        
        $siswas = [];
        $monitoringMapel = [];
        $stats = [
            'total_siswa'     => 0,
            'hadir_today'     => 0,
            'raport_selesai'  => 0,
            'perlu_perhatian' => []
        ];

        if ($kelas) {
            $siswas = Siswa::where('kelas_id', $kelas->id)->get();
            $stats['total_siswa'] = $siswas->count();

            // Kehadiran hari ini
            $today = date('Y-m-d');
            $stats['hadir_today'] = Kehadiran::whereIn('siswa_id', $siswas->pluck('id'))
                ->where('tanggal', $today)
                ->where('status', 'Hadir')
                ->count();

            // Progres Raport
            $stats['raport_selesai'] = Raport::whereIn('siswa_id', $siswas->pluck('id'))
                ->where('semester', $semester)
                ->where('tahun_ajaran', $tahunAjaran)
                ->where('status', 'selesai')
                ->count();

            // Siswa perlu perhatian: Alpa > 3 dalam semester ini
            // Digunakan sebagai dasar intervensi awal sebelum SPK dijalankan
            $stats['perlu_perhatian'] = Siswa::where('kelas_id', $kelas->id)
                ->whereHas('raports', function ($q) use ($semester, $tahunAjaran) {
                    $q->where('semester', $semester)
                      ->where('tahun_ajaran', $tahunAjaran)
                      ->where('alpa', '>', 3);
                })
                ->get();

            // 2. Monitoring Progres Guru Mapel
            $monitoringMapel = Jadwal::with(['mapel', 'guru'])
                ->where('kelas_id', $kelas->id)
                ->get()
                ->groupBy('mapel_id')
                ->map(function ($items) use ($kelas, $semester, $tahunAjaran) {
                    $first = $items->first();
                    $totalSiswa = $kelas->siswas->count();

                    $sudahDinilai = \App\Models\Nilai::where('mapel_id', $first->mapel_id)
                        ->whereIn('siswa_id', $kelas->siswas->pluck('id'))
                        ->where('semester', $semester)
                        ->where('tahun_ajaran', $tahunAjaran)
                        ->distinct('siswa_id')
                        ->count();

                    return [
                        'mapel'     => $first->mapel->nama_mapel ?? 'N/A',
                        'guru'      => $first->guru->nama ?? 'N/A',
                        'progres'   => $totalSiswa > 0 ? round(($sudahDinilai / $totalSiswa) * 100) : 0,
                        'avg_nilai' => \App\Models\Nilai::where('mapel_id', $first->mapel_id)
                            ->whereIn('siswa_id', $kelas->siswas->pluck('id'))
                            ->where('semester', $semester)
                            ->where('tahun_ajaran', $tahunAjaran)
                            ->avg('nilai_angka') ?? 0,
                        'count' => $sudahDinilai,
                        'total' => $totalSiswa,
                    ];
                });
        }

        // 3. Ambil jadwal mengajar pribadi wali kelas
        $jadwals = Jadwal::with(['mapel', 'kelas'])
            ->where('guru_id', $guru->id)
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->orderBy('jam_mulai')
            ->get();

        return view('walikelas.dashboard', compact(
            'kelas', 'siswas', 'jadwals', 'monitoringMapel', 'stats', 'semester', 'tahunAjaran'
        ));
    }

    // =========================================================================
    // MODUL SPK — METODE SAW (Simple Additive Weighting)
    //
    // Alur Metode SAW:
    //   1. Tentukan kriteria (Cj) dan bobot (Wj) — dikelola Admin
    //   2. Bangun Matriks Keputusan (X): nilai tiap siswa per kriteria
    //   3. Normalisasi Matriks (R):
    //        - Kriteria benefit : Rij = Xij / max(Xj)
    //        - Kriteria cost    : Rij = min(Xj) / Xij
    //   4. Hitung Skor Preferensi (V):
    //        Vi = Σ (Wj × Rij)
    //   5. Ranking berdasarkan Vi tertinggi → prioritas penanganan masalah
    //
    // Kriteria yang digunakan:
    //   C1 — Nilai Akademik (benefit)  : rata-rata nilai mata pelajaran
    //   C2 — Kehadiran     (benefit)   : persentase kehadiran siswa
    //   C3 — Poin Perilaku (benefit)   : akumulasi poin jurnal perilaku
    // =========================================================================

    /**
     * Menampilkan halaman ranking SPK beserta transparansi perhitungan
     * langkah demi langkah (matriks keputusan + matriks normalisasi).
     */
    public function ranking()
    {
        $user = Auth::user();
        $guru = $user->guru;

        if (!$guru) {
            return redirect()->back()->with('error', 'Data guru tidak ditemukan.');
        }

        $kelas    = Kelas::where('wali_id', $guru->id)->first();
        $rankings   = [];
        $matriksX   = []; // Matriks Keputusan  (X)  — nilai mentah tiap siswa
        $matriksR   = []; // Matriks Normalisasi (R)  — hasil normalisasi SAW
        $kriterias  = Kriteria::orderBy('kode')->get();

        if ($kelas) {
            // Ambil ranking tersimpan dari database
            $rankings = SpkRanking::with('siswa')
                ->whereIn('siswa_id', $kelas->siswas->pluck('id'))
                ->orderBy('ranking', 'asc')
                ->get();

            // Hitung ulang matriks untuk ditampilkan secara transparan di view
            $siswas      = Siswa::where('kelas_id', $kelas->id)->get();
            $tahunAjaran = Setting::get('tahun_ajaran', '2025/2026');
            $semester    = Setting::get('semester', 'Ganjil');

            // --- Langkah 2: Bangun Matriks Keputusan (X) ---
            foreach ($siswas as $siswa) {
                // C1: Rata-rata nilai akademik semester ini
                $avgNilai = Nilai::where('siswa_id', $siswa->id)
                    ->where('tahun_ajaran', $tahunAjaran)
                    ->where('semester', $semester)
                    ->avg('nilai_angka') ?? 0;

                // C2: Persentase kehadiran (hadir / total hari × 100)
                $totalHari    = Kehadiran::where('siswa_id', $siswa->id)->count();
                $jumlahHadir  = Kehadiran::where('siswa_id', $siswa->id)->where('status', 'Hadir')->count();
                $persenHadir  = $totalHari > 0 ? ($jumlahHadir / $totalHari) * 100 : 0;

                // C3: Akumulasi poin perilaku dari jurnal (positif menambah, negatif mengurangi)
                $poinPerilaku = JurnalPerilaku::where('siswa_id', $siswa->id)->sum('poin');

                $matriksX[$siswa->id] = [
                    'nama' => $siswa->nama,
                    'C1'   => (float) $avgNilai,
                    'C2'   => (float) $persenHadir,
                    'C3'   => (float) $poinPerilaku,
                ];
            }

            // --- Langkah 3: Normalisasi Matriks (R) ---
            // Benefit: Rij = Xij / max(Xj)
            // Cost   : Rij = min(Xj) / Xij
            foreach ($kriterias as $kriteria) {
                $nilaiKolom = array_column($matriksX, $kriteria->kode);
                if (empty($nilaiKolom)) continue;

                $maks = max($nilaiKolom);
                $min  = min($nilaiKolom);

                foreach ($siswas as $siswa) {
                    $xij = $matriksX[$siswa->id][$kriteria->kode];

                    if ($kriteria->jenis === 'benefit') {
                        // Semakin tinggi nilai, semakin baik
                        $matriksR[$siswa->id][$kriteria->kode] = $maks > 0 ? ($xij / $maks) : 0;
                    } else {
                        // Semakin rendah nilai (cost), semakin baik (misal: jumlah alpa)
                        $matriksR[$siswa->id][$kriteria->kode] = $xij > 0 ? ($min / $xij) : 0;
                    }
                }
            }
        }

        // Kirim matriksX dan matriksR ke view agar bisa ditampilkan step-by-step
        return view('walikelas.ranking.index', compact(
            'rankings', 'kelas', 'kriterias',
            'matriksX', 'matriksR'
        ));
    }

    /**
     * Menjalankan perhitungan SPK metode SAW dan menyimpan hasilnya.
     *
     * Hasil ranking digunakan untuk menentukan siswa mana yang
     * membutuhkan penanganan lebih lanjut (peringkat bawah = prioritas intervensi).
     */
    public function generateRanking()
    {
        $user  = Auth::user();
        $guru  = $user->guru;
        $kelas = Kelas::where('wali_id', $guru->id)->first();

        if (!$kelas) {
            return redirect()->back()->with('error', 'Anda tidak memiliki kelas wali.');
        }

        $kriterias = Kriteria::all();
        if ($kriterias->isEmpty()) {
            return redirect()->back()->with('error', 'Kriteria SPK belum diatur oleh Admin. Silakan tambahkan kriteria terlebih dahulu.');
        }

        $siswas = Siswa::where('kelas_id', $kelas->id)->get();
        if ($siswas->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada siswa di kelas ini.');
        }

        $tahunAjaran = Setting::get('tahun_ajaran', '2025/2026');
        $semester    = Setting::get('semester', 'Ganjil');

        // Validasi ketersediaan data sebelum menjalankan SAW
        $siswaIds    = $siswas->pluck('id');
        $adaNilai    = Nilai::whereIn('siswa_id', $siswaIds)
            ->where('tahun_ajaran', $tahunAjaran)
            ->where('semester', $semester)
            ->exists();
        $adaKehadiran = Kehadiran::whereIn('siswa_id', $siswaIds)->exists();

        if (!$adaNilai && !$adaKehadiran) {
            return redirect()->back()->with('error',
                'Gagal menjalankan SPK: Data nilai dan kehadiran masih kosong. ' .
                'Silakan lengkapi data terlebih dahulu.'
            );
        }

        // =====================================================================
        // LANGKAH 2: Bangun Matriks Keputusan (X)
        // Xij = nilai alternatif ke-i pada kriteria ke-j
        // =====================================================================
        $matriksX = [];
        foreach ($siswas as $siswa) {
            // C1 — Nilai Akademik (benefit): rata-rata semua mapel semester ini
            $avgNilai = Nilai::where('siswa_id', $siswa->id)
                ->where('tahun_ajaran', $tahunAjaran)
                ->where('semester', $semester)
                ->avg('nilai_angka') ?? 0;

            // C2 — Kehadiran (benefit): persentase hari hadir
            $totalHari   = Kehadiran::where('siswa_id', $siswa->id)->count();
            $jumlahHadir = Kehadiran::where('siswa_id', $siswa->id)->where('status', 'Hadir')->count();
            $persenHadir = $totalHari > 0 ? ($jumlahHadir / $totalHari) * 100 : 0;

            // C3 — Poin Perilaku (benefit): total poin jurnal perilaku
            $poinPerilaku = JurnalPerilaku::where('siswa_id', $siswa->id)->sum('poin');

            $matriksX[$siswa->id] = [
                'C1' => (float) $avgNilai,
                'C2' => (float) $persenHadir,
                'C3' => (float) $poinPerilaku,
            ];
        }

        // =====================================================================
        // LANGKAH 3: Normalisasi Matriks (R)
        // Benefit : Rij = Xij / max(Xj)   → nilai tertinggi = terbaik
        // Cost    : Rij = min(Xj) / Xij   → nilai terendah  = terbaik
        // =====================================================================
        $matriksR = [];
        foreach ($kriterias as $kriteria) {
            $nilaiKolom = array_column($matriksX, $kriteria->kode);
            if (empty($nilaiKolom)) continue;

            $maks = max($nilaiKolom);
            $min  = min($nilaiKolom);

            foreach ($siswas as $siswa) {
                $xij = $matriksX[$siswa->id][$kriteria->kode];

                if ($kriteria->jenis === 'benefit') {
                    $matriksR[$siswa->id][$kriteria->kode] = $maks > 0 ? ($xij / $maks) : 0;
                } else {
                    $matriksR[$siswa->id][$kriteria->kode] = $xij > 0 ? ($min / $xij) : 0;
                }
            }
        }

        // =====================================================================
        // LANGKAH 4: Hitung Skor Preferensi (V) — Vektor Keputusan SAW
        // Vi = Σ (Wj × Rij)
        // Wj = bobot kriteria ke-j (dalam persen, dibagi 100)
        // =====================================================================
        $skorV = [];
        foreach ($siswas as $siswa) {
            $vi = 0;
            foreach ($kriterias as $kriteria) {
                if (isset($matriksR[$siswa->id][$kriteria->kode])) {
                    // Bobot disimpan sebagai persentase (mis. 40), dibagi 100 → 0.40
                    $vi += $matriksR[$siswa->id][$kriteria->kode] * ($kriteria->bobot / 100);
                }
            }
            $skorV[$siswa->id] = $vi;
        }

        // =====================================================================
        // LANGKAH 5: Ranking — Urutkan berdasarkan Vi tertinggi
        // Siswa dengan Vi tertinggi = performa terbaik
        // Siswa dengan Vi terendah  = prioritas penanganan/intervensi
        // =====================================================================
        arsort($skorV); // Descending: Vi terbesar → ranking 1

        $ranking = 1;
        foreach ($skorV as $siswaId => $vi) {
            SpkRanking::updateOrCreate(
                ['siswa_id' => $siswaId, 'tahun_ajaran' => $tahunAjaran],
                ['skor_spk' => round($vi, 6), 'ranking' => $ranking++]
            );
        }

        return redirect()->back()->with('success',
            'Ranking SPK (metode SAW) berhasil diperbarui. ' .
            'Siswa dengan skor terendah membutuhkan perhatian lebih.'
        );
    }
}