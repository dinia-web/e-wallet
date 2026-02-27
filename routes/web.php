<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DispenController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\GurpikController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PengaturanController;
/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [AuthController::class, 'splash']);
Route::get('/pilihan', [AuthController::class, 'pilihan']);

Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);

Route::get('/register', [RegisterController::class, 'showRegister']);
Route::post('/register', [RegisterController::class, 'register']);

Route::post('/lupa-password', [AuthController::class, 'prosesLupaPassword'])
    ->name('password.reset.manual');

Route::get('/auth/dispen', [DispenController::class, 'create']); // ✅ siswa tanpa login
Route::post('/auth/dispen', [DispenController::class, 'store']); // ✅ submit siswa
Route::get('/get-siswa/{nis}', function ($nis) {
    $siswa = \App\Models\Siswa::where('nis', $nis)->first();

    if (!$siswa) {
        return response()->json(['status' => false]);
    }

    return response()->json([
        'status' => true,
        'nama' => $siswa->nama,
        'kelas' => $siswa->kelas
    ]);
});
/*
|--------------------------------------------------------------------------
| Protected Routes (WAJIB LOGIN)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::get('/dispen', [DispenController::class, 'index'])
        ->name('dispen.index');

    Route::get('/dispen/{id}', [DispenController::class, 'show'])
        ->name('dispen.show');

    Route::delete('/dispen/{id}', [DispenController::class, 'destroy'])
        ->name('dispen.destroy');
    
    Route::post('/dispen/{id}/action-admin', [DispenController::class, 'actionAdmin'])
    ->name('dispen.actionAdmin');

    Route::post('/dispen/{id}/action-guru', [DispenController::class, 'actionGuru'])
        ->name('dispen.actionGuru');

    Route::resource('siswa', SiswaController::class);
    Route::resource('users', UserController::class);
    Route::resource('kelas', KelasController::class);
    Route::resource('gurpik', GurpikController::class);
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export', [LaporanController::class, 'export'])->name('laporan.export');

    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan');

    Route::post('/pengaturan/update-profile', [PengaturanController::class, 'updateProfile'])->name('pengaturan.updateProfile');

    Route::post('/pengaturan/update-password', [PengaturanController::class, 'updatePassword'])->name('pengaturan.updatePassword');

    Route::post('/pengaturan/upload-foto', [PengaturanController::class, 'uploadFoto'])->name('pengaturan.uploadFoto');
});
