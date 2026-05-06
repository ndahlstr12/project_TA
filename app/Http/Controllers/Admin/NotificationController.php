<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        // Simulasi data konfigurasi (bisa diambil dari tabel settings nanti)
        $config = [
            'mail_subject' => 'E-Raport Semester Ganjil 2025/2026 - SMKN 1 Sungailiat',
            'mail_body' => 'Halo Bapak/Ibu, Berikut kami lampirkan hasil raport putra/putri Anda...',
            'auto_send' => true,
        ];

        return view('admin.notifications.index', compact('config'));
    }

    public function update(Request $request)
    {
        // Logika simpan konfigurasi
        return back()->with('success', 'Konfigurasi notifikasi berhasil diperbarui.');
    }
}
