@extends('layouts.admin')

@section('title', 'Edit Pengguna')
@section('page_title', 'Edit Data Pengguna')

@section('content')
<div class="max-w-3xl mx-auto">
    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-[10px] font-bold text-navy-400 uppercase tracking-widest hover:text-navy-900 dark:hover:text-white mb-6 transition-colors">
        <i class="ti ti-arrow-left mr-2 text-base"></i>
        Kembali ke Daftar
    </a>

    <div class="card-pro p-8 lg:p-10">
        <div class="flex items-center gap-4 mb-10">
            <div class="w-10 h-10 rounded-xl bg-navy-950 dark:bg-white text-white dark:text-navy-950 flex items-center justify-center shadow-xl">
                <i class="ti ti-edit text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-navy-900 dark:text-white">Edit Pengguna</h3>
                <p class="text-[10px] text-navy-400 font-bold uppercase tracking-widest">Memperbarui Akses: {{ $user->name }}</p>
            </div>
        </div>

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-navy-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                        class="w-full px-4 py-3.5 bg-navy-50 dark:bg-white/5 border @error('name') border-rose-500 @else border-navy-100 dark:border-white/10 @enderror rounded-xl text-sm font-bold focus:border-navy-950 dark:focus:border-white outline-none transition-all" 
                        placeholder="Nama Lengkap" required>
                    @error('name')
                        <p class="text-[10px] font-bold text-rose-500 mt-1 ml-1 uppercase">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-navy-400 uppercase tracking-widest ml-1">Alamat Email (Opsional)</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                        class="w-full px-4 py-3.5 bg-navy-50 dark:bg-white/5 border @error('email') border-rose-500 @else border-navy-100 dark:border-white/10 @enderror rounded-xl text-sm font-bold focus:border-navy-950 dark:focus:border-white outline-none transition-all" 
                        placeholder="email@sekolah.sch.id">
                    @error('email')
                        <p class="text-[10px] font-bold text-rose-500 mt-1 ml-1 uppercase">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-navy-400 uppercase tracking-widest ml-1">Peran / Aktor</label>
                    <select name="role" id="role-select" class="w-full px-4 py-3.5 bg-navy-50 dark:bg-white/5 border border-navy-100 dark:border-white/10 rounded-xl text-sm font-bold focus:border-navy-950 dark:focus:border-white outline-none transition-all appearance-none cursor-pointer">
                        <option value="siswa" {{ $user->role == 'siswa' ? 'selected' : '' }}>Siswa</option>
                        <option value="guru" {{ $user->role == 'guru' ? 'selected' : '' }}>Guru Pengajar</option>
                        <option value="walikelas" {{ $user->role == 'walikelas' ? 'selected' : '' }}>Wali Kelas</option>
                        <option value="orangtua" {{ $user->role == 'orangtua' ? 'selected' : '' }}>Orang Tua</option>
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrator</option>
                    </select>
                </div>

                @if(in_array($user->role, ['siswa', 'orangtua']))
                <div class="space-y-2" id="kelas-container">
                    <label class="text-[10px] font-bold text-navy-400 uppercase tracking-widest ml-1">Penempatan Kelas</label>
                    <select name="kelas_id" class="w-full px-4 py-3.5 bg-navy-50 dark:bg-white/5 border border-navy-100 dark:border-white/10 rounded-xl text-sm font-bold focus:border-navy-950 dark:focus:border-white outline-none transition-all appearance-none cursor-pointer">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->id }}" {{ (old('kelas_id', $user->siswa->kelas_id ?? '')) == $kelas->id ? 'selected' : '' }}>{{ $kelas->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-navy-400 uppercase tracking-widest ml-1">Kata Sandi Baru</label>
                    @if($user->role !== 'admin')
                        <input type="password" name="password" 
                            class="w-full px-4 py-3.5 bg-navy-50 dark:bg-white/5 border @error('password') border-rose-500 @else border-navy-100 dark:border-white/10 @enderror rounded-xl text-sm font-bold focus:border-navy-950 dark:focus:border-white outline-none transition-all" 
                            placeholder="Kosongkan jika tidak ingin diubah">
                        <p class="text-[9px] text-navy-400 font-bold uppercase tracking-wider mt-1 ml-1 italic">*Kosongkan jika tidak diganti</p>
                        @error('password')
                            <p class="text-[10px] font-bold text-rose-500 mt-1 ml-1 uppercase">{{ $message }}</p>
                        @enderror
                    @else
                        <div class="px-4 py-3 bg-amber-50 dark:bg-amber-900/10 border border-amber-200 dark:border-amber-900/20 rounded-xl">
                            <p class="text-[9px] font-bold text-amber-700 dark:text-amber-500 uppercase leading-relaxed">
                                <i class="ti ti-alert-triangle mr-1"></i> Proteksi Sistem: Sandi Admin tidak dapat diubah di sini.
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="pt-6">
                <button type="submit" class="w-full py-4 bg-navy-950 dark:bg-white text-white dark:text-navy-950 text-xs font-black rounded-xl uppercase tracking-[0.2em] hover:opacity-90 transition-all shadow-xl shadow-navy-950/20 active:scale-[0.98]">
                    Perbarui Data Pengguna
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
