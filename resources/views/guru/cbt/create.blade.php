@extends('layouts.admin')

@section('title', 'Buat Paket Ujian')
@section('page_title', 'Konfigurasi Paket Ujian Baru')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <a href="{{ route('shared.cbt.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/5 text-slate-600 dark:text-slate-400 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-50 transition-all">
            <i class="ti ti-arrow-left"></i> Kembali
        </a>
    </div>

    <form action="{{ route('shared.cbt.store') }}" method="POST" class="space-y-6">
        @csrf
        
        {{-- Identitas Ujian --}}
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/5 rounded-3xl p-8 shadow-sm">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 rounded-2xl bg-blue-500/10 flex items-center justify-center text-blue-500">
                    <i class="ti ti-info-circle text-2xl"></i>
                </div>
                <div>
                    <h4 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-widest">Informasi Dasar</h4>
                    <p class="text-[10px] text-slate-500 font-bold mt-1">Nama paket ujian dan kelas tujuan</p>
                </div>
            </div>

            <div class="space-y-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Nama Paket Ujian</label>
                    <input type="text" name="nama_ujian" required placeholder="Contoh: UTS Matematika Semester Ganjil" 
                           class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-white/10 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Mata Pelajaran</label>
                        <select name="mapel" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-white/10 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                            <option value="">Pilih Mata Pelajaran</option>
                            @foreach($mapels as $m)
                                <option value="{{ $m }}">{{ $m }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Kelas</label>
                        <select name="kelas" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-white/10 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                            <option value="">Pilih Kelas</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k }}">{{ $k }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pengaturan Teknis --}}
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/5 rounded-3xl p-8 shadow-sm">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/10 flex items-center justify-center text-amber-500">
                    <i class="ti ti-settings text-2xl"></i>
                </div>
                <div>
                    <h4 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-widest">Aturan & Durasi</h4>
                    <p class="text-[10px] text-slate-500 font-bold mt-1">Atur durasi, kesulitan, dan fitur pengacakan</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Durasi (Menit)</label>
                        <input type="number" name="durasi" required min="1" placeholder="60"
                               class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-white/10 rounded-2xl text-sm font-bold transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Target Jumlah Soal</label>
                        <input type="number" name="jumlah_soal" required min="1" placeholder="20"
                               class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-white/10 rounded-2xl text-sm font-bold transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Kesulitan</label>
                        <select name="level" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-white/10 rounded-2xl text-sm font-bold transition-all">
                            <option value="Mudah">Mudah</option>
                            <option value="Sedang" selected>Sedang</option>
                            <option value="Sulit">Sulit</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="p-6 bg-slate-50 dark:bg-slate-900/50 rounded-3xl border border-slate-100 dark:border-white/5">
                        <h5 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Fitur Cerdas</h5>
                        <div class="space-y-6">
                            <label class="flex items-center justify-between cursor-pointer group">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-white dark:bg-slate-800 flex items-center justify-center text-slate-400 group-hover:text-blue-500 transition-colors shadow-sm">
                                        <i class="ti ti-arrows-shuffle-2 text-xl"></i>
                                    </div>
                                    <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Acak Urutan Soal</span>
                                </div>
                                <input type="checkbox" name="acak_soal" value="1" checked class="w-5 h-5 rounded-lg border-slate-300 text-blue-600 focus:ring-blue-500/20 transition-all">
                            </label>
                            <label class="flex items-center justify-between cursor-pointer group">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-white dark:bg-slate-800 flex items-center justify-center text-slate-400 group-hover:text-indigo-500 transition-colors shadow-sm">
                                        <i class="ti ti-list-details text-xl"></i>
                                    </div>
                                    <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Acak Pilihan Jawaban</span>
                                </div>
                                <input type="checkbox" name="acak_jawaban" value="1" checked class="w-5 h-5 rounded-lg border-slate-300 text-indigo-600 focus:ring-indigo-500/20 transition-all">
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" class="px-10 py-4 bg-blue-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-blue-700 transition-all flex items-center gap-3 shadow-xl shadow-blue-600/20">
                <i class="ti ti-check text-lg"></i>
                Simpan & Lanjut Isi Soal
            </button>
        </div>
    </form>
</div>
@endsection
