@extends('layouts.admin')

@section('title', 'Jurnal Perilaku Siswa')
@section('page_title', 'Manajemen Jurnal Perilaku')

@section('content')
<div class="space-y-8" x-data="{ showAiModal: false, aiRecommendation: '', loadingAi: false, currentCatatan: '' }">
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Form Input Jurnal -->
        <div class="lg:col-span-1">
            <div class="card-pro p-6 sticky top-8">
                <h3 class="text-sm font-black uppercase tracking-widest text-slate-900 dark:text-white mb-6">Input Catatan Baru</h3>
                
                <form action="{{ route('walikelas.jurnal.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Pilih Siswa</label>
                        <select name="siswa_id" class="w-full bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-blue-500 outline-none transition-all" required>
                            <option value="">Pilih Siswa...</option>
                            @foreach($siswas as $siswa)
                                <option value="{{ $siswa->id }}">{{ $siswa->nama }} ({{ $siswa->nisn }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Tipe</label>
                            <select name="tipe" class="w-full bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-blue-500 outline-none transition-all" required>
                                <option value="Positif">Positif</option>
                                <option value="Negatif">Negatif</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Poin</label>
                            <input type="number" name="poin" value="0" class="w-full bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-blue-500 outline-none transition-all" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-blue-500 outline-none transition-all" required>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Catatan Perilaku</label>
                            <button type="button" 
                                @click="
                                    currentCatatan = document.getElementById('catatan_input').value;
                                    if(currentCatatan.length < 10) { alert('Tulis catatan yang lebih detail untuk mendapatkan rekomendasi.'); return; }
                                    loadingAi = true;
                                    showAiModal = true;
                                    fetch('{{ route('walikelas.jurnal.ai') }}', {
                                        method: 'POST',
                                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                        body: JSON.stringify({ catatan: currentCatatan })
                                    })
                                    .then(res => res.json())
                                    .then(data => {
                                        aiRecommendation = data.recommendation;
                                        loadingAi = false;
                                    })
                                "
                                class="text-[10px] font-black text-blue-500 hover:text-blue-600 flex items-center gap-1 uppercase tracking-tighter">
                                <i class="ti ti-sparkles"></i> Minta Saran AI
                            </button>
                        </div>
                        <textarea id="catatan_input" name="catatan" rows="4" class="w-full bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-blue-500 outline-none transition-all" placeholder="Tuliskan kejadian atau perilaku siswa..." required></textarea>
                    </div>

                    <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-xs font-black uppercase tracking-widest transition-all shadow-lg shadow-blue-500/25 flex items-center justify-center gap-2">
                        <i class="ti ti-device-floppy"></i> Simpan Jurnal
                    </button>
                </form>
            </div>
        </div>

        <!-- Riwayat Jurnal -->
        <div class="lg:col-span-2 space-y-6">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-black uppercase tracking-widest text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="ti ti-history text-blue-500"></i> Riwayat Jurnal Kelas {{ $kelas->nama_kelas ?? '' }}
                </h3>
            </div>

            <div class="card-pro overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-white/5 border-b border-slate-100 dark:border-white/5 text-[10px] font-black text-slate-500 uppercase tracking-widest">
                                <th class="px-6 py-4">Siswa</th>
                                <th class="px-6 py-4">Tanggal</th>
                                <th class="px-6 py-4">Tipe</th>
                                <th class="px-6 py-4">Catatan</th>
                                <th class="px-6 py-4 text-right">Poin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 dark:divide-white/5">
                            @forelse($jurnals as $jurnal)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-white/5 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="text-xs font-bold text-slate-900 dark:text-white">{{ $jurnal->siswa->nama }}</div>
                                    <div class="text-[10px] font-medium text-slate-400">{{ $jurnal->siswa->nisn }}</div>
                                </td>
                                <td class="px-6 py-4 text-xs font-medium text-slate-500">
                                    {{ \Carbon\Carbon::parse($jurnal->tanggal)->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($jurnal->tipe === 'Positif')
                                        <span class="px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-600 text-[10px] font-black uppercase">{{ $jurnal->tipe }}</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full bg-rose-500/10 text-rose-600 text-[10px] font-black uppercase">{{ $jurnal->tipe }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-600 dark:text-slate-400 max-w-xs truncate">
                                    {{ $jurnal->catatan }}
                                </td>
                                <td class="px-6 py-4 text-right font-black text-sm @if($jurnal->tipe === 'Positif') text-emerald-500 @else text-rose-500 @endif">
                                    {{ $jurnal->tipe === 'Positif' ? '+' : '-' }}{{ $jurnal->poin }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-slate-400 italic text-xs font-medium uppercase tracking-widest">Belum ada catatan perilaku</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($jurnals->hasPages())
                <div class="p-6 border-t border-slate-50 dark:border-white/5">
                    {{ $jurnals->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- AI Recommendation Modal -->
    <template x-if="showAiModal">
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showAiModal = false"></div>
            <div class="relative bg-white dark:bg-slate-900 rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden animate-in fade-in zoom-in duration-300">
                <div class="p-6 border-b border-slate-100 dark:border-white/5 flex items-center justify-between bg-blue-600 text-white">
                    <div class="flex items-center gap-2">
                        <i class="ti ti-sparkles text-xl"></i>
                        <h4 class="text-sm font-black uppercase tracking-widest">Rekomendasi Penanganan AI</h4>
                    </div>
                    <button @click="showAiModal = false" class="text-white/80 hover:text-white transition-colors">
                        <i class="ti ti-x text-xl"></i>
                    </button>
                </div>
                <div class="p-8">
                    <div x-show="loadingAi" class="flex flex-col items-center justify-center py-10 space-y-4">
                        <div class="w-12 h-12 border-4 border-blue-500/20 border-t-blue-500 rounded-full animate-spin"></div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest animate-pulse">Menganalisis perilaku...</p>
                    </div>
                    <div x-show="!loadingAi" class="space-y-6">
                        <div class="p-4 bg-slate-50 dark:bg-white/5 rounded-2xl border border-slate-100 dark:border-white/5">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Analisis Catatan:</p>
                            <p class="text-xs italic text-slate-600 dark:text-slate-400" x-text="currentCatatan"></p>
                        </div>
                        <div class="p-5 bg-blue-500/10 rounded-2xl border border-blue-200 dark:border-blue-500/20">
                            <p class="text-[10px] font-black text-blue-500 uppercase tracking-widest mb-3 flex items-center gap-1">
                                <i class="ti ti-bulb"></i> Rekomendasi:
                            </p>
                            <p class="text-sm font-medium text-slate-800 dark:text-slate-200 leading-relaxed" x-text="aiRecommendation"></p>
                        </div>
                        <button @click="showAiModal = false" class="w-full py-3 bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-slate-400 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-white/10 transition-all">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </template>

</div>
@endsection
