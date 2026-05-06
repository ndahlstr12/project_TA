@extends('layouts.admin')

@section('title', 'Notifikasi Email')
@section('page_title', 'Pusat Data & Pesan')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Pengaturan Notifikasi</h2>
        <p class="text-slate-500 font-medium">Konfigurasi pengiriman raport otomatis ke email orang tua.</p>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-xl mb-6 flex items-center shadow-sm">
        <i class="fas fa-check-circle mr-3"></i>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
    @endif

    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden">
        <div class="bg-indigo-900 p-8 text-white">
            <div class="flex items-center space-x-4">
                <div class="bg-indigo-500/20 p-4 rounded-2xl border border-indigo-400/30">
                    <i class="fas fa-envelope-open-text text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold">Template Email Raport</h3>
                    <p class="text-indigo-300 text-xs mt-1">Email ini akan dikirimkan otomatis saat raport diterbitkan.</p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.notifications.update') }}" method="POST" class="p-8 space-y-6">
            @csrf
            @method('PUT')
            
            <div class="space-y-2">
                <label class="text-sm font-bold text-slate-700 ml-1">Subjek Email</label>
                <input type="text" name="subject" value="{{ $config['mail_subject'] }}" 
                       class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none transition-all font-medium text-slate-600">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-bold text-slate-700 ml-1">Isi Pesan (Body)</label>
                <textarea name="body" rows="6" 
                          class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none transition-all font-medium text-slate-600 italic leading-relaxed">{{ $config['mail_body'] }}</textarea>
                <p class="text-[10px] text-slate-400 mt-1 ml-1 font-medium">*Gunakan placeholder seperti {nama_siswa}, {semester} untuk personalisasi.</p>
            </div>

            <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100 flex items-center justify-between">
                <div>
                    <h4 class="font-bold text-slate-800">Kirim Otomatis</h4>
                    <p class="text-xs text-slate-500">Kirim email segera setelah Wali Kelas memvalidasi raport.</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" class="sr-only peer" checked>
                    <div class="w-14 h-7 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-indigo-600"></div>
                </label>
            </div>

            <div class="pt-4 flex items-center space-x-4">
                <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 rounded-2xl shadow-xl shadow-indigo-200 transition-all flex items-center justify-center space-x-2">
                    <i class="fas fa-save"></i>
                    <span>Simpan Pengaturan</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
