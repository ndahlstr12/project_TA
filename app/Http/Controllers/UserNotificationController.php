<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserNotificationController extends Controller
{
    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back()->with('success', 'Semua notifikasi telah ditandai sebagai sudah dibaca.');
    }

    public function clearAll()
    {
        Notification::where('user_id', Auth::id())->delete();
        return back()->with('success', 'Semua notifikasi telah dihapus.');
    }

    /**
     * Fitur khusus untuk membersihkan notifikasi salah kelas (XII TKJ 2)
     * Dibuat lebih fleksibel agar menangkap semua pola yang mengandung XII TKJ 2
     */
    public function cleanupWrongNotifications()
    {
        $count = Notification::where('user_id', Auth::id())
            ->where(function($q) {
                $q->where('message', 'like', '%XII TKJ 2%')
                  ->orWhere('title', 'like', '%XII TKJ 2%');
            })
            ->delete();

        return back()->with('success', "$count notifikasi terkait XII TKJ 2 berhasil dihapus.");
    }

    /**
     * Hapus semua notifikasi kehadiran sebagai langkah terakhir jika masih bermasalah
     */
    public function clearAttendanceNotifications()
    {
        $count = Notification::where('user_id', Auth::id())
            ->where('type', 'attendance')
            ->delete();

        return back()->with('success', "$count semua notifikasi kehadiran berhasil dihapus.");
    }
}
