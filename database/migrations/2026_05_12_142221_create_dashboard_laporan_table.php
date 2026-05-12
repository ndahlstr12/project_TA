<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('DASHBOARD_LAPORAN', function (Blueprint $table) {
            $table->id();
            $table->integer('total_siswa')->default(0);
            $table->integer('total_guru')->default(0);
            $table->integer('total_kriteria')->default(0);
            $table->decimal('rata_rata_nilai', 5, 2)->default(0);
            $table->decimal('kehadiran_rata', 5, 2)->default(0);
            
            // Tambahan monitoring guru
            $table->integer('guru_mapel_total')->default(0);
            $table->integer('guru_mapel_selesai')->default(0);
            $table->integer('walikelas_total')->default(0);
            $table->integer('walikelas_selesai')->default(0);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dashboard_laporan');
    }
};
