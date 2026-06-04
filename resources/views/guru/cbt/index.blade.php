@extends('layouts.admin')

@section('title', 'Bank Soal CBT')
@section('page_title', 'Bank Soal CBT')

@section('content')
<div class="space-y-8">
    
    {{-- Header Card --}}
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 border border-slate-200 dark:border-white/5 flex flex-col md:flex-row items-center justify-between gap-6 shadow-sm">
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 rounded-2xl bg-blue-500/10 flex items-center justify-center text-blue-500">
                <i class="ti ti-device-laptop text-3xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-extrabold text-slate-900 dark:text-white">Bank Soal Digital</h3>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Buat Paket Ujian & Kelola Soal di satu tempat</p>
            </div>
        </div>
        <div class="flex gap-3 w-full md:w-auto">
            <a href="{{ route('shared.cbt.create') }}" class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-600/20 transition-all">
                <i class="ti ti-plus text-base"></i> Buat Paket Ujian
            </a>
        </div>
    </div>

    {{-- Daftar Paket Ujian (Gaya Kartu) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($ujians as $ujian)
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/5 rounded-3xl shadow-sm hover:border-blue-500/50 transition-all duration-500 group overflow-hidden flex flex-col h-full">
            <div class="p-8 flex-1">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-lg bg-blue-500/10 text-blue-500 text-[10px] font-black uppercase tracking-widest">{{ $ujian->mapel }}</span>
                        <span class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-white/5 text-slate-500 text-[10px] font-black uppercase tracking-widest">{{ $ujian->kelas }}</span>
                    </div>
                    <form action="{{ route('shared.cbt.toggle', $ujian->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-8 h-8 rounded-full flex items-center justify-center transition-all {{ $ujian->status ? 'bg-emerald-500/10 text-emerald-500' : 'bg-slate-100 text-slate-300' }}" title="{{ $ujian->status ? 'Ujian Aktif' : 'Ujian Nonaktif' }}">
                            <i class="ti ti-power text-base"></i>
                        </button>
                    </form>
                </div>

                <h4 class="text-lg font-extrabold text-slate-900 dark:text-white mb-2 leading-tight">{{ $ujian->nama_ujian }}</h4>
                
                <div class="grid grid-cols-2 gap-4 mt-6">
                    <div class="p-3 bg-slate-50 dark:bg-white/5 rounded-2xl">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Durasi</p>
                        <p class="text-xs font-bold text-slate-700 dark:text-slate-300 mt-1">{{ $ujian->durasi }} Menit</p>
                    </div>
                    <div class="p-3 bg-slate-50 dark:bg-white/5 rounded-2xl">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Jumlah Soal</p>
                        <p class="text-xs font-bold text-slate-700 dark:text-slate-300 mt-1">{{ $ujian->soals_count }} / {{ $ujian->jumlah_soal }}</p>
                    </div>
                </div>
            </div>

            <div class="px-8 py-4 bg-slate-50/50 dark:bg-white/5 border-t border-slate-100 dark:border-white/5 flex items-center justify-between">
                <span class="text-[10px] font-black uppercase tracking-widest @if($ujian->level == 'Sulit') text-rose-500 @elseif($ujian->level == 'Mudah') text-emerald-500 @else text-amber-500 @endif">
                    {{ $ujian->level }}
                </span>
                <a href="{{ route('shared.cbt.show', $ujian->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-700 transition-all">
                    <i class="ti ti-edit"></i> Kelola Soal
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/5 rounded-3xl flex flex-col items-center justify-center text-center opacity-50">
            <div class="w-20 h-20 rounded-3xl bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-400 mb-4">
                <i class="ti ti-database-off text-4xl"></i>
            </div>
            <h4 class="text-sm font-black uppercase tracking-widest">Belum Ada Paket Ujian</h4>
            <p class="text-xs font-medium mt-1">Mulai dengan membuat paket ujian pertama Anda</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
