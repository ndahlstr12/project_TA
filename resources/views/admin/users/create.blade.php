@extends('layouts.admin')

@section('title', 'Tambah Pengguna')
@section('page_title', 'Tambah Data Pengguna')

@section('content')
<div class="max-w-3xl mx-auto">
    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-[10px] font-bold text-navy-400 uppercase tracking-widest hover:text-navy-900 dark:hover:text-white mb-6 transition-colors">
        <i class="ti ti-arrow-left mr-2 text-base"></i>
        Kembali ke Daftar
    </a>

    <div class="card-pro p-8 lg:p-10">
        <div class="flex items-center gap-4 mb-10">
            <div class="w-10 h-10 rounded-xl bg-navy-950 dark:bg-white text-white dark:text-navy-950 flex items-center justify-center shadow-xl">
                <i class="ti ti-user-plus text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-navy-900 dark:text-white">Tambah Pengguna Baru</h3>
                <p class="text-[10px] text-navy-400 font-bold uppercase tracking-widest">Informasi Akses Sistem</p>
            </div>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-navy-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" 
                        class="w-full px-4 py-3.5 bg-navy-50 dark:bg-white/5 border @error('name') border-rose-500 @else border-navy-100 dark:border-white/10 @enderror rounded-xl text-sm font-bold focus:border-navy-950 dark:focus:border-white outline-none transition-all" 
                        placeholder="Contoh: Budi Santoso, S.Pd" required>
                    @error('name')
                        <p class="text-[10px] font-bold text-rose-500 mt-1 ml-1 uppercase">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-navy-400 uppercase tracking-widest ml-1">Alamat Email (Opsional)</label>
                    <input type="email" name="email" value="{{ old('email') }}" 
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
                        <option value="siswa" {{ old('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                        <option value="guru" {{ old('role') == 'guru' ? 'selected' : '' }}>Guru Pengajar</option>
                        <option value="walikelas" {{ old('role') == 'walikelas' ? 'selected' : '' }}>Wali Kelas</option>
                        <option value="orangtua" {{ old('role') == 'orangtua' ? 'selected' : '' }}>Orang Tua</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                    </select>
                </div>

                <div class="space-y-2" id="nip-container" style="display: none;">
                    <label class="text-[10px] font-bold text-navy-400 uppercase tracking-widest ml-1">NIP Guru</label>
                    <input type="text" name="nip" value="{{ old('nip') }}" 
                        class="w-full px-4 py-3.5 bg-navy-50 dark:bg-white/5 border @error('nip') border-rose-500 @else border-navy-100 dark:border-white/10 @enderror rounded-xl text-sm font-bold focus:border-navy-950 dark:focus:border-white outline-none transition-all" 
                        placeholder="19800101XXXXXXXX">
                    @error('nip')
                        <p class="text-[10px] font-bold text-rose-500 mt-1 ml-1 uppercase">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2" id="nisn-container" style="display: none;">
                    <label class="text-[10px] font-bold text-navy-400 uppercase tracking-widest ml-1">NISN Siswa</label>
                    <input type="text" name="nisn" value="{{ old('nisn') }}" 
                        class="w-full px-4 py-3.5 bg-navy-50 dark:bg-white/5 border @error('nisn') border-rose-500 @else border-navy-100 dark:border-white/10 @enderror rounded-xl text-sm font-bold focus:border-navy-950 dark:focus:border-white outline-none transition-all" 
                        placeholder="Masukkan 10 digit NISN">
                    @error('nisn')
                        <p class="text-[10px] font-bold text-rose-500 mt-1 ml-1 uppercase">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2" id="kelas-container" style="display: none;">
                    <label class="text-[10px] font-bold text-navy-400 uppercase tracking-widest ml-1">Penempatan Kelas</label>
                    <select name="kelas_id" class="w-full px-4 py-3.5 bg-navy-50 dark:bg-white/5 border border-navy-100 dark:border-white/10 rounded-xl text-sm font-bold focus:border-navy-950 dark:focus:border-white outline-none transition-all appearance-none cursor-pointer">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->id }}" {{ old('kelas_id') == $kelas->id ? 'selected' : '' }}>{{ $kelas->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-navy-400 uppercase tracking-widest ml-1">Kata Sandi</label>
                    <input type="password" name="password" 
                        class="w-full px-4 py-3.5 bg-navy-50 dark:bg-white/5 border @error('password') border-rose-500 @else border-navy-100 dark:border-white/10 @enderror rounded-xl text-sm font-bold focus:border-navy-950 dark:focus:border-white outline-none transition-all" 
                        placeholder="Kosongkan jika ingin menggunakan NIP/NISN">
                    <p class="text-[9px] text-navy-400 font-bold uppercase tracking-wider mt-1 ml-1">*Default: NIP/NISN jika kosong</p>
                    @error('password')
                        <p class="text-[10px] font-bold text-rose-500 mt-1 ml-1 uppercase">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="pt-6">
                <button type="submit" class="w-full py-4 bg-navy-950 dark:bg-white text-white dark:text-navy-950 text-xs font-black rounded-xl uppercase tracking-[0.2em] hover:opacity-90 transition-all shadow-xl shadow-navy-950/20 active:scale-[0.98]">
                    Simpan Data Pengguna
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role-select');
        const nipContainer = document.getElementById('nip-container');
        const nisnContainer = document.getElementById('nisn-container');
        const kelasContainer = document.getElementById('kelas-container');

        function toggleInputs() {
            const role = roleSelect.value;
            nipContainer.style.display = 'none';
            nisnContainer.style.display = 'none';
            kelasContainer.style.display = 'none';

            if (role === 'guru' || role === 'walikelas') {
                nipContainer.style.display = 'block';
            } else if (role === 'siswa' || role === 'orangtua') {
                nisnContainer.style.display = 'block';
                kelasContainer.style.display = 'block';
            }
        }

        roleSelect.addEventListener('change', toggleInputs);
        toggleInputs();
    });
</script>
@endsection
