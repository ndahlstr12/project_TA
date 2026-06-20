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
                return "{$n->mapel->nama_mapel}: {$n->nilai_akhir}";
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
            $catatanWali = $raport->catatan_wali ?? 'Tidak ada catatan';
            $absensi = "Sakit: {$raport->sakit}, Izin: {$raport->izin}, Alpa: {$raport->alpa}";

            $prompt = "Sebagai seorang konselor pendidikan ahli, berikan saran penanganan atau rekomendasi studi yang singkat, padat, dan motivatif untuk siswa berikut:
            
            Nama Siswa: {$siswa->nama}
            Absensi: {$absensi}
            Catatan Wali Kelas: {$catatanWali}
            
            Berikan rekomendasi dalam maksimal 3 kalimat dalam Bahasa Indonesia yang formal dan mendukung perkembangan siswa tersebut.";

            $hasilAi = $this->callGemini($prompt);

            $raport->update(['saran_ai' => $hasilAi]);

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
                'data' => $hasilAi
            ];

        } catch (\Exception $e) {
            Log::error('AI Error: ' . $e->getMessage());
            
            $errorMessage = $e->getMessage();
            if (str_contains($errorMessage, 'Could not resolve') || 
                str_contains($errorMessage, 'cURL error') || 
                str_contains($errorMessage, 'timed out') || 
                str_contains($errorMessage, 'API Key Gemini belum diatur')) {
                
                $fallback = $this->getFallbackRecommendation($raport);
                $raport->update(['saran_ai' => $fallback]);
                
                return [
                    'success' => true,
                    'message' => 'Rekomendasi berhasil dibuat (sistem cadangan offline).',
                    'data' => $fallback
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
            return $n->nilai_akhir ?? $n->nilai_angka ?? 0;
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
        
        if ($totalAlpa > 3) {
            return "Disarankan bagi orang tua untuk memantau kedisiplinan kehadiran {$siswa->nama} secara lebih ketat di rumah serta berkoordinasi secara aktif dengan pihak sekolah guna meminimalisir ketidakhadiran tanpa keterangan.";
        } else {
            return "Pertahankan motivasi belajar, sikap disiplin, serta partisipasi aktif {$siswa->nama} baik di dalam kelas maupun kegiatan sekolah untuk mendukung rencana studi lanjutannya.";
        }
    }
}
