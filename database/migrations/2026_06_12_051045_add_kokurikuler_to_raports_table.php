<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('raports', function (Blueprint $table) {
            $table->text('kokurikuler')->nullable()->after('catatan_wali');
        });
    }

    public function down(): void
    {
        Schema::table('raports', function (Blueprint $table) {
            $table->dropColumn('kokurikuler');
        });
    }
};
