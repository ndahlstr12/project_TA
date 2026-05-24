@extends('layouts.admin')

@section('title', 'Input Kehadiran Siswa')
@section('page_title', 'Manajemen Absensi')

@section('content')
<div class="space-y-8">
    
    <!-- Pro Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-base pb-8">
        <div>
            <h1 class="text-3xl font-bold tracking-tighter text-slate-900 dark:text-white">Presensi Siswa</h1>
            <p class="text-sm text-neutral-500 mt-2">Pilih kelas dan catat kehadiran siswa hari ini.</p>
        </div>
        <form action="{{ route('guru.kehadiran.index') }}" method="GET" class="flex gap-3">
            <select name="kelas_id" onchange="this.form.submit()" class="px-4 py-2 text-xs font-bold border border-base rounded-lg bg-white dark:bg-white/5 outline-none focus:ring-2 focus:ring-accent/10 transition-all">
                <option value="">Pilih Kelas</option>
                @foreach($allKelas as $k)
                    <option value="{{ $k->id }}" {{ $kelasId == $k->id ? 'selected' : '' }}>Kelas {{ $k->nama_kelas }}</option>
                @endforeach
            </select>
        </form>
    </div>

    @if($kelasId)
    <!-- Table Surface -->
    <div class="card-pro overflow-hidden">
        <div class="p-4 border-b border-base bg-neutral-50/30 dark:bg-white/5 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-neutral-400">Daftar Siswa</h3>
                <span class="text-[10px] font-bold text-blue-500 uppercase tracking-widest">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</span>
            </div>
            <button onclick="finalizeHadir()" class="w-full md:w-auto px-6 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-[10px] font-bold rounded-xl uppercase tracking-widest transition-all shadow-md shadow-emerald-500/20 flex items-center justify-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4"></i>
                Hadirkan Semua Sisa Siswa
            </button>
        </div>

        <div class="overflow-x-auto">
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
                    @forelse($siswas as $siswa)
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
                            <select class="status-select w-full px-3 py-1.5 text-xs font-bold border border-base rounded-lg bg-neutral-50 dark:bg-white/5 outline-none focus:border-neutral-950 dark:focus:border-white transition-all" 
                                    data-siswa-id="{{ $siswa->id }}">
                                <option value="Hadir" {{ ($siswa->kehadiran_hari_ini && $siswa->kehadiran_hari_ini->status == 'Hadir') ? 'selected' : '' }}>Hadir</option>
                                <option value="Izin" {{ ($siswa->kehadiran_hari_ini && $siswa->kehadiran_hari_ini->status == 'Izin') ? 'selected' : '' }}>Izin</option>
                                <option value="Sakit" {{ ($siswa->kehadiran_hari_ini && $siswa->kehadiran_hari_ini->status == 'Sakit') ? 'selected' : '' }}>Sakit</option>
                                <option value="Alpa" {{ ($siswa->kehadiran_hari_ini && $siswa->kehadiran_hari_ini->status == 'Alpa') ? 'selected' : '' }}>Alpha</option>
                            </select>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-2">
                                <input type="number" class="late-input w-20 px-3 py-1.5 text-xs font-bold border border-base rounded-lg bg-neutral-50 dark:bg-white/5 outline-none focus:border-neutral-950 dark:focus:border-white transition-all" 
                                       placeholder="0" min="0" value="{{ $siswa->kehadiran_hari_ini ? $siswa->kehadiran_hari_ini->menit_terlambat : 0 }}"
                                       data-siswa-id="{{ $siswa->id }}">
                                <span class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest">menit</span>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <input type="text" class="ket-input w-full px-3 py-1.5 text-xs font-bold border border-base rounded-lg bg-neutral-50 dark:bg-white/5 outline-none focus:border-neutral-950 dark:focus:border-white transition-all" 
                                   placeholder="Catatan..." value="{{ $siswa->kehadiran_hari_ini ? $siswa->kehadiran_hari_ini->keterangan : '' }}"
                                   data-siswa-id="{{ $siswa->id }}">
                        </td>
                        <td class="px-8 py-5 text-right">
                            <button onclick="saveKehadiran({{ $siswa->id }})" class="save-btn p-2.5 bg-neutral-900 dark:bg-white text-white dark:text-neutral-900 rounded-xl hover:opacity-90 transition-all active:scale-95 shadow-sm">
                                <i data-lucide="save" class="w-4 h-4"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center text-neutral-400 italic">Tidak ada siswa di kelas ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
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
    function saveKehadiran(siswaId) {
        const row = document.getElementById(`row-${siswaId}`);
        const status = row.querySelector('.status-select').value;
        const menit = row.querySelector('.late-input').value;
        const keterangan = row.querySelector('.ket-input').value;
        const btn = row.querySelector('.save-btn');
        
        btn.disabled = true;
        btn.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i>';
        lucide.createIcons();

        fetch("{{ route('guru.kehadiran.store') }}", {
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
                btn.classList.replace('bg-neutral-900', 'bg-emerald-500');
                btn.classList.replace('dark:bg-white', 'dark:bg-emerald-500');
                btn.innerHTML = '<i data-lucide="check" class="w-4 h-4 text-white"></i>';
                lucide.createIcons();
                
                setTimeout(() => {
                    btn.classList.replace('bg-emerald-500', 'bg-neutral-900');
                    btn.classList.replace('dark:bg-emerald-500', 'dark:bg-white');
                    btn.innerHTML = '<i data-lucide="save" class="w-4 h-4"></i>';
                    btn.disabled = false;
                    lucide.createIcons();
                }, 2000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            btn.disabled = false;
            btn.innerHTML = '<i data-lucide="save" class="w-4 h-4"></i>';
            lucide.createIcons();
        });
    }

    function finalizeHadir() {
        if(!confirm('Tandai semua siswa yang belum diabsen hari ini sebagai Hadir Tepat Waktu?')) return;

        fetch("{{ route('guru.kehadiran.batch-store-hadir') }}", {
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
