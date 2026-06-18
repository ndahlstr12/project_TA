@extends('layouts.admin')

@section('title', 'Peringkat SPK Siswa')
@section('page_title', 'Peringkat Berdasarkan Kriteria SPK')

@section('content')
<div class="space-y-8">
    
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 border border-slate-200 dark:border-white/5 flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 rounded-2xl bg-rose-500/10 flex items-center justify-center text-rose-500">
                <i class="ti ti-award text-3xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-extrabold text-slate-900 dark:text-white">Ranking SPK Kelas {{ $kelas->nama_kelas ?? '' }}</h3>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Sistem Pendukung Keputusan Berbasis Kriteria</p>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <form action="{{ route('walikelas.ranking.generate') }}" method="POST">
                @csrf
                <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-xs font-black uppercase tracking-widest transition-all shadow-lg shadow-blue-500/25 flex items-center gap-2">
                    <i class="ti ti-refresh"></i>
                    Generate Ranking
                </button>
            </form>
            <div class="px-6 py-3 bg-slate-50 dark:bg-white/5 rounded-2xl border border-slate-100 dark:border-white/5">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest text-center md:text-left">Tahun Ajaran</p>
                <p class="text-sm font-black text-slate-900 dark:text-white">2026/2027 - GENAP</p>
            </div>
        </div>
    </div>

    <!-- Alert Messaging -->
    @if(session('success'))
    <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-emerald-500 flex items-center justify-center text-white shrink-0">
            <i class="ti ti-check text-xl"></i>
        </div>
        <p class="text-xs font-bold text-emerald-600 tracking-tight">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 bg-rose-500/10 border border-rose-500/20 rounded-xl flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-rose-500 flex items-center justify-center text-white shrink-0">
            <i class="ti ti-x text-xl"></i>
        </div>
        <p class="text-xs font-bold text-rose-600 tracking-tight">{{ session('error') }}</p>
    </div>
    @endif

    <div class="card-pro overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 dark:bg-white/5 border-b border-slate-100 dark:border-white/5 text-[10px] font-black text-slate-500 uppercase tracking-widest">
                        <th class="px-6 py-5 text-center w-20">Rank</th>
                        <th class="px-6 py-5">Nama Siswa</th>
                        <th class="px-6 py-5">NISN</th>
                        <th class="px-6 py-5 text-center">Skor Akhir</th>
                        <th class="px-6 py-5 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-white/5">
                    @forelse($rankings as $rank)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/5 transition-colors">
                        <td class="px-6 py-5 text-center">
                            @if($rank->ranking <= 3)
                                <div class="inline-flex items-center justify-center w-10 h-10 rounded-full @if($rank->ranking == 1) bg-amber-400 @elseif($rank->ranking == 2) bg-slate-300 @else bg-orange-400 @endif text-white font-black text-sm shadow-sm">
                                    {{ $rank->ranking }}
                                </div>
                            @else
                                <span class="font-black text-slate-400 text-sm">#{{ $rank->ranking }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-5">
                            <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $rank->siswa->nama }}</span>
                        </td>
                        <td class="px-6 py-5 text-xs font-medium text-slate-500">
                            {{ $rank->siswa->nisn }}
                        </td>
                        <td class="px-6 py-5 text-center">
                            <span class="px-3 py-1 rounded-lg bg-blue-500/10 text-blue-600 font-black text-xs">
                                {{ number_format($rank->skor_spk, 4) }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-right">
                            @if($rank->ranking <= 5)
                                <span class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">Unggul</span>
                            @else
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Standar</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center opacity-30">
                                <i class="ti ti-award-off text-5xl mb-4"></i>
                                <p class="text-xs font-black uppercase tracking-widest">Data ranking belum digenerate. Silakan klik tombol di atas.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Transparansi Algoritma SAW -->
    @if(!empty($matrix))
    <div x-data="{ showTransparency: false }" class="space-y-6">
        <button @click="showTransparency = !showTransparency" class="flex items-center gap-2 text-xs font-black uppercase tracking-widest text-slate-500 hover:text-indigo-600 transition-colors">
            <i :class="showTransparency ? 'ti ti-chevron-down' : 'ti ti-chevron-right'"></i>
            Lihat Transparansi Perhitungan (Metode SAW)
        </button>

        <div x-show="showTransparency" x-transition class="space-y-8 pb-10">
            <!-- Step 1: Matriks Keputusan -->
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-500/10 flex items-center justify-center text-indigo-500 font-bold text-xs">1</div>
                    <h4 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-tight">Matriks Keputusan (X)</h4>
                </div>
                <div class="card-pro overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs min-w-[600px]">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-white/5 border-b border-slate-100 dark:border-white/10 font-bold text-slate-500">
                                    <th class="px-6 py-4">Nama Siswa</th>
                                    @foreach($kriterias as $k)
                                    <th class="px-6 py-4 text-center">{{ $k->nama }} ({{ $k->kode }})</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 dark:divide-white/5">
                                @foreach($matrix as $siswaId => $data)
                                <tr>
                                    <td class="px-6 py-4 font-bold text-slate-700 dark:text-slate-300">{{ $data['nama'] }}</td>
                                    @foreach($kriterias as $k)
                                    <td class="px-6 py-4 text-center text-slate-500">{{ number_format($data[$k->kode], 2) }}</td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Step 2: Matriks Normalisasi -->
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-500/10 flex items-center justify-center text-indigo-500 font-bold text-xs">2</div>
                    <h4 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-tight">Matriks Ternormalisasi (R)</h4>
                </div>
                <div class="card-pro overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs min-w-[600px]">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-white/5 border-b border-slate-100 dark:border-white/10 font-bold text-slate-500">
                                    <th class="px-6 py-4">Nama Siswa</th>
                                    @foreach($kriterias as $k)
                                    <th class="px-6 py-4 text-center">{{ $k->kode }} ({{ $k->jenis }})</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 dark:divide-white/5">
                                @foreach($normalized as $siswaId => $data)
                                <tr>
                                    <td class="px-6 py-4 font-bold text-slate-700 dark:text-slate-300">{{ $matrix[$siswaId]['nama'] }}</td>
                                    @foreach($kriterias as $k)
                                    <td class="px-6 py-4 text-center text-indigo-600 font-bold">{{ number_format($data[$k->kode], 4) }}</td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <p class="text-[10px] text-slate-400 italic">Rumus: Benefit (x/max), Cost (min/x)</p>
            </div>

            <!-- Step 3: Perhitungan Skor -->
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-500/10 flex items-center justify-center text-indigo-500 font-bold text-xs">3</div>
                    <h4 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-tight">Bobot Kriteria (W)</h4>
                </div>
                <div class="flex flex-wrap gap-4">
                    @foreach($kriterias as $k)
                    <div class="px-4 py-3 bg-white dark:bg-slate-900 border border-slate-100 dark:border-white/5 rounded-2xl flex items-center gap-3 shadow-sm">
                        <span class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-500 flex items-center justify-center text-[10px] font-black">{{ $k->kode }}</span>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $k->nama }}</p>
                            <p class="text-sm font-black text-slate-800 dark:text-white">{{ $k->bobot }}%</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                <p class="text-xs text-slate-500 leading-relaxed mt-4">
                    Skor Akhir (V<sub>i</sub>) = &Sigma; (w<sub>j</sub> * r<sub>ij</sub>)
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Info Box -->
    <div class="p-6 bg-blue-500/5 border border-blue-500/10 rounded-3xl flex gap-4">
        <i class="ti ti-info-circle text-blue-500 text-2xl shrink-0"></i>
        <div>
            <h4 class="text-xs font-black text-blue-500 uppercase tracking-widest mb-1">Informasi SPK</h4>
            <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">Peringkat ini dihitung secara otomatis oleh sistem berdasarkan kriteria yang telah ditentukan (Nilai Akademik, Kehadiran, dan Perilaku). Gunakan data ini sebagai referensi bimbingan konseling dan pemberian apresiasi siswa.</p>
        </div>
    </div>

</div>
@endsection
