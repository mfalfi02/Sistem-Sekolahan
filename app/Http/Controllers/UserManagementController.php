<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search', ''));

        $query = User::whereIn('role', ['admin', 'guru'])->with('siswa.kelas');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return view('users.index', [
            'users' => $query->latest()->paginate(10),
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('users.form', [
            'user' => null,
            'kelasList' => Kelas::orderBy('nama_kelas')->get(),
            'mode' => 'create',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateRequest($request);

        DB::transaction(function () use ($data): void {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'role' => $data['role'],
                'phone' => $data['phone'] ?? null,
                'status_aktif' => $data['status_aktif'] ?? false,
            ]);

            $this->syncSiswaProfile($user, $data);
        });

        ActivityLogger::record(
            $request->user(),
            'user_create',
            'Tambah user '.$data['name'],
            'Role '.strtoupper($data['role']).' berhasil dibuat.',
            route('users.index')
        );

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        $user->loadMissing('siswa.kelas');

        return view('users.form', [
            'user' => $user,
            'kelasList' => Kelas::orderBy('nama_kelas')->get(),
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $this->validateRequest($request, $user);

        DB::transaction(function () use ($data, $user): void {
            $payload = [
                'name' => $data['name'],
                'email' => $data['email'],
                'role' => $data['role'],
                'phone' => $data['phone'] ?? null,
                'status_aktif' => $data['status_aktif'] ?? false,
            ];

            if (filled($data['password'] ?? null)) {
                $payload['password'] = $data['password'];
            }

            $user->update($payload);

            $this->syncSiswaProfile($user->fresh(), $data);
        });

        ActivityLogger::record(
            $request->user(),
            'user_update',
            'Perbarui user '.$data['name'],
            'Role '.strtoupper($data['role']).' berhasil diperbarui.',
            route('users.index')
        );

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['user' => 'Anda tidak bisa menghapus akun sendiri.']);
        }

        $user->delete();

        ActivityLogger::record(
            $request->user(),
            'user_delete',
            'Hapus user '.$user->name,
            'Akun '.strtoupper($user->role).' dihapus dari sistem.',
            route('users.index')
        );

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validateRequest(Request $request, ?User $user = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                $user ? Rule::unique('users', 'email')->ignore($user->id) : Rule::unique('users', 'email'),
            ],
            'password' => [$user ? 'nullable' : 'required', 'string', Password::min(8)],
            'role' => ['required', 'in:admin,guru'],
            'phone' => ['nullable', 'string', 'max:20'],
            'status_aktif' => ['nullable', 'boolean'],
        ]);

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        return $data;
    }

    private function syncSiswaProfile(User $user, array $data): void
    {
        if ($user->role !== 'siswa') {
            return;
        }

        Siswa::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nis' => $user->siswa?->nis ?? 'SISWA-'.$user->id,
                'nisn' => $user->siswa?->nisn,
                'nama_siswa' => $data['name'],
                'jenis_kelamin' => $user->siswa?->jenis_kelamin,
                'tanggal_lahir' => $user->siswa?->tanggal_lahir,
                'alamat' => $user->siswa?->alamat,
                'no_hp' => $data['phone'] ?? $user->siswa?->no_hp,
                'foto' => $user->siswa?->foto,
                'kelas_id' => $data['kelas_id'] ?? $user->siswa?->kelas_id,
            ]
        );
    }
}
