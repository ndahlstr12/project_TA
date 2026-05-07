<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id();
            $table->string('hari');
            $table->string('jam_mulai'); // Use string for easier excel import compatibility or time
            $table->string('jam_selesai');
            $table->string('mapel');
            $table->string('guru');
            $table->string('kelas');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwals');
    }
};
