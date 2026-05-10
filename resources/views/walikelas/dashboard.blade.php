@extends('layouts.admin')

@section('title', 'Wali Kelas Dashboard')
@section('page_title', 'Homeroom Overview')

@section('content')
<div class="space-y-10">
    
    <!-- Pro Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-base pb-8">
        <div>
            <h1 class="text-3xl font-bold tracking-tighter">Selamat Datang, {{ Auth::user()->name }}</h1>
            <p class="text-sm text-neutral-500 mt-2 uppercase tracking-widest font-bold">Wali Kelas Overview &bull; Manajemen Kelas Aktif</p>
        </div>
        <div class="flex gap-3">
            <button class="px-5 py-2 text-[10px] font-bold bg-neutral-950 dark:bg-white text-white dark:text-neutral-950 rounded-lg hover:opacity-90 transition-all flex items-center gap-2 uppercase tracking-widest">
                <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                Laporan Kelas
            </button>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="card-pro p-6 flex items-center gap-5 group hover:border-indigo-500 transition-colors">
            <div class="w-12 h-12 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-500">
                <i data-lucide="users" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest mb-1">Siswa di Kelas</p>
                <p class="text-2xl font-bold tracking-tighter text-neutral-900 dark:text-white">32 Orang</p>
            </div>
        </div>

        <div class="card-pro p-6 flex items-center gap-5 group hover:border-teal-500 transition-colors">
            <div class="w-12 h-12 rounded-xl bg-teal-500/10 flex items-center justify-center text-teal-500">
                <i data-lucide="check-circle-2" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest mb-1">Kehadiran Hari Ini</p>
                <p class="text-2xl font-bold tracking-tighter text-neutral-900 dark:text-white">98%</p>
            </div>
        </div>

        <div class="card-pro p-6 flex items-center gap-5 group hover:border-orange-500 transition-colors">
            <div class="w-12 h-12 rounded-xl bg-orange-500/10 flex items-center justify-center text-orange-500">
                <i data-lucide="trending-up" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest mb-1">Rata-rata Nilai</p>
                <p class="text-2xl font-bold tracking-tighter text-neutral-900 dark:text-white">85.5</p>
            </div>
        </div>
    </div>

    <!-- Content Area -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 card-pro overflow-hidden">
            <div class="p-6 border-b border-base bg-neutral-50/30 dark:bg-white/5 flex items-center justify-between">
                <h3 class="text-sm font-bold uppercase tracking-widest">Daftar Siswa Bermasalah</h3>
                <span class="px-2 py-1 bg-rose-500/10 text-rose-500 text-[10px] font-bold rounded uppercase">Perhatian</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-base text-[10px] font-bold text-neutral-400 uppercase tracking-widest">
                            <th class="px-6 py-4">Nama Siswa</th>
                            <th class="px-6 py-4">Masalah</th>
                            <th class="px-6 py-4 text-right">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-base">
                        <tr class="hover:bg-neutral-50 dark:hover:bg-white/5 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium">Bambang Wijaya</td>
                            <td class="px-6 py-4">
                                <span class="text-[10px] font-bold text-rose-500 uppercase tracking-tight">Alfa > 3 Hari</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button class="text-[10px] font-bold text-accent uppercase hover:underline">Panggil</button>
                            </td>
                        </tr>
                        <tr class="hover:bg-neutral-50 dark:hover:bg-white/5 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium">Citra Lestari</td>
                            <td class="px-6 py-4">
                                <span class="text-[10px] font-bold text-amber-500 uppercase tracking-tight">Nilai < KKM</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button class="text-[10px] font-bold text-accent uppercase hover:underline">Bimbingan</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-pro p-6">
            <h3 class="text-[10px] font-bold uppercase tracking-widest mb-6">Informasi Kelas</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center py-2 border-b border-base">
                    <span class="text-[11px] text-neutral-500 font-medium">Tahun Ajaran</span>
                    <span class="text-xs font-bold">2026/2027</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-base">
                    <span class="text-[11px] text-neutral-500 font-medium">Semester</span>
                    <span class="text-xs font-bold uppercase">Genap</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-base">
                    <span class="text-[11px] text-neutral-500 font-medium">Total Pertemuan</span>
                    <span class="text-xs font-bold uppercase">120 Hari</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

