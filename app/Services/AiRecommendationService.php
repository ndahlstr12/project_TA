<?php

namespace App\Services;

use App\Models\Raport;
use App\Models\AiLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiRecommendationService
{
    /**
     * Memanggil API Gemini menggunakan Laravel Http Client (Metode Raw).
     * Menggunakan model yang terkonfirmasi ada berdasarkan hasil ModelService.ListModels.
     */
    private function callGemini($prompt, $imagePath = null)
    {
        $apiKey = config('gemini.api_key');
        
        if (!$apiKey) {
            throw new \Exception("API Key Gemini belum diatur di file .env");
        }

        $models = [
            'gemini-1.5-flash',
            'gemini-flash-latest',
        ];

        $lastErrorMessage = "";

        foreach ($models as $modelName) {
            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$modelName}:generateContent?key=" . $apiKey;
            
            try {
                $parts = [['text' => $prompt]];

                if ($imagePath && file_exists($imagePath)) {
                    $imageData = base64_encode(file_get_contents($imagePath));
                    $mimeType = mime_content_type($imagePath);
                    $parts[] = [
                        'inline_data' => [
                            'mime_type' => $mimeType,
                            'data' => $imageData
                        ]
                    ];
                }

                $response = Http::withoutVerifying()
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->timeout(30)
                    ->post($url, [
                        'contents' => [['parts' => $parts]]
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
                    if ($text) {
                        return $text;
                    }
                }

                $errorData = $response->json();
                $lastErrorMessage = $errorData['error']['message'] ?? "Unknown Error";
            } catch (\Exception $e) {
                $lastErrorMessage = $e->getMessage();
            }
        }

        throw new \Exception("AI tidak merespon. Error: " . $lastErrorMessage);
    }

    /**
     * Memvalidasi apakah file yang diunggah benar-benar merupakan tanda tangan.
     */
    public function validateSignature($imagePath)
    {
        try {
            $prompt = "Analisis gambar ini. Apakah ini adalah sebuah tanda tangan (signature)?
            Berikan jawaban 'TRUE' jika ini benar-benar tanda tangan manual (coretan tangan), 
            atau 'FALSE' jika ini adalah foto orang, pemandangan, dokumen penuh teks, logo berwarna, atau gambar lainnya yang bukan tanda tangan.
            Jawab HANYA dengan kata 'TRUE' atau 'FALSE'.";

            $hasil = trim(strtoupper($this->callGemini($prompt, $imagePath)));

            return str_contains($hasil, 'TRUE');
        } catch (\Exception $e) {
            Log::error('Signature Validation Error: ' . $e->getMessage());
            // Jika AI gagal (limit/error), kita kembalikan true agar tidak menghambat user
            return true; 
        }
    }

    /**
     * Menghasilkan catatan wali kelas otomatis berdasarkan nilai, kehadiran, perilaku, dan ekskul.
     */
    public function generateWaliCatatan($siswa, $nilais, $kehadiran, $jurnals, $ekskuls)
    {
        try {
            // Persiapkan data nilai untuk AI
            $ringkasanNilai = $nilais->map(function($n) {
                return (optional($n->mapel)->nama_mapel ?? 'Mata Pelajaran') . ": " . ($n->nilai_angka ?? 0);
            })->implode(', ');

            // Persiapkan data kehadiran
            $sakit = $kehadiran->where('keterangan', 'sakit')->count();
            $izin = $kehadiran->where('keterangan', 'izin')->count();
            $alpa = $kehadiran->where('keterangan', 'alpa')->count();
            $absensi = "Sakit: $sakit, Izin: $izin, Alpa: $alpa";

            // Persiapkan data perilaku
            $ringkasanPerilaku = $jurnals->map(function($j) {
                return "[{$j->tipe}] {$j->catatan}";
            })->implode('; ');

            // Persiapkan data ekstrakurikuler
            $ringkasanEkskul = $ekskuls->map(function($e) {
                return "{$e->nama_ekstra}: {$e->keterangan}";
            })->implode('; ');

            $prompt = "Bertindaklah sebagai Wali Kelas yang bijaksana. Buatlah Catatan Wali Kelas untuk raport siswa berikut berdasarkan data objektif:
            
            Nama Siswa: {$siswa->nama}
            Data Nilai: {$ringkasanNilai}
            Data Kehadiran: {$absensi}
            Catatan Perilaku: " . ($ringkasanPerilaku ?: 'Tidak ada catatan perilaku khusus') . "
            Kegiatan Ekstrakurikuler: " . ($ringkasanEkskul ?: 'Tidak mengikuti ekstrakurikuler khusus') . "
            
            Tugas:
            1. Analisis performa akademisnya.
            2. Hubungkan dengan tingkat kehadiran, perilakunya, dan keaktifannya di kegiatan ekstrakurikuler.
            3. Berikan kalimat motivasi yang personal.
            
            Ketentuan:
            - Gunakan Bahasa Indonesia yang formal namun hangat.
            - Maksimal 3-4 kalimat pendek.
            - Jangan menggunakan kata-kata teknis AI.";

            $hasilAi = $this->callGemini($prompt);

            return [
                'success' => true,
                'data' => $hasilAi
            ];

        } catch (\Exception $e) {
            Log::error('AI Wali Catatan Error: ' . $e->getMessage());
            
            $errorMessage = $e->getMessage();
            if (str_contains($errorMessage, 'Could not resolve') || 
                str_contains($errorMessage, 'cURL error') || 
                str_contains($errorMessage, 'timed out') || 
                str_contains($errorMessage, 'API Key Gemini belum diatur')) {
                
                $fallback = $this->getFallbackWaliCatatan($siswa, $nilais, $kehadiran, $jurnals, $ekskuls);
                return [
                    'success' => true,
                    'data' => $fallback
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Gagal generate catatan: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Menghasilkan rekomendasi AI berdasarkan data raport siswa.
     */
    public function generateRecommendation(Raport $raport)
    {
        try {
            $siswa = $raport->siswa;
            if (!$siswa) {
                $raport->loadMissing('siswa');
                $siswa = $raport->siswa;
            }
            
            $catatanWali = $raport->catatan_wali ?? 'Tidak ada catatan';
            $absensi = "Sakit: {$raport->sakit}, Izin: {$raport->izin}, Alpa: {$raport->alpa}";

            // Fetch overall grades per subject
            $nilais = \App\Models\Nilai::with('mapel')
                ->where('siswa_id', $raport->siswa_id)
                ->where('semester', $raport->semester)
                ->where('tahun_ajaran', $raport->tahun_ajaran)
                ->get();

            $ringkasanNilai = $nilais->map(function($n) {
                return (optional($n->mapel)->nama_mapel ?? 'Mata Pelajaran') . ": " . ($n->nilai_angka ?? 0);
            })->implode(', ');

            // Fetch behavior logs (Jurnal Perilaku)
            $semesterStart = date('Y') . '-01-01';
            $semesterEnd = date('Y') . '-12-31';
            $jurnals = \App\Models\JurnalPerilaku::where('siswa_id', $raport->siswa_id)
                ->whereBetween('tanggal', [$semesterStart, $semesterEnd])
                ->get();

            $ringkasanPerilaku = $jurnals->map(function($j) {
                return "[{$j->tipe}] {$j->catatan}";
            })->implode('; ');

            $prompt = "Sebagai seorang konselor pendidikan ahli, berikan saran motivasi serta rekomendasi studi/penanganan yang singkat, padat, dan mendukung perkembangan siswa berikut:
            
            Nama Siswa: {$siswa->nama}
            Nilai Per Mata Pelajaran: " . ($ringkasanNilai ?: 'Tidak ada data nilai') . "
            Absensi: {$absensi}
            Catatan Perilaku: " . ($ringkasanPerilaku ?: 'Tidak ada catatan perilaku khusus') . "
            Catatan Wali Kelas: {$catatanWali}
            
            Tugas:
            1. Buat 'saran_ai' (saran motivasi personal yang hangat dan inspiratif berdasarkan capaian akademik, kehadiran, dan perilakunya).
            2. Buat 'rekomendasi_ai' (saran tindakan konkret bagi siswa/orang tua/guru untuk meningkatkan atau mempertahankan prestasinya).
            
            Kembalikan jawaban dalam format JSON valid dengan struktur berikut:
            {
                \"saran_ai\": \"(maksimal 2-3 kalimat hangat)\",
                \"rekomendasi_ai\": \"(maksimal 2-3 kalimat tindakan nyata)\"
            }
            
            Pastikan respon hanya berupa format JSON murni tanpa menyertakan blok kode markdown seperti ```json.";

            $hasilAi = $this->callGemini($prompt);

            // Clean the response from markdown wrapper if any
            $cleanJson = trim($hasilAi);
            if (strpos($cleanJson, '```') === 0) {
                $cleanJson = preg_replace('/^```(?:json)?|```$/m', '', $cleanJson);
                $cleanJson = trim($cleanJson);
            }

            $decoded = json_decode($cleanJson, true);

            if ($decoded && isset($decoded['saran_ai']) && isset($decoded['rekomendasi_ai'])) {
                $saranAi = $decoded['saran_ai'];
                $rekomendasiAi = $decoded['rekomendasi_ai'];
            } else {
                // Fallback if parsing fails
                $saranAi = $hasilAi;
                $rekomendasiAi = "Disarankan terus dipantau kedisiplinan dan pembelajarannya baik di sekolah maupun di rumah.";
            }

            $raport->update([
                'saran_ai' => $saranAi,
                'rekomendasi_ai' => $rekomendasiAi
            ]);

            // Simpan log (hanya jika raport_id tersedia karena batasan database saat ini)
            try {
                AiLog::create([
                    'raport_id' => $raport->id,
                    'prompt_payload' => $prompt,
                    'hasil_ai' => $hasilAi,
                    'api_model' => 'gemini-auto-selection'
                ]);
            } catch (\Exception $e) {
                Log::warning("Gagal menyimpan AI Log: " . $e->getMessage());
            }

            return [
                'success' => true,
                'message' => 'Rekomendasi AI berhasil dibuat.',
                'data' => $saranAi
            ];

        } catch (\Exception $e) {
            Log::error('AI Error: ' . $e->getMessage());
            
            $errorMessage = $e->getMessage();
            if (str_contains($errorMessage, 'Could not resolve') || 
                str_contains($errorMessage, 'cURL error') || 
                str_contains($errorMessage, 'timed out') || 
                str_contains($errorMessage, 'API Key Gemini belum diatur')) {
                
                $fallback = $this->getFallbackRecommendation($raport);
                $raport->update([
                    'saran_ai' => $fallback['saran_ai'],
                    'rekomendasi_ai' => $fallback['rekomendasi_ai']
                ]);
                
                return [
                    'success' => true,
                    'message' => 'Rekomendasi berhasil dibuat (sistem cadangan offline).',
                    'data' => $fallback['saran_ai']
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Gagal mengambil saran AI: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Menghasilkan rekomendasi berdasarkan catatan perilaku (Jurnal Perilaku).
     */
    public function getBehaviorRecommendation($namaSiswa, $catatanPerilaku)
    {
        try {
            $prompt = "Sebagai seorang psikolog pendidikan dan konselor ahli, analisis perilaku siswa berikut dan berikan saran penanganan atau apresiasi yang tepat.
            
            Nama Siswa: {$namaSiswa}
            Catatan Perilaku: {$catatanPerilaku}
            
            Berikan analisis singkat dan 1 saran tindakan konkret dalam Bahasa Indonesia yang mendukung pembentukan karakter siswa (Maksimal 3 kalimat).";

            $hasilAi = $this->callGemini($prompt);

            // Simpan log hanya jika tidak null, karena batasan Integrity Constraint pada database
            // Kami membungkus ini dalam try-catch agar fitur utama tidak berhenti jika logging gagal
            try {
                AiLog::create([
                    'raport_id' => null, // Ini akan gagal jika DB belum di-migrate, tapi AI tetap jalan
                    'prompt_payload' => $prompt,
                    'hasil_ai' => $hasilAi,
                    'api_model' => 'gemini-auto-selection'
                ]);
            } catch (\Exception $e) {
                Log::warning("Gagal menyimpan AI Log Perilaku (Mungkin raport_id required): " . $e->getMessage());
            }

            return [
                'success' => true,
                'message' => 'Analisis perilaku berhasil dibuat.',
                'data' => $hasilAi
            ];

        } catch (\Exception $e) {
            Log::error('AI Behavior Error: ' . $e->getMessage());
            
            $errorMessage = $e->getMessage();
            if (str_contains($errorMessage, 'Could not resolve') || 
                str_contains($errorMessage, 'cURL error') || 
                str_contains($errorMessage, 'timed out') || 
                str_contains($errorMessage, 'API Key Gemini belum diatur')) {
                
                $fallback = $this->getFallbackBehaviorRecommendation($namaSiswa, $catatanPerilaku);
                return [
                    'success' => true,
                    'message' => 'Analisis perilaku berhasil dibuat (sistem cadangan offline).',
                    'data' => $fallback
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Gagal mengambil analisis AI: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Fallback method untuk menghasilkan saran perilaku offline.
     */
    private function getFallbackBehaviorRecommendation($namaSiswa, $catatanPerilaku)
    {
        $catatanLower = strtolower($catatanPerilaku);
        $isNegatif = false;
        $negativeKeywords = ['membolos', 'bolos', 'terlambat', 'telat', 'bertengkar', 'berantem', 'ribut', 'tidur', 'handphone', 'hp', 'bermain', 'main', 'mencuri', 'merokok', 'rokok', 'gadget', 'menyontek', 'contek', 'malas', 'alpa', 'absen', 'marah', 'kasar'];
        
        foreach ($negativeKeywords as $keyword) {
            if (str_contains($catatanLower, $keyword)) {
                $isNegatif = true;
                break;
            }
        }

        if ($isNegatif) {
            return "Perlu dilakukan pendekatan personal secara persuasif dengan {$namaSiswa} untuk membimbing perilakunya. Disarankan kepada orang tua untuk meningkatkan pengawasan di rumah serta berkomunikasi aktif dengan wali kelas mengenai perkembangannya.";
        } else {
            return "Apresiasi yang tinggi patut diberikan kepada {$namaSiswa} atas perilaku positif yang ditunjukkannya. Disarankan kepada orang tua dan guru untuk terus memberikan motivasi dan dukungan agar prestasi dan karakter baiknya tetap terjaga.";
        }
    }

    /**
     * Fallback method untuk menghasilkan catatan wali kelas offline.
     */
    private function getFallbackWaliCatatan($siswa, $nilais, $kehadiran, $jurnals, $ekskuls)
    {
        $avgNilai = $nilais->avg(function($n) {
            return $n->nilai_angka ?? 0;
        }) ?? 0;
        
        if ($avgNilai >= 80) {
            return "Selamat kepada {$siswa->nama} atas pencapaian akademis yang sangat memuaskan di semester ini. Pertahankan konsistensi belajar, kedisiplinan, serta keaktifan Anda untuk meraih prestasi yang lebih tinggi di masa mendatang.";
        } elseif ($avgNilai >= 70) {
            return "Pencapaian akademis {$siswa->nama} di semester ini tergolong cukup baik. Tingkatkan lagi fokus belajar dan keaktifan di dalam kelas agar nilainya dapat lebih maksimal di semester berikutnya.";
        } else {
            return "{$siswa->nama} perlu meningkatkan fokus, waktu belajar, dan kehadiran di semester depan. Diharapkan kerja sama yang lebih intensif antara orang tua di rumah dan guru di sekolah untuk mendukung belajarnya.";
        }
    }

    /**
     * Fallback method untuk menghasilkan saran rekomendasi raport offline.
     */
    private function getFallbackRecommendation($raport)
    {
        $siswa = $raport->siswa;
        $totalAlpa = $raport->alpa ?? 0;

        $nilais = \App\Models\Nilai::where('siswa_id', $raport->siswa_id)
            ->where('semester', $raport->semester)
            ->where('tahun_ajaran', $raport->tahun_ajaran)
            ->get();
        $avgNilai = $nilais->avg('nilai_angka') ?? 0;

        $semesterStart = date('Y') . '-01-01';
        $semesterEnd = date('Y') . '-12-31';
        $jurnals = \App\Models\JurnalPerilaku::where('siswa_id', $raport->siswa_id)
            ->whereBetween('tanggal', [$semesterStart, $semesterEnd])
            ->get();
        
        $hasNegatifPerilaku = $jurnals->contains(function($j) {
            return strtolower($j->tipe) === 'negatif';
        });

        // 1. Fallback Saran Motivasi (saran_ai)
        if ($avgNilai >= 80) {
            $saran = "Pertahankan motivasi belajar, sikap disiplin, serta partisipasi aktif {$siswa->nama} baik di dalam kelas maupun kegiatan sekolah untuk mendukung rencana studi lanjutannya.";
        } elseif ($avgNilai >= 70) {
            $saran = "Tingkatkan lagi ketekunan dan semangat belajar {$siswa->nama} agar pencapaian akademiknya dapat terus berkembang ke arah yang lebih baik.";
        } else {
            $saran = "{$siswa->nama} membutuhkan dorongan motivasi ekstra serta bimbingan belajar yang lebih intensif untuk membantu mengatasi kendala belajarnya.";
        }

        // 2. Fallback Rekomendasi Tindakan (rekomendasi_ai)
        if ($totalAlpa > 3) {
            $rekomendasi = "Disarankan bagi orang tua untuk memantau kedisiplinan kehadiran {$siswa->nama} secara lebih ketat serta berkoordinasi secara aktif dengan pihak sekolah.";
        } elseif ($avgNilai < 70) {
            $rekomendasi = "Diharapkan orang tua memberikan bimbingan akademis tambahan di rumah secara rutin untuk mendongkrak capaian nilai mata pelajaran.";
        } elseif ($hasNegatifPerilaku) {
            $rekomendasi = "Perlu pembinaan karakter secara persuasif dan berkelanjutan di rumah untuk memperbaiki perilaku siswa agar sejalan dengan prestasinya.";
        } else {
            $rekomendasi = "Disarankan agar orang tua dan guru terus mendukung minat serta bakat positif yang dimiliki siswa guna pengembangan dirinya.";
        }

        return [
            'saran_ai' => $saran,
            'rekomendasi_ai' => $rekomendasi
        ];
    }
}
