@extends('layouts.admin')

@section('title', 'Dashboard Wali Kelas')

@section('page_title', 'Dashboard Wali Kelas')

@section('content')
<div class="p-6">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">
            Selamat Datang, {{ Auth::user()->name }}!
        </h2>
        <p class="text-gray-600 mt-2">Anda login sebagai Wali Kelas. Pantau perkembangan siswa Anda di sini.</p>
    </div>

    <!-- Stats Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-indigo-500 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center">
                <div class="p-3 bg-indigo-600 rounded-full">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium opacity-75 uppercase">Siswa di Kelas</p>
                    <p class="text-2xl font-bold">32</p>
                </div>
            </div>
        </div>

        <div class="bg-teal-500 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center">
                <div class="p-3 bg-teal-600 rounded-full">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium opacity-75 uppercase">Kehadiran Hari Ini</p>
                    <p class="text-2xl font-bold">98%</p>
                </div>
            </div>
        </div>

        <div class="bg-orange-500 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center">
                <div class="p-3 bg-orange-600 rounded-full">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium opacity-75 uppercase">Rata-rata Nilai Kelas</p>
                    <p class="text-2xl font-bold">85.5</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
