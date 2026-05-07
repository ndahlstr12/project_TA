<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cbt_soals', function (Blueprint $table) {
            $table->id();
            $table->text('pertanyaan');
            $table->string('opsi_a');
            $table->string('opsi_b');
            $table->string('opsi_c');
            $table->string('opsi_d');
            $table->string('opsi_e')->nullable();
            $table->string('jawaban_benar'); // A, B, C, D, or E
            $table->string('mapel')->nullable();
            $table->string('kelas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cbt_soals');
    }
};
