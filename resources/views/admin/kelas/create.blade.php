@extends('layouts.admin')

@section('title', 'Tambah Kelas')
@section('page_title', 'Tambah Data Kelas')

@section('content')
<div class="max-w-2xl mx-auto">
    <a href="{{ route('admin.kelas.index') }}" class="inline-flex items-center text-[10px] font-bold text-navy-400 uppercase tracking-widest hover:text-navy-900 dark:hover:text-white mb-6 transition-colors">
        <i class="ti ti-arrow-left mr-2 text-base"></i>
        Kembali ke Daftar
    </a>

    <div class="card-pro p-8 lg:p-10">
        <div class="flex items-center gap-4 mb-10">
            <div class="w-10 h-10 rounded-xl bg-navy-950 dark:bg-white text-white dark:text-navy-950 flex items-center justify-center shadow-xl">
                <i class="ti ti-plus text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-navy-900 dark:text-white">Tambah Kelas Baru</h3>
                <p class="text-[10px] text-navy-400 font-bold uppercase tracking-widest">Informasi Struktural Kelas</p>
            </div>
        </div>

        <form action="{{ route('admin.kelas.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="space-y-2">
                <label class="text-[10px] font-bold text-navy-400 uppercase tracking-widest ml-1">Nama Kelas</label>
                <input type="text" name="nama_kelas" value="{{ old('nama_kelas') }}" 
                    class="w-full px-4 py-3.5 bg-navy-50 dark:bg-white/5 border @error('nama_kelas') border-rose-500 @else border-navy-100 dark:border-white/10 @enderror rounded-xl text-sm font-bold focus:border-navy-950 dark:focus:border-white outline-none transition-all" 
                    placeholder="Contoh: XII RPL 1" required>
                @error('nama_kelas')
                    <p class="text-[10px] font-bold text-rose-500 mt-1 ml-1 uppercase">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-navy-400 uppercase tracking-widest ml-1">Tingkat</label>
                    <select name="tingkat" class="w-full px-4 py-3.5 bg-navy-50 dark:bg-white/5 border @error('tingkat') border-rose-500 @else border-navy-100 dark:border-white/10 @enderror rounded-xl text-sm font-bold focus:border-navy-950 dark:focus:border-white outline-none transition-all appearance-none cursor-pointer" required>
                        <option value="" disabled selected>Pilih Tingkat</option>
                        <option value="X" {{ old('tingkat') == 'X' ? 'selected' : '' }}>X (Sepuluh)</option>
                        <option value="XI" {{ old('tingkat') == 'XI' ? 'selected' : '' }}>XI (Sebelas)</option>
                        <option value="XII" {{ old('tingkat') == 'XII' ? 'selected' : '' }}>XII (Dua Belas)</option>
                    </select>
                    @error('tingkat')
                        <p class="text-[10px] font-bold text-rose-500 mt-1 ml-1 uppercase">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-navy-400 uppercase tracking-widest ml-1">Jurusan</label>
                    <input type="text" name="jurusan" value="{{ old('jurusan') }}" 
                        class="w-full px-4 py-3.5 bg-navy-50 dark:bg-white/5 border @error('jurusan') border-rose-500 @else border-navy-100 dark:border-white/10 @enderror rounded-xl text-sm font-bold focus:border-navy-950 dark:focus:border-white outline-none transition-all" 
                        placeholder="Contoh: RPL" required>
                    @error('jurusan')
                        <p class="text-[10px] font-bold text-rose-500 mt-1 ml-1 uppercase">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="pt-6">
                <button type="submit" class="w-full py-4 bg-navy-950 dark:bg-white text-white dark:text-navy-950 text-xs font-black rounded-xl uppercase tracking-[0.2em] hover:opacity-90 transition-all shadow-xl shadow-navy-950/20 active:scale-[0.98]">
                    Simpan Data Kelas
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
