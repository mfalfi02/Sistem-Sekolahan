<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('siswa')->delete();
        DB::table('users')->where('role', 'siswa')->delete();

        $now = now('Asia/Jakarta');
        $password = Hash::make('Sekolah123!');

        $siswaUsers = [
            ['name' => 'Andreas Pratama', 'email' => 'andreas.pratama@siswa.test', 'phone' => '0812-0000-1001'],
            ['name' => 'Beni Saputra', 'email' => 'beni.saputra@siswa.test', 'phone' => '0812-0000-1002'],
            ['name' => 'Clara Angelia', 'email' => 'clara.angelia@siswa.test', 'phone' => '0812-0000-1003'],
            ['name' => 'Daniel Wijaya', 'email' => 'daniel.wijaya@siswa.test', 'phone' => '0812-0000-1004'],
            ['name' => 'Elisa Natalia', 'email' => 'elisa.natalia@siswa.test', 'phone' => '0812-0000-1005'],
            ['name' => 'Fransiskus Dimas', 'email' => 'fransiskus.dimas@siswa.test', 'phone' => '0812-0000-1006'],
            ['name' => 'Gabriella Putri', 'email' => 'gabriella.putri@siswa.test', 'phone' => '0812-0000-1007'],
            ['name' => 'Hendri Kurniawan', 'email' => 'hendri.kurniawan@siswa.test', 'phone' => '0812-0000-1008'],
            ['name' => 'Indah Lestari', 'email' => 'indah.lestari@siswa.test', 'phone' => '0812-0000-1009'],
            ['name' => 'Jonathan Kevin', 'email' => 'jonathan.kevin@siswa.test', 'phone' => '0812-0000-1010'],
            ['name' => 'Kevin Alexander', 'email' => 'kevin.alexander@siswa.test', 'phone' => '0812-0000-1101'],
            ['name' => 'Laura Christine', 'email' => 'laura.christine@siswa.test', 'phone' => '0812-0000-1102'],
            ['name' => 'Markus Febrianto', 'email' => 'markus.febrianto@siswa.test', 'phone' => '0812-0000-1103'],
            ['name' => 'Nadia Permata', 'email' => 'nadia.permata@siswa.test', 'phone' => '0812-0000-1104'],
            ['name' => 'Olivia Salsabila', 'email' => 'olivia.salsabila@siswa.test', 'phone' => '0812-0000-1105'],
            ['name' => 'Paulus Yordan', 'email' => 'paulus.yordan@siswa.test', 'phone' => '0812-0000-1106'],
            ['name' => 'Queenza Maharani', 'email' => 'queenza.maharani@siswa.test', 'phone' => '0812-0000-1107'],
            ['name' => 'Rafael Sihombing', 'email' => 'rafael.sihombing@siswa.test', 'phone' => '0812-0000-1108'],
            ['name' => 'Sinta Amelia', 'email' => 'sinta.amelia@siswa.test', 'phone' => '0812-0000-1109'],
            ['name' => 'Thomas Aditya', 'email' => 'thomas.aditya@siswa.test', 'phone' => '0812-0000-1110'],
            ['name' => 'Ursula Monica', 'email' => 'ursula.monica@siswa.test', 'phone' => '0812-0000-1201'],
            ['name' => 'Vania Oktaviani', 'email' => 'vania.oktaviani@siswa.test', 'phone' => '0812-0000-1202'],
            ['name' => 'William Fernando', 'email' => 'william.fernando@siswa.test', 'phone' => '0812-0000-1203'],
            ['name' => 'Xaverius Bastian', 'email' => 'xaverius.bastian@siswa.test', 'phone' => '0812-0000-1204'],
            ['name' => 'Yuliana Grace', 'email' => 'yuliana.grace@siswa.test', 'phone' => '0812-0000-1205'],
            ['name' => 'Zefanya Putra', 'email' => 'zefanya.putra@siswa.test', 'phone' => '0812-0000-1206'],
            ['name' => 'Agnes Theresia', 'email' => 'agnes.theresia@siswa.test', 'phone' => '0812-0000-1207'],
            ['name' => 'Brian Mahendra', 'email' => 'brian.mahendra@siswa.test', 'phone' => '0812-0000-1208'],
            ['name' => 'Citra Valentine', 'email' => 'citra.valentine@siswa.test', 'phone' => '0812-0000-1209'],
            ['name' => 'David Christian', 'email' => 'david.christian@siswa.test', 'phone' => '0812-0000-1210'],
        ];

        $siswaUsersPayload = [];

        foreach ($siswaUsers as $user) {
            $siswaUsersPayload[] = [
                'name' => $user['name'],
                'email' => $user['email'],
                'email_verified_at' => $now,
                'password' => $password,
                'remember_token' => null,
                'role' => 'siswa',
                'phone' => $user['phone'],
                'status_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('users')->insert($siswaUsersPayload);

        $kelasIds = DB::table('kelas')->pluck('id', 'nama_kelas');

        $siswaRecords = [
            ['email' => 'andreas.pratama@siswa.test', 'nis' => '2026100001', 'nisn' => '6100000001', 'nama_siswa' => 'Andreas Pratama', 'alamat' => 'Jl. Merdeka No. 12', 'tanggal_lahir' => '2010-01-12', 'kelas_label' => 'X'],
            ['email' => 'beni.saputra@siswa.test', 'nis' => '2026100002', 'nisn' => '6100000002', 'nama_siswa' => 'Beni Saputra', 'alamat' => 'Jl. Melati No. 08', 'tanggal_lahir' => '2010-02-25', 'kelas_label' => 'X'],
            ['email' => 'clara.angelia@siswa.test', 'nis' => '2026100003', 'nisn' => '6100000003', 'nama_siswa' => 'Clara Angelia', 'alamat' => 'Jl. Kenanga No. 21', 'tanggal_lahir' => '2010-03-08', 'kelas_label' => 'X'],
            ['email' => 'daniel.wijaya@siswa.test', 'nis' => '2026100004', 'nisn' => '6100000004', 'nama_siswa' => 'Daniel Wijaya', 'alamat' => 'Jl. Mawar No. 15', 'tanggal_lahir' => '2010-04-19', 'kelas_label' => 'X'],
            ['email' => 'elisa.natalia@siswa.test', 'nis' => '2026100005', 'nisn' => '6100000005', 'nama_siswa' => 'Elisa Natalia', 'alamat' => 'Jl. Anggrek No. 10', 'tanggal_lahir' => '2010-05-30', 'kelas_label' => 'X'],
            ['email' => 'fransiskus.dimas@siswa.test', 'nis' => '2026100006', 'nisn' => '6100000006', 'nama_siswa' => 'Fransiskus Dimas', 'alamat' => 'Jl. Cempaka No. 18', 'tanggal_lahir' => '2010-06-14', 'kelas_label' => 'X'],
            ['email' => 'gabriella.putri@siswa.test', 'nis' => '2026100007', 'nisn' => '6100000007', 'nama_siswa' => 'Gabriella Putri', 'alamat' => 'Jl. Pahlawan No. 07', 'tanggal_lahir' => '2010-07-21', 'kelas_label' => 'X'],
            ['email' => 'hendri.kurniawan@siswa.test', 'nis' => '2026100008', 'nisn' => '6100000008', 'nama_siswa' => 'Hendri Kurniawan', 'alamat' => 'Jl. Diponegoro No. 24', 'tanggal_lahir' => '2010-08-06', 'kelas_label' => 'X'],
            ['email' => 'indah.lestari@siswa.test', 'nis' => '2026100009', 'nisn' => '6100000009', 'nama_siswa' => 'Indah Lestari', 'alamat' => 'Jl. Kartini No. 09', 'tanggal_lahir' => '2010-09-17', 'kelas_label' => 'X'],
            ['email' => 'jonathan.kevin@siswa.test', 'nis' => '2026100010', 'nisn' => '6100000010', 'nama_siswa' => 'Jonathan Kevin', 'alamat' => 'Jl. Sudirman No. 31', 'tanggal_lahir' => '2010-10-29', 'kelas_label' => 'X'],
            ['email' => 'kevin.alexander@siswa.test', 'nis' => '2026110001', 'nisn' => '6110000001', 'nama_siswa' => 'Kevin Alexander', 'alamat' => 'Jl. Gajah Mada No. 11', 'tanggal_lahir' => '2009-01-11', 'kelas_label' => 'XI'],
            ['email' => 'laura.christine@siswa.test', 'nis' => '2026110002', 'nisn' => '6110000002', 'nama_siswa' => 'Laura Christine', 'alamat' => 'Jl. Imam Bonjol No. 16', 'tanggal_lahir' => '2009-02-23', 'kelas_label' => 'XI'],
            ['email' => 'markus.febrianto@siswa.test', 'nis' => '2026110003', 'nisn' => '6110000003', 'nama_siswa' => 'Markus Febrianto', 'alamat' => 'Jl. Ahmad Yani No. 20', 'tanggal_lahir' => '2009-03-05', 'kelas_label' => 'XI'],
            ['email' => 'nadia.permata@siswa.test', 'nis' => '2026110004', 'nisn' => '6110000004', 'nama_siswa' => 'Nadia Permata', 'alamat' => 'Jl. Tanjung Raya No. 05', 'tanggal_lahir' => '2009-04-16', 'kelas_label' => 'XI'],
            ['email' => 'olivia.salsabila@siswa.test', 'nis' => '2026110005', 'nisn' => '6110000005', 'nama_siswa' => 'Olivia Salsabila', 'alamat' => 'Jl. Karya Baru No. 14', 'tanggal_lahir' => '2009-05-27', 'kelas_label' => 'XI'],
            ['email' => 'paulus.yordan@siswa.test', 'nis' => '2026110006', 'nisn' => '6110000006', 'nama_siswa' => 'Paulus Yordan', 'alamat' => 'Jl. Sejahtera No. 19', 'tanggal_lahir' => '2009-06-09', 'kelas_label' => 'XI'],
            ['email' => 'queenza.maharani@siswa.test', 'nis' => '2026110007', 'nisn' => '6110000007', 'nama_siswa' => 'Queenza Maharani', 'alamat' => 'Jl. Damai No. 22', 'tanggal_lahir' => '2009-07-18', 'kelas_label' => 'XI'],
            ['email' => 'rafael.sihombing@siswa.test', 'nis' => '2026110008', 'nisn' => '6110000008', 'nama_siswa' => 'Rafael Sihombing', 'alamat' => 'Jl. Bhayangkara No. 06', 'tanggal_lahir' => '2009-08-04', 'kelas_label' => 'XI'],
            ['email' => 'sinta.amelia@siswa.test', 'nis' => '2026110009', 'nisn' => '6110000009', 'nama_siswa' => 'Sinta Amelia', 'alamat' => 'Jl. Pendidikan No. 25', 'tanggal_lahir' => '2009-09-15', 'kelas_label' => 'XI'],
            ['email' => 'thomas.aditya@siswa.test', 'nis' => '2026110010', 'nisn' => '6110000010', 'nama_siswa' => 'Thomas Aditya', 'alamat' => 'Jl. Kasih No. 13', 'tanggal_lahir' => '2009-10-26', 'kelas_label' => 'XI'],
            ['email' => 'ursula.monica@siswa.test', 'nis' => '2026120001', 'nisn' => '6120000001', 'nama_siswa' => 'Ursula Monica', 'alamat' => 'Jl. Harapan No. 17', 'tanggal_lahir' => '2008-01-10', 'kelas_label' => 'XII'],
            ['email' => 'vania.oktaviani@siswa.test', 'nis' => '2026120002', 'nisn' => '6120000002', 'nama_siswa' => 'Vania Oktaviani', 'alamat' => 'Jl. Pelita No. 04', 'tanggal_lahir' => '2008-02-22', 'kelas_label' => 'XII'],
            ['email' => 'william.fernando@siswa.test', 'nis' => '2026120003', 'nisn' => '6120000003', 'nama_siswa' => 'William Fernando', 'alamat' => 'Jl. Suka Maju No. 27', 'tanggal_lahir' => '2008-03-03', 'kelas_label' => 'XII'],
            ['email' => 'xaverius.bastian@siswa.test', 'nis' => '2026120004', 'nisn' => '6120000004', 'nama_siswa' => 'Xaverius Bastian', 'alamat' => 'Jl. Tunas Bangsa No. 30', 'tanggal_lahir' => '2008-04-14', 'kelas_label' => 'XII'],
            ['email' => 'yuliana.grace@siswa.test', 'nis' => '2026120005', 'nisn' => '6120000005', 'nama_siswa' => 'Yuliana Grace', 'alamat' => 'Jl. Bukit Indah No. 02', 'tanggal_lahir' => '2008-05-25', 'kelas_label' => 'XII'],
            ['email' => 'zefanya.putra@siswa.test', 'nis' => '2026120006', 'nisn' => '6120000006', 'nama_siswa' => 'Zefanya Putra', 'alamat' => 'Jl. Khatulistiwa No. 33', 'tanggal_lahir' => '2008-06-07', 'kelas_label' => 'XII'],
            ['email' => 'agnes.theresia@siswa.test', 'nis' => '2026120007', 'nisn' => '6120000007', 'nama_siswa' => 'Agnes Theresia', 'alamat' => 'Jl. Sawo No. 09', 'tanggal_lahir' => '2008-07-19', 'kelas_label' => 'XII'],
            ['email' => 'brian.mahendra@siswa.test', 'nis' => '2026120008', 'nisn' => '6120000008', 'nama_siswa' => 'Brian Mahendra', 'alamat' => 'Jl. Cemara No. 26', 'tanggal_lahir' => '2008-08-01', 'kelas_label' => 'XII'],
            ['email' => 'citra.valentine@siswa.test', 'nis' => '2026120009', 'nisn' => '6120000009', 'nama_siswa' => 'Citra Valentine', 'alamat' => 'Jl. Flamboyan No. 28', 'tanggal_lahir' => '2008-09-13', 'kelas_label' => 'XII'],
            ['email' => 'david.christian@siswa.test', 'nis' => '2026120010', 'nisn' => '6120000010', 'nama_siswa' => 'David Christian', 'alamat' => 'Jl. Teratai No. 03', 'tanggal_lahir' => '2008-10-24', 'kelas_label' => 'XII'],
        ];

        $siswaRows = [];

        foreach ($siswaRecords as $record) {
            $userId = DB::table('users')->where('email', $record['email'])->value('id');

            $siswaRows[] = [
                'user_id' => $userId,
                'nis' => $record['nis'],
                'nisn' => $record['nisn'],
                'nama_siswa' => $record['nama_siswa'],
                'jenis_kelamin' => null,
                'tanggal_lahir' => $record['tanggal_lahir'],
                'alamat' => $record['alamat'],
                'no_hp' => DB::table('users')->where('id', $userId)->value('phone'),
                'foto' => null,
                'kelas_id' => $kelasIds[$record['kelas_label']] ?? null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('siswa')->insert($siswaRows);
    }
}
