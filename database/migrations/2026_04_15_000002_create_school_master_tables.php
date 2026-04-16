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
        Schema::create('tahun_ajaran', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tahun_ajaran', 20);
            $table->string('semester', 20);
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->boolean('status_aktif')->default(false);
            $table->timestamps();
        });

        Schema::create('guru', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->unique()->constrained('users')->nullOnDelete();
            $table->string('nip', 30)->nullable()->unique();
            $table->string('nama_guru');
            $table->string('jenis_kelamin', 10)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
        });

        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelas', 50);
            $table->string('tingkat', 20)->nullable();
            $table->string('jurusan', 50)->nullable();
            $table->unsignedSmallInteger('kapasitas')->nullable();
            $table->foreignId('wali_guru_id')->nullable()->constrained('guru')->nullOnDelete();
            $table->foreignId('tahun_ajaran_id')->nullable()->constrained('tahun_ajaran')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('mata_pelajaran', function (Blueprint $table) {
            $table->id();
            $table->string('kode_mapel', 20)->nullable()->unique();
            $table->string('nama_mapel');
            $table->string('kelompok_mapel', 50)->nullable();
            $table->unsignedSmallInteger('jam_mingguan')->nullable();
            $table->unsignedSmallInteger('kkm')->nullable();
            $table->timestamps();
        });

        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->unique()->constrained('users')->nullOnDelete();
            $table->string('nis', 30)->unique();
            $table->string('nisn', 30)->nullable()->unique();
            $table->string('nama_siswa');
            $table->string('jenis_kelamin', 10)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->string('foto')->nullable();
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('jenis_penilaian', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jenis', 50);
            $table->decimal('bobot', 5, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_penilaian');
        Schema::dropIfExists('siswa');
        Schema::dropIfExists('mata_pelajaran');
        Schema::dropIfExists('kelas');
        Schema::dropIfExists('guru');
        Schema::dropIfExists('tahun_ajaran');
    }
};
