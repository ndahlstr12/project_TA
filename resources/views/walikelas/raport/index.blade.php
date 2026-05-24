@extends('layouts.admin')

@section('title', 'Manajemen Raport')
@section('page_title', 'Daftar Raport Siswa')

@section('content')
<div class="space-y-8">
    
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 border border-slate-200 dark:border-white/5 flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 rounded-2xl bg-indigo-500/10 flex items-center justify-center text-indigo-500">
                <i class="ti ti-file-certificate text-3xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-extrabold text-slate-900 dark:text-white">Manajemen E-Raport</h3>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">{{ $semester }} | {{ $tahunAjaran }}</p>
            </div>
        </div>
        <div class="flex gap-3">
            <button class="px-6 py-3 bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-slate-400 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-white/10 transition-all">
                <i class="ti ti-download mr-1"></i> Export Semua
            </button>
        </div>
    </div>

    <div class="card-pro overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 dark:bg-white/5 border-b border-slate-100 dark:border-white/5 text-[10px] font-black text-slate-500 uppercase tracking-widest">
                        <th class="px-6 py-5">Siswa</th>
                        <th class="px-6 py-5">NISN</th>
                        <th class="px-6 py-5 text-center">Status Raport</th>
                        <th class="px-6 py-5 text-center">Saran AI</th>
                        <th class="px-6 py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-white/5">
                    @forelse($siswas as $siswa)
                    @php
                        $raport = $siswa->raports()->where('semester', $semester)->where('tahun_ajaran', $tahunAjaran)->first();
                    @endphp
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/5 transition-colors">
                        <td class="px-6 py-5">
                            <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $siswa->nama }}</span>
                        </td>
                        <td class="px-6 py-5 text-xs font-medium text-slate-500">
                            {{ $siswa->nisn }}
                        </td>
                        <td class="px-6 py-5 text-center">
                            @if($raport && $raport->status === 'selesai')
                                <span class="px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-600 text-[10px] font-black uppercase tracking-tighter">Selesai</span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-amber-500/10 text-amber-600 text-[10px] font-black uppercase tracking-tighter">Belum Lengkap</span>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-center">
                            @if($raport && $raport->saran_ai)
                                <i class="ti ti-circle-check text-emerald-500 text-lg" title="Sudah di-generate"></i>
                            @else
                                <i class="ti ti-circle-x text-slate-300 text-lg" title="Belum di-generate"></i>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-right">
                            <a href="{{ route('walikelas.raport.show', $siswa->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-700 transition-all">
                                <i class="ti ti-edit"></i> Kelola
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-slate-400 italic text-xs font-medium uppercase tracking-widest">Tidak ada data siswa</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
