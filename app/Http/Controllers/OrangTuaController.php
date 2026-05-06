<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrangTuaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $siswa = $user->siswa;

        if (!$siswa) {
            return redirect('/')->with('error', 'Data anak tidak ditemukan.');
        }

        return view('parent.dashboard', compact('siswa'));
    }
}
