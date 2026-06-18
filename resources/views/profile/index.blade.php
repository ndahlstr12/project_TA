@extends('layouts.admin')

@section('title', 'Profil Saya')
@section('page_title', 'Pengaturan Profil')

@section('content')
<div class="max-w-5xl mx-auto space-y-10">
    
    <!-- Pro Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-base pb-8">
        <div>
            <h1 class="text-3xl font-bold tracking-tighter">Konfigurasi Profil</h1>
            <p class="text-sm text-neutral-500 mt-2 uppercase tracking-widest font-bold">Personalisasi Identitas & Keamanan Akun</p>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-emerald-500 flex items-center justify-center text-white">
            <i data-lucide="check" class="w-4 h-4"></i>
        </div>
        <p class="text-xs font-bold text-emerald-600 tracking-tight">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('info'))
    <div class="p-4 bg-blue-500/10 border border-blue-500/20 rounded-xl flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-blue-500 flex items-center justify-center text-white">
            <i data-lucide="info" class="w-4 h-4"></i>
        </div>
        <p class="text-xs font-bold text-blue-600 tracking-tight">{{ session('info') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 bg-rose-500/10 border border-rose-500/20 rounded-xl flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-rose-500 flex items-center justify-center text-white">
            <i data-lucide="alert-circle" class="w-4 h-4"></i>
        </div>
        <p class="text-xs font-bold text-rose-600 tracking-tight">{{ session('error') }}</p>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Profile Card -->
        <div class="lg:col-span-1 space-y-6">
            <div class="card-pro overflow-hidden">
                <div class="h-32 bg-neutral-900 dark:bg-black relative">
                    <div class="absolute inset-0 opacity-20 flex items-center justify-center overflow-hidden">
                        <i data-lucide="command" class="w-48 h-48 text-white"></i>
                    </div>
                </div>
                <div class="px-6 pb-8 -mt-12 text-center relative z-10">
                    <div class="relative inline-block group">
                        @if(Auth::user()->foto)
                            <img src="{{ asset('storage/' . Auth::user()->foto) }}" 
                                 alt="Profile" 
                                 class="w-24 h-24 rounded-2xl mx-auto border-4 border-white dark:border-surface-800 shadow-xl mb-4 object-cover">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0a0a0a&color=fff&size=128" 
                                 alt="Profile" 
                                 class="w-24 h-24 rounded-2xl mx-auto border-4 border-white dark:border-surface-800 shadow-xl mb-4 grayscale dark:invert">
                        @endif
                        <label for="foto-input" class="absolute inset-0 flex items-center justify-center bg-black/50 text-white rounded-2xl opacity-0 group-hover:opacity-100 cursor-pointer transition-opacity">
                            <i data-lucide="camera" class="w-6 h-6"></i>
                        </label>
                    </div>
                    <h3 class="text-xl font-bold tracking-tighter">{{ Auth::user()->name }}</h3>
                    <p class="text-[10px] font-bold text-neutral-400 uppercase tracking-[0.2em] mt-2">{{ Auth::user()->role }}</p>
                </div>
            </div>

            <div class="card-pro p-6">
                <h4 class="text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-6">Status Keanggotaan</h4>
                <div class="space-y-4">
                    <div class="flex items-center justify-between py-2 border-b border-base">
                        <span class="text-[11px] font-medium text-neutral-500">Terdaftar Sejak</span>
                        <span class="text-xs font-bold">{{ Auth::user()->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-base">
                        <span class="text-[11px] font-medium text-neutral-500">Status Akun</span>
                        <span class="px-2 py-0.5 bg-emerald-500/10 text-emerald-500 text-[10px] font-bold rounded uppercase">Aktif</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Forms -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Update Profil -->
            <div class="card-pro p-8 lg:p-10">
                <div class="flex items-center gap-3 mb-10">
                    <div class="w-8 h-8 rounded-lg bg-accent/10 flex items-center justify-center text-accent">
                        <i data-lucide="user-cog" class="w-4 h-4"></i>
                    </div>
                    <h4 class="text-sm font-bold uppercase tracking-widest">Informasi Dasar</h4>
                </div>

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    @method('PUT')
                    
                    <input type="file" name="foto" id="foto-input" class="hidden" accept="image/*" onchange="previewImage(event)">

                    <div id="image-preview-container" class="hidden space-y-2">
                        <label class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest ml-1">Pratinjau Foto Baru</label>
                        <div class="flex items-center gap-4 p-4 bg-neutral-50 dark:bg-white/5 border border-dashed border-base rounded-xl">
                            <img id="image-preview" src="#" alt="Preview" class="w-16 h-16 rounded-lg object-cover">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-bold text-neutral-900 dark:text-white uppercase tracking-tight">Siap diunggah</span>
                                <span class="text-[9px] text-neutral-500 italic mt-0.5">* Simpan perubahan untuk menerapkan foto ini.</span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" 
                                class="w-full px-4 py-3 bg-neutral-50 dark:bg-white/5 border @error('name') border-rose-500 @else border-base @enderror rounded-lg text-sm font-medium focus:border-neutral-950 dark:focus:border-white outline-none transition-all" required>
                            @error('name')
                                <p class="text-[10px] font-bold text-rose-500 mt-1 ml-1 uppercase tracking-tight">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest ml-1">Username / ID</label>
                            <input type="text" name="username" value="{{ old('username', Auth::user()->username) }}" 
                                class="w-full px-4 py-3 bg-neutral-50 dark:bg-white/5 border @error('username') border-rose-500 @else border-base @enderror rounded-lg text-sm font-medium focus:border-neutral-950 dark:focus:border-white outline-none transition-all">
                            @error('username')
                                <p class="text-[10px] font-bold text-rose-500 mt-1 ml-1 uppercase tracking-tight">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest ml-1">Email Sistem</label>
                        <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" 
                            class="w-full px-4 py-3 bg-neutral-50 dark:bg-white/5 border @error('email') border-rose-500 @else border-base @enderror rounded-lg text-sm font-medium focus:border-neutral-950 dark:focus:border-white outline-none transition-all">
                        @error('email')
                            <p class="text-[10px] font-bold text-rose-500 mt-1 ml-1 uppercase tracking-tight">{{ $message }}</p>
                        @enderror
                    </div>

                    @if((Auth::user()->role === 'siswa' || Auth::user()->role === 'orangtua') && Auth::user()->siswa)
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest ml-1">Email Orang Tua (Notifikasi Raport)</label>
                        <input type="email" name="email_orang_tua" value="{{ old('email_orang_tua', Auth::user()->siswa->email_orang_tua) }}" 
                            class="w-full px-4 py-3 bg-neutral-50 dark:bg-white/5 border @error('email_orang_tua') border-rose-500 @else border-base @enderror rounded-lg text-sm font-medium focus:border-neutral-950 dark:focus:border-white outline-none transition-all" placeholder="contoh@gmail.com">
                        @error('email_orang_tua')
                            <p class="text-[10px] font-bold text-rose-500 mt-1 ml-1 uppercase tracking-tight">{{ $message }}</p>
                        @enderror
                        <p class="text-[9px] text-neutral-400 italic mt-2">* Digunakan untuk transmisi dokumen raport PDF secara digital.</p>
                    </div>
                    @endif

                    @if(($user->role === 'guru' || $user->role === 'walikelas') && $user->guru)
                    <div class="space-y-4 pt-4 border-t border-base">
                        <div class="flex items-center gap-2 mb-2">
                            <i data-lucide="signature" class="w-4 h-4 text-neutral-400"></i>
                            <label class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest">Tanda Tangan Digital</label>
                        </div>
                        
                        <div class="flex flex-col md:flex-row gap-6 items-start">
                            <div class="w-full md:w-1/3">
                                <p class="text-[10px] font-bold text-neutral-400 uppercase tracking-tight mb-2">TTD Saat Ini</p>
                                <div class="h-32 bg-neutral-50 dark:bg-white/5 border border-base rounded-xl flex items-center justify-center overflow-hidden p-4">
                                    @if(Auth::user()->guru->ttd_digital)
                                        <img src="{{ asset('storage/' . Auth::user()->guru->ttd_digital) }}" class="max-w-full max-h-full object-contain grayscale dark:invert">
                                    @else
                                        <div class="text-center">
                                            <i data-lucide="image-off" class="w-8 h-8 text-neutral-300 mx-auto mb-2"></i>
                                            <p class="text-[9px] font-bold text-neutral-400 uppercase tracking-tighter">Belum Ada</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="w-full md:w-2/3 space-y-4">
                                <p class="text-[10px] font-bold text-neutral-400 uppercase tracking-tight">Unggah TTD Baru</p>
                                <div class="relative group h-[128px]">
                                    <input type="file" name="ttd_digital" id="ttd-input" 
                                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" 
                                           accept="image/png"
                                           onchange="previewTTD(event)">
                                    <div class="absolute inset-0 border-2 border-dashed border-base group-hover:border-neutral-950 dark:group-hover:border-white rounded-2xl flex flex-col items-center justify-center transition-all bg-neutral-50/50 dark:bg-white/5">
                                        <i data-lucide="upload-cloud" class="w-8 h-8 text-neutral-300 group-hover:text-neutral-950 dark:group-hover:text-white mb-2 transition-colors"></i>
                                        <p class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest group-hover:text-neutral-950 dark:group-hover:text-white transition-colors">Klik atau seret file</p>
                                        <p class="text-[9px] text-neutral-400 mt-1">Format PNG & Tanpa Background (Maks. 1MB)</p>
                                    </div>
                                </div>
                                <div id="ttd-preview-container" class="hidden flex items-center gap-3 p-3 bg-blue-500/5 border border-blue-500/10 rounded-xl">
                                    <div class="w-12 h-12 rounded bg-white flex items-center justify-center p-1 border border-base overflow-hidden">
                                        <img id="ttd-preview" src="#" class="max-w-full max-h-full object-contain grayscale">
                                    </div>
                                    <p class="text-[9px] font-bold text-blue-500 uppercase tracking-widest">Siap Diperbarui</p>
                                </div>
                            </div>
                        </div>
                        <p class="text-[9px] text-rose-500 font-medium italic mt-2">* Wajib format PNG dan sudah di-remove backgroundnya untuk hasil terbaik di raport.</p>
                    </div>
                    @endif

                    <div class="pt-4">
                        <button type="submit" class="px-8 py-3.5 bg-neutral-950 dark:bg-white text-white dark:text-neutral-950 text-[10px] font-bold rounded-lg uppercase tracking-widest hover:opacity-90 transition-all active:scale-[0.98]">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            @if(Auth::user()->role !== 'admin')
            <!-- Ganti Password -->
            <div class="card-pro p-8 lg:p-10">
                <div class="flex items-center gap-3 mb-10">
                    <div class="w-8 h-8 rounded-lg bg-amber-500/10 flex items-center justify-center text-amber-500">
                        <i data-lucide="shield-lock" class="w-4 h-4"></i>
                    </div>
                    <h4 class="text-sm font-bold uppercase tracking-widest">Kredensial Keamanan</h4>
                </div>

                <form action="{{ route('profile.password') }}" method="POST" class="space-y-8">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest ml-1">Kata Sandi Saat Ini</label>
                        <input type="password" name="current_password" 
                            class="w-full px-4 py-3 bg-neutral-50 dark:bg-white/5 border @error('current_password') border-rose-500 @else border-base @enderror rounded-lg text-sm font-medium focus:border-neutral-950 dark:focus:border-white outline-none transition-all" required>
                        @error('current_password')
                            <p class="text-[10px] font-bold text-rose-500 mt-1 ml-1 uppercase tracking-tight">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest ml-1">Kata Sandi Baru</label>
                            <input type="password" name="password" 
                                class="w-full px-4 py-3 bg-neutral-50 dark:bg-white/5 border @error('password') border-rose-500 @else border-base @enderror rounded-lg text-sm font-medium focus:border-neutral-950 dark:focus:border-white outline-none transition-all" required>
                            @error('password')
                                <p class="text-[10px] font-bold text-rose-500 mt-1 ml-1 uppercase tracking-tight">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest ml-1">Konfirmasi Sandi</label>
                            <input type="password" name="password_confirmation" 
                                class="w-full px-4 py-3 bg-neutral-50 dark:bg-white/5 border border-base rounded-lg text-sm font-medium focus:border-neutral-950 dark:focus:border-white outline-none transition-all" required>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="px-8 py-3.5 bg-amber-500 text-white text-[10px] font-bold rounded-lg uppercase tracking-widest hover:bg-amber-600 transition-all active:scale-[0.98]">
                            Perbarui Kredensial
                        </button>
                    </div>
                </form>
            </div>
            @else
            <!-- Notice for Admin -->
            <div class="card-pro p-8 lg:p-10 border-amber-500/20 bg-amber-500/5">
                <div class="flex items-start gap-4 text-amber-600 dark:text-amber-500">
                    <div class="p-3 bg-amber-500/10 rounded-xl">
                        <i data-lucide="shield-alert" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold uppercase tracking-widest mb-1">Kebijakan Keamanan</h4>
                        <p class="text-xs font-medium leading-relaxed opacity-80">
                            Akun Administrator tidak diperbolehkan mengganti kata sandi melalui panel ini demi menjaga integritas sistem. Hubungi administrator database untuk perubahan kredensial tingkat tinggi.
                        </p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('image-preview');
        const container = document.getElementById('image-preview-container');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                container.classList.remove('hidden');
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewTTD(event) {
        const input = event.target;
        const preview = document.getElementById('ttd-preview');
        const container = document.getElementById('ttd-preview-container');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                container.classList.remove('hidden');
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush

