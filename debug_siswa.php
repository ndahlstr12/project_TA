<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Siswa;

echo "--- DAFTAR SISWA ---\n";
foreach (Siswa::all() as $s) {
    echo "ID: {$s->id} | Nama: {$s->nama} | Kelas: " . ($s->kelas->nama_kelas ?? 'N/A') . "\n";
}
