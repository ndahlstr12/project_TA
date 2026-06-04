<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cbt_hasils', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cbt_ujian_id')->constrained('cbt_ujians')->onDelete('cascade');
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->float('skor');
            $table->integer('jumlah_benar');
            $table->integer('jumlah_salah');
            $table->string('status'); // Selesai, Remedial
            $table->text('rekomendasi_ai')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cbt_hasils');
    }
};
