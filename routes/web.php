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

use App\Http\Controllers\GuruController as TeacherController;
use App\Http\Controllers\WaliKelasController;
use App\Http\Controllers\SiswaController as StudentDashboardController;
use App\Http\Controllers\OrangTuaController;

// Halaman utama dialihkan ke login jika belum masuk
Route::get('/', function () {
    return redirect()->route('login');
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
});

// Route untuk Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Separate User Management
    Route::post('/users/import', [UserController::class, 'import'])->name('users.import');
    Route::get('/users/guru', [UserController::class, 'indexGuru'])->name('users.guru');
    Route::get('/users/siswa', [UserController::class, 'indexSiswa'])->name('users.siswa');
    Route::get('/users/orangtua', [UserController::class, 'indexOrangTua'])->name('users.orangtua');
    Route::resource('users', UserController::class);

    Route::resource('siswas', SiswaController::class);
    Route::resource('gurus', GuruController::class);
    Route::resource('kelas', KelasController::class);
    Route::resource('kriteria', KriteriaController::class);

    // Jadwal
    Route::resource('jadwal', JadwalController::class);
    Route::post('/jadwal/import', [JadwalController::class, 'import'])->name('jadwal.import');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::put('/notifications', [NotificationController::class, 'update'])->name('notifications.update');
    Route::post('/notifications/send-raport/{siswaId}', [NotificationController::class, 'sendRaport'])->name('notifications.send-raport');
    Route::post('/password-resets/{id}/resolve', [NotificationController::class, 'resolvePasswordReset'])->name('password-resets.resolve');
});

// Route untuk Guru
Route::middleware(['auth', 'role:guru'])->prefix('teacher')->name('guru.')->group(function () {
    Route::get('/dashboard', [TeacherController::class, 'index'])->name('dashboard');
    Route::resource('cbt', CbtController::class);
    Route::post('/cbt/import', [CbtController::class, 'import'])->name('cbt.import');
});

// Route untuk Wali Kelas
Route::middleware(['auth', 'role:walikelas'])->prefix('class-teacher')->name('walikelas.')->group(function () {
    Route::get('/dashboard', [WaliKelasController::class, 'index'])->name('dashboard');
    Route::resource('cbt', CbtController::class);
    Route::post('/cbt/import', [CbtController::class, 'import'])->name('cbt.import');
});

// Route untuk Siswa
Route::middleware(['auth', 'role:siswa'])->prefix('student')->name('siswa.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
});

// Route untuk Orang Tua
Route::middleware(['auth', 'role:orangtua'])->prefix('parent')->name('parent.')->group(function () {
    Route::get('/dashboard', [OrangTuaController::class, 'index'])->name('dashboard');
});
