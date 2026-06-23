@extends('layouts.admin')

@section('title', 'Detail Raport')
@section('page_title', 'Raport: ' . $siswa->nama)

@section('content')
<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-base pb-8">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <a href="{{ route('walikelas.raport.index') }}" class="p-1.5 rounded-lg border border-base hover:bg-neutral-50 dark:hover:bg-white/5 transition-all">
                    <i data-lucide="chevron-left" class="w-4 h-4 text-neutral-400"></i>
                </a>
                <span class="text-[10px] font-black uppercase tracking-widest text-blue-500">Detail Raport Siswa</span>
            </div>
            <h1 class="text-3xl font-bold tracking-tighter text-slate-900 dark:text-white">{{ $siswa->nama }}</h1>
            <p class="text-sm text-neutral-500 mt-2">NISN: {{ $siswa->nisn }} | Kelas: {{ $siswa->kelas }}</p>
        </div>
        
        <form action="{{ route('walikelas.raport.ai', $siswa->id) }}" method="POST">
            @csrf
            <button type="submit" class="px-4 py-2 bg-emerald-500 text-white text-xs font-bold rounded-lg flex items-center gap-2 hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-500/20">
                <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                Generate Saran AI
            </button>
        </form>
    </div>

    @if(session('success'))
    <div class="p-4 text-sm text-emerald-700 bg-emerald-100 rounded-lg dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800" role="alert">
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left: Grades Table -->
        <div class="lg:col-span-2 space-y-6">
            <div class="card-pro overflow-hidden">
                <div class="p-4 border-b border-base bg-neutral-50/30 dark:bg-white/5">
                    <h3 class="text-[10px] font-black uppercase tracking-widest text-neutral-400">Nilai Akademik</h3>
                </div>
                <div class="overflow-x-auto">
                <table class="w-full text-left min-w-[400px]">
                    <thead>
                        <tr class="bg-neutral-50/30 dark:bg-white/5 border-b border-base text-[10px] font-bold text-neutral-400 uppercase tracking-widest">
                            <th class="px-6 py-3">Mata Pelajaran</th>
                            <th class="px-6 py-3 text-center">Nilai</th>
                            <th class="px-6 py-3 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-base">
                        @forelse($nilais as $n)
                        <tr class="hover:bg-neutral-50/30 transition-colors">
                            <td class="px-6 py-4 text-sm font-bold">{{ $n->mapel }}</td>
                            <td class="px-6 py-4 text-center font-mono font-bold">{{ $n->nilai_angka }}</td>
                            <td class="px-6 py-4 text-right">
                                <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase {{ $n->nilai_angka >= 75 ? 'bg-emerald-500/10 text-emerald-500' : 'bg-rose-500/10 text-rose-500' }}">
                                    {{ $n->nilai_angka >= 75 ? 'Tuntas' : 'Remedial' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-10 text-center text-xs text-neutral-400 italic">Belum ada nilai yang diinput.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>

            <div class="card-pro p-6 space-y-4">
                <h3 class="text-[10px] font-black uppercase tracking-widest text-neutral-400">Jurnal Perilaku (Karakter)</h3>
                <div class="space-y-3">
                    @forelse($jurnals as $j)
                    <div class="p-4 rounded-2xl border border-base {{ $j->tipe == 'Positif' ? 'bg-emerald-500/5 border-emerald-500/10' : 'bg-rose-500/5 border-rose-500/10' }}">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-[9px] font-black uppercase tracking-widest {{ $j->tipe == 'Positif' ? 'text-emerald-500' : 'text-rose-500' }}">{{ $j->tipe }} ({{ $j->poin }} Poin)</span>
                            <span class="text-[9px] font-bold text-neutral-400">{{ \Carbon\Carbon::parse($j->tanggal)->translatedFormat('d M Y') }}</span>
                        </div>
                        <p class="text-xs text-neutral-700 dark:text-neutral-300 font-medium">{{ $j->catatan }}</p>
                    </div>
                    @empty
                    <p class="text-xs text-neutral-400 italic">Tidak ada catatan perilaku.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right: Attendance & Notes -->
        <div class="space-y-6">
            <form action="{{ route('walikelas.raport.update', $siswa->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="card-pro p-6 space-y-4">
                    <h3 class="text-[10px] font-black uppercase tracking-widest text-neutral-400">Ketidakhadiran</h3>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="text-[9px] font-black uppercase tracking-widest text-neutral-400 block mb-1">Sakit</label>
                            <input type="number" name="sakit" value="{{ $raport->sakit ?? $kehadiran->where('status', 'Sakit')->count() }}" class="w-full bg-slate-50 dark:bg-white/5 border border-base rounded-xl px-3 py-2 text-sm font-bold outline-none focus:ring-2 focus:ring-blue-500/20">
                        </div>
                        <div>
                            <label class="text-[9px] font-black uppercase tracking-widest text-neutral-400 block mb-1">Izin</label>
                            <input type="number" name="izin" value="{{ $raport->izin ?? $kehadiran->where('status', 'Izin')->count() }}" class="w-full bg-slate-50 dark:bg-white/5 border border-base rounded-xl px-3 py-2 text-sm font-bold outline-none focus:ring-2 focus:ring-blue-500/20">
                        </div>
                        <div>
                            <label class="text-[9px] font-black uppercase tracking-widest text-neutral-400 block mb-1">Alpa</label>
                            <input type="number" name="alpa" value="{{ $raport->alpa ?? $kehadiran->where('status', 'Alpa')->count() }}" class="w-full bg-slate-50 dark:bg-white/5 border border-base rounded-xl px-3 py-2 text-sm font-bold outline-none focus:ring-2 focus:ring-blue-500/20">
                        </div>
                    </div>
                </div>

                <div class="card-pro p-6 space-y-4">
                    <h3 class="text-[10px] font-black uppercase tracking-widest text-neutral-400">Saran Generative AI</h3>
                    <div class="p-4 bg-emerald-500/5 border border-emerald-500/10 rounded-2xl min-h-[100px]">
                        @if($raport && $raport->saran_ai)
                        <p class="text-xs text-emerald-800 dark:text-emerald-400 font-medium italic leading-relaxed">"{{ $raport->saran_ai }}"</p>
                        @else
                        <p class="text-xs text-neutral-400 italic">Klik tombol "Generate Saran AI" di atas untuk mendapatkan saran otomatis.</p>
                        @endif
                    </div>
                </div>

                <div class="card-pro p-6 space-y-4">
                    <h3 class="text-[10px] font-black uppercase tracking-widest text-neutral-400">Catatan Wali Kelas</h3>
                    <textarea name="catatan_wali" rows="4" class="w-full bg-slate-50 dark:bg-white/5 border border-base rounded-2xl px-4 py-3 text-xs font-medium outline-none focus:ring-2 focus:ring-blue-500/20 transition-all" placeholder="Tulis catatan perkembangan siswa di sini...">{{ $raport->catatan_wali ?? '' }}</textarea>
                </div>

                <button type="submit" class="w-full py-4 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-2xl text-xs font-black uppercase tracking-widest shadow-xl hover:opacity-90 transition-all">
                    Simpan Perubahan Raport
                </button>
            </form>
        </div>
    </div>
</div>
@endsection