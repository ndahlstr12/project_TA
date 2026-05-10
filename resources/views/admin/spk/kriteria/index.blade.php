@extends('layouts.admin')

@section('title', 'Kriteria SPK')
@section('page_title', 'Kriteria Keputusan')

@section('content')
<div class="space-y-8">
    
    <!-- Pro Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-base pb-8">
        <div>
            <h1 class="text-3xl font-bold tracking-tighter">Logika Keputusan</h1>
            <p class="text-sm text-neutral-500 mt-2">Konfigurasikan bobot kriteria untuk sistem evaluasi SAW (Simple Additive Weighting).</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="flex flex-col items-end px-4 border-r border-base">
                <span class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest">Pemanfaatan Bobot</span>
                <span class="text-lg font-black {{ ($totalBobot == 1 || $totalBobot == 100) ? 'text-accent' : 'text-rose-500' }}">{{ $totalBobot * 100 }}%</span>
            </div>
            <a href="{{ route('admin.kriteria.create') }}" class="px-5 py-2 text-xs font-bold bg-neutral-950 dark:bg-white text-white dark:text-neutral-950 rounded-lg hover:opacity-90 transition-all flex items-center gap-2">
                <i data-lucide="plus-circle" class="w-3.5 h-3.5"></i>
                Tambah Kriteria
            </a>
        </div>
    </div>

    <!-- Alert Messaging -->
    @if(session('success'))
    <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-emerald-500 flex items-center justify-center text-white shrink-0">
            <i data-lucide="check" class="w-4 h-4"></i>
        </div>
        <p class="text-xs font-bold text-emerald-600 tracking-tight">{{ session('success') }}</p>
    </div>
    @endif

    @if($totalBobot != 1 && $totalBobot != 100)
    <div class="p-4 bg-amber-500/10 border border-amber-500/20 rounded-xl flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-amber-500 flex items-center justify-center text-white shrink-0">
            <i data-lucide="alert-triangle" class="w-4 h-4"></i>
        </div>
        <p class="text-xs font-bold text-amber-600 tracking-tight italic">Peringatan: Total bobot harus 100% agar perhitungan valid. Saat ini: {{ $totalBobot * 100 }}%</p>
    </div>
    @endif

    <!-- Criteria Registry -->
    <div class="card-pro overflow-hidden">
        <div class="p-6 border-b border-base bg-neutral-50/30 dark:bg-white/5 flex items-center justify-between">
            <h3 class="text-sm font-bold uppercase tracking-widest text-neutral-400">Konfigurasi Parameter</h3>
            <div class="flex items-center gap-4 text-[10px] font-bold text-neutral-400">
                <span class="flex items-center gap-1.5"><div class="w-2 h-2 rounded-full bg-emerald-500"></div> Benefit</span>
                <span class="flex items-center gap-1.5"><div class="w-2 h-2 rounded-full bg-rose-500"></div> Cost</span>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-neutral-50/30 dark:bg-white/5 border-b border-base text-[10px] font-bold text-neutral-400 uppercase tracking-widest">
                        <th class="px-8 py-4">Kode</th>
                        <th class="px-8 py-4">Nama Atribut</th>
                        <th class="px-8 py-4">Alokasi Bobot</th>
                        <th class="px-8 py-4 text-center">Jenis</th>
                        <th class="px-8 py-4 text-right">Aksi Sistem</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-base">
                    @foreach($kriterias as $k)
                    <tr class="hover:bg-neutral-50/30 dark:hover:bg-white/5 transition-colors group">
                        <td class="px-8 py-5">
                            <span class="text-xs font-mono font-black text-neutral-900 dark:text-white px-2.5 py-1 bg-neutral-100 dark:bg-white/10 rounded-md border border-base">
                                {{ $k->kode }}
                            </span>
                        </td>
                        <td class="px-8 py-5">
                            <span class="text-sm font-bold text-neutral-800 dark:text-neutral-200 tracking-tight">{{ $k->nama }}</span>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="flex-1 max-w-[120px] h-1.5 bg-neutral-100 dark:bg-white/5 rounded-full overflow-hidden border border-base">
                                    <div class="h-full bg-accent rounded-full transition-all duration-700" style="width: {{ $k->bobot * 100 }}%"></div>
                                </div>
                                <span class="text-xs font-black text-neutral-700 dark:text-neutral-300">{{ $k->bobot * 100 }}%</span>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[9px] font-black uppercase tracking-tighter border
                                {{ $k->jenis == 'benefit' ? 'bg-emerald-500/5 text-emerald-500 border-emerald-500/10' : 'bg-rose-500/5 text-rose-500 border-rose-500/10' }}">
                                {{ $k->jenis }}
                            </span>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.kriteria.edit', $k->id) }}" class="p-2 text-neutral-400 hover:text-neutral-900 dark:hover:text-white hover:bg-neutral-100 dark:hover:bg-white/5 rounded-lg transition-colors">
                                    <i data-lucide="settings-2" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('admin.kriteria.destroy', $k->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kriteria ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-rose-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-500/10 rounded-lg transition-colors">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Registry Footer -->
        <div class="p-6 border-t border-base bg-neutral-50/30 dark:bg-white/5 flex items-center justify-between">
            <p class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest">Parameter Logika &bull; Mesin Keputusan Aktif</p>
        </div>
    </div>
</div>
@endsection
