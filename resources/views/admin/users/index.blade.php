@extends('layouts.admin')

@section('title', 'Kontrol Pengguna')
@section('page_title', 'Manajemen Pengguna')

@section('content')
<div x-data="{ modal: null }" class="space-y-8">
    
    <!-- Pro Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-base pb-8">
        <div>
            <h1 class="text-3xl font-bold tracking-tighter">Kontrol Pengguna</h1>
            <p class="text-sm text-neutral-500 mt-2">Kelola protokol akses untuk Administrator, Guru, dan Siswa.</p>
        </div>
        <div class="flex gap-3">
            <button @click="modal = 'import'" class="px-4 py-2 text-xs font-bold border border-base rounded-lg hover:bg-neutral-50 dark:hover:bg-white/5 transition-all flex items-center gap-2">
                <i data-lucide="upload" class="w-3.5 h-3.5"></i>
                Impor Data
            </button>
            <a href="{{ route('admin.users.create') }}" class="px-5 py-2 text-xs font-bold bg-neutral-950 dark:bg-white text-white dark:text-neutral-950 rounded-lg hover:opacity-90 transition-all flex items-center gap-2">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                Tambah Pengguna
            </a>
        </div>
    </div>

    <!-- System Feedback -->
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" class="p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center justify-between group">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-emerald-500 flex items-center justify-center text-white">
                <i data-lucide="check-circle-2" class="w-4 h-4"></i>
            </div>
            <p class="text-xs font-bold text-emerald-600 tracking-tight">{{ session('success') }}</p>
        </div>
        <button @click="show = false" class="text-emerald-500 opacity-0 group-hover:opacity-100 transition-opacity"><i data-lucide="x" class="w-4 h-4"></i></button>
    </div>
    @endif

    @if(session('error'))
    <div x-data="{ show: true }" x-show="show" class="p-4 bg-rose-500/10 border border-rose-500/20 rounded-xl flex items-center justify-between group">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-rose-500 flex items-center justify-center text-white">
                <i data-lucide="alert-circle" class="w-4 h-4"></i>
            </div>
            <p class="text-xs font-bold text-rose-600 tracking-tight">{{ session('error') }}</p>
        </div>
        <button @click="show = false" class="text-rose-500 opacity-0 group-hover:opacity-100 transition-opacity"><i data-lucide="x" class="w-4 h-4"></i></button>
    </div>
    @endif

    <!-- Advanced Filter Engine -->
    <div class="card-pro overflow-hidden">
        <!-- Control Bar -->
        <div class="p-4 border-b border-base bg-neutral-50/30 dark:bg-white/5 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center bg-white dark:bg-surface-900 border border-base rounded-lg px-3 py-1.5 gap-2 focus-within:ring-2 focus-within:ring-accent/10 transition-all">
                <i data-lucide="search" class="w-3.5 h-3.5 text-neutral-400"></i>
                <input type="text" placeholder="Cari berdasarkan nama, ID, atau email..." class="bg-transparent border-none focus:ring-0 text-xs font-medium w-48 lg:w-80">
            </div>
            
            <div class="flex items-center gap-2">
                <div class="flex bg-neutral-100 dark:bg-white/5 p-1 rounded-lg">
                    <a href="{{ route('admin.users.index') }}" class="px-4 py-1.5 text-[10px] font-bold {{ $activeTab === 'all' ? 'bg-white dark:bg-white/10 shadow-sm rounded text-neutral-900 dark:text-white' : 'text-neutral-400' }} uppercase tracking-widest transition-all">Semua</a>
                    <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" class="px-4 py-1.5 text-[10px] font-bold {{ $activeTab === 'admin' ? 'bg-white dark:bg-white/10 shadow-sm rounded text-neutral-900 dark:text-white' : 'text-neutral-400' }} uppercase tracking-widest transition-all">Admin</a>
                    <a href="{{ route('admin.users.index', ['role' => 'guru']) }}" class="px-4 py-1.5 text-[10px] font-bold {{ $activeTab === 'guru' ? 'bg-white dark:bg-white/10 shadow-sm rounded text-neutral-900 dark:text-white' : 'text-neutral-400' }} uppercase tracking-widest transition-all">Guru</a>
                    <a href="{{ route('admin.users.index', ['role' => 'siswa']) }}" class="px-4 py-1.5 text-[10px] font-bold {{ $activeTab === 'siswa' ? 'bg-white dark:bg-white/10 shadow-sm rounded text-neutral-900 dark:text-white' : 'text-neutral-400' }} uppercase tracking-widest transition-all">Siswa</a>
                </div>
                <button class="p-2 border border-base rounded-lg hover:bg-neutral-100 dark:hover:bg-white/5 transition-colors">
                    <i data-lucide="filter" class="w-3.5 h-3.5 text-neutral-500"></i>
                </button>
            </div>
        </div>

        <!-- High-Density Registry Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-neutral-50/30 dark:bg-white/5 border-b border-base text-[10px] font-bold text-neutral-400 uppercase tracking-widest">
                        <th class="px-8 py-4 w-10"><input type="checkbox" class="rounded border-base text-accent focus:ring-0"></th>
                        <th class="px-8 py-4">Profil Identitas</th>
                        <th class="px-8 py-4">Level Akses</th>
                        <th class="px-8 py-4">Kredensial</th>
                        <th class="px-8 py-4 text-right">Aksi Sistem</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-base">
                    @foreach($users as $user)
                    <tr class="hover:bg-neutral-50/30 dark:hover:bg-white/5 transition-colors group">
                        <td class="px-8 py-5"><input type="checkbox" class="rounded border-base text-accent focus:ring-0"></td>
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-neutral-100 dark:bg-white/5 border border-base flex items-center justify-center overflow-hidden shrink-0">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=f5f5f5&color=0a0a0a" class="w-full h-full grayscale">
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-neutral-800 dark:text-neutral-200 tracking-tight">{{ $user->name }}</p>
                                    <p class="text-[10px] text-neutral-500 font-medium uppercase tracking-widest mt-0.5">ID: {{ $user->username ?: 'PROT-'.(1000 + $user->id) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-tighter border
                                @if($user->role == 'admin') bg-rose-500/5 text-rose-500 border-rose-500/10 
                                @elseif($user->role == 'guru') bg-blue-500/5 text-blue-500 border-blue-500/10 
                                @elseif($user->role == 'siswa') bg-emerald-500/5 text-emerald-500 border-emerald-500/10 
                                @else bg-neutral-500/5 text-neutral-500 border-neutral-500/10 @endif">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="px-8 py-5">
                            <div class="space-y-1">
                                <p class="text-[11px] font-semibold text-neutral-600 dark:text-neutral-400 flex items-center gap-2">
                                    <i data-lucide="mail" class="w-3 h-3 opacity-50"></i> {{ $user->email ?: 'tidak-ada-email@domain.com' }}
                                </p>
                                @if($user->last_login_at)
                                <p class="text-[9px] text-neutral-400 font-medium italic">Aktif terakhir {{ $user->last_login_at->diffForHumans() }}</p>
                                @endif
                            </div>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                @if(($user->role === 'siswa' || $user->role === 'orangtua') && $user->siswa)
                                <form action="{{ route('admin.notifications.send-raport', $user->siswa->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-2 text-emerald-500 hover:bg-emerald-50 dark:hover:bg-emerald-500/10 rounded-lg transition-colors" title="Kirim Raport">
                                        <i data-lucide="send" class="w-4 h-4"></i>
                                    </button>
                                </form>
                                @endif
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="p-2 text-neutral-400 hover:text-neutral-900 dark:hover:text-white hover:bg-neutral-100 dark:hover:bg-white/5 rounded-lg transition-colors">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Arsipkan identitas?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-rose-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-500/10 rounded-lg transition-colors">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Registry Footer -->
        <div class="p-6 border-t border-base bg-neutral-50/30 dark:bg-white/5 flex items-center justify-between">
            <p class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest">Pusat Identitas Global &bull; {{ $users->total() }} Entitas</p>
            <div class="pro-pagination">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    <!-- Import Modal -->
    <div x-show="modal === 'import'" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-6">
        <div @click="modal = null" class="absolute inset-0 bg-neutral-950/20 backdrop-blur-sm"></div>
        <div class="relative w-full max-w-md bg-white dark:bg-surface-800 border border-base rounded-2xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
            <div class="p-6 border-b border-base flex items-center justify-between">
                <h3 class="font-bold tracking-tight text-navy-900 dark:text-white">Impor Pengguna (CSV)</h3>
                <button @click="modal = null" class="text-neutral-400 hover:text-neutral-900"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <form action="{{ route('admin.users.import') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                @csrf
                <div class="space-y-4">
                    <div class="p-4 bg-blue-50 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-500/20 rounded-xl">
                        <p class="text-[10px] font-bold text-blue-600 dark:text-blue-400 uppercase leading-relaxed">
                            Format CSV: nama, email, password, role, identifier(nip/nisn)
                        </p>
                    </div>
                    <div class="relative group">
                        <input type="file" name="file" accept=".csv" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" required>
                        <div class="border-2 border-dashed border-base rounded-2xl p-8 text-center group-hover:border-navy-400 transition-all">
                            <i data-lucide="upload-cloud" class="w-8 h-8 mx-auto text-neutral-300 group-hover:text-navy-500 transition-colors mb-2"></i>
                            <p class="text-xs font-bold text-neutral-500 uppercase tracking-widest">Pilih File CSV</p>
                        </div>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button type="button" @click="modal = null" class="flex-1 py-3.5 text-xs font-bold text-neutral-400">Batal</button>
                    <button type="submit" class="flex-1 py-3.5 bg-navy-950 dark:bg-white text-white dark:text-navy-950 rounded-xl text-xs font-black uppercase tracking-widest shadow-xl active:scale-95">Mulai Impor</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
