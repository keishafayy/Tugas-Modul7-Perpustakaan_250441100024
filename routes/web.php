<?php

use App\Http\Controllers\Api\BukuController as DataBukuController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KelolaBukuController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PeminjamanSayaController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman publik
|--------------------------------------------------------------------------
*/

Route::view('/', 'home')->name('home');

/*
|--------------------------------------------------------------------------
| Halaman yang butuh login (middleware auth) + permission (Spatie)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // ---- Dashboard ----
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
        ->middleware('permission:akses-dashboard-admin')->name('admin.dashboard');

    Route::get('/petugas/dashboard', [DashboardController::class, 'petugas'])
        ->middleware('permission:akses-dashboard-petugas')->name('petugas.dashboard');

    Route::get('/siswa/dashboard', [DashboardController::class, 'siswa'])
        ->middleware('permission:lihat-status-peminjaman-sendiri')->name('siswa.dashboard');

    // ---- Profil sendiri ----
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->middleware('permission:lihat-profil-sendiri')->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->middleware('permission:ubah-profil-sendiri')->name('profile.update');

    // ---- Daftar buku ----
    Route::get('/buku', [BukuController::class, 'index'])
        ->middleware('permission:lihat-daftar-buku')->name('buku.index');

    // ---- Kelola buku (tambah, ubah, hapus) : pemeriksaan permission Modul 5 ----
    Route::middleware('can:kelola-buku')->group(function () {
        Route::get('/buku/create', [BukuController::class, 'create'])->name('buku.create');
        Route::post('/buku', [BukuController::class, 'store'])->name('buku.store');
        Route::get('/buku/{buku}/edit', [BukuController::class, 'edit'])->whereNumber('buku')->name('buku.edit');
        Route::put('/buku/{buku}', [BukuController::class, 'update'])->whereNumber('buku')->name('buku.update');
        Route::delete('/buku/{buku}', [BukuController::class, 'destroy'])->whereNumber('buku')->name('buku.destroy');
    });

    // ---- Detail buku ----
    Route::get('/buku/{id}', [BukuController::class, 'show'])
        ->whereNumber('id')->middleware('permission:lihat-daftar-buku')->name('buku.show');

    // ======================= SOAL 1: CELAH KEAMANAN SENGAJA (A2, A3, A4) =======================

    // A2: SQL Injection (query digabung langsung ke string SQL)
    Route::get('/buku-cari', [BukuController::class, 'cari'])->name('buku.cari');

    // A4: IDOR (detail peminjaman tanpa pemeriksaan pemilik)
    Route::get('/peminjaman/{id}', [PeminjamanController::class, 'show'])->name('peminjaman.show');

    // ===========================================================================================

    // ---- Endpoint data buku untuk Fetch API (Modul 4) lewat session login ----
    Route::prefix('data')->name('data.')->group(function () {
        Route::get('buku', [DataBukuController::class, 'index'])
            ->middleware('permission:lihat-daftar-buku')->name('buku.index');

        Route::middleware('can:kelola-buku')->group(function () {
            Route::post('buku', [DataBukuController::class, 'store'])->name('buku.store');
            Route::put('buku/{buku}', [DataBukuController::class, 'update'])->name('buku.update');
            Route::delete('buku/{buku}', [DataBukuController::class, 'destroy'])->name('buku.destroy');
        });
    });

    // ---- Kelola buku versi Fetch API (Modul 4) ----
    Route::get('/kelola/buku', [KelolaBukuController::class, 'index'])
        ->middleware('can:kelola-buku')->name('kelola.buku');

    // ---- Peminjaman dan pengembalian: admin dan petugas ----
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])
        ->middleware('permission:kelola-peminjaman')->name('peminjaman.index');
    Route::post('/peminjaman/{peminjaman}/setujui', [PeminjamanController::class, 'setujui'])
        ->middleware('permission:proses-persetujuan-peminjaman')->name('peminjaman.setujui');
    Route::post('/peminjaman/{peminjaman}/tolak', [PeminjamanController::class, 'tolak'])
        ->middleware('permission:proses-persetujuan-peminjaman')->name('peminjaman.tolak');

    Route::get('/pengembalian', [PengembalianController::class, 'index'])
        ->middleware('permission:kelola-pengembalian')->name('pengembalian.index');
    Route::post('/pengembalian/{peminjaman}', [PengembalianController::class, 'store'])
        ->middleware('permission:catat-pengembalian-buku')->name('pengembalian.store');

    // ---- Laporan ----
    Route::get('/laporan', [LaporanController::class, 'index'])
        ->middleware('permission:lihat-laporan-perpustakaan')->name('laporan.index');

    // ---- Peminjaman milik sendiri ----
    Route::post('/buku/{buku}/ajukan', [PeminjamanSayaController::class, 'ajukan'])
        ->middleware('permission:ajukan-peminjaman-buku')->name('peminjaman.ajukan');
    Route::get('/peminjaman-saya', [PeminjamanSayaController::class, 'status'])
        ->middleware('permission:lihat-status-peminjaman-sendiri')->name('peminjaman.saya');
    Route::get('/riwayat-peminjaman', [PeminjamanSayaController::class, 'riwayatSaya'])
        ->middleware('permission:lihat-riwayat-peminjaman-sendiri')->name('riwayat.saya');
    Route::get('/anggota/{anggota}/riwayat', [PeminjamanSayaController::class, 'riwayat'])
        ->middleware('permission:lihat-riwayat-peminjaman-sendiri')->name('riwayat.show');

    // ---- Kelola pengguna dan role: khusus admin ----
    Route::prefix('admin')->group(function () {
        Route::middleware('permission:kelola-data-pengguna')->group(function () {
            Route::resource('pengguna', UserController::class)
                ->parameters(['pengguna' => 'user'])
                ->except('show');
        });

        Route::put('pengguna/{user}/role', [UserController::class, 'updateRole'])
            ->middleware('permission:kelola-role-pengguna')->name('pengguna.role');
    });
});

require __DIR__ . '/auth.php';
