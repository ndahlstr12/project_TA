@extends('layouts.admin')

@section('title', 'Manajemen Nilai')
@section('page_title', 'Input Nilai Mata Pelajaran')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-base pb-8">
        <div>
            <h1 class="text-3xl font-bold tracking-tighter text-slate-900 dark:text-white">Input Nilai</h1>
            <p class="text-sm text-neutral-500 mt-2">Pilih kelas dan mata pelajaran untuk menginput nilai siswa.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 mb-4 text-sm text-emerald-700 bg-emerald-100 rounded-lg dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800" role="alert">
        <span class="font-bold">Berhasil!</span> {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {{-- Section 1: Mata Pelajaran yang diampu sendiri --}}
        @foreach($guruMapels as $gm)
        <div class="card-pro p-6 hover:border-blue-500 transition-all group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-500">
                    <i data-lucide="book-open" class="w-6 h-6"></i>
                </div>
                <div class="flex flex-col items-end gap-1">
                    <span class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-tighter border bg-blue-500/5 text-blue-500 border-blue-500/10">
                        {{ $gm->kelas->nama_kelas }}
                    </span>
                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Anda Pengajar</span>
                </div>
            </div>
            <h3 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">{{ $gm->mapel->nama_mapel }}</h3>
            <div class="flex items-center gap-2 mt-1">
                <p class="text-xs text-neutral-500">Kelola nilai siswa |</p>
                <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 dark:bg-emerald-500/10 px-1.5 py-0.5 rounded">KKM: {{ $gm->mapel->kkm ?? 70 }}</span>
            </div>
            
            <div class="mt-8 space-y-3">
                <a href="{{ route('shared.nilai.create', ['guru_mapel_id' => $gm->id]) }}" class="w-full py-2.5 px-4 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-xl text-xs font-bold flex items-center justify-center gap-2 hover:opacity-90 transition-all shadow-sm">
                    <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                    Input Manual
                </a>
                
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" @click="$dispatch('open-import-modal', { id: {{ $gm->id }}, name: '{{ $gm->mapel->nama_mapel }} - {{ $gm->kelas->nama_kelas }}' })" class="py-2.5 px-4 bg-white dark:bg-white/5 border border-base text-slate-900 dark:text-white rounded-xl text-[10px] font-bold flex items-center justify-center gap-2 hover:bg-neutral-50 dark:hover:bg-white/10 transition-all">
                        <i data-lucide="file-up" class="w-3.5 h-3.5"></i>
                        Import
                    </button>
                    <button type="button" @click="$dispatch('open-kkm-modal', { id: {{ $gm->mapel->id }}, name: '{{ $gm->mapel->nama_mapel }}', kkm: {{ $gm->mapel->kkm ?? 70 }} })" class="py-2.5 px-4 bg-white dark:bg-white/5 border border-base text-slate-900 dark:text-white rounded-xl text-[10px] font-bold flex items-center justify-center gap-2 hover:bg-neutral-50 dark:hover:bg-white/10 transition-all">
                        <i data-lucide="settings-2" class="w-3.5 h-3.5"></i>
                        Atur KKM
                    </button>
                </div>
            </div>
        </div>
        @endforeach

        {{-- Section 2: Monitoring Nilai (Khusus Wali Kelas) --}}
        @if(isset($monitoringMapels) && $monitoringMapels->count() > 0)
            <div class="col-span-full pt-8 pb-4 border-b border-base mb-6">
                <h2 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tighter flex items-center gap-3">
                    <i data-lucide="monitor" class="w-6 h-6 text-indigo-500"></i>
                    Monitoring Nilai Kelas (Wali Kelas)
                </h2>
                <p class="text-xs text-neutral-500 mt-1">Pantau progres input nilai akhir dari guru mata pelajaran lain.</p>
            </div>

            @foreach($monitoringMapels as $monitor)
            <div class="card-pro p-6 hover:border-indigo-500 transition-all group border-l-4 border-l-indigo-500">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-500">
                        <i data-lucide="user-check" class="w-6 h-6"></i>
                    </div>
                    <span class="text-[8px] font-black text-indigo-500 uppercase tracking-widest bg-indigo-50 dark:bg-indigo-500/10 px-2 py-1 rounded-md">Guru Mapel Lain</span>
                </div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">{{ $monitor->mapel->nama_mapel }}</h3>
                <p class="text-xs text-neutral-500 mt-1">Pengajar: <span class="font-bold text-slate-700 dark:text-slate-300">{{ $monitor->guru->nama }}</span></p>
                
                <div class="mt-8">
                    <a href="{{ route('shared.nilai.monitoring', ['jadwal_id' => $monitor->id]) }}" class="w-full py-2.5 px-4 bg-indigo-600 text-white rounded-xl text-xs font-bold flex items-center justify-center gap-2 hover:bg-indigo-700 transition-all shadow-md shadow-indigo-600/20">
                        <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                        Lihat Nilai Akhir
                    </a>
                </div>
            </div>
            @endforeach
        @endif
        
        @if($guruMapels->isEmpty() && (!isset($monitoringMapels) || $monitoringMapels->isEmpty()))
        <div class="col-span-full py-20 text-center card-pro opacity-50">
            <div class="flex flex-col items-center gap-4">
                <i data-lucide="alert-circle" class="w-12 h-12 text-neutral-300"></i>
                <p class="text-xs font-bold uppercase tracking-widest text-neutral-400">Belum ada mata pelajaran yang dapat dikelola.</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Import Modal (Alpine.js) -->
    <div x-data="{ open: false, id: null, name: '' }" 
         @open-import-modal.window="open = true; id = $event.detail.id; name = $event.detail.name"
         x-show="open" 
         x-cloak
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
        <div @click.away="open = false" class="bg-white dark:bg-slate-900 rounded-3xl w-full max-w-md overflow-hidden shadow-2xl border border-base">
            <div class="p-6 border-b border-base flex justify-between items-center">
                <h3 class="text-lg font-bold">Import Nilai</h3>
                <button @click="open = false" class="text-neutral-400 hover:text-neutral-900 dark:hover:text-white">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form :action="'{{ route('shared.nilai.import') }}'" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="guru_mapel_id" :value="id">
                
                <div class="p-4 bg-blue-500/5 border border-blue-500/10 rounded-2xl">
                    <p class="text-[10px] font-black uppercase tracking-widest text-blue-500 mb-1">Mata Pelajaran</p>
                    <p class="text-sm font-bold" x-text="name"></p>
                </div>

                <div>
                    <label class="text-[10px] font-black uppercase tracking-widest text-neutral-400 block mb-2">Pilih File CSV</label>
                    <input type="file" name="file_excel" accept=".csv" required class="w-full text-xs text-neutral-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-blue-50 dark:file:bg-white/5 file:text-blue-700 dark:file:text-white hover:file:bg-blue-100 transition-all">
                    <p class="text-[10px] text-neutral-400 mt-2">* Format: NISN, Nama, Nilai</p>
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="button" @click="open = false" class="flex-1 py-3 text-xs font-bold border border-base rounded-xl hover:bg-neutral-50 dark:hover:bg-white/5 transition-all">Batal</button>
                    <button type="submit" class="flex-1 py-3 text-xs font-bold bg-blue-500 text-white rounded-xl hover:bg-blue-600 shadow-lg shadow-blue-500/20 transition-all">Upload & Import</button>
                </div>
            </form>
        </div>
    </div>

    <!-- KKM Modal (Alpine.js) -->
    <div x-data="{ open: false, id: null, name: '', kkm: 70 }" 
         @open-kkm-modal.window="open = true; id = $event.detail.id; name = $event.detail.name; kkm = $event.detail.kkm"
         x-show="open" 
         x-cloak
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
        <div @click.away="open = false" class="bg-white dark:bg-slate-900 rounded-3xl w-full max-w-md overflow-hidden shadow-2xl border border-base">
            <div class="p-6 border-b border-base flex justify-between items-center">
                <h3 class="text-lg font-bold">Atur KKM</h3>
                <button @click="open = false" class="text-neutral-400 hover:text-neutral-900 dark:hover:text-white">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form action="{{ route('shared.nilai.update-kkm') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="mapel_id" :value="id">
                
                <div class="p-4 bg-emerald-500/5 border border-emerald-500/10 rounded-2xl">
                    <p class="text-[10px] font-black uppercase tracking-widest text-emerald-500 mb-1">Mata Pelajaran</p>
                    <p class="text-sm font-bold" x-text="name"></p>
                </div>

                <div>
                    <label class="text-[10px] font-black uppercase tracking-widest text-neutral-400 block mb-2">Nilai KKM (0-100)</label>
                    <input type="number" name="kkm" :value="kkm" min="0" max="100" required class="w-full px-4 py-3 rounded-xl border border-base bg-neutral-50 dark:bg-white/5 focus:ring-2 focus:ring-emerald-500 outline-none transition-all text-sm font-bold">
                    <p class="text-[10px] text-neutral-400 mt-2">* KKM ini akan berlaku untuk semua kelas yang menggunakan mata pelajaran ini.</p>
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="button" @click="open = false" class="flex-1 py-3 text-xs font-bold border border-base rounded-xl hover:bg-neutral-50 dark:hover:bg-white/5 transition-all">Batal</button>
                    <button type="submit" class="flex-1 py-3 text-xs font-bold bg-emerald-500 text-white rounded-xl hover:bg-emerald-600 shadow-lg shadow-emerald-500/20 transition-all">Simpan KKM</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
