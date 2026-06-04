@extends('layouts.admin')

@section('title', 'Jurnal Siswa')
@section('page_title', 'Catatan Aktivitas & Perilaku')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 dark:text-white">Riwayat Jurnal</h2>
            <p class="text-sm text-slate-500 mt-1">Lihat catatan perilaku dan tambahkan jurnal harian.</p>
        </div>
        <button onclick="document.getElementById('modal-jurnal').classList.remove('hidden')" class="px-6 py-4 bg-rose-600 text-white text-xs font-bold rounded-2xl shadow-lg shadow-rose-600/20 hover:bg-rose-700 transition-all flex items-center gap-2 uppercase tracking-widest">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Tambah Jurnal
        </button>
    </div>

    <div class="grid grid-cols-1 gap-6">
        @forelse($jurnals as $jurnal)
        <div class="card-soft p-8 group hover:border-rose-500/30 transition-all duration-300">
            <div class="flex items-start justify-between gap-6">
                <div class="flex gap-6">
                    <div class="w-14 h-14 rounded-2xl {{ $jurnal->tipe === 'Positif' ? 'bg-green-100 text-green-600' : 'bg-rose-100 text-rose-600' }} flex items-center justify-center shrink-0">
                        <i data-lucide="{{ $jurnal->tipe === 'Positif' ? 'trending-up' : 'trending-down' }}" class="w-7 h-7"></i>
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
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed max-w-2xl">
                            {{ $jurnal->catatan }}
                        </p>
                        <div class="flex items-center gap-2 mt-4">
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Poin:</p>
                            <span class="text-xs font-black {{ $jurnal->poin >= 0 ? 'text-green-600' : 'text-rose-600' }}">
                                {{ $jurnal->poin > 0 ? '+' : '' }}{{ $jurnal->poin }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1">Dicatat Oleh</p>
                    <p class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ $jurnal->guru->nama ?? 'Sistem/Siswa' }}</p>
                </div>
            </div>
        </div>
        @empty
        <div class="py-20 flex flex-col items-center justify-center bg-white dark:bg-white/5 rounded-[2.5rem] border border-dashed border-slate-200 dark:border-white/10">
            <div class="w-20 h-20 bg-slate-50 dark:bg-white/5 rounded-3xl flex items-center justify-center text-slate-300 mb-4">
                <i data-lucide="book-open" class="w-10 h-10"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800 dark:text-white">Jurnal Kosong</h3>
            <p class="text-sm text-slate-500 mt-1">Belum ada catatan aktivitas untuk Anda.</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Modal Tambah Jurnal -->
<div id="modal-jurnal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="this.parentElement.classList.add('hidden')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-lg p-6">
        <div class="bg-white dark:bg-surface-800 rounded-[2.5rem] shadow-2xl overflow-hidden border border-white/10">
            <div class="p-8 border-b border-slate-100 dark:border-white/5 flex items-center justify-between">
                <h3 class="text-xl font-bold text-slate-800 dark:text-white">Tambah Jurnal Harian</h3>
                <button onclick="document.getElementById('modal-jurnal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
            <form action="{{ route('siswa.jurnal.store') }}" method="POST" class="p-8 space-y-6">
                @csrf
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full px-5 py-4 bg-slate-50 dark:bg-white/5 rounded-2xl border-none text-sm focus:ring-2 focus:ring-rose-500 transition-all text-slate-800 dark:text-white" required>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Catatan Aktivitas</label>
                    <textarea name="catatan" rows="4" class="w-full px-5 py-4 bg-slate-50 dark:bg-white/5 rounded-2xl border-none text-sm focus:ring-2 focus:ring-rose-500 transition-all text-slate-800 dark:text-white placeholder:text-slate-400" placeholder="Apa yang Anda lakukan hari ini?" required></textarea>
                </div>
                <div class="pt-4 flex gap-4">
                    <button type="button" onclick="document.getElementById('modal-jurnal').classList.add('hidden')" class="flex-1 py-4 bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-slate-400 text-xs font-bold rounded-2xl uppercase tracking-widest hover:bg-slate-200 transition-all">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 py-4 bg-rose-600 text-white text-xs font-bold rounded-2xl shadow-lg shadow-rose-600/20 hover:bg-rose-700 transition-all uppercase tracking-widest">
                        Simpan Jurnal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
