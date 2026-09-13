<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class KepalaSekolahAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_kepala_sekolah_bisa_melihat_laporan_dan_daftar_data(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-08-01 08:00:00', 'Asia/Jakarta'));

        $user = $this->createKepalaSekolah();

        $this->actingAs($user)->get(route('dashboard'))->assertOk();
        $this->actingAs($user)->get(route('rekap.absensi'))->assertOk();
        $this->actingAs($user)->get(route('rekap.nilai'))->assertOk();
        $this->actingAs($user)->get(route('masters.index', 'guru'))->assertOk();
        $this->actingAs($user)->get(route('masters.index', 'siswa'))->assertOk();
    }

    public function test_kepala_sekolah_tidak_bisa_membuka_halaman_kelola_user(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-08-01 08:00:00', 'Asia/Jakarta'));

        $user = $this->createKepalaSekolah();

        $this->actingAs($user)->get(route('users.index'))->assertForbidden();
    }

    private function createKepalaSekolah(): User
    {
        $now = now('Asia/Jakarta');

        $userId = DB::table('users')->insertGetId([
            'name' => 'Kepala Sekolah',
            'email' => 'kepala.sekolah@example.test',
            'email_verified_at' => $now,
            'password' => Hash::make('password'),
            'remember_token' => null,
            'role' => 'kepala_sekolah',
            'phone' => '081200000099',
            'status_aktif' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return User::query()->findOrFail($userId);
    }
}
