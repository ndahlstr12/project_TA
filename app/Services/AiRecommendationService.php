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
    private function callGemini($prompt)
    {
        $apiKey = config('gemini.api_key');
        
        if (!$apiKey) {
            throw new \Exception("API Key Gemini belum diatur di file .env");
        }

        // Daftar model yang terkonfirmasi tersedia untuk API Key Anda (Hasil Diagnosa)
        $models = [
            'gemini-1.5-flash',
            'gemini-flash-latest',
            'gemini-3.5-flash',
            'gemini-2.5-flash',
            'gemini-2.0-flash',
            'gemini-pro-latest'
        ];

        $lastErrorMessage = "";

        foreach ($models as $modelName) {
            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$modelName}:generateContent?key=" . $apiKey;
            
            try {
                Log::info("Trying Gemini Model: {$modelName}...");

                $response = Http::withoutVerifying()
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->timeout(20)
                    ->post($url, [
                        'contents' => [['parts' => [['text' => $prompt]]]]
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
                    if ($text) {
                        Log::info("Success using model: {$modelName}");
                        return $text;
                    }
                }

                $errorData = $response->json();
                $message = $errorData['error']['message'] ?? "Unknown Error";
                
                Log::warning("Model {$modelName} failed: " . $message);
                $lastErrorMessage = $message;

            } catch (\Exception $e) {
                Log::error("Connection failed for model {$modelName}: " . $e->getMessage());
                $lastErrorMessage = $e->getMessage();
            }
        }

        throw new \Exception("AI tidak merespon. Error terakhir: " . $lastErrorMessage);
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
            return [
                'success' => false,
                'message' => 'Gagal mengambil analisis AI: ' . $e->getMessage()
            ];
        }
    }
}
