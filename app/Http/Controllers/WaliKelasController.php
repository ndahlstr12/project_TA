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

        // 1. Ambil kelas yang diwali
        $kelas = Kelas::where('wali_id', $guru->id)->first();
        
        $siswas = [];
        $monitoringMapel = [];
        if ($kelas) {
            $siswas = Siswa::where('kelas_id', $kelas->id)->get();

            // 2. Monitoring Progres Guru Mapel Lain untuk Kelas ini
            $monitoringMapel = Jadwal::with(['mapel', 'guru'])
                ->where('kelas_id', $kelas->id)
                ->get()
                ->groupBy('mapel_id')
                ->map(function ($items) use ($kelas) {
                    $first = $items->first();
                    $totalSiswa = $kelas->siswas->count();
                    
                    $sudahDinilai = \App\Models\Nilai::where('mapel', $first->mapel->nama_mapel ?? '')
                        ->whereIn('siswa_id', $kelas->siswas->pluck('id'))
                        ->distinct('siswa_id')
                        ->count();

                    return [
                        'mapel' => $first->mapel->nama_mapel ?? 'N/A',
                        'guru' => $first->guru->nama ?? 'N/A',
                        'progres' => $totalSiswa > 0 ? round(($sudahDinilai / $totalSiswa) * 100) : 0,
                        'count' => $sudahDinilai,
                        'total' => $totalSiswa
                    ];
                });
        }

        // 3. Ambil jadwal mengajar pribadi (Tugas Mandiri)
        $jadwals = Jadwal::with(['mapel', 'kelas'])
            ->where('guru_id', $guru->id)
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->orderBy('jam_mulai')
            ->get();

        return view('walikelas.dashboard', compact('kelas', 'siswas', 'jadwals', 'monitoringMapel'));
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
        if ($kelas) {
            $rankings = SpkRanking::with('siswa')
                ->whereIn('siswa_id', $kelas->siswas->pluck('id'))
                ->orderBy('ranking', 'asc')
                ->get();
        }

        return view('walikelas.ranking.index', compact('rankings', 'kelas'));
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
