<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nilai_akhir', function (Blueprint $table) {
            $table->unsignedSmallInteger('absensi_total')->default(0)->after('nilai_akhir');
            $table->unsignedSmallInteger('absensi_hadir')->default(0)->after('absensi_total');
            $table->unsignedSmallInteger('absensi_terlambat')->default(0)->after('absensi_hadir');
            $table->unsignedSmallInteger('absensi_sakit')->default(0)->after('absensi_terlambat');
            $table->unsignedSmallInteger('absensi_izin')->default(0)->after('absensi_sakit');
            $table->unsignedSmallInteger('absensi_alfa')->default(0)->after('absensi_izin');
            $table->decimal('persentase_absensi', 5, 2)->default(0)->after('absensi_alfa');
            $table->decimal('nilai_absensi', 5, 2)->default(0)->after('persentase_absensi');
        });
    }

    public function down(): void
    {
        Schema::table('nilai_akhir', function (Blueprint $table) {
            $table->dropColumn([
                'absensi_total',
                'absensi_hadir',
                'absensi_terlambat',
                'absensi_sakit',
                'absensi_izin',
                'absensi_alfa',
                'persentase_absensi',
                'nilai_absensi',
            ]);
        });
    }
};
