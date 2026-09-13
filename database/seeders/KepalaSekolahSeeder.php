<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class KepalaSekolahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->where('role', 'kepala_sekolah')->delete();

        $now = now('Asia/Jakarta');

        DB::table('users')->insert([
            [
                'name' => 'Kepala Sekolah',
                'email' => 'kepala.sekolah@sekolah.test',
                'email_verified_at' => $now,
                'password' => Hash::make('Sekolah123!'),
                'remember_token' => null,
                'role' => 'kepala_sekolah',
                'phone' => '081234567891',
                'status_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
