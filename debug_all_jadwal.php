<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Jadwal;

echo "--- SEMUA JADWAL ---\n";
$jadwals = Jadwal::with(['guru', 'mapel', 'kelas'])->get();
foreach ($jadwals as $j) {
    echo "ID: {$j->id} | Kelas: " . ($j->kelas->nama_kelas ?? 'N/A') . 
         " | Mapel: " . ($j->mapel->nama_mapel ?? 'N/A') . 
         " | Guru: " . ($j->guru->nama ?? 'N/A') . 
         " | Hari: {$j->hari} | Jam: {$j->jam_mulai}\n";
}
