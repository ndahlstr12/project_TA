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
        $this->checkStudentAccess($id);
        $siswa = Siswa::with('kelas')->findOrFail($id);
        $semester = Setting::get('semester', 'Ganjil');
        $tahunAjaran = Setting::get('tahun_ajaran', '2025/2026');

        $nilais = Nilai::with('mapel')
            ->where('siswa_id', $id)
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

        $ekstrakurikulers = \App\Models\Ekstrakurikuler::where('siswa_id', $id)
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahunAjaran)
            ->get();

        // Kelompokkan nilai berdasarkan kategori mapel
        $nilaiUmum = $nilais->filter(function($n) {
            return $n->mapel && $n->mapel->kategori == 'Umum';
        });

        $nilaiKejuruan = $nilais->filter(function($n) {
            return $n->mapel && $n->mapel->kategori == 'Kejuruan';
        });

        return view('walikelas.raport.show', compact(
            'siswa', 
            'nilais', 
            'nilaiUmum', 
            'nilaiKejuruan', 
            'kehadiran', 
            'jurnals', 
            'raport', 
            'ekstrakurikulers',
            'semester', 
            'tahunAjaran'
        ));
    }

    public function update(Request $request, $id)
    {
        $this->checkStudentAccess($id);
        $request->validate([
            'catatan_wali' => 'nullable|string',
            'kokurikuler' => 'nullable|string',
            'sakit' => 'integer|min:0',
            'izin' => 'integer|min:0',
            'alpa' => 'integer|min:0',
            'ekstra' => 'nullable|array',
        ]);

        $semester = Setting::get('semester', 'Ganjil');
        $tahunAjaran = Setting::get('tahun_ajaran', '2025/2026');

        // Update Raport (Catatan & Kehadiran & Kokurikuler)
        Raport::updateOrCreate(
            [
                'siswa_id' => $id,
                'semester' => $semester,
                'tahun_ajaran' => $tahunAjaran,
            ],
            [
                'wali_id' => Auth::user()->guru_id,
                'catatan_wali' => $request->catatan_wali,
                'kokurikuler' => $request->kokurikuler,
                'sakit' => $request->sakit,
                'izin' => $request->izin,
                'alpa' => $request->alpa,
                'status' => 'selesai'
            ]
        );

        // Update Ekstrakurikuler
        \App\Models\Ekstrakurikuler::where('siswa_id', $id)
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahunAjaran)
            ->delete();

        if ($request->has('ekstra')) {
            foreach ($request->ekstra as $ekstraData) {
                if (!empty($ekstraData['nama'])) {
                    \App\Models\Ekstrakurikuler::create([
                        'siswa_id' => $id,
                        'nama_ekstra' => $ekstraData['nama'],
                        'keterangan' => $ekstraData['keterangan'],
                        'semester' => $semester,
                        'tahun_ajaran' => $tahunAjaran
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Data raport berhasil diperbarui.');
    }

    public function getAiWaliCatatan($id, \App\Services\AiRecommendationService $aiService)
    {
        try {
            $this->checkStudentAccess($id);
            $siswa = Siswa::findOrFail($id);
            $semester = Setting::get('semester', 'Ganjil');
            $tahunAjaran = Setting::get('tahun_ajaran', '2025/2026');

            // Ambil data untuk konteks AI
            $nilais = Nilai::with('mapel')
                ->where('siswa_id', $id)
                ->where('semester', $semester)
                ->where('tahun_ajaran', $tahunAjaran)
                ->get();

            $kehadiran = Kehadiran::where('siswa_id', $id)
                ->whereBetween('tanggal', [$this->getSemesterStart(), $this->getSemesterEnd()])
                ->get();

            $jurnals = JurnalPerilaku::where('siswa_id', $id)
                ->whereBetween('tanggal', [$this->getSemesterStart(), $this->getSemesterEnd()])
                ->get();

            $ekskuls = \App\Models\Ekstrakurikuler::where('siswa_id', $id)
                ->where('semester', $semester)
                ->where('tahun_ajaran', $tahunAjaran)
                ->get();

            $result = $aiService->generateWaliCatatan($siswa, $nilais, $kehadiran, $jurnals, $ekskuls);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'catatan' => $result['data']
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $result['message']
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function generateAiSaran($id, \App\Services\AiRecommendationService $aiService)
    {
        $this->checkStudentAccess($id);
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
        $this->checkStudentAccess($id);
        $siswa = Siswa::with(['kelas', 'raports'])->findOrFail($id);
        
        if (!$siswa->email_orang_tua) {
            return redirect()->back()->with('error', 'Email orang tua tidak ditemukan. Silakan lengkapi di pengaturan profil.');
        }

        try {
            $semester = Setting::get('semester', 'Ganjil');
            $tahunAjaran = Setting::get('tahun_ajaran', '2025/2026');

            $nilais = Nilai::with('mapel')
                ->where('siswa_id', $id)
                ->where('semester', $semester)
                ->where('tahun_ajaran', $tahunAjaran)
                ->get();

            $raport = Raport::where('siswa_id', $id)
                ->where('semester', $semester)
                ->where('tahun_ajaran', $tahunAjaran)
                ->first();

            if (!$raport) {
                return redirect()->back()->with('error', 'Data raport belum lengkap. Harap simpan data kehadiran dan catatan terlebih dahulu.');
            }

            $ekstrakurikulers = \App\Models\Ekstrakurikuler::where('siswa_id', $id)
                ->where('semester', $semester)
                ->where('tahun_ajaran', $tahunAjaran)
                ->get();

            // Kelompokkan nilai berdasarkan kategori mapel
            $nilaiUmum = $nilais->filter(function($n) {
                return $n->mapel && $n->mapel->kategori == 'Umum';
            });

            $nilaiKejuruan = $nilais->filter(function($n) {
                return $n->mapel && $n->mapel->kategori == 'Kejuruan';
            });

            // Generate PDF internal untuk lampiran
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

            // Simpan sementara di storage
            $fileName = 'Raport_' . str_replace(' ', '_', $siswa->nama) . '_' . time() . '.pdf';
            $filePath = storage_path('app/public/temp/' . $fileName);
            
            // Pastikan folder temp ada
            if (!file_exists(storage_path('app/public/temp'))) {
                mkdir(storage_path('app/public/temp'), 0755, true);
            }

            $pdf->save($filePath);

            // Kirim Email dengan Lampiran
            \Illuminate\Support\Facades\Mail::to($siswa->email_orang_tua)
                ->send(new \App\Mail\RaportMail($siswa, $filePath));

            // Hapus file sementara setelah dikirim
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            return redirect()->back()->with('success', 'E-Raport berhasil dikirim ke email orang tua (' . $siswa->email_orang_tua . ') beserta lampiran PDF.');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Email Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengirim email: ' . $e->getMessage());
        }
    }

    public function exportPdf($id)
    {
        $this->checkStudentAccess($id);
        $siswa = Siswa::with(['kelas', 'raports'])->findOrFail($id);
        $semester = Setting::get('semester', 'Ganjil');
        $tahunAjaran = Setting::get('tahun_ajaran', '2025/2026');

        $nilais = Nilai::with('mapel')
            ->where('siswa_id', $id)
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahunAjaran)
            ->get();

        $raport = Raport::where('siswa_id', $id)
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahunAjaran)
            ->first();

        $ekstrakurikulers = \App\Models\Ekstrakurikuler::where('siswa_id', $id)
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahunAjaran)
            ->get();

        // Kelompokkan nilai berdasarkan kategori mapel
        $nilaiUmum = $nilais->filter(function($n) {
            return $n->mapel && $n->mapel->kategori == 'Umum';
        });

        $nilaiKejuruan = $nilais->filter(function($n) {
            return $n->mapel && $n->mapel->kategori == 'Kejuruan';
        });

        $raport = Raport::with('wali')->where('siswa_id', $id)
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahunAjaran)
            ->first();

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
        
        return $pdf->download('Raport_' . str_replace(' ', '_', $siswa->nama) . '.pdf');
    }

    private function checkStudentAccess($siswaId)
    {
        $user = Auth::user();
        $guru = $user->guru;
        
        if (!$guru) {
            abort(403, 'Akses ditolak.');
        }

        $kelas = \App\Models\Kelas::where('wali_id', $guru->id)->first();
        if (!$kelas) {
            abort(403, 'Anda bukan wali kelas.');
        }

        $siswa = Siswa::findOrFail($siswaId);
        if ($siswa->kelas_id !== $kelas->id) {
            abort(403, 'Siswa ini bukan berada di bawah perwalian Anda.');
        }
    }

    public function bulkAttendance()
    {
        $user = Auth::user();
        $guru = $user->guru;
        $kelas = \App\Models\Kelas::where('wali_id', $guru->id)->first();

        if (!$kelas) {
            return redirect()->back()->with('error', 'Anda belum ditugaskan sebagai wali kelas.');
        }

        $semester = Setting::get('semester', 'Ganjil');
        $tahunAjaran = Setting::get('tahun_ajaran', '2025/2026');
        $siswas = Siswa::where('kelas_id', $kelas->id)->get();

        foreach ($siswas as $siswa) {
            $raport = Raport::where('siswa_id', $siswa->id)
                ->where('semester', $semester)
                ->where('tahun_ajaran', $tahunAjaran)
                ->first();

            // Jika data raport belum ada atau masih 0, coba hitung dari absensi harian
            if (!$raport || ($raport->sakit == 0 && $raport->izin == 0 && $raport->alpa == 0)) {
                $statsHarian = \App\Models\Kehadiran::where('siswa_id', $siswa->id)
                    ->whereBetween('tanggal', [$this->getSemesterStart(), $this->getSemesterEnd()])
                    ->get();
                
                $siswa->auto_sakit = $statsHarian->where('status', 'Sakit')->count();
                $siswa->auto_izin = $statsHarian->where('status', 'Izin')->count();
                $siswa->auto_alpa = $statsHarian->where('status', 'Alpa')->count();
            }

            $siswa->raport = $raport;
        }

        return view('walikelas.raport.bulk_attendance', compact('siswas', 'semester', 'tahunAjaran', 'kelas'));
    }

    public function updateBulkAttendance(Request $request)
    {
        $request->validate([
            'attendance' => 'required|array',
            'attendance.*.sakit' => 'nullable|integer|min:0',
            'attendance.*.izin' => 'nullable|integer|min:0',
            'attendance.*.alpa' => 'nullable|integer|min:0',
        ]);

        $semester = Setting::get('semester', 'Ganjil');
        $tahunAjaran = Setting::get('tahun_ajaran', '2025/2026');
        $waliId = Auth::user()->guru_id;

        foreach ($request->attendance as $siswaId => $data) {
            Raport::updateOrCreate(
                [
                    'siswa_id' => $siswaId,
                    'semester' => $semester,
                    'tahun_ajaran' => $tahunAjaran,
                ],
                [
                    'wali_id' => $waliId,
                    'sakit' => $data['sakit'] ?? 0,
                    'izin' => $data['izin'] ?? 0,
                    'alpa' => $data['alpa'] ?? 0,
                ]
            );
        }

        return redirect()->route('walikelas.raport.index')->with('success', 'Rekapitulasi kehadiran berhasil diperbarui.');
    }

    public function importAttendance(Request $request)
    {
        $request->validate([
            'file_csv' => 'required|file|mimes:csv,txt'
        ]);

        $semester = Setting::get('semester', 'Ganjil');
        $tahunAjaran = Setting::get('tahun_ajaran', '2025/2026');
        $waliId = Auth::user()->guru_id;

        $file = $request->file('file_csv');
        $handle = fopen($file->getRealPath(), 'r');
        
        // Skip header (Format: NISN, Sakit, Izin, Alpa)
        fgetcsv($handle);

        $count = 0;
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $nisn = trim($data[0]);
            $sakit = isset($data[1]) ? (int)$data[1] : 0;
            $izin = isset($data[2]) ? (int)$data[2] : 0;
            $alpa = isset($data[3]) ? (int)$data[3] : 0;

            $siswa = Siswa::where('nisn', $nisn)->first();
            if ($siswa) {
                Raport::updateOrCreate(
                    [
                        'siswa_id' => $siswa->id,
                        'semester' => $semester,
                        'tahun_ajaran' => $tahunAjaran,
                    ],
                    [
                        'wali_id' => $waliId,
                        'sakit' => $sakit,
                        'izin' => $izin,
                        'alpa' => $alpa,
                    ]
                );
                $count++;
            }
        }

        fclose($handle);

        return redirect()->route('walikelas.raport.index')->with('success', "$count data kehadiran berhasil diimport.");
    }

    private function getSemesterStart() { return date('Y') . '-01-01'; }
    private function getSemesterEnd() { return date('Y') . '-12-31'; }
}
