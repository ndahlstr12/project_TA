<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Buat Tabel Siswa
        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            $table->string('nisn')->unique();
            $table->string('nama');
            $table->string('kelas')->nullable();
            $table->string('jenis_kelamin')->nullable();
            $table->timestamps();
        });

        // 2. Tambahkan kolom siswa_id ke tabel users
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('siswa_id')->nullable()->constrained('siswas')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('siswa_id');
        });
        Schema::dropIfExists('siswas');
    }
};
