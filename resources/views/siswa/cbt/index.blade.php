@extends('layouts.admin')

@section('title', 'CBT Siswa')
@section('page_title', 'Ujian Berbasis Komputer')

@section('content')
<div class="space-y-8">
    
    {{-- Header Section --}}
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 border border-slate-200 dark:border-white/5 flex flex-col md:flex-row items-center justify-between gap-6 shadow-sm">
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 rounded-2xl bg-rose-500/10 flex items-center justify-center text-rose-500">
                <i class="ti ti-device-laptop text-3xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-extrabold text-slate-900 dark:text-white">Portal Ujian CBT</h3>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Selesaikan ujian tepat waktu & kejujuran adalah utama</p>
            </div>
        </div>
    </div>

    {{-- Grid Ujian --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($ujians as $ujian)
            @php
                $hasil = $hasils->get($ujian->id);
            @endphp
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/5 rounded-3xl shadow-sm hover:border-rose-500/50 transition-all duration-500 group overflow-hidden flex flex-col h-full">
                <div class="p-8 flex-1">
                    <div class="flex items-center justify-between mb-6">
                        <span class="px-2.5 py-1 rounded-lg bg-rose-500/10 text-rose-500 text-[10px] font-black uppercase tracking-widest">
                            {{ $ujian->mapel }}
                        </span>
                        @if($hasil)
                            <span class="px-2.5 py-1 rounded-lg bg-emerald-500/10 text-emerald-500 text-[10px] font-black uppercase tracking-widest">
                                <i class="ti ti-check mr-1"></i> Selesai
                            </span>
                        @else
                            <span class="flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-amber-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                            </span>
                        @endif
                    </div>

                    <h4 class="text-lg font-extrabold text-slate-900 dark:text-white mb-2 leading-tight">{{ $ujian->nama_ujian }}</h4>
                    
                    <div class="space-y-3 mt-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 text-slate-400">
                                <i class="ti ti-clock-play text-sm"></i>
                                <span class="text-[10px] font-bold uppercase tracking-widest">Durasi</span>
                            </div>
                            <span class="text-[10px] font-black text-slate-700 dark:text-slate-300 uppercase tracking-widest">{{ $ujian->durasi }} Menit</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 text-slate-400">
                                <i class="ti ti-list-numbers text-sm"></i>
                                <span class="text-[10px] font-bold uppercase tracking-widest">Soal</span>
                            </div>
                            <span class="text-[10px] font-black text-slate-700 dark:text-slate-300 uppercase tracking-widest">{{ $ujian->jumlah_soal }} Butir</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 text-slate-400">
                                <i class="ti ti-bolt text-sm"></i>
                                <span class="text-[10px] font-bold uppercase tracking-widest">Kesulitan</span>
                            </div>
                            <span class="text-[10px] font-black uppercase tracking-widest @if($ujian->level == 'Sulit') text-rose-500 @elseif($ujian->level == 'Mudah') text-emerald-500 @else text-amber-500 @endif">
                                {{ $ujian->level }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="px-8 py-6 bg-slate-50/50 dark:bg-white/5 border-t border-slate-100 dark:border-white/5">
                    @if($hasil)
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</span>
                            <span class="text-[10px] font-black uppercase tracking-widest @if($hasil->status == 'Remedial') text-rose-500 @else text-emerald-500 @endif">
                                {{ $hasil->status }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Skor Akhir</span>
                            <span class="text-xl font-black text-rose-600">{{ round($hasil->skor) }}</span>
                        </div>
                        <a href="{{ route('siswa.cbt.result', $ujian->id) }}" class="w-full py-3.5 bg-slate-200 dark:bg-white/10 text-slate-600 dark:text-slate-300 text-[10px] font-black rounded-2xl uppercase tracking-widest flex items-center justify-center gap-2 hover:bg-slate-300 dark:hover:bg-white/20 transition-all">
                            <i class="ti ti-eye text-base"></i> Detail Hasil
                        </a>
                    @else
                        <a href="{{ route('siswa.cbt.show', $ujian->id) }}" class="w-full py-3.5 bg-rose-600 text-white text-[10px] font-black rounded-2xl uppercase tracking-widest flex items-center justify-center gap-2 hover:bg-rose-700 shadow-lg shadow-rose-600/20 transition-all group-hover:scale-[1.02]">
                            <i class="ti ti-player-play text-base fill-current"></i> Mulai Ujian
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full py-24 bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/5 rounded-[3rem] flex flex-col items-center justify-center text-center">
                <div class="w-24 h-24 bg-slate-50 dark:bg-white/5 rounded-full flex items-center justify-center text-slate-300 mb-6">
                    <i class="ti ti-clipboard-x text-5xl"></i>
                </div>
                <h3 class="text-xl font-extrabold text-slate-800 dark:text-white uppercase tracking-widest">Belum Ada Ujian</h3>
                <p class="text-xs font-bold text-slate-400 mt-2">Saat ini tidak ada ujian aktif untuk kelas Anda.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
