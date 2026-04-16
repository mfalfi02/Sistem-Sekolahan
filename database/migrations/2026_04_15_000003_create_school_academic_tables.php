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
        Schema::create('jadwal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->foreignId('guru_id')->constrained('guru')->cascadeOnDelete();
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajaran')->cascadeOnDelete();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->cascadeOnDelete();
            $table->string('hari', 20);
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('ruang', 50)->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
        });

        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->foreignId('guru_id')->nullable()->constrained('guru')->nullOnDelete();
            $table->foreignId('jadwal_id')->nullable()->constrained('jadwal')->nullOnDelete();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->cascadeOnDelete();
            $table->date('tanggal_absen');
            $table->unsignedSmallInteger('pertemuan_ke')->nullable();
            $table->enum('status_kehadiran', ['hadir', 'sakit', 'izin', 'alfa', 'terlambat'])->default('hadir');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index(['siswa_id', 'tanggal_absen']);
        });

        Schema::create('nilai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignId('guru_id')->nullable()->constrained('guru')->nullOnDelete();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajaran')->cascadeOnDelete();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->cascadeOnDelete();
            $table->foreignId('jenis_penilaian_id')->constrained('jenis_penilaian')->cascadeOnDelete();
            $table->date('tanggal_nilai')->nullable();
            $table->decimal('nilai', 5, 2);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index(['siswa_id', 'mata_pelajaran_id', 'tahun_ajaran_id']);
        });

        Schema::create('nilai_akhir', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajaran')->cascadeOnDelete();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->cascadeOnDelete();
            $table->string('semester', 20);
            $table->decimal('nilai_akhir', 5, 2);
            $table->string('predikat', 5)->nullable();
            $table->unsignedSmallInteger('ranking')->nullable();
            $table->boolean('status_lulus')->default(false);
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->unique(['siswa_id', 'mata_pelajaran_id', 'tahun_ajaran_id', 'semester'], 'nilai_akhir_unique');
        });

        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('modul', 50)->nullable();
            $table->string('aksi', 50);
            $table->text('deskripsi')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });

        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('judul');
            $table->text('pesan');
            $table->string('tipe', 30)->default('info');
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->string('link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
        Schema::dropIfExists('log_aktivitas');
        Schema::dropIfExists('nilai_akhir');
        Schema::dropIfExists('nilai');
        Schema::dropIfExists('absensi');
        Schema::dropIfExists('jadwal');
    }
};
