<?php

namespace App\Http\Controllers;

use App\Models\CbtHasil;
use App\Models\CbtSoal;
use App\Models\CbtUjian;
use App\Models\Jadwal;
use App\Models\JurnalPerilaku;
use App\Models\Nilai;
use App\Models\Raport;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SiswaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $siswa = $user->siswa;

        if (!$siswa) {
            // Jika user adalah siswa tapi tidak ada data di tabel siswas (biasanya tidak terjadi)
            return redirect()->route('login')->withErrors(['error' => 'Data siswa tidak ditemukan.']);
        }

        // Summary data
        $latestGrades = Nilai::where('siswa_id', $siswa->id)->latest()->take(5)->get();
        
        // Kehadiran percentage
        $totalHadir = \App\Models\Kehadiran::where('siswa_id', $siswa->id)->where('status', 'Hadir')->count();
        $totalPertemuan = \App\Models\Kehadiran::where('siswa_id', $siswa->id)->count();
        $persentaseHadir = $totalPertemuan > 0 ? round(($totalHadir / $totalPertemuan) * 100) : 100;

        // Jadwal hari ini
        $hariMap = [
            0 => 'Minggu', 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu'
        ];
        $hariIni = $hariMap[now()->dayOfWeek];
        $jadwal = Jadwal::with(['mapel', 'guru'])
            ->where('kelas_id', $siswa->kelas_id)
            ->where('hari', $hariIni)
            ->orderBy('jam_mulai')
            ->get();

        // CBT Aktif
        $cbtAktif = CbtUjian::where('kelas', $siswa->kelas->nama_kelas ?? '')
            ->where('status', true)
            ->get();

        return view('siswa.dashboard', compact('siswa', 'latestGrades', 'jadwal', 'cbtAktif', 'persentaseHadir'));
    }

    public function cbtIndex()
    {
        $siswa = Auth::user()->siswa;
        $ujians = CbtUjian::where('kelas', $siswa->kelas->nama_kelas ?? '')
            ->where('status', true)
            ->get();
        
        $hasils = CbtHasil::where('siswa_id', $siswa->id)->get()->keyBy('cbt_ujian_id');

        return view('siswa.cbt.index', compact('ujians', 'hasils'));
    }

    public function cbtShow($id)
    {
        $ujian = CbtUjian::findOrFail($id);
        $siswa = Auth::user()->siswa;

        // Cek apakah sudah pernah mengerjakan
        $hasil = CbtHasil::where('cbt_ujian_id', $id)->where('siswa_id', $siswa->id)->first();
        if ($hasil) {
            return redirect()->route('siswa.cbt.result', $id);
        }

        return view('siswa.cbt.show', compact('ujian'));
    }

    public function cbtTest($id)
    {
        $ujian = CbtUjian::findOrFail($id);
        $siswa = Auth::user()->siswa;

        // Cek apakah sudah pernah mengerjakan
        $hasil = CbtHasil::where('cbt_ujian_id', $id)->where('siswa_id', $siswa->id)->first();
        if ($hasil) {
            return redirect()->route('siswa.cbt.result', $id);
        }

        // Ambil soal berdasarkan mapel dan kelas ujian
        $query = CbtSoal::where('mapel', $ujian->mapel)
            ->where('kelas', $ujian->kelas);
        
        if ($ujian->acak_soal) {
            $query->inRandomOrder();
        }

        $soals = $query->take($ujian->jumlah_soal)->get();

        return view('siswa.cbt.test', compact('ujian', 'soals'));
    }

    public function cbtSubmit(Request $request, $id)
    {
        $ujian = CbtUjian::findOrFail($id);
        $siswa = Auth::user()->siswa;

        $answers = $request->input('answers', []);
        $jumlahBenar = 0;
        
        // Ambil kunci jawaban dari DB untuk validasi skor
        $soalIds = array_keys($answers);
        $soals = CbtSoal::whereIn('id', $soalIds)->get()->keyBy('id');

        foreach ($answers as $soalId => $jawaban) {
            if (isset($soals[$soalId]) && $soals[$soalId]->jawaban_benar === $jawaban) {
                $jumlahBenar++;
            }
        }

        $totalSoalDitampilkan = count($answers);
        $skor = $totalSoalDitampilkan > 0 ? ($jumlahBenar / $totalSoalDitampilkan) * 100 : 0;
        $jumlahSalah = $totalSoalDitampilkan - $jumlahBenar;
        
        // Ambil KKM dari Mapel
        $mapel = \App\Models\Mapel::where('nama_mapel', $ujian->mapel)->first();
        $kkm = $mapel->kkm ?? 75; // Default 75 jika mapel tidak ditemukan

        $status = $skor >= $kkm ? 'Selesai' : 'Remedial';

        CbtHasil::updateOrCreate(
            ['cbt_ujian_id' => $id, 'siswa_id' => $siswa->id],
            [
                'skor' => $skor,
                'jumlah_benar' => $jumlahBenar,
                'jumlah_salah' => $jumlahSalah,
                'status' => $status,
                'rekomendasi_ai' => $status === 'Remedial' ? 'Siswa disarankan untuk mempelajari kembali materi ' . $ujian->mapel . ' karena belum mencapai KKM (' . $kkm . ').' : 'Siswa telah menguasai materi dengan baik.'
            ]
        );

        return redirect()->route('siswa.cbt.result', $id)->with('success', 'Ujian telah selesai.');
    }

    public function cbtResult($id)
    {
        $ujian = CbtUjian::findOrFail($id);
        $siswa = Auth::user()->siswa;
        $hasil = CbtHasil::where('cbt_ujian_id', $id)->where('siswa_id', $siswa->id)->firstOrFail();

        return view('siswa.cbt.result', compact('ujian', 'hasil'));
    }

    public function raportIndex()
    {
        $siswa = Auth::user()->siswa;
        $raports = Raport::where('siswa_id', $siswa->id)->get();
        return view('siswa.raport.index', compact('raports'));
    }

    public function jurnalIndex()
    {
        $siswa = Auth::user()->siswa;
        $jurnals = JurnalPerilaku::where('siswa_id', $siswa->id)->latest()->get();
        return view('siswa.jurnal.index', compact('jurnals'));
    }

    public function jurnalStore(Request $request)
    {
        $request->validate([
            'catatan' => 'required',
            'tanggal' => 'required|date',
        ]);

        $siswa = Auth::user()->siswa;
        $waliKelasId = $siswa->kelas->wali_id ?? null;

        JurnalPerilaku::create([
            'siswa_id' => $siswa->id,
            'guru_id' => $waliKelasId,
            'catatan' => $request->catatan,
            'poin' => 0,
            'tipe' => 'Positif',
            'tanggal' => $request->tanggal,
        ]);

        return back()->with('success', 'Jurnal berhasil ditambahkan.');
    }
}
