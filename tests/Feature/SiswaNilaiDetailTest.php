<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SiswaNilaiDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_detail_nilai_siswa_bisa_dibuka_tanpa_server_error(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-07-19 08:00:00', 'Asia/Jakarta'));

        $siswa = $this->createStudentFixture();
        $mapelId = DB::table('mata_pelajaran')->insertGetId([
            'kode_mapel' => 'SEJ-001',
            'nama_mapel' => 'Sejarah Indonesia',
            'kelompok_mapel' => 'A',
            'jam_mingguan' => 4,
            'kkm' => 75,
            'created_at' => now('Asia/Jakarta'),
            'updated_at' => now('Asia/Jakarta'),
        ]);

        $response = $this->actingAs($siswa)->get(route('siswa.nilai.detail', $mapelId));

        $response->assertOk();
        $response->assertSee('Sejarah Indonesia');
    }

    private function createStudentFixture(): User
    {
        $now = now('Asia/Jakarta');

        $userId = DB::table('users')->insertGetId([
            'name' => 'Andreas Pratama',
            'email' => 'andreas.detail@example.test',
            'email_verified_at' => $now,
            'password' => Hash::make('password'),
            'remember_token' => null,
            'role' => 'siswa',
            'phone' => '081200000033',
            'status_aktif' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('siswa')->insert([
            'user_id' => $userId,
            'nis' => '2026000033',
            'nisn' => '0012345699',
            'nama_siswa' => 'Andreas Pratama',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_lahir' => '2010-01-01',
            'alamat' => 'Jl. Siswa 33',
            'no_hp' => '081200000033',
            'foto' => null,
            'kelas_id' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return User::query()->findOrFail($userId);
    }
}
