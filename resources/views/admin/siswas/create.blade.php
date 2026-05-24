@extends('layouts.admin')

@section('title', 'Tambah Siswa')
@section('page_title', 'Master Data')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Back Button -->
    <a href="{{ route('admin.siswas.index') }}" class="inline-flex items-center text-slate-400 hover:text-indigo-600 mb-6 transition font-semibold group">
        <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i>
        Kembali ke Daftar
    </a>

    <!-- Card Form -->
    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-2xl shadow-slate-200/50 overflow-hidden">
        <div class="bg-indigo-950 p-8 text-white relative overflow-hidden">
            <div class="relative z-10">
                <h2 class="text-2xl font-bold tracking-tight">Input Data Siswa</h2>
                <p class="text-indigo-300 text-sm mt-1">Pastikan NISN benar agar sinkron dengan akun orang tua.</p>
            </div>
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-indigo-500/20 rounded-full text-9xl flex items-center justify-center opacity-20">
                <i class="fas fa-user-graduate"></i>
            </div>
        </div>

        <form action="{{ route('admin.siswas.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- NISN -->
                <div class="space-y-2">
                    <label class="text-sm font-bold text-slate-700 ml-1">NISN (10 Digit)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <i class="fas fa-id-card"></i>
                        </span>
                        <input type="text" name="nisn" value="{{ old('nisn') }}" maxlength="10" placeholder="0012345678" 
                               class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all outline-none" required>
                    </div>
                    @error('nisn') <p class="text-red-500 text-xs mt-1 ml-1 font-medium">{{ $message }}</p> @enderror
                </div>

                <!-- Nama Lengkap -->
                <div class="space-y-2">
                    <label class="text-sm font-bold text-slate-700 ml-1">Nama Lengkap</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <i class="far fa-user"></i>
                        </span>
                        <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Nama Lengkap Siswa" 
                               class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all outline-none" required>
                    </div>
                    @error('nama') <p class="text-red-500 text-xs mt-1 ml-1 font-medium">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kelas -->
                <div class="space-y-2">
                    <label class="text-sm font-bold text-slate-700 ml-1">Kelas</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <i class="fas fa-school"></i>
                        </span>
                        <select name="kelas_id" class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none appearance-none cursor-pointer" required>
                            <option value="" disabled selected>Pilih Kelas</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-400">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                    @error('kelas_id') <p class="text-red-500 text-xs mt-1 ml-1 font-medium">{{ $message }}</p> @enderror
                </div>

                <!-- Jenis Kelamin -->
                <div class="space-y-2">
                    <label class="text-sm font-bold text-slate-700 ml-1">Jenis Kelamin</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <i class="fas fa-venus-mars"></i>
                        </span>
                        <select name="jenis_kelamin" class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none appearance-none cursor-pointer">
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-400">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 rounded-2xl shadow-xl shadow-indigo-200 transition-all flex items-center justify-center space-x-2">
                    <i class="fas fa-save"></i>
                    <span>Simpan Data Siswa</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
