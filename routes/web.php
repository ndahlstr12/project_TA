<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\KriteriaController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\CbtController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\SettingController;

use App\Http\Controllers\GuruController as TeacherController;
use App\Http\Controllers\WaliKelasController;
use App\Http\Controllers\SiswaController as StudentDashboardController;
use App\Http\Controllers\OrangTuaController;

// Halaman utama dialihkan ke login jika belum masuk
Route::get('/', function () {
    return redirect()->route ('login');
});

// Autentikasi
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');

// Profile & Password Management (Common for all roles)
Route::middleware('auth')->group(function() {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Notification Management
    Route::get('/notifications/unread', [\App\Http\Controllers\UserNotificationController::class, 'getUnreadNotifications'])->name('notifications.unread');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\UserNotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
    Route::post('/notifications/clear-all', [\App\Http\Controllers\UserNotificationController::class, 'clearAll'])->name('notifications.clearAll');
    Route::post('/notifications/clear-attendance', [\App\Http\Controllers\UserNotificationController::class, 'clearAttendanceNotifications'])->name('notifications.clearAttendance');
    Route::post('/notifications/cleanup-wrong', [\App\Http\Controllers\UserNotificationController::class, 'cleanupWrongNotifications'])->name('notifications.cleanupWrong');
});

// Route untuk Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/export-pdf', [DashboardController::class, 'exportPDF'])->name('dashboard.export-pdf');
    
    // Separate User Management
    Route::post('/users/import', [UserController::class, 'import'])->name('users.import');
    Route::get('/users/guru', [UserController::class, 'indexGuru'])->name('users.guru');
    Route::get('/users/siswa', [UserController::class, 'indexSiswa'])->name('users.siswa');
    Route::get('/users/orangtua', [UserController::class, 'indexOrangTua'])->name('users.orangtua');
    Route::resource('users', UserController::class);

    Route::post('/siswas/import', [SiswaController::class, 'import'])->name('siswas.import');
    Route::resource('siswas', SiswaController::class);
    Route::resource('gurus', GuruController::class);
    Route::resource('kelas', KelasController::class);
    Route::resource('mapel', \App\Http\Controllers\Admin\MapelController::class);
    Route::resource('kriteria', KriteriaController::class);
    
    // Jadwal
    Route::resource('jadwal', JadwalController::class);
    Route::post('/jadwal/import', [JadwalController::class, 'import'])->name('jadwal.import');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::put('/notifications', [NotificationController::class, 'update'])->name('notifications.update');
    Route::post('/password-resets/{id}/resolve', [NotificationController::class, 'resolvePasswordReset'])->name('password-resets.resolve');

    // Pengaturan Sistem
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
});

// Route Bersama untuk Guru dan Wali Kelas (Fitur Mengajar)
Route::middleware(['auth', 'role:guru|walikelas'])->group(function () {
    // Kehadiran Management (Shared)
    Route::get('/kehadiran', [\App\Http\Controllers\KehadiranController::class, 'index'])->name('shared.kehadiran.index');
    Route::post('/kehadiran/store', [\App\Http\Controllers\KehadiranController::class, 'store'])->name('shared.kehadiran.store');
    Route::post('/kehadiran/batch-store-hadir', [\App\Http\Controllers\KehadiranController::class, 'batchStoreHadir'])->name('shared.kehadiran.batch-store-hadir');

    // Nilai Management (Shared)
    Route::get('/nilai', [\App\Http\Controllers\NilaiController::class, 'index'])->name('shared.nilai.index');
    Route::get('/nilai/create', [\App\Http\Controllers\NilaiController::class, 'create'])->name('shared.nilai.create');
    Route::post('/nilai/store', [\App\Http\Controllers\NilaiController::class, 'store'])->name('shared.nilai.store');
    Route::post('/nilai/update-kkm', [\App\Http\Controllers\NilaiController::class, 'updateKkm'])->name('shared.nilai.update-kkm');
    Route::post('/nilai/import', [\App\Http\Controllers\NilaiController::class, 'import'])->name('shared.nilai.import');
    Route::get('/nilai/monitoring/{jadwal_id}', [\App\Http\Controllers\NilaiController::class, 'showMonitoring'])->name('shared.nilai.monitoring');

    // Unified CBT & Ujian Management (Shared)
    Route::get('/cbt', [CbtController::class, 'index'])->name('shared.cbt.index');
    Route::get('/cbt/create', [CbtController::class, 'create'])->name('shared.cbt.create');
    Route::post('/cbt', [CbtController::class, 'store'])->name('shared.cbt.store');
    Route::get('/cbt/{id}', [CbtController::class, 'show'])->name('shared.cbt.show');
    Route::post('/cbt/{id}/soal', [CbtController::class, 'storeSoal'])->name('shared.cbt.soal.store');
    Route::post('/cbt/{id}/import', [CbtController::class, 'import'])->name('shared.cbt.import');
    Route::post('/cbt/{id}/toggle', [CbtController::class, 'toggleStatus'])->name('shared.cbt.toggle');
});

// Route untuk Guru
Route::middleware(['auth', 'role:guru'])->prefix('teacher')->name('guru.')->group(function () {
    Route::get('/dashboard', [TeacherController::class, 'index'])->name('dashboard');
    
    // Alias ke route bersama untuk kompatibilitas
    Route::get('/nilai', fn() => redirect()->route('shared.nilai.index'))->name('nilai.index');
    Route::get('/kehadiran', fn() => redirect()->route('shared.kehadiran.index'))->name('kehadiran.index');
    Route::get('/cbt', fn() => redirect()->route('shared.cbt.index'))->name('cbt.index');
    Route::get('/cbt/create', fn() => redirect()->route('shared.cbt.create'))->name('cbt.create');
    Route::get('/cbt/{id}', fn($id) => redirect()->route('shared.cbt.show', $id))->name('cbt.show');
});

