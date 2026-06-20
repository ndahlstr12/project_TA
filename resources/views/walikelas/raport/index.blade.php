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
            <a href="{{ route('walikelas.raport.attendance.bulk') }}" class="px-6 py-3 bg-emerald-500 hover:bg-emerald-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest transition-all shadow-lg shadow-emerald-500/25 flex items-center gap-2">
                <i class="ti ti-calendar-stats"></i> Rekap Kehadiran
            </a>
            <button class="px-6 py-3 bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-slate-400 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-white/10 transition-all">
                <i class="ti ti-download mr-1"></i> Export Semua
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($siswas as $siswa)
        @php
            $raport = $siswa->raports()->where('semester', $semester)->where('tahun_ajaran', $tahunAjaran)->first();
        @endphp
        <div class="card-pro p-6 flex flex-col justify-between hover:-translate-y-1 transition-all duration-300 group">
            <div class="space-y-4">
                <div class="flex items-start justify-between">
                    <div class="w-10 h-10 rounded-xl bg-indigo-500/10 text-indigo-500 flex items-center justify-center font-black text-sm uppercase">
                        {{ strtoupper(substr($siswa->nama, 0, 2)) }}
                    </div>
                    <div>
                        @if($raport && $raport->status === 'selesai')
                            <span class="px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-600 text-[9px] font-black uppercase tracking-widest">Selesai</span>
                        @else
                            <span class="px-3 py-1 rounded-full bg-amber-500/10 text-amber-600 text-[9px] font-black uppercase tracking-widest">Belum Lengkap</span>
                        @endif
                    </div>
                </div>

                <div>
                    <h4 class="text-sm font-black text-slate-900 dark:text-white group-hover:text-blue-500 transition-colors">{{ $siswa->nama }}</h4>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight mt-1">NIS: {{ $siswa->nis ?? '-' }} / NISN: {{ $siswa->nisn }}</p>
                </div>

                <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-white/5 rounded-xl border border-slate-100 dark:border-white/5">
                    <div class="flex items-center gap-2">
                        <i class="ti ti-brain text-indigo-500 text-sm"></i>
                        <span class="text-[9px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest">Rekomendasi AI</span>
                    </div>
                    <div>
                        @if($raport && ($raport->saran_ai || $raport->rekomendasi_ai))
                            <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/10 text-emerald-600 text-[8px] font-black uppercase tracking-wider">Ready</span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full bg-slate-200 dark:bg-white/10 text-slate-500 dark:text-slate-400 text-[8px] font-black uppercase tracking-wider">Empty</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100 dark:border-white/5 flex items-center justify-between gap-4">
                <div class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter leading-normal">
                    @if($raport)
                        Absen: S:{{ $raport->sakit }} I:{{ $raport->izin }} A:{{ $raport->alpa }}
                    @else
                        Absen: Belum diisi
                    @endif
                </div>
                <a href="{{ route('walikelas.raport.show', $siswa->id) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                    <i class="ti ti-edit"></i> Kelola
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full card-pro p-12 text-center text-slate-400 italic text-xs font-medium uppercase tracking-widest">
            Tidak ada data siswa
        </div>
        @endforelse
    </div>

</div>
@endsection
