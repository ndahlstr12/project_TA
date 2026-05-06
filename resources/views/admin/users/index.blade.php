@extends('layouts.admin')

@section('title', 'Manajemen User')
@section('page_title', 'Manajemen Pengguna')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Manajemen Pengguna</h2>
            <p class="text-slate-500 font-medium">Kelola akses Admin, Guru, Siswa, dan Orang Tua.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center justify-center space-x-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-2xl shadow-lg shadow-indigo-200 transition-all active:scale-95">
            <i class="fas fa-plus"></i>
            <span>Tambah Pengguna</span>
        </a>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-xl mb-6 flex items-center shadow-sm">
        <i class="fas fa-check-circle mr-3"></i>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
    @endif

    <!-- User Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($users as $user)
        <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden group hover:-translate-y-1 transition-all duration-300">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 text-xl font-bold">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-widest 
                        @if($user->role == 'admin') bg-red-50 text-red-600 
                        @elseif($user->role == 'guru') bg-blue-50 text-blue-600 
                        @elseif($user->role == 'siswa') bg-emerald-50 text-emerald-600 
                        @else bg-orange-50 text-orange-600 @endif">
                        {{ $user->role }}
                    </span>
                </div>
                <h4 class="text-lg font-bold text-slate-800 truncate">{{ $user->name }}</h4>
                <p class="text-sm text-slate-500 mb-6 truncate">{{ $user->email }}</p>
                
                <div class="flex items-center justify-between pt-4 border-t border-slate-50">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="text-indigo-600 hover:text-indigo-800 font-bold text-sm flex items-center">
                        <i class="far fa-edit mr-2"></i> Edit
                    </a>
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus pengguna ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-400 hover:text-red-600 font-bold text-sm flex items-center">
                            <i class="far fa-trash-alt mr-2"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-8 text-indigo-600">
        {{ $users->links() }}
    </div>
</div>
@endsection
