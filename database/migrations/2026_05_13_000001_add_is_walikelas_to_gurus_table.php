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
        Schema::table('gurus', function (Blueprint $table) {
            $table->boolean('is_walikelas')->default(false);
            $table->string('kelas_ampu')->nullable(); // Nama kelas yang diampu, misal: XII RPL 1
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gurus', function (Blueprint $table) {
            $table->dropColumn(['is_walikelas', 'kelas_ampu']);
        });
    }
};
