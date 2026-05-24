@extends('layouts.admin')

@section('title', 'Kelola Raport Siswa')
@section('page_title', 'Detail Raport - ' . $siswa->nama)

@section('content')
<div class="space-y-8">
    
    <div class="flex flex-col md:flex-row justify-between items-start gap-6">
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 rounded-3xl bg-blue-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/30">
                <i class="ti ti-user text-3xl"></i>
            </div>
            <div>
                <h3 class="text-2xl font-extrabold text-slate-900 dark:text-white">{{ $siswa->nama }}</h3>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">NISN: {{ $siswa->nisn }} | Kelas: {{ $siswa->kelas->nama_kelas ?? '-' }}</p>
            </div>
        </div>
        <div class="flex gap-3">
            <form action="{{ route('walikelas.raport.send-email', $siswa->id) }}" method="POST">
                @csrf
                <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all shadow-lg shadow-emerald-500/25 flex items-center gap-2">
                    <i class="ti ti-mail"></i> Kirim ke Orang Tua
                </button>
            </form>
            <a href="{{ route('walikelas.raport.export-pdf', $siswa->id) }}" class="px-6 py-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:opacity-90 transition-all flex items-center gap-2">
                <i class="ti ti-printer"></i> Cetak PDF
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Input Form -->
        <div class="lg:col-span-2 space-y-8">
            <div class="card-pro p-8">
                <h4 class="text-sm font-black uppercase tracking-widest text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                    <i class="ti ti-pencil text-blue-500"></i> Pengisian Data Raport
                </h4>
                
                <form action="{{ route('walikelas.raport.update', $siswa->id) }}" method="POST" class="space-y-8">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Sakit</label>
                            <input type="number" name="sakit" value="{{ $raport->sakit ?? 0 }}" class="w-full bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm font-bold">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Izin</label>
                            <input type="number" name="izin" value="{{ $raport->izin ?? 0 }}" class="w-full bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm font-bold">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Alpa</label>
                            <input type="number" name="alpa" value="{{ $raport->alpa ?? 0 }}" class="w-full bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm font-bold">
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Catatan Wali Kelas</label>
                            <button type="button" 
                                onclick="event.preventDefault(); document.getElementById('ai-form').submit();"
                                class="text-[10px] font-black text-blue-500 hover:text-blue-600 flex items-center gap-1 uppercase tracking-tighter">
                                <i class="ti ti-sparkles"></i> Generate dengan AI
                            </button>
                        </div>
                        <textarea name="catatan_wali" rows="4" class="w-full bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl px-4 py-4 text-sm font-medium focus:ring-2 focus:ring-blue-500 outline-none transition-all" placeholder="Berikan catatan tentang perkembangan siswa...">{{ $raport->catatan_wali ?? '' }}</textarea>
                    </div>

                    <div class="flex items-center justify-between pt-6 border-t border-slate-100 dark:border-white/5">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-white/5 flex items-center justify-center border-2 border-dashed border-slate-300 dark:border-white/10 overflow-hidden">
                                @if(Auth::user()->guru->ttd_digital)
                                    <img src="{{ asset('storage/' . Auth::user()->guru->ttd_digital) }}" class="max-w-full max-h-full object-contain grayscale">
                                @else
                                    <i class="ti ti-signature text-slate-400"></i>
                                @endif
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Tanda Tangan Digital</p>
                                <p class="text-[9px] font-bold text-emerald-500 uppercase mt-1">Tersemat Otomatis</p>
                            </div>
                        </div>
                        <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-black uppercase tracking-widest transition-all">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>

                <form id="ai-form" action="{{ route('walikelas.raport.ai', $siswa->id) }}" method="POST" class="hidden">@csrf</form>
            </div>

            <!-- AI Output Section -->
            @if($raport && ($raport->saran_ai || $raport->rekomendasi_ai))
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 animate-in slide-in-from-bottom-4 duration-500">
                <div class="p-6 bg-blue-500/5 border border-blue-500/10 rounded-3xl">
                    <p class="text-[10px] font-black text-blue-500 uppercase tracking-widest mb-3 flex items-center gap-1">
                        <i class="ti ti-bulb"></i> Saran Motivasi (AI)
                    </p>
                    <p class="text-sm font-medium text-slate-700 dark:text-slate-300 leading-relaxed italic">"{{ $raport->saran_ai }}"</p>
                </div>
                <div class="p-6 bg-emerald-500/5 border border-emerald-500/10 rounded-3xl">
                    <p class="text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-3 flex items-center gap-1">
                        <i class="ti ti-shield-check"></i> Rekomendasi Penanganan (AI)
                    </p>
                    <p class="text-sm font-medium text-slate-700 dark:text-slate-300 leading-relaxed">{{ $raport->rekomendasi_ai }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Summary Section -->
        <div class="space-y-8">
            <!-- Nilai Summary -->
            <div class="card-pro p-6">
                <h4 class="text-xs font-black uppercase tracking-widest text-slate-900 dark:text-white mb-6">Ringkasan Nilai</h4>
                <div class="space-y-4">
                    @forelse($nilais as $nilai)
                    <div class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-white/5 last:border-0">
                        <span class="text-xs font-bold text-slate-500">{{ $nilai->mapel->nama_mapel ?? 'Mapel' }}</span>
                        <span class="text-sm font-black @if($nilai->nilai_angka < 75) text-rose-500 @else text-slate-900 dark:text-white @endif">{{ $nilai->nilai_angka }}</span>
                    </div>
                    @empty
                    <p class="text-[10px] text-center text-slate-400 font-bold uppercase py-4">Belum ada nilai terinput</p>
                    @endforelse
                </div>
            </div>

            <!-- Jurnal Summary -->
            <div class="card-pro p-6">
                <h4 class="text-xs font-black uppercase tracking-widest text-slate-900 dark:text-white mb-6">Catatan Perilaku</h4>
                <div class="space-y-4">
                    @forelse($jurnals->take(5) as $jurnal)
                    <div class="p-3 bg-slate-50 dark:bg-white/5 rounded-xl border border-slate-100 dark:border-white/5">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-[9px] font-black uppercase @if($jurnal->tipe === 'Positif') text-emerald-500 @else text-rose-500 @endif">{{ $jurnal->tipe }}</span>
                            <span class="text-[9px] font-bold text-slate-400">{{ \Carbon\Carbon::parse($jurnal->tanggal)->format('d/m/y') }}</span>
                        </div>
                        <p class="text-[11px] text-slate-600 dark:text-slate-400 line-clamp-2">{{ $jurnal->catatan }}</p>
                    </div>
                    @empty
                    <p class="text-[10px] text-center text-slate-400 font-bold uppercase py-4">Tidak ada catatan perilaku</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
