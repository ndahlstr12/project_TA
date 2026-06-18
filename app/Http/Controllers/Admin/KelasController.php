<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $kelasList = Kelas::with('waliKelas')->latest()->paginate(10);
        return view('admin.kelas.index', compact('kelasList'));
    }

    public function create()
    {
        $gurus = \App\Models\Guru::orderBy('nama')->get();
        return view('admin.kelas.create', compact('gurus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kelas' => 'required|string|unique:kelas,nama_kelas|max:255',
            'tingkat' => 'required|string|max:50',
            'jurusan' => 'required|string|max:100',
            'wali_id' => 'nullable|exists:gurus,id|unique:kelas,wali_id',
        ], [
            'wali_id.unique' => 'Guru ini sudah menjadi wali kelas di kelas lain.'
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($validated) {
            $kelas = Kelas::create($validated);

            if ($kelas->wali_id) {
                $this->syncWaliKelasRole($kelas->wali_id, $kelas->nama_kelas);
            }
        });

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit(Kelas $kela)
    {
        $gurus = \App\Models\Guru::orderBy('nama')->get();
        return view('admin.kelas.edit', compact('kela', 'gurus'));
    }

    public function update(Request $request, Kelas $kela)
    {
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:255|unique:kelas,nama_kelas,' . $kela->id,
            'tingkat' => 'required|string|max:50',
            'jurusan' => 'required|string|max:100',
            'wali_id' => 'nullable|exists:gurus,id|unique:kelas,wali_id,' . $kela->id,
        ], [
            'wali_id.unique' => 'Guru ini sudah menjadi wali kelas di kelas lain.'
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($validated, $kela) {
            $oldWaliId = $kela->wali_id;
            $kela->update($validated);

            // Jika wali kelas berubah
            if ($oldWaliId != $kela->wali_id) {
                if ($oldWaliId) {
                    $this->revertOldWaliKelasRole($oldWaliId);
                }
                if ($kela->wali_id) {
                    $this->syncWaliKelasRole($kela->wali_id, $kela->nama_kelas);
                }
            } else if ($kela->wali_id) {
                // Update nama kelas ampu jika nama kelas berubah
                $this->syncWaliKelasRole($kela->wali_id, $kela->nama_kelas);
            }
        });

        return redirect()->route('admin.kelas.index')->with('success', 'Data kelas berhasil diperbarui.');
    }

    private function syncWaliKelasRole($guruId, $namaKelas = null)
    {
        $guru = \App\Models\Guru::find($guruId);
        if ($guru) {
            $guru->update([
                'is_walikelas' => true,
                'kelas_ampu' => $namaKelas
            ]);

            if ($guru->user) {
                $guru->user->update(['role' => \App\Models\User::ROLE_WALIKELAS]);
            }
        }
    }

    private function revertOldWaliKelasRole($oldGuruId)
    {
        // Cek apakah guru ini masih jadi wali di kelas lain
        $isStillWali = \App\Models\Kelas::where('wali_id', $oldGuruId)->exists();

        if (!$isStillWali) {
            $guru = \App\Models\Guru::find($oldGuruId);
            if ($guru) {
                $guru->update([
                    'is_walikelas' => false,
                    'kelas_ampu' => null
                ]);

                if ($guru->user) {
                    $guru->user->update(['role' => \App\Models\User::ROLE_GURU]);
                }
            }
        }
    }

    public function destroy(Kelas $kela)
    {
        $oldWaliId = $kela->wali_id;
        $kela->delete();

        if ($oldWaliId) {
            $this->revertOldWaliKelasRole($oldWaliId);
        }

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil dihapus.');
    }
}
