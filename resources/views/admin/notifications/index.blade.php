@extends('layouts.admin')

@section('title', 'Notifikasi & Konfigurasi')
@section('page_title', 'Notifikasi')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    @if(session('success'))
    <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-xl mb-6 flex items-center shadow-sm">
        <i class="fas fa-check-circle mr-3"></i>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Password Reset Requests -->
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 p-8">
            <h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center">
                <i class="fas fa-key text-amber-500 mr-3"></i> Permintaan Reset Password
            </h3>
            
            <div class="space-y-4">
                @forelse($resetRequests as $req)
                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100 group hover:border-indigo-200 transition">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-indigo-600 shadow-sm font-bold">
                            {{ strtoupper(substr($req->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800">{{ $req->user->name }}</h4>
                            <p class="text-xs text-slate-500">ID: {{ $req->user->username ?? $req->user->email }}</p>
                        </div>
                    </div>
                    <form action="{{ route('admin.password-resets.resolve', $req->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-indigo-700 transition">
                            Reset & Setujui
                        </button>
                    </form>
                </div>
                @empty
                <div class="text-center py-8">
                    <p class="text-slate-400 italic">Tidak ada permintaan reset password.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Notification Config -->
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 p-8">
            <h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center">
                <i class="fas fa-envelope-open-text text-indigo-500 mr-3"></i> Konfigurasi Email
            </h3>
            
            <form action="{{ route('admin.notifications.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Subjek Email</label>
                    <input type="text" name="mail_subject" value="{{ $config['mail_subject'] }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Isi Pesan</label>
                    <textarea name="mail_body" rows="4" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition">{{ $config['mail_body'] }}</textarea>
                </div>
                <div class="flex items-center space-x-3">
                    <input type="checkbox" name="auto_send" {{ $config['auto_send'] ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 rounded">
                    <span class="text-sm font-medium text-slate-600">Kirim otomatis saat raport diterbitkan</span>
                </div>
                <button type="submit" class="w-full bg-slate-800 text-white py-3.5 rounded-xl font-bold hover:bg-slate-900 transition shadow-lg">
                    Simpan Konfigurasi
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
