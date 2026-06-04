<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cbt_soals', function (Blueprint $table) {
            $table->foreignId('ujian_id')->nullable()->constrained('cbt_ujians')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('cbt_soals', function (Blueprint $table) {
            $table->dropForeign(['ujian_id']);
            $table->dropColumn('ujian_id');
        });
    }
};
