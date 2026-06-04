<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Raport;
use App\Models\Siswa;
use App\Models\Nilai;
use App\Models\Kehadiran;
use App\Models\JurnalPerilaku;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class RaportController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $guru = $user->guru;

        \Illuminate\Support\Facades\Log::info('WaliKelas Raport Access', [
            'user_id' => $user->id,
            'role' => $user->role,
            'guru_id' => $user->guru_id,
            'guru_exists' => (bool)$guru
        ]);

        if (!$guru) {
            return redirect()->back()->with('error', 'Data guru tidak ditemukan untuk user ini (ID Guru: ' . ($user->guru_id ?? 'NULL') . ').');
        }

        // Ambil kelas yang diwali oleh guru ini
        $kelas = \App\Models\Kelas::where('wali_id', $guru->id)->first();

        \Illuminate\Support\Facades\Log::info('WaliKelas Class Check', [
            'guru_id' => $guru->id,
            'kelas_found' => (bool)$kelas,
            'kelas_id' => $kelas->id ?? null
        ]);

        if (!$kelas) {
            return redirect()->back()->with('error', 'Anda (Guru: ' . $guru->nama . ') belum ditugaskan sebagai wali kelas di kelas manapun.');
        }

        $siswas = Siswa::where('kelas_id', $kelas->id)->get();
        $semester = Setting::get('semester', 'Ganjil');
        $tahunAjaran = Setting::get('tahun_ajaran', '2025/2026');

        return view('walikelas.raport.index', compact('siswas', 'semester', 'tahunAjaran', 'kelas'));
    }

    public function show($id)
    {
        $siswa = Siswa::findOrFail($id);
        $semester = Setting::get('semester', 'Ganjil');
        $tahunAjaran = Setting::get('tahun_ajaran', '2025/2026');

        $nilais = Nilai::where('siswa_id', $id)
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahunAjaran)
            ->get();

        $kehadiran = Kehadiran::where('siswa_id', $id)
            ->whereBetween('tanggal', [$this->getSemesterStart(), $this->getSemesterEnd()])
            ->get();

        $jurnals = JurnalPerilaku::where('siswa_id', $id)
            ->whereBetween('tanggal', [$this->getSemesterStart(), $this->getSemesterEnd()])
            ->get();

        $raport = Raport::where('siswa_id', $id)
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahunAjaran)
            ->first();

        return view('walikelas.raport.show', compact('siswa', 'nilais', 'kehadiran', 'jurnals', 'raport', 'semester', 'tahunAjaran'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'catatan_wali' => 'nullable|string',
            'sakit' => 'integer|min:0',
            'izin' => 'integer|min:0',
            'alpa' => 'integer|min:0',
        ]);

        $semester = Setting::get('semester', 'Ganjil');
        $tahunAjaran = Setting::get('tahun_ajaran', '2025/2026');

        Raport::updateOrCreate(
            [
                'siswa_id' => $id,
                'semester' => $semester,
                'tahun_ajaran' => $tahunAjaran,
            ],
            [
                'wali_id' => Auth::user()->guru_id,
                'catatan_wali' => $request->catatan_wali,
                'sakit' => $request->sakit,
                'izin' => $request->izin,
                'alpa' => $request->alpa,
                'status' => 'selesai'
            ]
        );

        return redirect()->back()->with('success', 'Data raport berhasil diperbarui.');
    }

    public function generateAiSaran($id, \App\Services\AiRecommendationService $aiService)
    {
        $semester = Setting::get('semester', 'Ganjil');
        $tahunAjaran = Setting::get('tahun_ajaran', '2025/2026');

        $raport = Raport::where('siswa_id', $id)
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahunAjaran)
            ->first();

        if (!$raport) {
            return redirect()->back()->with('error', 'Silahkan simpan data raport terlebih dahulu sebelum menggunakan AI.');
        }

        $result = $aiService->generateRecommendation($raport);

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        } else {
            return redirect()->back()->with('error', $result['message']);
        }
    }

    public function sendEmail($id)
    {
        $siswa = Siswa::findOrFail($id);
        
        if (!$siswa->email_orang_tua) {
            return redirect()->back()->with('error', 'Email orang tua tidak ditemukan.');
        }

        // Logic to generate PDF first would go here
        // For now, we'll just send the mail with the view
        \Illuminate\Support\Facades\Mail::to($siswa->email_orang_tua)->send(new \App\Mail\RaportMail($siswa));

        return redirect()->back()->with('success', 'E-Raport berhasil dikirim ke email orang tua.');
    }

    public function exportPdf($id)
    {
        $siswa = Siswa::findOrFail($id);
        $semester = Setting::get('semester', 'Ganjil');
        $tahunAjaran = Setting::get('tahun_ajaran', '2025/2026');

        $nilais = Nilai::where('siswa_id', $id)
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahunAjaran)
            ->get();

        $raport = Raport::where('siswa_id', $id)
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahunAjaran)
            ->first();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('walikelas.raport.pdf', compact('siswa', 'nilais', 'raport', 'semester', 'tahunAjaran'));
        
        return $pdf->download('Raport_' . str_replace(' ', '_', $siswa->nama) . '.pdf');
    }

    private function getSemesterStart() { return date('Y') . '-01-01'; }
    private function getSemesterEnd() { return date('Y') . '-12-31'; }
}
