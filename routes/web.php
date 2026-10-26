<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\adminController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\PetugasController;
use Faker\Guesser\Name;

Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect()->route('loginfrom');
    });
    Route::post('login', [AuthController::class, 'login'])->name('login');

   Route::get('loginfrom', function () {
         return view('loginfrom');
   })->name('loginfrom');
});

Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('loginfrom');
    });

    Route::post('login', [AuthController::class, 'login'])->name('login');
});

Route::middleware('auth', 'role:owner')->group(function () {
    Route::get('owner', [OwnerController::class, 'index'])->name('owner.dashboard');
    Route::get('owner/rekap', [OwnerController::class, 'rekap'])->name('owner.rekap');

    Route::get('/cetakRekap', [OwnerController::class, 'cetakRekap'])->name('cetak.rekap');
});

/// admin
Route::middleware('auth', 'role:admin')->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');

    //kelola user
    Route::get('/admin/user', [AdminController::class, 'adminUser'])->name('admin.user');
    Route::post('/admin/user', [AdminController::class, 'storeUser'])->name('admin.user.store');
    Route::put('/admin/user/{id}', [AdminController::class, 'updateUser'])->name('admin.user.update');
    Route::delete('/admin/user/{id}', [AdminController::class, 'deleteUser'])->name('admin.user.delete');

    // jenis kendaraan
    Route::get('/admin/jenis', [AdminController::class, 'jenis'])->name('admin.jenis');
    Route::post('/admin/jenis', [AdminController::class, 'jenisStore'])->name('admin.jenis.store');

    Route::put('/admin/jenis/{id}', [AdminController::class, 'editJenis'])->name('admin.update');
    Route::delete('/admin/jenis/{id}', [AdminController::class, 'hapusJenis'])->name('admin.jenis.delete');

    // area parkir
    Route::get('/admin/area', [AdminController::class, 'area'])->name('admin.area');
    Route::put('/admin/area', [AdminController::class, 'areaUpdate'])->name('admin.area.Update');

    // tarif
    Route::get('/admin/tarif', [AdminController::class, 'tarif'])->name('admin.tarif');
    Route::put('/admin/tarif', [AdminController::class, 'tarifUpdate'])->name('admin.tarif.Update');
    // tarif

    // riwayat & log
    Route::get('/admin/riwayat', [AdminController::class, 'riwayat'])->name('admin.riwayat');
    Route::get('/admin/log', [AdminController::class, 'Log'])->name('admin.log');

    Route::get('/admin/riwayat/cetak', [AdminController::class, 'cetak'])->name('admin.riwayat.cetak');
});

/// petugas
Route::middleware('auth', 'role:petugas')->group(function () {
    Route::get('petugas', [PetugasController::class, 'index'])->name('petugas.dashboard');

    Route::get('masuk', [PetugasController::class, 'createMasuk'])->name('masuk.from');
    Route::post('kendaraan_masuk', [PetugasController::class, 'storeMasuk'])->name('masuk.store');

    Route::get('keluar', [PetugasController::class, 'createKeluar'])->name('keluar.from');
    Route::post('keluar', [PetugasController::class, 'processKeluar'])->name('prosess.keluar');
    Route::post('preview_keluar', [petugasController::class, 'previewKeluar'])->name('preview.keluar');

    Route::get('riwayat', [PetugasController::class, 'riwayat'])->name('Riwayat');

    // Route::get('/sedang_parkir', [PetugasController::class, 'sedangParkir'])->name('sedang.parkir');
});

Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::fallback(function () {
    return view('404');
});
