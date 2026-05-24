@extends('layouts.admin')

@section('title', 'Manajemen Raport')
@section('page_title', 'Daftar Siswa Perwalian')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-base pb-8">
        <div>
            <h1 class="text-3xl font-bold tracking-tighter text-slate-900 dark:text-white">Manajemen Raport</h1>
            <p class="text-sm text-neutral-500 mt-2">Kelola nilai raport, kehadiran, dan saran AI untuk kelas Anda.</p>
        </div>
    </div>

    @if(session('error'))
    <div class="p-4 mb-4 text-sm text-rose-700 bg-rose-100 rounded-lg dark:bg-rose-900/30 dark:text-rose-400 border border-rose-200 dark:border-rose-800" role="alert">
        <span class="font-bold">Gagal!</span> {{ session('error') }}
    </div>
    @endif

    <div class="card-pro overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-neutral-50/30 dark:bg-white/5 border-b border-base text-[10px] font-bold text-neutral-400 uppercase tracking-widest">
                        <th class="px-8 py-4">Siswa</th>
                        <th class="px-8 py-4">NISN</th>
                        <th class="px-8 py-4">Semester</th>
                        <th class="px-8 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-base">
                    @forelse($siswas as $siswa)
                    <tr class="hover:bg-neutral-50/30 dark:hover:bg-white/5 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-neutral-100 dark:bg-white/5 border border-base flex items-center justify-center overflow-hidden shrink-0">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($siswa->nama) }}&background=f5f5f5&color=0a0a0a" class="w-full h-full grayscale">
                                </div>
                                <p class="text-sm font-bold text-neutral-800 dark:text-neutral-200 tracking-tight">{{ $siswa->nama }}</p>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-xs font-mono font-bold text-neutral-500">{{ $siswa->nisn }}</td>
                        <td class="px-8 py-5 text-xs font-bold text-neutral-600 dark:text-neutral-400">{{ $semester }} {{ $tahunAjaran }}</td>
                        <td class="px-8 py-5 text-right">
                            <a href="{{ route('walikelas.raport.show', $siswa->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500 text-white text-[10px] font-black uppercase tracking-widest rounded-lg hover:bg-blue-600 transition-all">
                                <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                                Kelola Raport
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-10 text-center text-xs text-neutral-400 italic">Belum ada data siswa di kelas ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
