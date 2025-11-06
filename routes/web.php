<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SiswaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('/siswa', SiswaController::class)->names('siswa');

    Route::get('/dashboard/admin', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

Route::middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('/dashboard/siswa', function () {
        return view('siswa.dashboard');
    })->name('siswa.dashboard');
});

Route::middleware(['auth', 'role:guru'])->group(function () {
    Route::get('/dashboard/guru', function () {
        return view('guru.dashboard');
    })->name('guru.dashboard');
});



require __DIR__ . '/auth.php';
