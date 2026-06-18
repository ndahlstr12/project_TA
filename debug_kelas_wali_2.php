<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Kelas;
use App\Models\Guru;

echo "--- KELAS & WALI ---\n";
foreach (Kelas::with('waliKelas')->get() as $k) {
    echo "Kelas: {$k->nama_kelas} | Wali: " . ($k->waliKelas->nama ?? 'N/A') . "\n";
}
