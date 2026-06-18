<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Siswa;
use App\Models\Setting;
use App\Models\Nilai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuruController extends Controller
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

        // 1. Ambil jadwal mengajar guru ini
        $schedules = Jadwal::with(['mapel', 'kelas'])->where('guru_id', $guru->id)->get();
        
        $kelasIds = $schedules->pluck('kelas_id')->unique();
        $studentsInClasses = Siswa::with('kelas')->whereIn('kelas_id', $kelasIds)->get();
        
        $stats = [
            'total_siswa' => $studentsInClasses->count(),
            'jadwal_today' => $schedules->where('hari', \Carbon\Carbon::now()->translatedFormat('l'))->count(),
            'total_kelas' => $kelasIds->count(),
        ];

        // 2. Progres Penginputan Nilai per Mapel & Kelas
        $progresNilai = $schedules->map(function ($j) use ($semester, $tahunAjaran) {
            $totalSiswa = Siswa::where('kelas_id', $j->kelas_id)->count();
            $sudahDinilai = \App\Models\Nilai::where('mapel_id', $j->mapel_id)
                ->whereIn('siswa_id', Siswa::where('kelas_id', $j->kelas_id)->pluck('id'))
                ->where('semester', $semester)
                ->where('tahun_ajaran', $tahunAjaran)
                ->distinct('siswa_id')
                ->count();

            return [
                'jadwal_id' => $j->id,
                'mapel' => $j->mapel->nama_mapel ?? 'N/A',
                'kelas' => $j->kelas->nama_kelas ?? 'N/A',
                'kelas_id' => $j->kelas_id,
                'progres' => $totalSiswa > 0 ? round(($sudahDinilai / $totalSiswa) * 100) : 0,
                'count' => $sudahDinilai,
                'total' => $totalSiswa,
                'hari' => $j->hari,
                'jam' => $j->jam_mulai . ' - ' . $j->jam_selesai
            ];
        });

        // 3. Jadwal Hari Ini
        $jadwalHariIni = $schedules->where('hari', \Carbon\Carbon::now()->translatedFormat('l'));

        return view('guru.dashboard', compact(
            'schedules', 
            'stats', 
            'progresNilai', 
            'jadwalHariIni',
            'semester',
            'tahunAjaran'
        ));
    }
}
