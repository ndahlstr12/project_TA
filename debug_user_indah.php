<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Guru;

$indah = Guru::where('nip', '123456789')->first();
if ($indah) {
    $user = User::where('guru_id', $indah->id)->first();
    echo "User for Indah: " . ($user->name ?? 'N/A') . " | Username: " . ($user->username ?? 'N/A') . "\n";
} else {
    echo "Guru Indah not found.\n";
}
