<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('guru')->delete();
        DB::table('users')->where('role', 'guru')->delete();

        $now = now('Asia/Jakarta');
        $password = Hash::make('Sekolah123!');

        $guruUsers = [
            [
                'name' => 'Yohanes Prasetyo',
                'email' => 'yohanes.prasetyo@sekolah.test',
                'email_verified_at' => $now,
                'password' => $password,
                'remember_token' => null,
                'role' => 'guru',
                'phone' => '0812-1000-0001',
                'status_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Maria Elisabeth',
                'email' => 'maria.elisabeth@sekolah.test',
                'email_verified_at' => $now,
                'password' => $password,
                'remember_token' => null,
                'role' => 'guru',
                'phone' => '0812-1000-0002',
                'status_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Daniel Sihombing',
                'email' => 'daniel.sihombing@sekolah.test',
                'email_verified_at' => $now,
                'password' => $password,
                'remember_token' => null,
                'role' => 'guru',
                'phone' => '0812-1000-0003',
                'status_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Agnes Natalia',
                'email' => 'agnes.natalia@sekolah.test',
                'email_verified_at' => $now,
                'password' => $password,
                'remember_token' => null,
                'role' => 'guru',
                'phone' => '0812-1000-0004',
                'status_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Markus Wijaya',
                'email' => 'markus.wijaya@sekolah.test',
                'email_verified_at' => $now,
                'password' => $password,
                'remember_token' => null,
                'role' => 'guru',
                'phone' => '0812-1000-0005',
                'status_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Debora Lestari',
                'email' => 'debora.lestari@sekolah.test',
                'email_verified_at' => $now,
                'password' => $password,
                'remember_token' => null,
                'role' => 'guru',
                'phone' => '0812-1000-0006',
                'status_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Samuel Hutabarat',
                'email' => 'samuel.hutabarat@sekolah.test',
                'email_verified_at' => $now,
                'password' => $password,
                'remember_token' => null,
                'role' => 'guru',
                'phone' => '0812-1000-0007',
                'status_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Ruth Anggraini',
                'email' => 'ruth.anggraini@sekolah.test',
                'email_verified_at' => $now,
                'password' => $password,
                'remember_token' => null,
                'role' => 'guru',
                'phone' => '0812-1000-0008',
                'status_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Petrus Fernando',
                'email' => 'petrus.fernando@sekolah.test',
                'email_verified_at' => $now,
                'password' => $password,
                'remember_token' => null,
                'role' => 'guru',
                'phone' => '0812-1000-0009',
                'status_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Ester Monica',
                'email' => 'ester.monica@sekolah.test',
                'email_verified_at' => $now,
                'password' => $password,
                'remember_token' => null,
                'role' => 'guru',
                'phone' => '0812-1000-0010',
                'status_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('users')->insert($guruUsers);

        $guruRecords = [
            [
                'user_email' => 'yohanes.prasetyo@sekolah.test',
                'nip' => '2001000000001000',
                'nama_guru' => 'Yohanes Prasetyo',
                'no_hp' => '0812-1000-0001',
                'alamat' => 'Jl. Merdeka No. 10',
                'tanggal_lahir' => '1985-01-12',
            ],
            [
                'user_email' => 'maria.elisabeth@sekolah.test',
                'nip' => '2001000000002000',
                'nama_guru' => 'Maria Elisabeth',
                'no_hp' => '0812-1000-0002',
                'alamat' => 'Jl. Melati No. 15',
                'tanggal_lahir' => '1987-02-24',
            ],
            [
                'user_email' => 'daniel.sihombing@sekolah.test',
                'nip' => '2001000000003000',
                'nama_guru' => 'Daniel Sihombing',
                'no_hp' => '0812-1000-0003',
                'alamat' => 'Jl. Kenanga No. 08',
                'tanggal_lahir' => '1983-03-05',
            ],
            [
                'user_email' => 'agnes.natalia@sekolah.test',
                'nip' => '2001000000004000',
                'nama_guru' => 'Agnes Natalia',
                'no_hp' => '0812-1000-0004',
                'alamat' => 'Jl. Mawar No. 22',
                'tanggal_lahir' => '1989-04-18',
            ],
            [
                'user_email' => 'markus.wijaya@sekolah.test',
                'nip' => '2001000000005000',
                'nama_guru' => 'Markus Wijaya',
                'no_hp' => '0812-1000-0005',
                'alamat' => 'Jl. Anggrek No. 11',
                'tanggal_lahir' => '1984-05-30',
            ],
            [
                'user_email' => 'debora.lestari@sekolah.test',
                'nip' => '2001000000006000',
                'nama_guru' => 'Debora Lestari',
                'no_hp' => '0812-1000-0006',
                'alamat' => 'Jl. Cempaka No. 19',
                'tanggal_lahir' => '1990-06-14',
            ],
            [
                'user_email' => 'samuel.hutabarat@sekolah.test',
                'nip' => '2001000000007000',
                'nama_guru' => 'Samuel Hutabarat',
                'no_hp' => '0812-1000-0007',
                'alamat' => 'Jl. Pahlawan No. 07',
                'tanggal_lahir' => '1986-07-21',
            ],
            [
                'user_email' => 'ruth.anggraini@sekolah.test',
                'nip' => '2001000000008000',
                'nama_guru' => 'Ruth Anggraini',
                'no_hp' => '0812-1000-0008',
                'alamat' => 'Jl. Diponegoro No. 25',
                'tanggal_lahir' => '1991-08-09',
            ],
            [
                'user_email' => 'petrus.fernando@sekolah.test',
                'nip' => '2001000000009000',
                'nama_guru' => 'Petrus Fernando',
                'no_hp' => '0812-1000-0009',
                'alamat' => 'Jl. Kartini No. 13',
                'tanggal_lahir' => '1988-09-17',
            ],
            [
                'user_email' => 'ester.monica@sekolah.test',
                'nip' => '2001000000010000',
                'nama_guru' => 'Ester Monica',
                'no_hp' => '0812-1000-0010',
                'alamat' => 'Jl. Sudirman No. 31',
                'tanggal_lahir' => '1985-10-26',
            ],
        ];

        $guruRows = [];

        foreach ($guruRecords as $record) {
            $userId = DB::table('users')->where('email', $record['user_email'])->value('id');

            $guruRows[] = [
                'user_id' => $userId,
                'nip' => $record['nip'],
                'nama_guru' => $record['nama_guru'],
                'jenis_kelamin' => null,
                'tanggal_lahir' => $record['tanggal_lahir'],
                'alamat' => $record['alamat'],
                'no_hp' => $record['no_hp'],
                'foto' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('guru')->insert($guruRows);
    }
}
