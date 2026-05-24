<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('raports', function (Blueprint $table) {
            $table->integer('sakit')->default(0)->after('catatan_wali');
            $table->integer('izin')->default(0)->after('sakit');
            $table->integer('alpa')->default(0)->after('izin');
            $table->text('saran_ai')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('raports', function (Blueprint $table) {
            $table->dropColumn(['sakit', 'izin', 'alpa', 'saran_ai']);
        });
    }
};
