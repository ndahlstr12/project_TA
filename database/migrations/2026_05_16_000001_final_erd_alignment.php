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
        // 1. Tabel orang_tuas
        Schema::create('orang_tuas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nama_wali');
            $table->string('no_telp');
            $table->text('alamat');
            $table->string('email_notifikasi');
            $table->timestamps();
        });

        // 2. Update tabel siswas
        Schema::table('siswas', function (Blueprint $table) {
            $table->foreignId('orang_tua_id')->nullable()->after('id')->constrained('orang_tuas')->onDelete('set null');
            $table->foreignId('kelas_id')->nullable()->after('orang_tua_id')->constrained('kelas')->onDelete('set null');
            
            // Hapus kolom string 'kelas' jika ada
            if (Schema::hasColumn('siswas', 'kelas')) {
                $table->dropColumn('kelas');
            }
        });

        // 3. Update tabel gurus
        Schema::table('gurus', function (Blueprint $table) {
            $table->string('ttd_digital')->nullable()->after('spesialisasi');
        });

        // 4. Update tabel kelas
        Schema::table('kelas', function (Blueprint $table) {
            $table->foreignId('wali_id')->nullable()->after('jurusan')->constrained('gurus')->onDelete('set null');
        });

        // 5. Tabel ai_logs
        Schema::create('ai_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('raport_id')->constrained('raports')->onDelete('cascade');
            $table->text('prompt_payload'); // Bisa text atau json
            $table->text('hasil_ai');
            $table->string('api_model');
            $table->timestamps();
        });

        // 6. Tabel raport_files
        Schema::create('raport_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('raport_id')->constrained('raports')->onDelete('cascade');
            $table->string('template_name');
            $table->string('file_path_pdf');
            $table->timestamp('generated_at');
            $table->timestamps();
        });

        // 7. Tabel spk_rankings
        Schema::create('spk_rankings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->float('skor_spk');
            $table->integer('ranking');
            $table->string('tahun_ajaran');
            $table->timestamps();
        });

        // 8. Update tabel notifications
        Schema::table('notifications', function (Blueprint $table) {
            $table->enum('penerima', ['Ortu', 'Wali Kelas'])->nullable()->after('type');
            $table->boolean('is_sent_email')->default(false)->after('is_read');
        });

        // 9. Update tabel cbt_ujians
        Schema::table('cbt_ujians', function (Blueprint $table) {
            if (!Schema::hasColumn('cbt_ujians', 'evaluasi_ujian')) {
                $table->text('evaluasi_ujian')->nullable()->after('status');
            }
        });

        // 10. Update tabel jadwals untuk relasi yang lebih baik
        Schema::table('jadwals', function (Blueprint $table) {
            $table->foreignId('guru_id')->nullable()->after('id')->constrained('gurus')->onDelete('cascade');
            $table->foreignId('mapel_id')->nullable()->after('guru_id')->constrained('mapels')->onDelete('cascade');
            $table->foreignId('kelas_id')->nullable()->after('mapel_id')->constrained('kelas')->onDelete('cascade');
            
            // Hapus kolom string lama jika ada
            $table->dropColumn(['mapel', 'guru', 'kelas']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwals', function (Blueprint $table) {
            $table->string('mapel')->nullable();
            $table->string('guru')->nullable();
            $table->string('kelas')->nullable();
            $table->dropConstrainedForeignId('kelas_id');
            $table->dropConstrainedForeignId('mapel_id');
            $table->dropConstrainedForeignId('guru_id');
        });

        Schema::table('cbt_ujians', function (Blueprint $table) {
            $table->dropColumn('evaluasi_ujian');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn(['penerima', 'is_sent_email']);
        });

        Schema::dropIfExists('spk_rankings');
        Schema::dropIfExists('raport_files');
        Schema::dropIfExists('ai_logs');

        Schema::table('kelas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('wali_id');
        });

        Schema::table('gurus', function (Blueprint $table) {
            $table->dropColumn('ttd_digital');
        });

        Schema::table('siswas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('kelas_id');
            $table->dropConstrainedForeignId('orang_tua_id');
            $table->string('kelas')->nullable()->after('nama');
        });

        Schema::dropIfExists('orang_tuas');
    }
};
