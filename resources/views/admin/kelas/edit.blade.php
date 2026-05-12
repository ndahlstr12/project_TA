@extends('layouts.admin')

@section('title', 'Edit Kelas')
@section('page_title', 'Perbarui Data Kelas')

@section('content')
<div class="max-w-2xl mx-auto">
    <a href="{{ route('admin.kelas.index') }}" class="inline-flex items-center text-[10px] font-bold text-navy-400 uppercase tracking-widest hover:text-navy-900 dark:hover:text-white mb-6 transition-colors">
        <i class="ti ti-arrow-left mr-2 text-base"></i>
        Kembali ke Daftar
    </a>

    <div class="card-pro p-8 lg:p-10">
        <div class="flex items-center gap-4 mb-10">
            <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center shadow-xl shadow-amber-500/20">
                <i class="ti ti-edit text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-navy-900 dark:text-white">Edit Kelas: {{ $kela->nama_kelas }}</h3>
                <p class="text-[10px] text-navy-400 font-bold uppercase tracking-widest">Perbarui Informasi Struktural</p>
            </div>
        </div>

        <form action="{{ route('admin.kelas.update', $kela->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="space-y-2">
                <label class="text-[10px] font-bold text-navy-400 uppercase tracking-widest ml-1">Nama Kelas</label>
                <input type="text" name="nama_kelas" value="{{ old('nama_kelas', $kela->nama_kelas) }}" 
                    class="w-full px-4 py-3.5 bg-navy-50 dark:bg-white/5 border @error('nama_kelas') border-rose-500 @else border-navy-100 dark:border-white/10 @enderror rounded-xl text-sm font-bold focus:border-navy-950 dark:focus:border-white outline-none transition-all" required>
                @error('nama_kelas')
                    <p class="text-[10px] font-bold text-rose-500 mt-1 ml-1 uppercase">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-navy-400 uppercase tracking-widest ml-1">Tingkat</label>
                    <select name="tingkat" class="w-full px-4 py-3.5 bg-navy-50 dark:bg-white/5 border @error('tingkat') border-rose-500 @else border-navy-100 dark:border-white/10 @enderror rounded-xl text-sm font-bold focus:border-navy-950 dark:focus:border-white outline-none transition-all appearance-none cursor-pointer" required>
                        <option value="X" {{ old('tingkat', $kela->tingkat) == 'X' ? 'selected' : '' }}>X (Sepuluh)</option>
                        <option value="XI" {{ old('tingkat', $kela->tingkat) == 'XI' ? 'selected' : '' }}>XI (Sebelas)</option>
                        <option value="XII" {{ old('tingkat', $kela->tingkat) == 'XII' ? 'selected' : '' }}>XII (Dua Belas)</option>
                    </select>
                    @error('tingkat')
                        <p class="text-[10px] font-bold text-rose-500 mt-1 ml-1 uppercase">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-navy-400 uppercase tracking-widest ml-1">Jurusan</label>
                    <input type="text" name="jurusan" value="{{ old('jurusan', $kela->jurusan) }}" 
                        class="w-full px-4 py-3.5 bg-navy-50 dark:bg-white/5 border @error('jurusan') border-rose-500 @else border-navy-100 dark:border-white/10 @enderror rounded-xl text-sm font-bold focus:border-navy-950 dark:focus:border-white outline-none transition-all" required>
                    @error('jurusan')
                        <p class="text-[10px] font-bold text-rose-500 mt-1 ml-1 uppercase">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="pt-6">
                <button type="submit" class="w-full py-4 bg-amber-500 text-white text-xs font-black rounded-xl uppercase tracking-[0.2em] hover:bg-amber-600 transition-all shadow-xl shadow-amber-500/20 active:scale-[0.98]">
                    Perbarui Data Kelas
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
