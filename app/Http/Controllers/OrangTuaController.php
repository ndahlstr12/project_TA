<?php

namespace App\Http\Controllers;

use App\Models\Kehadiran;
use App\Models\Nilai;
use App\Models\JurnalPerilaku;
use App\Models\Raport;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrangTuaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $siswa = $user->siswa;

        if (!$siswa) {
            return redirect('/login')->with('error', 'Data anak tidak ditemukan.');
        }

        // Ambil notifikasi keterlambatan
        $notifications = Notification::where('penerima', 'Ortu')
            ->where('message', 'like', '%' . $siswa->nama . '%')
            ->latest()
            ->take(5)
            ->get();

        // Ringkasan nilai terbaru
        $latestGrades = Nilai::where('siswa_id', $siswa->id)
            ->latest()
            ->take(5)
            ->get();

        // Kehadiran hari ini/terbaru
        $latestAttendance = Kehadiran::where('siswa_id', $siswa->id)
            ->latest()
            ->take(5)
            ->get();

        return view('parent.dashboard', compact('siswa', 'notifications', 'latestGrades', 'latestAttendance'));
    }

    public function nilai()
    {
        $siswa = Auth::user()->siswa;
        $nilais = Nilai::where('siswa_id', $siswa->id)
            ->orderBy('semester', 'desc')
            ->get();
        return view('parent.nilai', compact('siswa', 'nilais'));
    }

    public function jurnal()
    {
        $siswa = Auth::user()->siswa;
        $jurnals = JurnalPerilaku::where('siswa_id', $siswa->id)
            ->latest()
            ->get();
        return view('parent.jurnal', compact('siswa', 'jurnals'));
    }

    public function raport()
    {
        $siswa = Auth::user()->siswa;
        $raports = Raport::where('siswa_id', $siswa->id)->get();
        return view('parent.raport', compact('siswa', 'raports'));
    }
}
