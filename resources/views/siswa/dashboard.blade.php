@extends('layouts.admin')

@section('title', 'Siswa Dashboard')
@section('page_title', 'Ringkasan Akademik')

@section('content')
<div class="space-y-12">
    
    <!-- Soft Header -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 bg-white/40 dark:bg-white/5 p-10 rounded-[2.5rem] border border-white dark:border-white/5 backdrop-blur-md">
        <div class="flex items-center gap-6">
            <div class="w-20 h-20 bg-rose-100 dark:bg-rose-600/20 rounded-3xl flex items-center justify-center text-rose-600">
                <i data-lucide="sparkles" class="w-10 h-10"></i>
            </div>
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">Halo, {{ explode(' ', Auth::user()->name)[0] }}!</h1>
                <p class="text-sm text-slate-400 font-medium mt-1">Tetap semangat belajar, hari ini ada 4 mata pelajaran.</p>
            </div>
        </div>
        <div class="flex gap-4">
            <button class="px-6 py-4 bg-white dark:bg-surface-800 text-slate-600 dark:text-slate-300 text-xs font-bold rounded-2xl shadow-sm border border-slate-100 dark:border-white/5 transition-all hover:shadow-md hover:scale-105 uppercase tracking-widest flex items-center gap-2">
                <i data-lucide="printer" class="w-4 h-4"></i>
                Cetak Raport
            </button>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="card-soft p-8 group hover:scale-[1.02]">
            <div class="w-14 h-14 rounded-2xl bg-rose-50 dark:bg-rose-600/10 flex items-center justify-center text-rose-600 mb-6 group-hover:bg-rose-600 group-hover:text-white transition-all duration-500">
                <i data-lucide="clock" class="w-7 h-7"></i>
            </div>
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-2">Kehadiran</p>
            <h3 class="text-3xl font-black tracking-tight text-slate-900 dark:text-white">95% <span class="text-sm font-bold text-slate-300">Hadir</span></h3>
        </div>

        <div class="card-soft p-8 group hover:scale-[1.02]">
            <div class="w-14 h-14 rounded-2xl bg-sky-50 dark:bg-sky-600/10 flex items-center justify-center text-sky-600 mb-6 group-hover:bg-sky-600 group-hover:text-white transition-all duration-500">
                <i data-lucide="clipboard-check" class="w-7 h-7"></i>
            </div>
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-2">Tugas Aktif</p>
            <h3 class="text-3xl font-black tracking-tight text-slate-900 dark:text-white">02 <span class="text-sm font-bold text-slate-300">Tugas</span></h3>
        </div>

        <div class="card-soft p-8 group hover:scale-[1.02]">
            <div class="w-14 h-14 rounded-2xl bg-amber-50 dark:bg-amber-600/10 flex items-center justify-center text-amber-600 mb-6 group-hover:bg-amber-600 group-hover:text-white transition-all duration-500">
                <i data-lucide="award" class="w-7 h-7"></i>
            </div>
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-2">Predikat</p>
            <h3 class="text-3xl font-black tracking-tight text-slate-900 dark:text-white uppercase">A- <span class="text-sm font-bold text-slate-300">Baik</span></h3>
        </div>
    </div>

    <!-- Main Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <div class="lg:col-span-2 card-soft overflow-hidden">
            <div class="p-8 border-b border-slate-50 dark:border-white/5 flex items-center justify-between bg-white/50 dark:bg-white/5">
                <h3 class="text-sm font-bold uppercase tracking-widest text-slate-900 dark:text-white">Jadwal Pelajaran Besok</h3>
                <i data-lucide="calendar-days" class="w-4 h-4 text-slate-300"></i>
            </div>
            <div class="p-0">
                <div class="divide-y divide-slate-50 dark:divide-white/5">
                    @php
                        $classes = [
                            ['time' => '07:30 - 09:00', 'subject' => 'Matematika Terapan', 'teacher' => 'Drs. Sudirman'],
                            ['time' => '09:15 - 10:45', 'subject' => 'Bahasa Inggris', 'teacher' => 'Siti Aminah, M.Pd'],
                            ['time' => '11:00 - 12:30', 'subject' => 'Pemrograman Web', 'teacher' => 'Budi Raharjo, S.Kom'],
                        ];
                    @endphp
                    @foreach($classes as $c)
                    <div class="p-8 flex items-center justify-between hover:bg-slate-50/50 dark:hover:bg-white/5 transition-all group">
                        <div class="flex gap-10 items-center">
                            <span class="text-[11px] font-black text-slate-200 group-hover:text-rose-500 transition-colors w-24 tracking-tighter">{{ $c['time'] }}</span>
                            <div>
                                <p class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $c['subject'] }}</p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">{{ $c['teacher'] }}</p>
                            </div>
                        </div>
                        <i data-lucide="chevron-right" class="w-5 h-5 text-slate-200 group-hover:translate-x-1 transition-all"></i>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="space-y-10">
            <div class="card-soft p-10 bg-gradient-to-br from-rose-500 to-pink-600 text-white border-none relative overflow-hidden">
                <div class="absolute -right-6 -top-6 opacity-20">
                    <i data-lucide="bell" class="w-32 h-32"></i>
                </div>
                <div class="relative z-10">
                    <h4 class="text-xl font-extrabold tracking-tight mb-4">Pengingat</h4>
                    <p class="text-sm text-rose-50 leading-relaxed mb-8">Ujian Tengah Semester (UTS) akan dimulai dalam 5 hari lagi. Jangan lupa belajar!</p>
                    <button class="px-6 py-3 bg-white text-rose-600 text-[10px] font-bold rounded-xl uppercase tracking-widest shadow-lg shadow-black/10 transition-all hover:scale-105">Jadwal Ujian</button>
                </div>
            </div>

            <div class="card-soft p-8">
                <h4 class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-6">Wali Kelas Anda</h4>
                <div class="flex items-center gap-5">
                    <img src="https://ui-avatars.com/api/?name=Siti+Aminah&background=fdf2f8&color=db2777" class="w-12 h-12 rounded-2xl">
                    <div>
                        <p class="text-sm font-bold text-slate-800 dark:text-slate-200">Siti Aminah, M.Pd</p>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1">NIP. 19820312001</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
