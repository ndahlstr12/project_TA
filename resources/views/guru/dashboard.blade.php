@extends('layouts.admin')

@section('title', 'Guru Dashboard')
@section('page_title', 'Ringkasan Aktivitas')

@section('content')
<div class="space-y-12">
    
    <!-- Soft Header -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 bg-white/40 dark:bg-white/5 p-10 rounded-[2.5rem] border border-white dark:border-white/5 backdrop-blur-md">
        <div class="flex items-center gap-6">
            <div class="w-20 h-20 bg-accent-100 dark:bg-accent-600/20 rounded-3xl flex items-center justify-center text-accent-600">
                <i data-lucide="sparkles" class="w-10 h-10"></i>
            </div>
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">Halo, {{ explode(' ', Auth::user()->name)[0] }}!</h1>
                <p class="text-sm text-slate-400 font-medium mt-1">Anda memiliki {{ $schedules->count() }} agenda pengajaran terdaftar.</p>
            </div>
        </div>
        <div class="flex gap-4">
            <button class="px-6 py-4 bg-white dark:bg-surface-800 text-slate-600 dark:text-slate-300 text-xs font-bold rounded-2xl shadow-sm border border-slate-100 dark:border-white/5 transition-all hover:shadow-md hover:scale-105 uppercase tracking-widest flex items-center gap-2">
                <i data-lucide="download" class="w-4 h-4"></i>
                Rekap Nilai
            </button>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="card-soft p-8 group hover:scale-[1.02]">
            <div class="w-14 h-14 rounded-2xl bg-accent-50 dark:bg-accent-600/10 flex items-center justify-center text-accent-600 mb-6 group-hover:bg-accent-600 group-hover:text-white transition-all duration-500">
                <i data-lucide="book-marked" class="w-7 h-7"></i>
            </div>
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-2">Mata Pelajaran</p>
            <h3 class="text-3xl font-black tracking-tight text-slate-900 dark:text-white">{{ sprintf('%02d', $totalKelas) }} <span class="text-sm font-bold text-slate-300">Kelas</span></h3>
        </div>

        <div class="card-soft p-8 group hover:scale-[1.02]">
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 dark:bg-emerald-600/10 flex items-center justify-center text-emerald-600 mb-6 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500">
                <i data-lucide="users" class="w-7 h-7"></i>
            </div>
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-2">Total Siswa</p>
            <h3 class="text-3xl font-black tracking-tight text-slate-900 dark:text-white">{{ $totalSiswa }} <span class="text-sm font-bold text-slate-300">Siswa</span></h3>
        </div>

        <div class="card-soft p-8 group hover:scale-[1.02]">
            <div class="w-14 h-14 rounded-2xl bg-amber-50 dark:bg-amber-600/10 flex items-center justify-center text-amber-600 mb-6 group-hover:bg-amber-600 group-hover:text-white transition-all duration-500">
                <i data-lucide="timer" class="w-7 h-7"></i>
            </div>
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-2">Jadwal Aktif</p>
            <h3 class="text-3xl font-black tracking-tight text-slate-900 dark:text-white">{{ sprintf('%02d', $schedules->count()) }} <span class="text-sm font-bold text-slate-300">Sesi</span></h3>
        </div>
    </div>

    <!-- Main Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        <div class="card-soft overflow-hidden">
            <div class="p-8 border-b border-slate-50 dark:border-white/5 flex items-center justify-between bg-white/50 dark:bg-white/5">
                <h3 class="text-sm font-bold uppercase tracking-widest text-slate-900 dark:text-white">Aktivitas Mengajar</h3>
                <i data-lucide="calendar" class="w-4 h-4 text-slate-300"></i>
            </div>
            <div class="p-8 space-y-8">
                @forelse($schedules as $s)
                <div class="flex items-center justify-between group cursor-default">
                    <div class="flex items-center gap-6">
                        <span class="text-xs font-black text-slate-300 group-hover:text-accent-600 transition-colors">{{ $s->jam_mulai }}</span>
                        <div>
                            <p class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $s->mapel }}</p>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">{{ $s->kelas }} | {{ $s->hari }}</p>
                        </div>
                    </div>
                    <span class="px-3 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-tighter bg-slate-50 text-slate-400">
                        Jadwal
                    </span>
                </div>
                @empty
                <div class="text-center py-8">
                    <p class="text-sm text-slate-400">Belum ada jadwal mengajar.</p>
                </div>
                @endforelse
            </div>
        </div>

        <div class="space-y-10">
            <div class="card-soft p-10 bg-gradient-to-br from-accent-600 to-indigo-700 text-white border-none relative overflow-hidden">
                <div class="absolute -right-10 -bottom-10 opacity-10">
                    <i data-lucide="info" class="w-64 h-64"></i>
                </div>
                <div class="relative z-10">
                    <h4 class="text-xl font-extrabold tracking-tight mb-4">Informasi Penting</h4>
                    <p class="text-sm text-accent-50 leading-relaxed mb-8">Pemasukan nilai raport untuk penilaian tengah semester (PTS) akan ditutup pada hari Jumat ini pukul 23:59 WIB.</p>
                    <button class="px-6 py-3 bg-white text-accent-600 text-[10px] font-bold rounded-xl uppercase tracking-widest shadow-lg shadow-black/10 transition-all hover:scale-105">Lihat Pengumuman</button>
                </div>
            </div>

            <div class="card-soft p-8">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-slate-50 dark:bg-white/5 flex items-center justify-center text-slate-400">
                        <i data-lucide="help-circle" class="w-5 h-5"></i>
                    </div>
                    <h4 class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Pusat Bantuan</h4>
                </div>
                <p class="text-xs text-slate-500 leading-relaxed">Butuh bantuan terkait penggunaan sistem? Hubungi Tim IT Support SMKN 1 Sungailiat melalui Telegram.</p>
            </div>
        </div>
    </div>
</div>
@endsection
