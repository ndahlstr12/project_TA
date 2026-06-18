<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Jadwal;
use App\Models\Guru;
use App\Models\Mapel;

// Cari Jadwal ID 3 (XII-TKJ-1 | B.Indo | Mila)
$jadwal = Jadwal::find(3);
if ($jadwal) {
    $math = Mapel::where('nama_mapel', 'like', '%matematika%')->first();
    $indah = Guru::where('nama', 'like', '%indah%')->first();
    
    if ($math && $indah) {
        $jadwal->update([
            'mapel_id' => $math->id,
            'guru_id' => $indah->id
        ]);
        echo "Jadwal ID 3 berhasil diperbarui menjadi Matematika - Indah.\n";
    } else {
        echo "Gagal menemukan Mapel Matematika atau Guru Indah.\n";
    }
} else {
    echo "Jadwal ID 3 tidak ditemukan.\n";
}
