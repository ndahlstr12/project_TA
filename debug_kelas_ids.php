<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Kelas;

foreach(Kelas::all() as $k) {
    echo "ID: {$k->id} | Name: {$k->nama_kelas}\n";
}
