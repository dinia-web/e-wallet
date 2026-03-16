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
use App\Http\Controllers\PerizinanController;
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
Route::get('/get-siswa-kelas/{kelas}', function ($kelas) {
    return \App\Models\Siswa::where('kelas', $kelas)->get();
});
/*
|--------------------------------------------------------------------------
| Protected Routes (WAJIB LOGIN)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Dashboard umum
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Dispensasi index & detail untuk semua role login
Route::get('/dispen', [DispenController::class, 'index'])->name('dispen.index');

/* PRINT BUKTI DISPEN */
Route::get('/dispen/{id}/print', [DispenController::class,'printBukti'])
    ->name('dispen.print');

Route::get('/dispen/{id}', [DispenController::class, 'show'])->name('dispen.show');

    // Hanya admin/guru bisa hapus
    Route::delete('/dispen/{id}', [DispenController::class, 'destroy'])
        ->name('dispen.destroy')
        ->middleware('role:admin,guru'); // middleware opsional, bisa dibuat sendiri

    // Action untuk admin & guru
    Route::post('/dispen/{id}/action-admin', [DispenController::class, 'actionAdmin'])->name('dispen.actionAdmin');
    Route::post('/dispen/{id}/action-guru', [DispenController::class, 'actionGuru'])->name('dispen.actionGuru');

    // Halaman dashboard khusus siswa
    Route::get('/siswa/dashboard', [DashboardController::class, 'dashboardSiswa']);

/// ================= PERIZINAN =================
Route::middleware(['auth','role:admin,guru,siswa'])->group(function () {

    Route::get('/perizinan', [PerizinanController::class,'index'])
        ->name('perizinan.index');

    Route::get('/perizinan/create', [PerizinanController::class,'create'])
        ->name('perizinan.create');

    Route::post('/perizinan', [PerizinanController::class,'store'])
        ->name('perizinan.store');

    // PRINT (HARUS DIATAS)
    Route::get('/perizinan/print', [PerizinanController::class,'printForm'])
        ->name('perizinan.printForm');

    Route::get('/perizinan/print-data', [PerizinanController::class,'printData'])
        ->name('perizinan.printData');

    // DETAIL
    Route::get('/perizinan/{id}', [PerizinanController::class,'show'])
        ->name('perizinan.show');

    Route::get('/perizinan/{id}/edit', [PerizinanController::class,'edit'])
        ->name('perizinan.edit');

    Route::put('/perizinan/{id}', [PerizinanController::class,'update'])
        ->name('perizinan.update');

    Route::delete('/perizinan/{id}', [PerizinanController::class,'destroy'])
        ->name('perizinan.destroy');
});

    // untuk siswa lihat dispensasi mereka sendiri
Route::prefix('siswa')->middleware('auth')->group(function () {
    Route::get('/dispen', [DispenController::class, 'index'])
        ->name('siswa.dispen.index');
});
    // Resource route lainnya
    Route::resource('siswa', SiswaController::class)->except(['show']);
    Route::resource('users', UserController::class);
    Route::resource('kelas', KelasController::class);
    Route::resource('gurpik', GurpikController::class);

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export', [LaporanController::class, 'export'])->name('laporan.export');

    // Pengaturan
    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan');
    Route::post('/pengaturan/update-profile', [PengaturanController::class, 'updateProfile'])->name('pengaturan.updateProfile');
    Route::post('/pengaturan/update-password', [PengaturanController::class, 'updatePassword'])->name('pengaturan.updatePassword');
    Route::post('/pengaturan/upload-foto', [PengaturanController::class, 'uploadFoto'])->name('pengaturan.uploadFoto');
});