@extends('layouts.admin')

@section('title', 'Edit Data Guru')
@section('page_title', 'Master Data')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Back Button -->
    <a href="{{ route('admin.gurus.index') }}" class="inline-flex items-center text-slate-400 hover:text-indigo-600 mb-6 transition font-semibold group">
        <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i>
        Kembali ke Daftar
    </a>

    <!-- Card Form -->
    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-2xl shadow-slate-200/50 overflow-hidden">
        <div class="bg-indigo-900 p-8 text-white relative overflow-hidden">
            <div class="relative z-10">
                <h2 class="text-2xl font-bold tracking-tight">Edit Data Guru</h2>
                <p class="text-indigo-300 text-sm mt-1">Perbarui informasi identitas pengajar di bawah ini.</p>
            </div>
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-indigo-500/20 rounded-full text-9xl flex items-center justify-center opacity-20">
                <i class="fas fa-user-edit"></i>
            </div>
        </div>

        <form action="{{ route('admin.gurus.update', $guru->id) }}" method="POST" class="p-8 space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- NIP -->
                <div class="space-y-2">
                    <label class="text-sm font-bold text-slate-700 ml-1">NIP</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <i class="fas fa-id-badge"></i>
                        </span>
                        <input type="text" name="nip" value="{{ old('nip', $guru->nip) }}" placeholder="19XXXXXXXXXXXXXX" 
                               class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all outline-none" required>
                    </div>
                    @error('nip') <p class="text-red-500 text-xs mt-1 ml-1 font-medium">{{ $message }}</p> @enderror
                </div>

                <!-- Nama Lengkap -->
                <div class="space-y-2">
                    <label class="text-sm font-bold text-slate-700 ml-1">Nama Lengkap</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <i class="far fa-user"></i>
                        </span>
                        <input type="text" name="nama" value="{{ old('nama', $guru->nama) }}" placeholder="Tanpa Gelar" 
                               class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all outline-none" required>
                    </div>
                    @error('nama') <p class="text-red-500 text-xs mt-1 ml-1 font-medium">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Gelar -->
                <div class="space-y-2">
                    <label class="text-sm font-bold text-slate-700 ml-1">Gelar</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <i class="fas fa-graduation-cap"></i>
                        </span>
                        <input type="text" name="gelar" value="{{ old('gelar', $guru->gelar) }}" placeholder="Contoh: S.Pd, M.Kom" 
                               class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all outline-none">
                    </div>
                </div>

                <!-- Spesialisasi -->
                <div class="space-y-2">
                    <label class="text-sm font-bold text-slate-700 ml-1">Bidang Keahlian</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <i class="fas fa-briefcase"></i>
                        </span>
                        <input type="text" name="spesialisasi" value="{{ old('spesialisasi', $guru->spesialisasi) }}" placeholder="Contoh: Teknik Komputer" 
                               class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all outline-none">
                    </div>
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 rounded-2xl shadow-xl shadow-indigo-200 transition-all flex items-center justify-center space-x-2">
                    <i class="fas fa-save"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
