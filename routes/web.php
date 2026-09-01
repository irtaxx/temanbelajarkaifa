<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\PenggajianController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RateGajiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Form tambah & edit memakai modal di halaman index,
    // jadi route "create" dan "edit" tidak dipakai.
    // parameters() dipakai pada kelas & rate-gaji karena penunggalan otomatis Laravel
    // menghasilkan {kela} dan {rate_gaji}, tidak cocok dengan nama argumen controller
    // ($kelas dan $rateGaji), sehingga route model binding gagal tanpa pesan error.
    Route::resource('gurus', GuruController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('kelas', KelasController::class)
        ->parameters(['kelas' => 'kelas'])
        ->only(['index', 'store', 'update', 'destroy']);
    Route::resource('jadwals', JadwalController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::post('/rate-gaji/pengaturan', [RateGajiController::class, 'simpanPengaturan'])->name('rate-gaji.pengaturan');
    Route::resource('rate-gaji', RateGajiController::class)
        ->parameters(['rate-gaji' => 'rateGaji'])
        ->only(['index', 'store', 'update', 'destroy']);

    Route::get('/presensi', [PresensiController::class, 'index'])->name('presensi.index');
    Route::post('/presensi', [PresensiController::class, 'store'])->name('presensi.store');
    Route::delete('/presensi/{presensi}', [PresensiController::class, 'destroy'])->name('presensi.destroy');

    Route::get('/penggajian', [PenggajianController::class, 'index'])->name('penggajian.index');
    Route::get('/penggajian/{guru}', [PenggajianController::class, 'detail'])->name('penggajian.detail');
});

require __DIR__.'/auth.php';
