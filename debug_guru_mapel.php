<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\GuruMapel;

echo "--- GURU MAPEL ---\n";
foreach (GuruMapel::with(['guru', 'mapel', 'kelas'])->get() as $gm) {
    echo "Guru: " . ($gm->guru->nama ?? 'N/A') . 
         " | Mapel: " . ($gm->mapel->nama_mapel ?? 'N/A') . 
         " | Kelas: " . ($gm->kelas->nama_kelas ?? 'N/A') . "\n";
}
