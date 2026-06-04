@extends('layouts.admin')

@section('title', 'Jurnal Perilaku Anak')
@section('page_title', 'Catatan Karakter & Aktivitas')

@section('content')
<div class="space-y-8">
    <div class="card-soft p-10 bg-white dark:bg-surface-800 border-none shadow-xl relative overflow-hidden">
        <div class="flex flex-col md:flex-row items-center gap-8 relative z-10">
            <div class="w-24 h-24 rounded-3xl bg-rose-50 dark:bg-rose-600/10 flex items-center justify-center text-rose-600 shrink-0">
                <i data-lucide="notebook" class="w-12 h-12"></i>
            </div>
            <div class="text-center md:text-left">
                <h2 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">Jurnal Perilaku Siswa</h2>
                <p class="text-slate-500 mt-2 leading-relaxed max-w-xl">
                    Kumpulan catatan perilaku harian anak Anda yang dicatat oleh wali kelas maupun guru mata pelajaran.
                </p>
            </div>
        </div>
        <div class="absolute right-0 top-0 w-64 h-64 bg-rose-500/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
    </div>

    <div class="grid grid-cols-1 gap-6">
        @forelse($jurnals as $jurnal)
        <div class="card-soft p-8 group hover:border-rose-500/30 transition-all duration-300">
            <div class="flex items-start justify-between gap-6">
                <div class="flex gap-6">
                    <div class="w-14 h-14 rounded-2xl {{ $jurnal->tipe === 'Positif' ? 'bg-green-100 text-green-600' : 'bg-rose-100 text-rose-600' }} flex items-center justify-center shrink-0">
                        <i data-lucide="{{ $jurnal->tipe === 'Positif' ? 'award' : 'alert-circle' }}" class="w-7 h-7"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <h4 class="text-sm font-bold text-slate-800 dark:text-white">
                                {{ $jurnal->tanggal->format('d F Y') }}
                            </h4>
                            <span class="px-2 py-0.5 {{ $jurnal->tipe === 'Positif' ? 'bg-green-100 text-green-600' : 'bg-rose-100 text-rose-600' }} text-[9px] font-black rounded-md uppercase tracking-tighter">
                                {{ $jurnal->tipe }}
                            </span>
                        </div>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed max-w-3xl">
                            {{ $jurnal->catatan }}
                        </p>
                    </div>
                </div>
                <div class="text-right hidden sm:block">
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1">Dicatat Oleh</p>
                    <p class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ $jurnal->guru->nama ?? 'Sistem' }}</p>
                    @if($jurnal->poin != 0)
                    <div class="flex items-center justify-end gap-2 mt-2">
                        <span class="text-xs font-black {{ $jurnal->poin > 0 ? 'text-green-600' : 'text-rose-600' }}">
                            {{ $jurnal->poin > 0 ? '+' : '' }}{{ $jurnal->poin }} Poin
                        </span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="py-20 flex flex-col items-center justify-center bg-white dark:bg-white/5 rounded-[2.5rem] border border-dashed border-slate-200 dark:border-white/10">
            <div class="w-20 h-20 bg-slate-50 dark:bg-white/5 rounded-3xl flex items-center justify-center text-slate-300 mb-4">
                <i data-lucide="notebook-pen" class="w-10 h-10"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800 dark:text-white">Jurnal Kosong</h3>
            <p class="text-sm text-slate-500 mt-1">Belum ada catatan perilaku untuk anak Anda.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
