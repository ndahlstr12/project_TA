<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $tahun_ajaran = Setting::get('tahun_ajaran', '2025/2026');
        $semester = Setting::get('semester', 'Ganjil');
        
        return view('admin.settings.index', compact('tahun_ajaran', 'semester'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'tahun_ajaran' => 'required|string',
            'semester' => 'required|in:Ganjil,Genap',
        ]);

        Setting::set('tahun_ajaran', $request->tahun_ajaran, 'akademik');
        Setting::set('semester', $request->semester, 'akademik');

        return redirect()->back()->with('success', 'Konfigurasi akademik berhasil diperbarui.');
    }
}
