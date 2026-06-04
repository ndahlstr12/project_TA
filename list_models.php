<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$apiKey = config('gemini.api_key');
$url = "https://generativelanguage.googleapis.com/v1beta/models?key=" . $apiKey;

$response = \Illuminate\Support\Facades\Http::withoutVerifying()->get($url);
echo $response->body();
