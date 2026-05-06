@extends('layouts.admin')

@section('title', 'Data Guru')
@section('page_title', 'Master Data')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Data Tenaga Pengajar</h2>
            <p class="text-slate-500 font-medium italic">Manajemen data guru SMKN 1 Sungailiat.</p>
        </div>
        <a href="{{ route('admin.gurus.create') }}" class="inline-flex items-center justify-center space-x-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-2xl shadow-lg shadow-indigo-200 transition-all active:scale-95">
            <i class="fas fa-plus"></i>
            <span>Tambah Guru</span>
        </a>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-xl mb-6 flex items-center shadow-sm">
        <i class="fas fa-check-circle mr-3"></i>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($gurus as $guru)
        <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden group hover:-translate-y-1 transition-all duration-300">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 text-xl font-bold">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    @if($guru->spesialisasi)
                    <span class="px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-[10px] font-extrabold uppercase tracking-widest">
                        {{ $guru->spesialisasi }}
                    </span>
                    @endif
                </div>
                <h4 class="text-lg font-bold text-slate-800 truncate">{{ $guru->nama }}{{ $guru->gelar ? ', ' . $guru->gelar : '' }}</h4>
                <p class="text-sm text-slate-400 font-medium mb-6">NIP: <span class="text-slate-600">{{ $guru->nip }}</span></p>
                
                <div class="flex items-center justify-between pt-4 border-t border-slate-50">
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('admin.gurus.edit', $guru->id) }}" class="text-slate-400 hover:text-indigo-600 transition">
                            <i class="far fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.gurus.destroy', $guru->id) }}" method="POST" onsubmit="return confirm('Hapus data guru ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-slate-400 hover:text-red-500 transition">
                                <i class="far fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white p-12 rounded-[2rem] border-2 border-dashed border-slate-200 text-center">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300 text-3xl">
                <i class="fas fa-user-slash"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800">Belum ada data guru</h3>
            <p class="text-slate-400 mt-1">Silakan tambah data pengajar untuk mengelola nilai.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $gurus->links() }}
    </div>
</div>
@endsection
