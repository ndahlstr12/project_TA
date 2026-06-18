<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Jadwal;
use App\Models\Guru;
use App\Models\Mapel;
use App\Models\Kelas;

echo "--- DAFTAR GURU ---\n";
foreach (Guru::all() as $g) {
    echo "ID: {$g->id} | Nama: {$g->nama} | Spesialisasi: {$g->spesialisasi}\n";
}

echo "\n--- DAFTAR MAPEL ---\n";
foreach (Mapel::all() as $m) {
    echo "ID: {$m->id} | Nama: {$m->nama_mapel}\n";
}

echo "\n--- DAFTAR JADWAL ---\n";
$jadwals = Jadwal::with(['guru', 'mapel', 'kelas'])->get();
foreach ($jadwals as $j) {
    echo "Jadwal ID: {$j->id} | Kelas: " . ($j->kelas->nama_kelas ?? 'N/A') . 
         " | Mapel: " . ($j->mapel->nama_mapel ?? 'N/A') . 
         " | Guru: " . ($j->guru->nama ?? 'N/A') . "\n";
}
