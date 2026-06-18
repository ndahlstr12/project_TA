<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Nilai;
use App\Models\Siswa;

$count = Nilai::where('mapel_id', 1)
    ->whereIn('siswa_id', Siswa::where('kelas_id', 31)->pluck('id'))
    ->count();

echo "Jumlah nilai Matematika di kelas XII-TKJ-1: {$count}\n";
