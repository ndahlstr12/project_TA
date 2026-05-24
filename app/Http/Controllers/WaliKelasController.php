<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kelas;
use App\Models\Jadwal;
use App\Models\Siswa;
use App\Models\SpkRanking;

class WaliKelasController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $guru = $user->guru;

        if (!$guru) {
            return redirect()->route('login')->with('error', 'Data guru tidak ditemukan.');
        }

        // Ambil kelas yang diwali
        $kelas = Kelas::where('wali_id', $guru->id)->first();
        
        $siswas = [];
        if ($kelas) {
            $siswas = Siswa::where('kelas_id', $kelas->id)->get();
        }

        // Ambil jadwal mengajar
        $jadwals = Jadwal::with(['mapel', 'kelas'])
            ->where('guru_id', $guru->id)
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->orderBy('jam_mulai')
            ->get();

        return view('walikelas.dashboard', compact('kelas', 'siswas', 'jadwals'));
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
}
