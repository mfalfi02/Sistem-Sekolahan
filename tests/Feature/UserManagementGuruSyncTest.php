<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserManagementGuruSyncTest extends TestCase
{
    use RefreshDatabase;

    public function test_menambah_user_guru_juga_membuat_data_guru(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-07-19 08:00:00', 'Asia/Jakarta'));

        $admin = $this->createAdminUser();

        $response = $this->actingAs($admin)->post(route('users.store'), [
            'name' => 'Samuel Hutabarat',
            'email' => 'samuel.guru@example.test',
            'password' => 'password123',
            'role' => 'guru',
            'phone' => '081200000021',
            'nip' => '198700000021',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_lahir' => '1987-01-01',
            'alamat' => 'Jl. Guru 21',
            'status_aktif' => 1,
        ]);

        $response->assertRedirect(route('users.index'));

        $user = User::query()->where('email', 'samuel.guru@example.test')->firstOrFail();
        $guru = Guru::query()->where('user_id', $user->id)->firstOrFail();

        $this->assertSame('Samuel Hutabarat', $guru->nama_guru);
        $this->assertSame('081200000021', $guru->no_hp);
    }

    public function test_mengubah_user_guru_ikut_memperbarui_data_guru(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-07-19 08:00:00', 'Asia/Jakarta'));

        $admin = $this->createAdminUser();
        $fixture = $this->createGuruUserFixture('Samuel Hutabarat', 'samuel.edit@example.test', '081200000021');

        $response = $this->actingAs($admin)->put(route('users.update', $fixture['user']), [
            'name' => 'Samuel Update',
            'email' => 'samuel.edit@example.test',
            'password' => '',
            'role' => 'guru',
            'phone' => '081200000099',
            'nip' => '198700000099',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_lahir' => '1987-02-02',
            'alamat' => 'Jl. Guru Update',
            'status_aktif' => 1,
        ]);

        $response->assertRedirect(route('users.index'));

        $guru = Guru::query()->where('user_id', $fixture['user']->id)->firstOrFail();

        $this->assertSame('Samuel Update', $guru->nama_guru);
        $this->assertSame('081200000099', $guru->no_hp);
    }

    public function test_menghapus_user_guru_juga_menghapus_data_guru(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-07-19 08:00:00', 'Asia/Jakarta'));

        $admin = $this->createAdminUser();
        $fixture = $this->createGuruUserFixture('Samuel Hutabarat', 'samuel.delete@example.test', '081200000021');

        $response = $this->actingAs($admin)->delete(route('users.destroy', $fixture['user']));

        $response->assertRedirect(route('users.index'));

        $this->assertDatabaseMissing('users', [
            'id' => $fixture['user']->id,
        ]);

        $this->assertDatabaseMissing('guru', [
            'user_id' => $fixture['user']->id,
        ]);
    }

    private function createAdminUser(): User
    {
        $now = now('Asia/Jakarta');

        $userId = DB::table('users')->insertGetId([
            'name' => 'Admin Uji',
            'email' => 'admin.uji@example.test',
            'email_verified_at' => $now,
            'password' => Hash::make('password'),
            'remember_token' => null,
            'role' => 'admin',
            'phone' => '081200000099',
            'status_aktif' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return User::query()->findOrFail($userId);
    }

    /**
     * @return array{user: User, guru: Guru}
     */
    private function createGuruUserFixture(string $name, string $email, string $phone): array
    {
        $now = now('Asia/Jakarta');

        $userId = DB::table('users')->insertGetId([
            'name' => $name,
            'email' => $email,
            'email_verified_at' => $now,
            'password' => Hash::make('password'),
            'remember_token' => null,
            'role' => 'guru',
            'phone' => $phone,
            'status_aktif' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $guruId = DB::table('guru')->insertGetId([
            'user_id' => $userId,
            'nip' => '198700000099',
            'nama_guru' => $name,
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_lahir' => '1987-01-01',
            'alamat' => 'Jl. Guru Uji',
            'no_hp' => $phone,
            'foto' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return [
            'user' => User::query()->findOrFail($userId),
            'guru' => Guru::query()->findOrFail($guruId),
        ];
    }
}
