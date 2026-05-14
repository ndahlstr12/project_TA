<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::set('tahun_ajaran', '2025/2026', 'akademik');
        Setting::set('semester', 'Ganjil', 'akademik');
    }
}
