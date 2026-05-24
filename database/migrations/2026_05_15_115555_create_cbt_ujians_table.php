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
        Schema::create('cbt_ujians', function (Blueprint $table) {
            $table->id();
            $table->string('nama_ujian');
            $table->string('mapel');
            $table->string('kelas');
            $table->string('level')->default('Sedang'); // Mudah, Sedang, Sulit
            $table->integer('durasi'); // In minutes
            $table->boolean('acak_soal')->default(true);
            $table->boolean('acak_jawaban')->default(true);
            $table->integer('jumlah_soal')->default(0);
            $table->boolean('status')->default(false); // Active/Inactive
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cbt_ujians');
    }
};
