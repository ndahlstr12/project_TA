<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "--- ISI TABEL GURU_MAPEL ---\n";
$rows = DB::table('guru_mapel')->get();
foreach ($rows as $r) {
    print_r($r);
}
