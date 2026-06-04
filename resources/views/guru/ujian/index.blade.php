@extends('layouts.admin')

@section('title', 'Manajemen Ujian CBT')
@section('page_title', 'Daftar Ujian CBT')

@section('content')
<div class="space-y-8">
    
    {{-- Header Card --}}
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 border border-slate-200 dark:border-white/5 flex flex-col md:flex-row items-center justify-between gap-6 shadow-sm">
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 rounded-2xl bg-amber-500/10 flex items-center justify-center text-amber-500">
                <i class="ti ti-clock-play text-3xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-extrabold text-slate-900 dark:text-white">Pelaksanaan Ujian</h3>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Atur durasi, acak soal, dan tingkat kesulitan ujian</p>
            </div>
        </div>
        <div class="flex gap-3 w-full md:w-auto">
            <a href="{{ route('guru.ujian.create') }}" class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 px-6 py-3 bg-indigo-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-600/20 transition-all">
                <i class="ti ti-plus text-base"></i> Buat Ujian Baru
            </a>
        </div>
    </div>

    {{-- Grid Ujian --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($ujians as $ujian)
        <div class="card-pro group hover:border-indigo-500/50 transition-all duration-500 relative flex flex-col h-full overflow-hidden">
            {{-- Status Indicator --}}
            <div class="absolute top-4 right-4 z-10">
                @if($ujian->status)
                    <span class="flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                    </span>
                @else
                    <span class="inline-flex rounded-full h-3 w-3 bg-slate-300 dark:bg-slate-700"></span>
                @endif
            </div>

            <div class="p-8">
                <div class="flex items-center gap-3 mb-6">
                    <span class="px-2.5 py-1 rounded-lg bg-indigo-500/10 text-indigo-500 text-[10px] font-black uppercase tracking-widest">
                        {{ $ujian->mapel }}
                    </span>
                    <span class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-white/5 text-slate-500 text-[10px] font-black uppercase tracking-widest">
                        {{ $ujian->kelas }}
                    </span>
                </div>

                <h4 class="text-lg font-extrabold text-slate-900 dark:text-white mb-2 leading-tight">{{ $ujian->nama_ujian }}</h4>
                
                {{-- Detail Ujian --}}
                <div class="space-y-3 mt-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-slate-400">
                            <i class="ti ti-bolt text-sm"></i>
                            <span class="text-[10px] font-bold uppercase tracking-widest">Kesulitan</span>
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-widest @if($ujian->level == 'Sulit') text-rose-500 @elseif($ujian->level == 'Mudah') text-emerald-500 @else text-amber-500 @endif">
                            {{ $ujian->level }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-slate-400">
                            <i class="ti ti-hourglass-low text-sm"></i>
                            <span class="text-[10px] font-bold uppercase tracking-widest">Durasi</span>
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-700 dark:text-slate-300">{{ $ujian->durasi }} Menit</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-slate-400">
                            <i class="ti ti-list-numbers text-sm"></i>
                            <span class="text-[10px] font-bold uppercase tracking-widest">Soal</span>
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-700 dark:text-slate-300">{{ $ujian->jumlah_soal }} Butir</span>
                    </div>
                </div>

                {{-- Fitur Acak --}}
                <div class="flex items-center gap-4 mt-8 pt-6 border-t border-slate-100 dark:border-white/5">
                    <div class="flex items-center gap-2 @if($ujian->acak_soal) text-blue-500 @else text-slate-300 @endif" title="Acak Soal">
                        <i class="ti ti-arrows-shuffle-2 text-lg"></i>
                        <span class="text-[9px] font-black uppercase tracking-widest">Acak Soal</span>
                    </div>
                    <div class="flex items-center gap-2 @if($ujian->acak_jawaban) text-blue-500 @else text-slate-300 @endif" title="Acak Jawaban">
                        <i class="ti ti-list-details text-lg"></i>
                        <span class="text-[9px] font-black uppercase tracking-widest">Acak Jawaban</span>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="px-8 py-4 bg-slate-50 dark:bg-white/5 border-t border-slate-100 dark:border-white/5 flex items-center justify-between">
                <form action="{{ route('guru.ujian.toggle', $ujian->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="text-[10px] font-black uppercase tracking-widest {{ $ujian->status ? 'text-rose-500' : 'text-emerald-500' }}">
                        {{ $ujian->status ? 'Nonaktifkan' : 'Aktifkan' }}
                    </button>
                </form>
                <div class="flex items-center gap-3">
                    <a href="{{ route('guru.ujian.edit', $ujian->id) }}" class="text-slate-400 hover:text-indigo-500 transition-colors">
                        <i class="ti ti-edit text-lg"></i>
                    </a>
                    <button class="text-slate-400 hover:text-rose-500 transition-colors">
                        <i class="ti ti-trash text-lg"></i>
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 card-pro flex flex-col items-center justify-center text-center opacity-50">
            <div class="w-20 h-20 rounded-3xl bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-400 mb-4">
                <i class="ti ti-calendar-off text-4xl"></i>
            </div>
            <h4 class="text-sm font-black uppercase tracking-widest">Belum Ada Ujian</h4>
            <p class="text-xs font-medium mt-1">Silakan buat sesi ujian pertama Anda</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
