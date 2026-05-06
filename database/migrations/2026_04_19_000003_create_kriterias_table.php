<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kriterias', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique(); // Contoh: C1, C2
            $table->string('nama');
            $table->float('bobot'); // Persentase (0-1 atau 0-100)
            $table->enum('jenis', ['benefit', 'cost']); // Benefit: Makin besar makin baik, Cost: Makin kecil makin baik
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kriterias');
    }
};
