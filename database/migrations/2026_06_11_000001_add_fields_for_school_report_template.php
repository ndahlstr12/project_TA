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
        // Add kategori to mapels
        Schema::table('mapels', function (Blueprint $table) {
            $table->string('kategori')->default('Umum')->after('nama_mapel'); // Umum, Kejuruan
        });

        // Add nis to siswas
        Schema::table('siswas', function (Blueprint $table) {
            $table->string('nis')->nullable()->after('nisn');
        });

        // Add capaian_kompetensi to nilais and change mapel to mapel_id
        Schema::table('nilais', function (Blueprint $table) {
            $table->text('capaian_kompetensi')->nullable()->after('nilai_angka');
            $table->foreignId('mapel_id')->nullable()->after('guru_id')->constrained('mapels')->onDelete('cascade');
            
            if (Schema::hasColumn('nilais', 'mapel')) {
                $table->dropColumn('mapel');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilais', function (Blueprint $table) {
            $table->string('mapel')->nullable()->after('guru_id');
            $table->dropConstrainedForeignId('mapel_id');
            $table->dropColumn('capaian_kompetensi');
        });

        Schema::table('siswas', function (Blueprint $table) {
            $table->dropColumn('nis');
        });

        Schema::table('mapels', function (Blueprint $table) {
            $table->dropColumn('kategori');
        });
    }
};
