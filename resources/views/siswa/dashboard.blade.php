@extends('layouts.admin')

@section('title', 'Dashboard Siswa')

@section('page_title', 'Dashboard Siswa')

@section('content')
<div class="p-6">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">
            Selamat Datang, {{ Auth::user()->name }}!
        </h2>
        <p class="text-gray-600 mt-2">Semangat belajar hari ini! Cek progres akademikmu di bawah ini.</p>
    </div>

    <!-- Stats Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-rose-500 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center">
                <div class="p-3 bg-rose-600 rounded-full">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium opacity-75 uppercase">Kehadiran Saya</p>
                    <p class="text-2xl font-bold">95%</p>
                </div>
            </div>
        </div>

        <div class="bg-sky-500 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center">
                <div class="p-3 bg-sky-600 rounded-full">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium opacity-75 uppercase">Tugas Mendatang</p>
                    <p class="text-2xl font-bold">2</p>
                </div>
            </div>
        </div>

        <div class="bg-amber-500 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center">
                <div class="p-3 bg-amber-600 rounded-full">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium opacity-75 uppercase">Nilai Terakhir</p>
                    <p class="text-2xl font-bold">A-</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
