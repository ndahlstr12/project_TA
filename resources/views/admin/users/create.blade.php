@extends('layouts.admin')

@section('title', 'Tambah Pengguna')
@section('page_title', 'Manajemen Pengguna')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Back Button -->
    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-slate-400 hover:text-indigo-600 mb-6 transition font-semibold group">
        <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i>
        Kembali ke Daftar
    </a>

    <!-- Card Form -->
    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-2xl shadow-slate-200/50 overflow-hidden">
        <div class="bg-indigo-950 p-8 text-white relative overflow-hidden">
            <div class="relative z-10">
                <h2 class="text-2xl font-bold tracking-tight">Tambah Pengguna Baru</h2>
                <p class="text-indigo-300 text-sm mt-1">Silakan lengkapi data akses sistem di bawah ini.</p>
            </div>
            <!-- Decorative Circle -->
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-indigo-500/20 rounded-full"></div>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Lengkap -->
                <div class="space-y-2">
                    <label class="text-sm font-bold text-slate-700 ml-1">Nama Lengkap</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <i class="far fa-user"></i>
                        </span>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Budi Santoso, S.Pd" 
                               class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none" required>
                    </div>
                    @error('name') <p class="text-red-500 text-xs mt-1 ml-1 font-medium">{{ $message }}</p> @enderror
                </div>

                <!-- Email -->
                <div class="space-y-2">
                    <label class="text-sm font-bold text-slate-700 ml-1">Alamat Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <i class="far fa-envelope"></i>
                        </span>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="email@sekolah.sch.id" 
                               class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none" required>
                    </div>
                    @error('email') <p class="text-red-500 text-xs mt-1 ml-1 font-medium">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Role / Aktor -->
                <div class="space-y-2">
                    <label class="text-sm font-bold text-slate-700 ml-1">Peran / Aktor</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <i class="fas fa-user-tag"></i>
                        </span>
                        <select name="role" id="role-select" class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none appearance-none cursor-pointer">
                            <option value="siswa" {{ old('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                            <option value="guru" {{ old('role') == 'guru' ? 'selected' : '' }}>Guru Pengajar</option>
                            <option value="walikelas" {{ old('role') == 'walikelas' ? 'selected' : '' }}>Wali Kelas</option>
                            <option value="orangtua" {{ old('role') == 'orangtua' ? 'selected' : '' }}>Orang Tua</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-400">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>

                <!-- NIP (Hidden by default, shown for Guru/Wali Kelas) -->
                <div class="space-y-2" id="nip-container" style="display: none;">
                    <label class="text-sm font-bold text-slate-700 ml-1">NIP Guru</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <i class="fas fa-id-badge"></i>
                        </span>
                        <input type="text" name="nip" value="{{ old('nip') }}" placeholder="Contoh: 19800101XXXXXXXX" 
                               class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none">
                    </div>
                    @error('nip') <p class="text-red-500 text-xs mt-1 ml-1 font-medium">{{ $message }}</p> @enderror
                </div>

                <!-- NISN (Hidden by default, shown for Siswa/Orang Tua) -->
                <div class="space-y-2" id="nisn-container" style="display: none;">
                    <label class="text-sm font-bold text-slate-700 ml-1">NISN Siswa</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <i class="fas fa-fingerprint"></i>
                        </span>
                        <input type="text" name="nisn" value="{{ old('nisn') }}" placeholder="Masukkan 10 digit NISN" 
                               class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none">
                    </div>
                    <p class="text-[10px] text-slate-500 mt-1 ml-1">*Pastikan data siswa sudah diinput di Master Data Siswa</p>
                    @error('nisn') <p class="text-red-500 text-xs mt-1 ml-1 font-medium">{{ $message }}</p> @enderror
                </div>

                <!-- Password -->
                <div class="space-y-2" id="password-container">
                    <label class="text-sm font-bold text-slate-700 ml-1">Kata Sandi</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" name="password" placeholder="Minimal 8 karakter" 
                               class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none" required>
                    </div>
                    @error('password') <p class="text-red-500 text-xs mt-1 ml-1 font-medium">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 rounded-2xl shadow-xl shadow-indigo-200 transition-all active:scale-[0.98] flex items-center justify-center space-x-2">
                    <i class="fas fa-save"></i>
                    <span>Simpan Pengguna</span>
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

        function toggleInputs() {
            const role = roleSelect.value;
            
            // Reset visibility
            nipContainer.style.display = 'none';
            nisnContainer.style.display = 'none';

            if (role === 'guru' || role === 'walikelas') {
                nipContainer.style.display = 'block';
            } else if (role === 'siswa' || role === 'orangtua') {
                nisnContainer.style.display = 'block';
            }
        }

        roleSelect.addEventListener('change', toggleInputs);
        toggleInputs(); // Jalankan saat awal load
    });
</script>
@endsection
