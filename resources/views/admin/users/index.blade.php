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

    <!-- Filter Tabs -->
    <div class="flex flex-wrap gap-2 mb-8 bg-white p-2 rounded-2xl border border-slate-100 shadow-sm w-fit">
        <a href="{{ route('admin.users.index') }}" class="px-6 py-2.5 rounded-xl text-sm font-bold transition {{ $activeTab === 'all' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'text-slate-500 hover:bg-slate-50' }}">
            Semua
        </a>
        <a href="{{ route('admin.users.index', ['role' => 'guru']) }}" class="px-6 py-2.5 rounded-xl text-sm font-bold transition {{ $activeTab === 'guru' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'text-slate-500 hover:bg-slate-50' }}">
            Guru
        </a>
        <a href="{{ route('admin.users.index', ['role' => 'siswa']) }}" class="px-6 py-2.5 rounded-xl text-sm font-bold transition {{ $activeTab === 'siswa' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'text-slate-500 hover:bg-slate-50' }}">
            Siswa
        </a>
        <a href="{{ route('admin.users.index', ['role' => 'orangtua']) }}" class="px-6 py-2.5 rounded-xl text-sm font-bold transition {{ $activeTab === 'orangtua' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'text-slate-500 hover:bg-slate-50' }}">
            Orang Tua
        </a>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-xl mb-6 flex items-center shadow-sm">
        <i class="fas fa-check-circle mr-3"></i>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
    @endif

    <!-- User Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @foreach($users as $user)
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 group overflow-hidden">
            <div class="p-4">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 text-sm font-bold flex-shrink-0">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <h4 class="text-sm font-bold text-slate-800 truncate" title="{{ $user->name }}">{{ $user->name }}</h4>
                        <span class="inline-block px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-tight 
                            @if($user->role == 'admin') bg-red-50 text-red-600 
                            @elseif($user->role == 'guru') bg-blue-50 text-blue-600 
                            @elseif($user->role == 'siswa') bg-emerald-50 text-emerald-600 
                            @else bg-orange-50 text-orange-600 @endif">
                            {{ $user->role }}
                        </span>
                    </div>
                </div>
                
                <div class="space-y-1.5 mb-3">
                    <div class="flex items-center text-[11px] text-slate-500">
                        <i class="far fa-envelope w-4"></i>
                        <span class="truncate">{{ $user->email ?: '-' }}</span>
                    </div>
                    <div class="flex items-center text-[11px] text-slate-500">
                        <i class="far fa-id-badge w-4"></i>
                        <span class="truncate">{{ $user->username ?: 'No ID' }}</span>
                    </div>
                </div>

                @if(($user->role === 'siswa' || $user->role === 'orangtua') && $user->siswa)
                <div class="mb-3">
                    <form action="{{ route('admin.notifications.send-raport', $user->siswa->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white py-1.5 rounded-lg text-[10px] font-bold transition flex items-center justify-center">
                            <i class="far fa-envelope mr-1.5"></i> Kirim Raport ke Email
                        </button>
                    </form>
                </div>
                @endif
                
                <div class="flex items-center gap-2 pt-3 border-t border-slate-50">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="flex-1 text-center bg-slate-50 hover:bg-indigo-50 text-indigo-600 py-1.5 rounded-lg text-[10px] font-bold transition">
                        <i class="far fa-edit mr-1"></i> Edit
                    </a>
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus pengguna ini?')" class="flex-1">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full bg-slate-50 hover:bg-red-50 text-red-400 hover:text-red-600 py-1.5 rounded-lg text-[10px] font-bold transition">
                            <i class="far fa-trash-alt mr-1"></i> Hapus
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
