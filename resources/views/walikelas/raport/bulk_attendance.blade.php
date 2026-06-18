@extends('layouts.admin')

@section('title', 'Rekapitulasi Kehadiran Massal')
@section('page_title', 'Input Kehadiran Semester')

@section('content')
<div class="space-y-8">
    
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 border border-slate-200 dark:border-white/5 flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-500">
                <i class="ti ti-calendar-stats text-3xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-extrabold text-slate-900 dark:text-white">Rekapitulasi Kehadiran</h3>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Kelas {{ $kelas->nama_kelas }} | {{ $semester }}</p>
            </div>
        </div>
        <div class="flex gap-4">
            <button onclick="document.getElementById('importSection').classList.toggle('hidden')" class="px-6 py-3 bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-slate-400 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-white/10 transition-all flex items-center gap-2">
                <i class="ti ti-file-import"></i> Import CSV
            </button>
            <a href="{{ route('walikelas.raport.index') }}" class="px-6 py-3 bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-slate-400 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-white/10 transition-all">
                Kembali
            </a>
        </div>
    </div>

    <!-- Import Section (Hidden by default) -->
    <div id="importSection" class="hidden animate-in fade-in slide-in-from-top-4">
        <div class="card-pro p-8 bg-slate-50 dark:bg-white/5">
            <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-4">Import Data Kehadiran dari CSV</h4>
            <p class="text-xs text-slate-500 mb-6">Format CSV: <code class="bg-slate-200 dark:bg-white/10 px-2 py-1 rounded">NISN, Sakit, Izin, Alpa</code> (Gunakan baris pertama sebagai header)</p>
            
            <form action="{{ route('walikelas.raport.attendance.import') }}" method="POST" enctype="multipart/form-data" class="flex flex-col md:flex-row gap-4 items-end">
                @csrf
                <div class="flex-1 w-full">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Pilih File CSV</label>
                    <input type="file" name="file_csv" required class="w-full px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/5 rounded-xl text-xs outline-none focus:ring-2 focus:ring-blue-500/20">
                </div>
                <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/20">
                    Mulai Import
                </button>
            </form>
        </div>
    </div>

    <form action="{{ route('walikelas.raport.attendance.update-bulk') }}" method="POST">
        @csrf
        <div class="card-pro overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-white/5 border-b border-slate-100 dark:border-white/5 text-[10px] font-black text-slate-500 uppercase tracking-widest">
                            <th class="px-6 py-5">Nama Siswa</th>
                            <th class="px-6 py-5 text-center w-32">Sakit (S)</th>
                            <th class="px-6 py-5 text-center w-32">Izin (I)</th>
                            <th class="px-6 py-5 text-center w-32">Alpa (A)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-white/5">
                        @foreach($siswas as $siswa)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-white/5 transition-colors">
                            <td class="px-6 py-5">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $siswa->nama }}</span>
                                    <span class="text-[10px] text-slate-400 font-medium uppercase tracking-widest">NISN: {{ $siswa->nisn }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <input type="number" name="attendance[{{ $siswa->id }}][sakit]" value="{{ $siswa->raport->sakit ?? $siswa->auto_sakit ?? 0 }}" min="0" class="w-20 px-3 py-2 bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/5 rounded-lg text-xs font-bold text-center focus:ring-2 focus:ring-blue-500/20 outline-none">
                                @if(!isset($siswa->raport->sakit) && isset($siswa->auto_sakit))
                                    <div class="text-[8px] font-black text-blue-500 uppercase mt-1 tracking-tighter">Otomatis</div>
                                @endif
                            </td>
                            <td class="px-6 py-5 text-center">
                                <input type="number" name="attendance[{{ $siswa->id }}][izin]" value="{{ $siswa->raport->izin ?? $siswa->auto_izin ?? 0 }}" min="0" class="w-20 px-3 py-2 bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/5 rounded-lg text-xs font-bold text-center focus:ring-2 focus:ring-blue-500/20 outline-none">
                                @if(!isset($siswa->raport->izin) && isset($siswa->auto_izin))
                                    <div class="text-[8px] font-black text-blue-500 uppercase mt-1 tracking-tighter">Otomatis</div>
                                @endif
                            </td>
                            <td class="px-6 py-5 text-center">
                                <input type="number" name="attendance[{{ $siswa->id }}][alpa]" value="{{ $siswa->raport->alpa ?? $siswa->auto_alpa ?? 0 }}" min="0" class="w-20 px-3 py-2 bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/5 rounded-lg text-xs font-bold text-center focus:ring-2 focus:ring-blue-500/20 outline-none">
                                @if(!isset($siswa->raport->alpa) && isset($siswa->auto_alpa))
                                    <div class="text-[8px] font-black text-blue-500 uppercase mt-1 tracking-tighter">Otomatis</div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex justify-end pt-8">
            <button type="submit" class="px-12 py-4 bg-emerald-600 text-white rounded-2xl text-sm font-black uppercase tracking-widest hover:bg-emerald-700 transition-all shadow-xl shadow-emerald-500/25 flex items-center gap-3">
                <i class="ti ti-device-floppy text-xl"></i>
                Simpan Rekapitulasi Kehadiran
            </button>
        </div>
    </form>

</div>
@endsection