// Route untuk Wali Kelas
Route::middleware(['auth', 'role:walikelas'])->prefix('class-teacher')->name('walikelas.')->group(function () {
    Route::get('/dashboard', [WaliKelasController::class, 'index'])->name('dashboard');
    Route::get('/ranking', [WaliKelasController::class, 'ranking'])->name('ranking.index');
    
    // Alias ke route bersama
    Route::get('/nilai', fn() => redirect()->route('shared.nilai.index'))->name('nilai.index');
    Route::get('/kehadiran', fn() => redirect()->route('shared.kehadiran.index'))->name('kehadiran.index');
    Route::get('/cbt', fn() => redirect()->route('shared.cbt.index'))->name('cbt.index');
    Route::get('/cbt/create', fn() => redirect()->route('shared.cbt.create'))->name('cbt.create');
    Route::get('/cbt/{id}', fn($id) => redirect()->route('shared.cbt.show', $id))->name('cbt.show');

    // Jurnal Perilaku
    Route::get('/jurnal', [\App\Http\Controllers\JurnalPerilakuController::class, 'index'])->name('jurnal.index');
    Route::post('/jurnal', [\App\Http\Controllers\JurnalPerilakuController::class, 'store'])->name('jurnal.store');
    Route::put('/jurnal/{id}', [\App\Http\Controllers\JurnalPerilakuController::class, 'update'])->name('jurnal.update');
    Route::delete('/jurnal/{id}', [\App\Http\Controllers\JurnalPerilakuController::class, 'destroy'])->name('jurnal.destroy');
    Route::post('/jurnal/ai', [\App\Http\Controllers\JurnalPerilakuController::class, 'generateAiRecommendation'])->name('jurnal.ai');

    // Raport Management
    Route::get('/raport', [\App\Http\Controllers\RaportController::class, 'index'])->name('raport.index');
    Route::get('/raport/attendance/bulk', [\App\Http\Controllers\RaportController::class, 'bulkAttendance'])->name('raport.attendance.bulk');
    Route::post('/raport/attendance/bulk', [\App\Http\Controllers\RaportController::class, 'updateBulkAttendance'])->name('raport.attendance.update-bulk');
    Route::post('/raport/attendance/import', [\App\Http\Controllers\RaportController::class, 'importAttendance'])->name('raport.attendance.import');
    Route::get('/raport/{id}', [\App\Http\Controllers\RaportController::class, 'show'])->name('raport.show');
    Route::put('/raport/{id}', [\App\Http\Controllers\RaportController::class, 'update'])->name('raport.update');
    Route::post('/raport/{id}/ai', [\App\Http\Controllers\RaportController::class, 'generateAiSaran'])->name('raport.ai');
    Route::get('/raport/{id}/ai-catatan', [\App\Http\Controllers\RaportController::class, 'getAiWaliCatatan'])->name('raport.ai-catatan');
    Route::post('/raport/{id}/send-email', [\App\Http\Controllers\RaportController::class, 'sendEmail'])->name('raport.send-email');
    Route::get('/raport/{id}/export-pdf', [\App\Http\Controllers\RaportController::class, 'exportPdf'])->name('raport.export-pdf');

    // SPK Ranking Generation
    Route::post('/ranking/generate', [WaliKelasController::class, 'generateRanking'])->name('ranking.generate');
});

// Route untuk Siswa
Route::middleware(['auth', 'role:siswa'])->prefix('student')->name('siswa.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    
    // CBT
    Route::get('/cbt', [StudentDashboardController::class, 'cbtIndex'])->name('cbt.index');
    Route::get('/cbt/{id}', [StudentDashboardController::class, 'cbtShow'])->name('cbt.show');
    Route::get('/cbt/{id}/test', [StudentDashboardController::class, 'cbtTest'])->name('cbt.test');
    Route::post('/cbt/{id}/submit', [StudentDashboardController::class, 'cbtSubmit'])->name('cbt.submit');
    Route::get('/cbt/{id}/result', [StudentDashboardController::class, 'cbtResult'])->name('cbt.result');

    // Raport
    Route::get('/raport', [StudentDashboardController::class, 'raportIndex'])->name('raport.index');
    Route::get('/raport/{id}', [StudentDashboardController::class, 'raportShow'])->name('raport.show');
    Route::get('/raport/{id}/pdf', [StudentDashboardController::class, 'exportPdf'])->name('raport.export-pdf');

    // Jurnal
    Route::get('/jurnal', [StudentDashboardController::class, 'jurnalIndex'])->name('jurnal.index');
    Route::post('/jurnal', [StudentDashboardController::class, 'jurnalStore'])->name('jurnal.store');
});

// Route untuk Orang Tua
Route::middleware(['auth', 'role:orangtua'])->prefix('parent')->name('parent.')->group(function () {
    Route::get('/dashboard', [OrangTuaController::class, 'index'])->name('dashboard');
    Route::get('/nilai', [OrangTuaController::class, 'nilai'])->name('nilai.index');
    Route::get('/jurnal', [OrangTuaController::class, 'jurnal'])->name('jurnal.index');
    Route::get('/raport', [OrangTuaController::class, 'raport'])->name('raport.index');
    Route::get('/raport/{id}', [OrangTuaController::class, 'raportShow'])->name('raport.show');
    Route::get('/raport/{id}/pdf', [OrangTuaController::class, 'exportPdf'])->name('raport.export-pdf');
});
