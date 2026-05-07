@extends('layouts.admin')

@section('title', 'Profil Saya')
@section('page_title', 'Pengaturan Profil')

@section('content')
<div class="max-w-4xl mx-auto">
    @if(session('success'))
        <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-lg mb-6 flex items-center shadow-sm">
            <i class="fas fa-check-circle mr-3"></i>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-700 p-4 rounded-lg mb-6 shadow-sm">
            <div class="flex items-center mb-2">
                <i class="fas fa-exclamation-circle mr-3"></i>
                <span class="text-sm font-bold">Terjadi Kesalahan:</span>
            </div>
            <ul class="list-disc list-inside text-xs font-medium ml-7 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Profile Card -->
        <div class="md:col-span-1">
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                <div class="h-24 bg-indigo-600"></div>
                <div class="px-6 pb-8 -mt-12 text-center">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6366f1&color=fff&size=128" alt="Profile" class="w-24 h-24 rounded-2xl mx-auto border-4 border-white shadow-lg mb-4">
                    <h3 class="text-xl font-bold text-slate-800">{{ Auth::user()->name }}</h3>
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-widest mt-1">{{ Auth::user()->role }}</p>
                </div>
            </div>
        </div>

        <!-- Forms -->
        <div class="md:col-span-2 space-y-8">
            <!-- Update Profil -->
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 p-8">
                <h4 class="text-lg font-bold text-slate-800 mb-6 flex items-center">
                    <i class="fas fa-user-circle text-indigo-500 mr-3"></i> Informasi Pribadi
                </h4>
                <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ Auth::user()->name }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Username / ID</label>
                        <input type="text" name="username" value="{{ Auth::user()->username }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Email Akun</label>
                        <input type="email" name="email" value="{{ Auth::user()->email }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition">
                    </div>

                    @if((Auth::user()->role === 'siswa' || Auth::user()->role === 'orangtua') && Auth::user()->siswa)
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Email Orang Tua (Untuk Notifikasi Raport)</label>
                        <input type="email" name="email_orang_tua" value="{{ Auth::user()->siswa->email_orang_tua }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition" placeholder="contoh@gmail.com">
                        <p class="text-[10px] text-slate-400 mt-1">* Email ini akan digunakan untuk menerima notifikasi raport PDF.</p>
                    </div>
                    @endif
                    <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                        Simpan Perubahan
                    </button>
                </form>
            </div>

            <!-- Ganti Password -->
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 p-8">
                <h4 class="text-lg font-bold text-slate-800 mb-6 flex items-center">
                    <i class="fas fa-key text-amber-500 mr-3"></i> Keamanan Akun
                </h4>
                <form action="{{ route('profile.password') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Password Saat Ini</label>
                        <input type="password" name="current_password" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Password Baru</label>
                        <input type="password" name="password" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition" required>
                    </div>
                    <button type="submit" class="bg-amber-500 text-white px-8 py-3 rounded-xl font-bold hover:bg-amber-600 transition shadow-lg shadow-amber-200">
                        Perbarui Kata Sandi
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
