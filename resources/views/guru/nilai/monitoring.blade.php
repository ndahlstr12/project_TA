@extends('layouts.admin')

@section('title', 'Monitoring Nilai')
@section('page_title', 'Monitoring Nilai: ' . $jadwal->mapel->nama_mapel)

@section('content')
<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-base pb-8">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <a href="{{ route('shared.nilai.index') }}" class="p-1.5 rounded-lg border border-base hover:bg-neutral-50 dark:hover:bg-white/5 transition-all">
                    <i data-lucide="chevron-left" class="w-4 h-4 text-neutral-400"></i>
                </a>
                <span class="text-[10px] font-black uppercase tracking-widest text-blue-500">Monitoring Nilai Akhir</span>
            </div>
            <h1 class="text-3xl font-bold tracking-tighter text-slate-900 dark:text-white">{{ $jadwal->mapel->nama_mapel }}</h1>
            <p class="text-sm text-neutral-500 mt-2">Kelas {{ $jadwal->kelas->nama_kelas }} | Guru: {{ $jadwal->guru->nama }}</p>
            <p class="text-[10px] font-bold text-neutral-400 mt-1 uppercase tracking-widest">Semester {{ $semester }} | TA {{ $tahunAjaran }}</p>
        </div>
    </div>

    <div class="card-pro overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-neutral-50/30 dark:bg-white/5 border-b border-base text-[10px] font-bold text-neutral-400 uppercase tracking-widest">
                        <th class="px-8 py-4 w-16">No</th>
                        <th class="px-8 py-4 w-1/4">Siswa</th>
                        <th class="px-8 py-4">NISN</th>
                        <th class="px-8 py-4 text-center">Nilai Angka</th>
                        <th class="px-8 py-4">Capaian Kompetensi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-base">
                    @forelse($siswas as $index => $siswa)
                    <tr class="hover:bg-neutral-50/30 dark:hover:bg-white/5 transition-colors">
                        <td class="px-8 py-4 text-xs font-bold text-neutral-400">{{ $index + 1 }}</td>
                        <td class="px-8 py-4">
                            <p class="text-sm font-bold text-neutral-800 dark:text-neutral-200 tracking-tight">{{ $siswa->nama }}</p>
                        </td>
                        <td class="px-8 py-4 text-xs font-mono font-bold text-neutral-400">{{ $siswa->nisn }}</td>
                        <td class="px-8 py-4 text-center">
                            @if($nilais->has($siswa->id))
                                <span class="px-4 py-1.5 rounded-lg bg-blue-500/10 text-blue-600 text-sm font-black">
                                    {{ $nilais[$siswa->id]->nilai_angka }}
                                </span>
                            @else
                                <span class="text-xs font-bold text-rose-500 italic">Belum Diisi</span>
                            @endif
                        </td>
                        <td class="px-8 py-4">
                            <p class="text-xs text-neutral-600 dark:text-neutral-400 leading-relaxed italic">
                                {{ $nilais->has($siswa->id) ? ($nilais[$siswa->id]->capaian_kompetensi ?? '-') : 'Menunggu input guru mapel...' }}
                            </p>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-12 text-center text-neutral-400 italic text-sm">Tidak ada data siswa.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-8 flex justify-end">
        <a href="{{ route('shared.nilai.index') }}" class="px-8 py-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-xl text-xs font-bold shadow-lg transition-all">Kembali</a>
    </div>
</div>
@endsection
