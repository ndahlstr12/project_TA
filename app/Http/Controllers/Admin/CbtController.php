<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CbtSoal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CbtController extends Controller
{
    public function index()
    {
        $soals = CbtSoal::latest()->paginate(10);
        return view('admin.cbt.index', compact('soals'));
    }

    public function create()
    {
        return view('admin.cbt.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pertanyaan' => 'required',
            'opsi_a' => 'required',
            'opsi_b' => 'required',
            'opsi_c' => 'required',
            'opsi_d' => 'required',
            'opsi_e' => 'nullable',
            'jawaban_benar' => 'required|in:A,B,C,D,E',
            'mapel' => 'nullable',
            'kelas' => 'nullable',
        ]);

        CbtSoal::create($validated);

        $route = Auth::user()->role === 'guru' ? 'guru.cbt.index' : 'walikelas.cbt.index';
        return redirect()->route($route)->with('success', 'Soal berhasil ditambahkan.');
    }

    public function import(Request $request)
    {
        // Logika import excel (bisa pakai Laravel Excel)
        return back()->with('success', 'Soal berhasil diimport.');
    }
}
