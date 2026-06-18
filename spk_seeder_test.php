<?php

use Illuminate\Support\Facades\DB;
use App\Models\Siswa;
use App\Models\Kriteria;
use App\Models\Nilai;
use App\Models\Kehadiran;
use App\Models\JurnalPerilaku;
use App\Models\Mapel;
use App\Models\Setting;
use App\Models\User;
use App\Models\Guru;

// Bootstrapping Laravel (jika dijalankan sebagai script mandiri)
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Mulai simulasi data SPK...\n";

$tahunAjaran = Setting::get('tahun_ajaran', '2025/2026');
$semester = Setting::get('semester', 'Ganjil');

// 1. Pastikan Kriteria Ada
$kriterias = [
    ['kode' => 'C1', 'nama' => 'Nilai Akademik', 'bobot' => 50, 'jenis' => 'benefit'],
    ['kode' => 'C2', 'nama' => 'Kehadiran', 'bobot' => 30, 'jenis' => 'benefit'],
    ['kode' => 'C3', 'nama' => 'Perilaku', 'bobot' => 20, 'jenis' => 'benefit'],
];

foreach ($kriterias as $k) {
    Kriteria::updateOrCreate(['kode' => $k['kode']], $k);
}
echo "Kriteria SPK siap.\n";

// 2. Ambil SEMUA siswa di kelas XII-TKJ-1 (ID: 31) agar bisa di-ranking
$siswas = Siswa::where('kelas_id', 31)->get();

if ($siswas->isEmpty()) {
    echo "Tidak ada siswa ditemukan di kelas XII-TKJ-1. Mengambil siswa pertama yang ada sebagai fallback...\n";
    $siswas = Siswa::take(10)->get();
}

// 3. Pastikan ada Mapel untuk input nilai
$mapel = Mapel::first();
if (!$mapel) {
    $mapel = Mapel::create(['nama_mapel' => 'Bahasa Indonesia', 'kategori' => 'Umum']);
}

$guru = Guru::first();
if (!$guru) {
    echo "Pastikan ada data Guru di database.\n";
    exit;
}

foreach ($siswas as $index => $siswa) {
    echo "Mengisi data untuk: {$siswa->nama}...\n";

    // A. Input Nilai (Variasi antara 70 - 95)
    $nilaiBase = 70 + ($index * 5);
    Nilai::updateOrCreate(
        [
            'siswa_id' => $siswa->id, 
            'mapel_id' => $mapel->id, 
            'semester' => $semester, 
            'tahun_ajaran' => $tahunAjaran
        ],
        [
            'guru_id' => $guru->id,
            'nilai_angka' => $nilaiBase > 100 ? 98 : $nilaiBase,
            'capaian_kompetensi' => 'Sangat baik dalam pemahaman materi.'
        ]
    );

    // B. Input Kehadiran (Variasi Hadir)
    // Hapus kehadiran lama agar tidak menumpuk dalam tes
    Kehadiran::where('siswa_id', $siswa->id)->delete();
    
    $hariHadir = 10 - $index; // Makin ke bawah makin jarang hadir
    for ($i = 0; $i < 10; $i++) {
        Kehadiran::create([
            'siswa_id' => $siswa->id,
            'tanggal' => date('Y-m-d', strtotime("-$i days")),
            'status' => ($i < $hariHadir) ? 'Hadir' : 'Alpa',
            'keterangan' => 'Keterangan ' . $i
        ]);
    }

    // C. Input Perilaku (Poin Variasi)
    JurnalPerilaku::updateOrCreate(
        ['siswa_id' => $siswa->id, 'catatan' => 'Contoh Catatan SPK'],
        [
            'guru_id' => $guru->id,
            'tanggal' => date('Y-m-d'),
            'tipe' => $index % 2 == 0 ? 'Positif' : 'Negatif',
            'poin' => $index % 2 == 0 ? 10 : -5
        ]
    );
}

echo "Simulasi data selesai! Silakan cek menu Ranking di Dashboard Wali Kelas.\n";
