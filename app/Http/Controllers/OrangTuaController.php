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

        // Ringkasan hasil ujian CBT terbaru
        $latestCbt = \App\Models\CbtHasil::with('ujian')
            ->where('siswa_id', $siswa->id)
            ->latest()
            ->take(5)
            ->get();

        // Kehadiran hari ini/terbaru
        $latestAttendance = Kehadiran::where('siswa_id', $siswa->id)
            ->latest()
            ->take(5)
            ->get();

        // Jurnal perilaku terbaru untuk ringkasan dasbor
        $latestBehaviors = JurnalPerilaku::where('siswa_id', $siswa->id)
            ->latest()
            ->take(3)
            ->get();

        return view('parent.dashboard', compact('siswa', 'notifications', 'latestCbt', 'latestAttendance', 'latestBehaviors'));
    }

    public function nilai()
    {
        $siswa = Auth::user()->siswa;
        $nilais = Nilai::with('mapel')
            ->where('siswa_id', $siswa->id)
            ->orderBy('semester', 'desc')
            ->get();

        $cbtHasils = \App\Models\CbtHasil::with('ujian')
            ->where('siswa_id', $siswa->id)
            ->latest()
            ->get();

        return view('parent.nilai', compact('siswa', 'nilais', 'cbtHasils'));
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

    public function raportShow($id)
    {
        $raport = Raport::findOrFail($id);
        $siswa = Auth::user()->siswa;

        // Keamanan: Pastikan raport milik anak ortu yang login
        if ($raport->siswa_id !== $siswa->id) {
            abort(403, 'Akses ditolak.');
        }

        $semester = $raport->semester;
        $tahunAjaran = $raport->tahun_ajaran;

        $nilais = Nilai::with('mapel')
            ->where('siswa_id', $siswa->id)
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahunAjaran)
            ->get();

        $ekstrakurikulers = \App\Models\Ekstrakurikuler::where('siswa_id', $siswa->id)
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahunAjaran)
            ->get();

        $nilaiUmum = $nilais->filter(fn($n) => $n->mapel && $n->mapel->kategori == 'Umum');
        $nilaiKejuruan = $nilais->filter(fn($n) => $n->mapel && $n->mapel->kategori == 'Kejuruan');

        return view('shared.raport_digital', compact('siswa', 'raport', 'nilais', 'nilaiUmum', 'nilaiKejuruan', 'ekstrakurikulers', 'semester', 'tahunAjaran'));
    }

    public function exportPdf($id)
    {
        $raport = Raport::with('wali')->findOrFail($id);
        $siswa = Siswa::with('kelas')->findOrFail(Auth::user()->siswa_id);

        if ($raport->siswa_id !== $siswa->id) {
            abort(403, 'Akses ditolak.');
        }

        $semester = $raport->semester;
        $tahunAjaran = $raport->tahun_ajaran;

        $nilais = Nilai::with('mapel')
            ->where('siswa_id', $siswa->id)
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahunAjaran)
            ->get();

        $ekstrakurikulers = \App\Models\Ekstrakurikuler::where('siswa_id', $siswa->id)
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahunAjaran)
            ->get();

        $nilaiUmum = $nilais->filter(fn($n) => $n->mapel && $n->mapel->kategori == 'Umum');
        $nilaiKejuruan = $nilais->filter(fn($n) => $n->mapel && $n->mapel->kategori == 'Kejuruan');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('walikelas.raport.pdf', compact(
            'siswa', 
            'nilais', 
            'nilaiUmum', 
            'nilaiKejuruan', 
            'raport', 
            'ekstrakurikulers',
            'semester', 
            'tahunAjaran'
        ));
        
        return $pdf->download('Raport_' . str_replace(' ', '_', $siswa->nama) . '_' . $semester . '.pdf');
    }
}
