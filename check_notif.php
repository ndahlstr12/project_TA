<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Notification;
use App\Models\User;
use App\Models\Guru;

$indah = Guru::where('nip', '123456789')->first();
if (!$indah) {
    echo "Indah not found\n";
    exit;
}

$user = User::where('guru_id', $indah->id)->first();
if (!$user) {
    echo "User for Indah not found\n";
    exit;
}

echo "Checking notifications for User ID: " . $user->id . " (" . $user->name . ")\n";

$notifications = Notification::where('user_id', $user->id)->get();
echo "Total notifications: " . $notifications->count() . "\n";

foreach ($notifications as $n) {
    echo "- ID: " . $n->id . " | Title: " . $n->title . " | Message: " . $n->message . "\n";
}
