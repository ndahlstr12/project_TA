@extends('layouts.admin')

@section('title', 'Kelola Paket Ujian')
@section('page_title', $ujian->nama_ujian)

@section('content')
<div class="space-y-8">
    
    <div class="flex items-center justify-between">
        <a href="{{ route('shared.cbt.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/5 text-slate-600 dark:text-slate-400 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-50 transition-all">
            <i class="ti ti-arrow-left"></i> Kembali ke Daftar
        </a>
        <div class="flex gap-3">
            <button onclick="openImportModal()" class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-600/20">
                <i class="ti ti-file-spreadsheet"></i> Import Excel
            </button>
            <button onclick="openSoalModal()" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-blue-700 transition-all shadow-lg shadow-blue-600/20">
                <i class="ti ti-plus"></i> Tambah Soal Manual
            </button>
        </div>
    </div>

    {{-- Ringkasan Pengaturan Ujian --}}
    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/5 rounded-3xl p-8 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-indigo-500/10 flex items-center justify-center text-indigo-500">
                    <i class="ti ti-settings text-2xl"></i>
                </div>
                <div>
                    <h4 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-widest">Pengaturan Ujian</h4>
                    <p class="text-[10px] text-slate-500 font-bold mt-1">{{ $ujian->mapel }} | {{ $ujian->kelas }}</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-4">
                <div class="px-4 py-2 bg-slate-50 dark:bg-white/5 rounded-xl border border-slate-100 dark:border-white/5">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Durasi</span>
                    <span class="text-xs font-bold text-slate-700 dark:text-slate-200">{{ $ujian->durasi }} Menit</span>
                </div>
                <div class="px-4 py-2 bg-slate-50 dark:bg-white/5 rounded-xl border border-slate-100 dark:border-white/5">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Tingkat</span>
                    <span class="text-xs font-bold text-emerald-500">{{ $ujian->level }}</span>
                </div>
                <div class="px-4 py-2 bg-slate-50 dark:bg-white/5 rounded-xl border border-slate-100 dark:border-white/5 flex items-center gap-3">
                    <div class="flex items-center gap-1 {{ $ujian->acak_soal ? 'text-blue-500' : 'text-slate-300' }}">
                        <i class="ti ti-arrows-shuffle-2 text-base"></i>
                        <span class="text-[9px] font-black uppercase tracking-tighter">Acak Soal</span>
                    </div>
                    <div class="flex items-center gap-1 {{ $ujian->acak_jawaban ? 'text-indigo-500' : 'text-slate-300' }}">
                        <i class="ti ti-list-details text-base"></i>
                        <span class="text-[9px] font-black uppercase tracking-tighter">Acak Jawaban</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Daftar Soal --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($ujian->soals as $index => $soal)
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/5 rounded-2xl p-6 shadow-sm hover:border-blue-500/30 transition-all group">
            <div class="flex items-start justify-between mb-4">
                <span class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-white/5 flex items-center justify-center text-xs font-black text-slate-500">
                    {{ $index + 1 }}
                </span>
                <span class="px-2.5 py-1 rounded-lg bg-emerald-500/10 text-emerald-500 text-[10px] font-black">
                    Kunci: {{ $soal->jawaban_benar }}
                </span>
            </div>
            <div class="text-sm font-bold text-slate-700 dark:text-slate-200 leading-relaxed mb-6">
                {!! $soal->pertanyaan !!}
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div class="text-[11px] text-slate-500"><span class="font-black text-slate-400 mr-1">A.</span> {{ Str::limit($soal->opsi_a, 20) }}</div>
                <div class="text-[11px] text-slate-500"><span class="font-black text-slate-400 mr-1">B.</span> {{ Str::limit($soal->opsi_b, 20) }}</div>
                <div class="text-[11px] text-slate-500"><span class="font-black text-slate-400 mr-1">C.</span> {{ Str::limit($soal->opsi_c, 20) }}</div>
                <div class="text-[11px] text-slate-500"><span class="font-black text-slate-400 mr-1">D.</span> {{ Str::limit($soal->opsi_d, 20) }}</div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/5 rounded-3xl flex flex-col items-center justify-center text-center opacity-50 italic">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Belum ada soal di paket ini</p>
        </div>
        @endforelse
    </div>

    {{-- Modal Tambah Soal Manual --}}
    <div id="soalModal" class="fixed inset-0 z-[100] hidden">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeSoalModal()"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl p-6">
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/10 rounded-3xl p-8 relative shadow-2xl overflow-y-auto max-h-[90vh]">
                <button onclick="closeSoalModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors">
                    <i class="ti ti-x text-xl"></i>
                </button>
                <h4 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-widest mb-8 flex items-center gap-3">
                    <i class="ti ti-plus text-blue-500"></i> Tambah Soal Manual
                </h4>
                <form action="{{ route('shared.cbt.soal.store', $ujian->id) }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Pertanyaan</label>
                        <textarea name="pertanyaan" rows="3" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-white/10 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Opsi A</label>
                            <input type="text" name="opsi_a" required class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-white/10 rounded-xl text-xs font-bold transition-all">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Opsi B</label>
                            <input type="text" name="opsi_b" required class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-white/10 rounded-xl text-xs font-bold transition-all">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Opsi C</label>
                            <input type="text" name="opsi_c" required class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-white/10 rounded-xl text-xs font-bold transition-all">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Opsi D</label>
                            <input type="text" name="opsi_d" required class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-white/10 rounded-xl text-xs font-bold transition-all">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Jawaban Benar</label>
                        <select name="jawaban_benar" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-white/10 rounded-2xl text-xs font-bold transition-all">
                            <option value="A">A</option><option value="B">B</option><option value="C">C</option><option value="D">D</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full py-4 bg-blue-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-blue-700 transition-all">Simpan Soal</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Import Excel --}}
    <div id="importModal" class="fixed inset-0 z-[100] hidden">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeImportModal()"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-lg p-6">
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/10 rounded-3xl p-8 relative shadow-2xl">
                <button onclick="closeImportModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors">
                    <i class="ti ti-x text-xl"></i>
                </button>
                <h4 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-widest mb-8 flex items-center gap-3">
                    <i class="ti ti-file-spreadsheet text-emerald-500"></i> Import Excel ke Paket Ini
                </h4>
                <form action="{{ route('shared.cbt.import', $ujian->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <input type="file" name="file" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-white/10 rounded-2xl text-xs font-bold transition-all">
                    <button type="submit" class="w-full py-4 bg-emerald-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-emerald-700 transition-all">Mulai Import</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openSoalModal() { document.getElementById('soalModal').classList.remove('hidden'); }
    function closeSoalModal() { document.getElementById('soalModal').classList.add('hidden'); }
    function openImportModal() { document.getElementById('importModal').classList.remove('hidden'); }
    function closeImportModal() { document.getElementById('importModal').classList.add('hidden'); }
</script>
@endpush
@endsection
