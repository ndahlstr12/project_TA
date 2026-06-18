@extends('layouts.admin')

@section('title', 'Input Kehadiran Siswa')
@section('page_title', 'Manajemen Absensi')

@section('content')
<div class="space-y-8">
    
    <!-- Pro Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-base pb-8">
        <div>
            <h1 class="text-3xl font-bold tracking-tighter text-slate-900 dark:text-white">Presensi Siswa</h1>
            <p class="text-sm text-neutral-500 mt-2">Pilih sesi mengajar dan catat kehadiran siswa.</p>
        </div>
        <form action="{{ route('shared.kehadiran.index') }}" method="GET" class="flex gap-3">
            <select name="jadwal_id" onchange="this.form.submit()" class="px-4 py-2 text-xs font-bold border border-base rounded-lg bg-white dark:bg-white/5 outline-none focus:ring-2 focus:ring-accent/10 transition-all">
                <option value="">Pilih Sesi Mengajar</option>
                @foreach($allJadwal as $j)
                    <option value="{{ $j->id }}" {{ $jadwalId == $j->id ? 'selected' : '' }}>
                        {{ $j->mapel->nama_mapel }} - Kelas {{ $j->kelas->nama_kelas }} ({{ $j->hari }}, {{ $j->jam_mulai }})
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    @if($jadwalId)
    <!-- Table Surface -->
    <div class="card-pro overflow-hidden">
        <div class="p-4 border-b border-base bg-neutral-50/30 dark:bg-white/5 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-neutral-400">Daftar Siswa - {{ $selectedJadwal->mapel->nama_mapel }}</h3>
                <span class="text-[10px] font-bold text-blue-500 uppercase tracking-widest">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</span>
            </div>
            <button onclick="finalizeHadir()" class="w-full md:w-auto px-6 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-[10px] font-bold rounded-xl uppercase tracking-widest transition-all shadow-md shadow-emerald-500/20 flex items-center justify-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4"></i>
                Hadirkan Semua Sisa Siswa
            </button>
        </div>

        <div class="overflow-x-auto hidden md:block">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-neutral-50/30 dark:bg-white/5 border-b border-base text-[10px] font-bold text-neutral-400 uppercase tracking-widest">
                        <th class="px-8 py-4">Nama Siswa</th>
                        <th class="px-8 py-4">Status Kehadiran</th>
                        <th class="px-8 py-4">Menit Terlambat</th>
                        <th class="px-8 py-4">Keterangan</th>
                        <th class="px-8 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-base">
                    @foreach($siswas as $siswa)
                    <tr class="hover:bg-neutral-50/30 dark:hover:bg-white/5 transition-colors group" id="row-{{ $siswa->id }}">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-neutral-100 dark:bg-white/5 border border-base flex items-center justify-center overflow-hidden shrink-0">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($siswa->nama) }}&background=f5f5f5&color=0a0a0a" class="w-full h-full grayscale">
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-neutral-800 dark:text-neutral-200 tracking-tight">{{ $siswa->nama }}</p>
                                    <p class="text-[9px] text-neutral-400 font-medium uppercase tracking-widest mt-0.5">NISN: {{ $siswa->nisn }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <select class="status-select w-full px-3 py-1.5 text-xs font-bold border border-base rounded-lg bg-neutral-50 dark:bg-white/5 outline-none focus:border-neutral-950 dark:focus:border-white transition-all">
                                <option value="Hadir" {{ ($siswa->kehadiran_hari_ini && $siswa->kehadiran_hari_ini->status == 'Hadir') ? 'selected' : '' }}>Hadir</option>
                                <option value="Izin" {{ ($siswa->kehadiran_hari_ini && $siswa->kehadiran_hari_ini->status == 'Izin') ? 'selected' : '' }}>Izin</option>
                                <option value="Sakit" {{ ($siswa->kehadiran_hari_ini && $siswa->kehadiran_hari_ini->status == 'Sakit') ? 'selected' : '' }}>Sakit</option>
                                <option value="Alpa" {{ ($siswa->kehadiran_hari_ini && $siswa->kehadiran_hari_ini->status == 'Alpa') ? 'selected' : '' }}>Alpha</option>
                            </select>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-2">
                                <input type="number" class="late-input w-20 px-3 py-1.5 text-xs font-bold border border-base rounded-lg bg-neutral-50 dark:bg-white/5 outline-none focus:border-neutral-950 dark:focus:border-white transition-all" 
                                       placeholder="0" min="0" value="{{ $siswa->kehadiran_hari_ini ? $siswa->kehadiran_hari_ini->menit_terlambat : 0 }}">
                                <span class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest">menit</span>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <input type="text" class="ket-input w-full px-3 py-1.5 text-xs font-bold border border-base rounded-lg bg-neutral-50 dark:bg-white/5 outline-none focus:border-neutral-950 dark:focus:border-white transition-all" 
                                   placeholder="Catatan..." value="{{ $siswa->kehadiran_hari_ini ? $siswa->kehadiran_hari_ini->keterangan : '' }}">
                        </td>
                        <td class="px-8 py-5 text-right">
                            <button onclick="saveKehadiran({{ $siswa->id }}, 'desktop')" class="save-btn p-2.5 bg-neutral-900 dark:bg-white text-white dark:text-neutral-900 rounded-xl hover:opacity-90 transition-all active:scale-95 shadow-sm">
                                <i data-lucide="save" class="w-4 h-4"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile Card Layout -->
        <div class="md:hidden divide-y divide-base">
            @forelse($siswas as $siswa)
            <div class="p-4 space-y-4" id="card-{{ $siswa->id }}">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-neutral-100 dark:bg-white/5 border border-base flex items-center justify-center overflow-hidden shrink-0">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($siswa->nama) }}&background=f5f5f5&color=0a0a0a" class="w-full h-full grayscale">
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-black text-neutral-800 dark:text-neutral-200 truncate">{{ $siswa->nama }}</p>
                        <p class="text-[9px] text-neutral-400 font-bold uppercase mt-1">NISN: {{ $siswa->nisn }}</p>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[8px] font-black text-neutral-400 uppercase tracking-[0.2em]">Status Kehadiran</label>
                        <select class="status-select-mobile w-full px-4 py-2.5 text-xs font-bold border border-base rounded-xl bg-neutral-50 dark:bg-white/5 outline-none">
                            <option value="Hadir" {{ ($siswa->kehadiran_hari_ini && $siswa->kehadiran_hari_ini->status == 'Hadir') ? 'selected' : '' }}>Hadir</option>
                            <option value="Izin" {{ ($siswa->kehadiran_hari_ini && $siswa->kehadiran_hari_ini->status == 'Izin') ? 'selected' : '' }}>Izin</option>
                            <option value="Sakit" {{ ($siswa->kehadiran_hari_ini && $siswa->kehadiran_hari_ini->status == 'Sakit') ? 'selected' : '' }}>Sakit</option>
                            <option value="Alpa" {{ ($siswa->kehadiran_hari_ini && $siswa->kehadiran_hari_ini->status == 'Alpa') ? 'selected' : '' }}>Alpha</option>
                        </select>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[8px] font-black text-neutral-400 uppercase tracking-[0.2em]">Terlambat (Min)</label>
                            <input type="number" class="late-input-mobile w-full px-4 py-2.5 text-xs font-bold border border-base rounded-xl bg-neutral-50 dark:bg-white/5 outline-none" 
                                   placeholder="0" min="0" value="{{ $siswa->kehadiran_hari_ini ? $siswa->kehadiran_hari_ini->menit_terlambat : 0 }}">
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[8px] font-black text-neutral-400 uppercase tracking-[0.2em]">Aksi</label>
                            <button onclick="saveKehadiran({{ $siswa->id }}, 'mobile')" class="save-btn w-full h-[42px] bg-neutral-900 dark:bg-white text-white dark:text-neutral-900 rounded-xl font-bold flex items-center justify-center gap-2">
                                <i data-lucide="save" class="w-4 h-4"></i>
                                <span class="text-[10px] uppercase tracking-widest">Simpan</span>
                            </button>
                        </div>
                    </div>
                    
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[8px] font-black text-neutral-400 uppercase tracking-[0.2em]">Catatan Tambahan</label>
                        <input type="text" class="ket-input-mobile w-full px-4 py-2.5 text-xs font-bold border border-base rounded-xl bg-neutral-50 dark:bg-white/5 outline-none" 
                               placeholder="Catatan..." value="{{ $siswa->kehadiran_hari_ini ? $siswa->kehadiran_hari_ini->keterangan : '' }}">
                    </div>
                </div>
            </div>
            @empty
            <div class="p-20 text-center text-neutral-400 italic">Tidak ada siswa.</div>
            @endforelse
        </div>
    </div>
    @else
    <div class="py-20 flex flex-col items-center justify-center text-center opacity-30">
        <div class="w-20 h-20 rounded-3xl bg-neutral-100 dark:bg-white/5 flex items-center justify-center mb-6">
            <i data-lucide="layers" class="w-10 h-10"></i>
        </div>
        <p class="text-xs font-black uppercase tracking-[0.2em]">Silakan Pilih Kelas Terlebih Dahulu</p>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    function saveKehadiran(siswaId, mode) {
        let container;
        if (mode === 'desktop') {
            container = document.getElementById(`row-${siswaId}`);
        } else {
            container = document.getElementById(`card-${siswaId}`);
        }

        const status = container.querySelector(mode === 'desktop' ? '.status-select' : '.status-select-mobile').value;
        const menit = container.querySelector(mode === 'desktop' ? '.late-input' : '.late-input-mobile').value;
        const keterangan = container.querySelector(mode === 'desktop' ? '.ket-input' : '.ket-input-mobile').value;
        const btn = container.querySelector('.save-btn');
        
        btn.disabled = true;
        const originalContent = btn.innerHTML;
        btn.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i>' + (mode === 'mobile' ? ' <span class="text-[10px] uppercase tracking-widest">Saving...</span>' : '');
        lucide.createIcons();

        fetch("{{ route('shared.kehadiran.store') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                siswa_id: siswaId,
                status: status,
                menit_terlambat: menit,
                keterangan: keterangan
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                btn.classList.add('bg-emerald-500', 'dark:bg-emerald-500', 'text-white');
                btn.innerHTML = '<i data-lucide="check" class="w-4 h-4"></i>' + (mode === 'mobile' ? ' <span class="text-[10px] uppercase tracking-widest">Berhasil</span>' : '');
                lucide.createIcons();
                
                setTimeout(() => {
                    btn.classList.remove('bg-emerald-500', 'dark:bg-emerald-500', 'text-white');
                    btn.innerHTML = originalContent;
                    btn.disabled = false;
                    lucide.createIcons();
                }, 2000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            btn.disabled = false;
            btn.innerHTML = originalContent;
            lucide.createIcons();
        });
    }

    function finalizeHadir() {
        if(!confirm('Tandai semua siswa yang belum diabsen hari ini sebagai Hadir Tepat Waktu?')) return;

        fetch("{{ route('shared.kehadiran.batch-store-hadir') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                kelas_id: '{{ $kelasId }}'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal memproses data.');
        });
    }
</script>
@endpush
