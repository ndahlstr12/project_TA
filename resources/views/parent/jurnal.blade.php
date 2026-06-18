@extends('layouts.admin')

@section('title', 'Jurnal Perilaku Anak')
@section('page_title', 'Catatan Karakter & Aktivitas')

@section('content')
<div class="space-y-10">
    <!-- Header Section -->
    <div class="card-soft p-12 bg-white dark:bg-surface-800 border-none shadow-xl relative overflow-hidden">
        <div class="flex flex-col md:flex-row items-center gap-10 relative z-10">
            <div class="w-24 h-24 rounded-[2.5rem] bg-indigo-50 dark:bg-indigo-600/10 flex items-center justify-center text-indigo-600 shrink-0 shadow-inner">
                <i data-lucide="book-open-check" class="w-12 h-12"></i>
            </div>
            <div class="text-center md:text-left">
                <h2 class="text-3xl font-black text-slate-800 dark:text-white tracking-tighter">Jurnal Perilaku Siswa</h2>
                <p class="text-slate-500 mt-2 leading-relaxed max-w-2xl font-medium">
                    Pantau perkembangan karakter dan catatan aktivitas harian anak Anda secara real-time. Setiap catatan adalah langkah menuju pembentukan karakter yang lebih baik.
                </p>
            </div>
        </div>
        <div class="absolute right-0 top-0 w-80 h-80 bg-indigo-500/5 rounded-full -mr-40 -mt-40 blur-3xl"></div>
    </div>

    <!-- Stats Summary (Optional/Quick View) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="card-pro p-6 bg-emerald-500/5 border-emerald-500/10 flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-500 flex items-center justify-center text-white">
                <i data-lucide="trending-up" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Catatan Positif</p>
                <p class="text-xl font-black text-slate-800 dark:text-white">{{ $jurnals->where('tipe', 'Positif')->count() }} Kejadian</p>
            </div>
        </div>
        <div class="card-pro p-6 bg-rose-500/5 border-rose-500/10 flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-rose-500 flex items-center justify-center text-white">
                <i data-lucide="alert-circle" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-rose-600 uppercase tracking-widest">Catatan Negatif</p>
                <p class="text-xl font-black text-slate-800 dark:text-white">{{ $jurnals->where('tipe', 'Negatif')->count() }} Kejadian</p>
            </div>
        </div>
    </div>

    <!-- Main List -->
    <div class="space-y-6">
        @forelse($jurnals as $jurnal)
        <div class="card-soft overflow-hidden group hover:border-indigo-500/30 transition-all duration-500">
            <div class="flex flex-col lg:flex-row">
                <!-- Date Sidepane -->
                <div class="lg:w-48 p-8 flex flex-col items-center justify-center text-center border-b lg:border-b-0 lg:border-r border-slate-50 dark:border-white/5 bg-slate-50/30 dark:bg-white/5">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">
                        {{ $jurnal->tanggal->translatedFormat('M') }}
                    </span>
                    <span class="text-4xl font-black text-slate-800 dark:text-white tracking-tighter">
                        {{ $jurnal->tanggal->format('d') }}
                    </span>
                    <span class="text-[10px] font-bold text-slate-500 mt-1 uppercase tracking-widest">
                        {{ $jurnal->tanggal->format('Y') }}
                    </span>
                </div>

                <!-- Content Area -->
                <div class="flex-1 p-8 md:p-10 space-y-6">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1 rounded-full {{ $jurnal->tipe === 'Positif' ? 'bg-emerald-500/10 text-emerald-600' : 'bg-rose-500/10 text-rose-600' }} text-[10px] font-black uppercase tracking-widest border {{ $jurnal->tipe === 'Positif' ? 'border-emerald-500/20' : 'border-rose-500/20' }}">
                                <i class="ti ti-{{ $jurnal->tipe === 'Positif' ? 'circle-check' : 'alert-circle' }} mr-1"></i>
                                {{ $jurnal->tipe }}
                            </span>
                            @if($jurnal->poin != 0)
                            <span class="text-xs font-black {{ $jurnal->poin > 0 ? 'text-emerald-500' : 'text-rose-500' }}">
                                {{ $jurnal->poin > 0 ? '+' : '' }}{{ $jurnal->poin }} Poin
                            </span>
                            @endif
                        </div>
                        <div class="flex items-center gap-2 text-slate-400">
                            <i data-lucide="user-pen" class="w-3.5 h-3.5"></i>
                            <span class="text-[10px] font-bold uppercase tracking-widest">Dicatat: {{ $jurnal->guru->nama ?? 'Wali Kelas' }}</span>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="relative pl-6 border-l-2 border-slate-100 dark:border-white/10">
                            <p class="text-sm font-bold text-slate-400 uppercase tracking-widest text-[9px] mb-2">Kejadian / Perilaku:</p>
                            <p class="text-base font-medium text-slate-700 dark:text-slate-300 leading-relaxed">
                                {{ $jurnal->catatan }}
                            </p>
                        </div>

                        @if($jurnal->rekomendasi)
                        <div class="p-6 bg-indigo-500/5 rounded-3xl border border-indigo-500/10 relative overflow-hidden group/advice">
                            <div class="absolute right-0 bottom-0 opacity-10 group-hover/advice:scale-110 transition-transform duration-500 p-4">
                                <i data-lucide="sparkles" class="w-12 h-12 text-indigo-500"></i>
                            </div>
                            <div class="relative z-10">
                                <p class="text-[10px] font-black text-indigo-500 uppercase tracking-widest mb-3 flex items-center gap-2">
                                    <i data-lucide="lightbulb" class="w-3.5 h-3.5"></i> Saran Guru & Penanganan:
                                </p>
                                <p class="text-sm font-bold text-slate-800 dark:text-slate-100 leading-relaxed">
                                    {{ $jurnal->rekomendasi }}
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="py-24 flex flex-col items-center justify-center bg-white/50 dark:bg-white/5 rounded-[3rem] border-2 border-dashed border-slate-200 dark:border-white/10">
            <div class="w-24 h-24 bg-slate-50 dark:bg-white/5 rounded-[2rem] flex items-center justify-center text-slate-300 mb-6">
                <i data-lucide="clipboard-list" class="w-12 h-12"></i>
            </div>
            <h3 class="text-xl font-black text-slate-800 dark:text-white uppercase tracking-tighter">Jurnal Masih Kosong</h3>
            <p class="text-sm text-slate-500 mt-2 font-medium">Belum ada catatan perkembangan perilaku untuk anak Anda saat ini.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
