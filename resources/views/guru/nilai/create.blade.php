@extends('layouts.admin')

@section('title', 'Input Nilai Manual')
@section('page_title', 'Input Nilai: ' . $guruMapel->mapel->nama_mapel)

@section('content')
<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-base pb-8">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <a href="{{ route('shared.nilai.index') }}" class="p-1.5 rounded-lg border border-base hover:bg-neutral-50 dark:hover:bg-white/5 transition-all">
                    <i data-lucide="chevron-left" class="w-4 h-4 text-neutral-400"></i>
                </a>
                <span class="text-[10px] font-black uppercase tracking-widest text-blue-500">Input Nilai Manual</span>
            </div>
            <h1 class="text-3xl font-bold tracking-tighter text-slate-900 dark:text-white">{{ $guruMapel->mapel->nama_mapel }}</h1>
            <p class="text-sm text-neutral-500 mt-2">Kelas {{ $guruMapel->kelas->nama_kelas }} | Semester {{ $semester }} | TA {{ $tahunAjaran }}</p>
        </div>
    </div>

    <form action="{{ route('shared.nilai.store') }}" method="POST">
        @csrf
        <input type="hidden" name="guru_mapel_id" value="{{ $guruMapel->id }}">

        <div class="card-pro overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-neutral-50/30 dark:bg-white/5 border-b border-base text-[10px] font-bold text-neutral-400 uppercase tracking-widest">
                            <th class="px-8 py-4 w-16">No</th>
                            <th class="px-8 py-4">Siswa</th>
                            <th class="px-8 py-4">NISN</th>
                            <th class="px-8 py-4 text-center">Nilai Angka (0-100)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-base">
                        @foreach($siswas as $index => $siswa)
                        <tr class="hover:bg-neutral-50/30 dark:hover:bg-white/5 transition-colors">
                            <td class="px-8 py-4 text-xs font-bold text-neutral-400">{{ $index + 1 }}</td>
                            <td class="px-8 py-4">
                                <p class="text-sm font-bold text-neutral-800 dark:text-neutral-200 tracking-tight">{{ $siswa->nama }}</p>
                            </td>
                            <td class="px-8 py-4 text-xs font-mono font-bold text-neutral-400">{{ $siswa->nisn }}</td>
                            <td class="px-8 py-4 flex justify-center">
                                <div class="w-24">
                                    <input type="number" 
                                           name="nilai[{{ $siswa->id }}]" 
                                           step="0.01" 
                                           min="0" 
                                           max="100" 
                                           value="{{ $existingNilai->has($siswa->id) ? $existingNilai[$siswa->id]->nilai_angka : '' }}"
                                           placeholder="..."
                                           class="w-full bg-white dark:bg-white/5 border border-base rounded-xl px-4 py-2 text-center text-sm font-bold focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none">
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-8 flex justify-end gap-3">
            <a href="{{ route('shared.nilai.index') }}" class="px-6 py-3 text-xs font-bold border border-base rounded-xl hover:bg-neutral-50 dark:hover:bg-white/5 transition-all">Batal</a>
            <button type="submit" class="px-8 py-3 bg-blue-500 text-white rounded-xl text-xs font-bold shadow-lg shadow-blue-500/20 hover:bg-blue-600 transition-all">Simpan Nilai</button>
        </div>
    </form>
</div>
@endsection
