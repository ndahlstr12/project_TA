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

    public function generateAiRecommendation(Request $request)
    {
        $request->validate([
            'catatan' => 'required|string',
        ]);

        // Mock AI call for now
        $catatan = $request->catatan;
        $recommendation = "Berdasarkan catatan perilaku: '{$catatan}', direkomendasikan untuk melakukan pendekatan personal dengan siswa, melibatkan orang tua dalam diskusi, serta memberikan bimbingan konseling jika diperlukan untuk mengidentifikasi akar permasalahan.";

        return response()->json([
            'success' => true,
            'recommendation' => $recommendation
        ]);
    }
}
