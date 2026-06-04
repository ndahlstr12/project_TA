<?php

namespace App\Http\Controllers;

use App\Models\JurnalPerilaku;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JurnalPerilakuController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $guru = $user->guru;

        if (!$guru) {
            return redirect()->back()->with('error', 'Data guru tidak ditemukan.');
        }

        $kelas = Kelas::where('wali_id', $guru->id)->first();
        
        $siswas = [];
        if ($kelas) {
            $siswas = Siswa::where('kelas_id', $kelas->id)->get();
        }

        $jurnals = JurnalPerilaku::with('siswa')
            ->where('guru_id', $guru->id)
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        return view('walikelas.jurnal.index', compact('siswas', 'jurnals', 'kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'catatan' => 'required|string',
            'tipe' => 'required|in:Positif,Negatif',
            'poin' => 'required|integer',
            'tanggal' => 'required|date',
        ]);

        JurnalPerilaku::create([
            'siswa_id' => $request->siswa_id,
            'guru_id' => Auth::user()->guru_id,
            'catatan' => $request->catatan,
            'tipe' => $request->tipe,
            'poin' => $request->poin,
            'tanggal' => $request->tanggal,
        ]);

        return redirect()->back()->with('success', 'Jurnal perilaku berhasil disimpan.');
    }

    public function update(Request $request, $id)
    {
        $jurnal = JurnalPerilaku::findOrFail($id);

        // Pastikan hanya pemilik (guru) yang bisa edit
        if ($jurnal->guru_id !== Auth::user()->guru_id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengubah data ini.');
        }

        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'catatan' => 'required|string',
            'tipe' => 'required|in:Positif,Negatif',
            'poin' => 'required|integer',
            'tanggal' => 'required|date',
        ]);

        $jurnal->update([
            'siswa_id' => $request->siswa_id,
            'catatan' => $request->catatan,
            'tipe' => $request->tipe,
            'poin' => $request->poin,
            'tanggal' => $request->tanggal,
        ]);

        return redirect()->back()->with('success', 'Jurnal perilaku berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $jurnal = JurnalPerilaku::findOrFail($id);

        if ($jurnal->guru_id !== Auth::user()->guru_id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menghapus data ini.');
        }

        $jurnal->delete();

        return redirect()->back()->with('success', 'Catatan jurnal berhasil dihapus.');
    }

    public function generateAiRecommendation(Request $request, \App\Services\AiRecommendationService $aiService)
    {
        $request->validate([
            'catatan' => 'required|string',
            'siswa_id' => 'nullable|exists:siswas,id'
        ]);

        $namaSiswa = "Siswa";
        if ($request->siswa_id) {
            $siswa = \App\Models\Siswa::find($request->siswa_id);
            $namaSiswa = $siswa->nama;
        }

        $result = $aiService->getBehaviorRecommendation($namaSiswa, $request->catatan);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'recommendation' => $result['data']
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => $result['message']
            ], 500);
        }
    }
}
