@extends('layouts.admin')

@section('title', 'Dashboard Guru')

@section('page_title', 'Dashboard Guru')

@section('content')
<div class="p-6">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">
            Selamat Datang, {{ Auth::user()->name }}!
        </h2>
        <p class="text-gray-600 mt-2">Anda login sebagai Guru. Berikut adalah ringkasan aktivitas Anda hari ini.</p>
    </div>

    <!-- Stats Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-blue-500 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center">
                <div class="p-3 bg-blue-600 rounded-full">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium opacity-75 uppercase">Mata Pelajaran</p>
                    <p class="text-2xl font-bold">4</p>
                </div>
            </div>
        </div>

        <div class="bg-green-500 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center">
                <div class="p-3 bg-green-600 rounded-full">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium opacity-75 uppercase">Total Siswa</p>
                    <p class="text-2xl font-bold">120</p>
                </div>
            </div>
        </div>

        <div class="bg-purple-500 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center">
                <div class="p-3 bg-purple-600 rounded-full">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium opacity-75 uppercase">Jadwal Hari Ini</p>
                    <p class="text-2xl font-bold">3 Sesi</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
