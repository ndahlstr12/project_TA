@extends('layouts.admin')

@section('title', 'Faculty')
@section('page_title', 'Instructors Registry')

@section('content')
<div class="space-y-8">
    
    <!-- Pro Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-base pb-8">
        <div>
            <h1 class="text-3xl font-bold tracking-tighter">Faculty Registry</h1>
            <p class="text-sm text-neutral-500 mt-2">Certified instructors and academic personnel management system.</p>
        </div>
        <div class="flex gap-3">
            <button class="px-4 py-2 text-xs font-bold border border-base rounded-lg hover:bg-neutral-50 dark:hover:bg-white/5 transition-all">
                Export Data
            </button>
            <a href="{{ route('admin.gurus.create') }}" class="px-5 py-2 text-xs font-bold bg-neutral-950 dark:bg-white text-white dark:text-neutral-950 rounded-lg hover:opacity-90 transition-all flex items-center gap-2">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                Add Instructor
            </a>
        </div>
    </div>

    <!-- Feedback -->
    @if(session('success'))
    <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-emerald-500 flex items-center justify-center text-white">
            <i data-lucide="check" class="w-4 h-4"></i>
        </div>
        <p class="text-xs font-bold text-emerald-600 tracking-tight">{{ session('success') }}</p>
    </div>
    @endif

    <!-- Data Surface -->
    <div class="card-pro overflow-hidden">
        <!-- Control Bar -->
        <div class="p-4 border-b border-base bg-neutral-50/30 dark:bg-white/5 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center bg-white dark:bg-surface-900 border border-base rounded-lg px-3 py-1.5 gap-2 focus-within:ring-2 focus-within:ring-accent/10 transition-all">
                <i data-lucide="search" class="w-3.5 h-3.5 text-neutral-400"></i>
                <input type="text" placeholder="Search by NIP or name..." class="bg-transparent border-none focus:ring-0 text-xs font-medium w-48 lg:w-80">
            </div>
            
            <div class="flex items-center gap-2">
                <button class="px-3 py-1.5 text-[10px] font-bold border border-base rounded-lg hover:bg-neutral-100 dark:hover:bg-white/5 transition-colors uppercase tracking-widest text-neutral-500">
                    Filter Faculty
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-neutral-50/30 dark:bg-white/5 border-b border-base text-[10px] font-bold text-neutral-400 uppercase tracking-widest">
                        <th class="px-8 py-4">Instructor Profile</th>
                        <th class="px-8 py-4">Institutional ID</th>
                        <th class="px-8 py-4">Specialization</th>
                        <th class="px-8 py-4 text-right">System Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-base">
                    @forelse($gurus as $guru)
                    <tr class="hover:bg-neutral-50/30 dark:hover:bg-white/5 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-neutral-100 dark:bg-white/5 border border-base flex items-center justify-center shrink-0">
                                    <i data-lucide="briefcase" class="w-4 h-4 text-neutral-400"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-neutral-800 dark:text-neutral-200 tracking-tight">{{ $guru->nama }}{{ $guru->gelar ? ', ' . $guru->gelar : '' }}</p>
                                    <p class="text-[10px] text-neutral-400 font-medium uppercase tracking-widest mt-0.5">Faculty Member</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <span class="text-xs font-mono font-bold text-neutral-600 dark:text-neutral-400">{{ $guru->nip }}</span>
                        </td>
                        <td class="px-8 py-5">
                            <span class="px-2.5 py-1 bg-blue-500/5 text-blue-600 dark:text-blue-400 rounded-md text-[10px] font-black uppercase tracking-tighter border border-blue-500/10">
                                {{ $guru->spesialisasi ?: 'General' }}
                            </span>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.gurus.edit', $guru->id) }}" class="p-2 text-neutral-400 hover:text-neutral-900 dark:hover:text-white hover:bg-neutral-100 dark:hover:bg-white/5 rounded-lg transition-colors">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('admin.gurus.destroy', $guru->id) }}" method="POST" onsubmit="return confirm('Archive data?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-rose-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-500/10 rounded-lg transition-colors">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center gap-3 opacity-20">
                                <i data-lucide="user-x" class="w-10 h-10"></i>
                                <p class="text-xs font-bold uppercase tracking-[0.2em]">No instructors registered</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="p-6 border-t border-base bg-neutral-50/30 dark:bg-white/5 flex items-center justify-between">
            <p class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest">Faculty Management Terminal &bull; {{ $gurus->total() }} Entities</p>
            <div>
                {{ $gurus->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
