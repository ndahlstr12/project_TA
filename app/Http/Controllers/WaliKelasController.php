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
            'total_siswa' => 0,
            'hadir_today' => 0,
            'raport_selesai' => 0,
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

            // Progres Raport (Wali Kelas)
            $stats['raport_selesai'] = Raport::whereIn('siswa_id', $siswas->pluck('id'))
                ->where('semester', $semester)
                ->where('tahun_ajaran', $tahunAjaran)
                ->where('status', 'selesai')
                ->count();

            // Siswa perlu perhatian (Alpa > 3 dalam semester ini)
            $stats['perlu_perhatian'] = Siswa::where('kelas_id', $kelas->id)
                ->whereHas('raports', function($q) use ($semester, $tahunAjaran) {
                    $q->where('semester', $semester)
                      ->where('tahun_ajaran', $tahunAjaran)
                      ->where('alpa', '>', 3);
                })
                ->get();

            // 2. Monitoring Progres Guru Mapel Lain
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
                        'mapel' => $first->mapel->nama_mapel ?? 'N/A',
                        'guru' => $first->guru->nama ?? 'N/A',
                        'progres' => $totalSiswa > 0 ? round(($sudahDinilai / $totalSiswa) * 100) : 0,
                        'avg_nilai' => \App\Models\Nilai::where('mapel_id', $first->mapel_id)
                            ->whereIn('siswa_id', $kelas->siswas->pluck('id'))
                            ->where('semester', $semester)
                            ->where('tahun_ajaran', $tahunAjaran)
                            ->avg('nilai_angka') ?? 0,
                        'count' => $sudahDinilai,
                        'total' => $totalSiswa
                    ];
                });
        }

        // 3. Ambil jadwal mengajar pribadi
        $jadwals = Jadwal::with(['mapel', 'kelas'])
            ->where('guru_id', $guru->id)
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->orderBy('jam_mulai')
            ->get();

        return view('walikelas.dashboard', compact('kelas', 'siswas', 'jadwals', 'monitoringMapel', 'stats', 'semester', 'tahunAjaran'));
    }

    public function ranking()
    {
        $user = Auth::user();
        $guru = $user->guru;

        if (!$guru) {
            return redirect()->back()->with('error', 'Data guru tidak ditemukan.');
        }

        $kelas = Kelas::where('wali_id', $guru->id)->first();
        $rankings = [];
        $matrix = [];
        $normalized = [];
        $kriterias = Kriteria::all();

        if ($kelas) {
            $rankings = SpkRanking::with('siswa')
                ->whereIn('siswa_id', $kelas->siswas->pluck('id'))
                ->orderBy('ranking', 'asc')
                ->get();

            // RE-CALCULATE for Transparency (Step-by-step)
            $siswas = Siswa::where('kelas_id', $kelas->id)->get();
            $tahunAjaran = Setting::get('tahun_ajaran', '2025/2026');
            $semester = Setting::get('semester', 'Ganjil');

            foreach ($siswas as $siswa) {
                // C1: Nilai Akademik
                $avgNilai = Nilai::where('siswa_id', $siswa->id)
                    ->where('tahun_ajaran', $tahunAjaran)
                    ->where('semester', $semester)
                    ->avg('nilai_angka') ?? 0;

                // C2: Kehadiran
                $totalHari = Kehadiran::where('siswa_id', $siswa->id)->count();
                $hadir = Kehadiran::where('siswa_id', $siswa->id)->where('status', 'Hadir')->count();
                $persenHadir = $totalHari > 0 ? ($hadir / $totalHari) * 100 : 0;

                // C3: Perilaku
                $poinPerilaku = JurnalPerilaku::where('siswa_id', $siswa->id)->sum('poin');

                $matrix[$siswa->id] = [
                    'nama' => $siswa->nama,
                    'C1' => (float)$avgNilai,
                    'C2' => (float)$persenHadir,
                    'C3' => (float)$poinPerilaku,
                ];
            }

            // Normalisasi
            foreach ($kriterias as $k) {
                $values = array_column($matrix, $k->kode);
                if (empty($values)) continue;
                
                $max = max($values);
                $min = min($values);

                foreach ($siswas as $siswa) {
                    $val = $matrix[$siswa->id][$k->kode];
                    if ($k->jenis == 'benefit') {
                        $normalized[$siswa->id][$k->kode] = $max > 0 ? ($val / $max) : 0;
                    } else {
                        $normalized[$siswa->id][$k->kode] = $val > 0 ? ($min / $val) : 0;
                    }
                }
            }
        }

        return view('walikelas.ranking.index', compact('rankings', 'kelas', 'matrix', 'normalized', 'kriterias'));
    }

    public function generateRanking()
    {
        $user = Auth::user();
        $guru = $user->guru;
        $kelas = Kelas::where('wali_id', $guru->id)->first();

        if (!$kelas) {
            return redirect()->back()->with('error', 'Anda tidak memiliki kelas wali.');
        }

        $kriterias = Kriteria::all();
        if ($kriterias->isEmpty()) {
            return redirect()->back()->with('error', 'Kriteria SPK belum diatur oleh Admin.');
        }

        $siswas = Siswa::where('kelas_id', $kelas->id)->get();
        if ($siswas->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada siswa di kelas ini.');
        }

        $tahunAjaran = Setting::get('tahun_ajaran', '2025/2026');
        $semester = Setting::get('semester', 'Ganjil');

        // VALIDASI: Cek ketersediaan data Nilai dan Kehadiran
        $siswaIds = $siswas->pluck('id');
        $hasNilai = Nilai::whereIn('siswa_id', $siswaIds)
            ->where('tahun_ajaran', $tahunAjaran)
            ->where('semester', $semester)
            ->exists();
        
        $hasKehadiran = Kehadiran::whereIn('siswa_id', $siswaIds)->exists();

        if (!$hasNilai && !$hasKehadiran) {
            return redirect()->back()->with('error', 'Gagal generate ranking: Data Nilai dan Kehadiran untuk kelas ini masih kosong. Silakan isi data absensi atau nilai terlebih dahulu.');
        }

        // 1. Matriks Keputusan (X)
        $matrix = [];
        foreach ($siswas as $siswa) {
            // C1: Nilai Akademik
            $avgNilai = Nilai::where('siswa_id', $siswa->id)
                ->where('tahun_ajaran', $tahunAjaran)
                ->where('semester', $semester)
                ->avg('nilai_angka') ?? 0;

            // C2: Kehadiran
            $totalHari = Kehadiran::where('siswa_id', $siswa->id)->count();
            $hadir = Kehadiran::where('siswa_id', $siswa->id)->where('status', 'Hadir')->count();
            $persenHadir = $totalHari > 0 ? ($hadir / $totalHari) * 100 : 0;

            // C3: Perilaku
            $poinPerilaku = JurnalPerilaku::where('siswa_id', $siswa->id)->sum('poin');

            $matrix[$siswa->id] = [
                'C1' => (float)$avgNilai,
                'C2' => (float)$persenHadir,
                'C3' => (float)$poinPerilaku,
            ];
        }

        // 2. Normalisasi (R)
        $normalized = [];
        foreach ($kriterias as $k) {
            $values = array_column($matrix, $k->kode);
            if (empty($values)) continue;
            
            $max = max($values);
            $min = min($values);

            foreach ($siswas as $siswa) {
                $val = $matrix[$siswa->id][$k->kode];
                if ($k->jenis == 'benefit') {
                    $normalized[$siswa->id][$k->kode] = $max > 0 ? ($val / $max) : 0;
                } else {
                    $normalized[$siswa->id][$k->kode] = $val > 0 ? ($min / $val) : 0;
                }
            }
        }

        // 3. Perhitungan Skor Akhir (V)
        $scores = [];
        foreach ($siswas as $siswa) {
            $score = 0;
            foreach ($kriterias as $k) {
                if (isset($normalized[$siswa->id][$k->kode])) {
                    $score += $normalized[$siswa->id][$k->kode] * ($k->bobot / 100);
                }
            }
            $scores[$siswa->id] = $score;
        }

        // 4. Sorting & Simpan
        arsort($scores);
        
        $rank = 1;
        foreach ($scores as $siswaId => $finalScore) {
            SpkRanking::updateOrCreate(
                ['siswa_id' => $siswaId, 'tahun_ajaran' => $tahunAjaran],
                ['skor_spk' => $finalScore, 'ranking' => $rank++]
            );
        }

        return redirect()->back()->with('success', 'Ranking SPK berhasil diperbarui.');
    }
}
