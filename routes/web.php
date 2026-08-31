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

    // Form tambah data memakai modal di halaman index, jadi route "create" tidak dipakai.
    Route::resource('gurus', GuruController::class)->except(['show', 'create']);
    Route::resource('kelas', KelasController::class)->except(['show', 'create']);
    Route::resource('jadwals', JadwalController::class)->except(['show', 'create']);
    Route::resource('rate-gaji', RateGajiController::class)->except(['show', 'create']);

    Route::get('/presensi', [PresensiController::class, 'index'])->name('presensi.index');
    Route::post('/presensi', [PresensiController::class, 'store'])->name('presensi.store');
    Route::delete('/presensi/{presensi}', [PresensiController::class, 'destroy'])->name('presensi.destroy');

    Route::get('/penggajian', [PenggajianController::class, 'index'])->name('penggajian.index');
    Route::get('/penggajian/{guru}', [PenggajianController::class, 'detail'])->name('penggajian.detail');
});

require __DIR__.'/auth.php';
