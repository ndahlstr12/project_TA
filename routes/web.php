<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\AuthController;

// Halaman utama dialihkan ke login jika belum masuk
Route::get('/', function () {
    return redirect()->route('login');
});

// Autentikasi
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\KriteriaController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\GuruController;

use App\Http\Controllers\GuruController as TeacherController;
use App\Http\Controllers\WaliKelasController;
use App\Http\Controllers\SiswaController as StudentDashboardController;
use App\Http\Controllers\OrangTuaController;

// Route untuk Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', UserController::class);
    Route::resource('siswas', SiswaController::class);
    Route::resource('gurus', GuruController::class);
    Route::resource('kriteria', KriteriaController::class);
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::put('/notifications', [NotificationController::class, 'update'])->name('notifications.update');
});

// Route untuk Guru
Route::middleware(['auth', 'role:guru'])->prefix('teacher')->name('guru.')->group(function () {
    Route::get('/dashboard', [TeacherController::class, 'index'])->name('dashboard');
});

// Route untuk Wali Kelas
Route::middleware(['auth', 'role:walikelas'])->prefix('class-teacher')->name('walikelas.')->group(function () {
    Route::get('/dashboard', [WaliKelasController::class, 'index'])->name('dashboard');
});

// Route untuk Siswa
Route::middleware(['auth', 'role:siswa'])->prefix('student')->name('siswa.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
});

// Route untuk Orang Tua
Route::middleware(['auth', 'role:orangtua'])->prefix('parent')->name('parent.')->group(function () {
    Route::get('/dashboard', [OrangTuaController::class, 'index'])->name('dashboard');
});
