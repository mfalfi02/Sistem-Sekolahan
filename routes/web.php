<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\KenaikanKelasController;
use App\Http\Controllers\RecapController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\SiswaPortalController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

    Route::middleware('role:guru')->group(function () {
        Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
        Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');

        Route::get('/nilai', [NilaiController::class, 'index'])->name('nilai.index');
        Route::post('/nilai', [NilaiController::class, 'store'])->name('nilai.store');
    });

    Route::middleware('role:siswa')->group(function () {
        Route::get('/siswa/portal', [SiswaPortalController::class, 'index'])->name('siswa.portal');
    });

    Route::middleware('role:guru,admin,tu')->group(function () {
        Route::get('/rekap/absensi', [RecapController::class, 'absensi'])->name('rekap.absensi');
        Route::get('/rekap/absensi/export', [RecapController::class, 'exportAbsensi'])->name('rekap.absensi.export');
        Route::get('/rekap/nilai', [RecapController::class, 'nilai'])->name('rekap.nilai');
        Route::get('/rekap/nilai/export', [RecapController::class, 'exportNilai'])->name('rekap.nilai.export');
    });

    Route::middleware('role:admin,tu')->group(function () {
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
        Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');

        Route::prefix('masters')->name('masters.')->group(function () {
            Route::get('{type}', [MasterDataController::class, 'index'])->name('index');
            Route::get('{type}/create', [MasterDataController::class, 'create'])->name('create');
            Route::post('{type}', [MasterDataController::class, 'store'])->name('store');
            Route::get('{type}/{id}/edit', [MasterDataController::class, 'edit'])->name('edit');
            Route::put('{type}/{id}', [MasterDataController::class, 'update'])->name('update');
            Route::delete('{type}/{id}', [MasterDataController::class, 'destroy'])->name('destroy');
        });

        Route::get('/kenaikan-kelas', [KenaikanKelasController::class, 'index'])->name('kenaikan-kelas.index');
        Route::post('/kenaikan-kelas', [KenaikanKelasController::class, 'store'])->name('kenaikan-kelas.store');
    });
});
