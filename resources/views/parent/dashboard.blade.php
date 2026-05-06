@extends('layouts.admin')

@section('title', 'Dashboard Orang Tua')

@section('page_title', 'Dashboard Orang Tua')

@section('content')
<div class="p-6">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">
            Selamat Datang, {{ Auth::user()->name }}!
        </h2>
        <p class="text-gray-600 mt-2">Pantau perkembangan akademik dan kehadiran anak Anda di sini.</p>
    </div>

    <!-- Stats Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-emerald-500 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center">
                <div class="p-3 bg-emerald-600 rounded-full">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium opacity-75 uppercase">Kehadiran Anak</p>
                    <p class="text-2xl font-bold">100%</p>
                </div>
            </div>
        </div>

        <div class="bg-violet-500 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center">
                <div class="p-3 bg-violet-600 rounded-full">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium opacity-75 uppercase">Rata-rata Nilai</p>
                    <p class="text-2xl font-bold">88.0</p>
                </div>
            </div>
        </div>

        <div class="bg-pink-500 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center">
                <div class="p-3 bg-pink-600 rounded-full">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium opacity-75 uppercase">Pengumuman Baru</p>
                    <p class="text-2xl font-bold">1</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
