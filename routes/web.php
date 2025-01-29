<?php

use App\Http\Controllers\ProfileController;
use App\Models\University;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UniversityController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BobotController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;

Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        if (Auth::check()) {
            Auth::logout();
            return redirect()->route('login')->with('message', 'Anda telah logout.');
        }
        return view('auth.login'); // Ganti dengan view login Anda
    })->name('login');

    Route::post('/', [AuthenticatedSessionController::class, 'store']);
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
});

Route::middleware('auth')->group(function () {
    // Dashboard Admin
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');

    // Dashboard User
    Route::get('/user/dashboard', [DashboardController::class, 'userDashboard'])->name('user.dashboard');

    // Logout
    Route::get('/logout', function () {
        Auth::logout();
        return redirect()->route('login')->with('message', 'Anda telah logout.');
    })->name('logout');

    // Universitas CRUD
    Route::get('/tambah', [UniversityController::class, 'create'])->name('universities.create');
    Route::post('/tambah', [UniversityController::class, 'store'])->name('universities.store');
    Route::get('/universitas/{id}/edit', [UniversityController::class, 'edit'])->name('universities.edit');
    Route::put('/universitas/{id}', [UniversityController::class, 'update'])->name('universities.update');
    Route::delete('/universitas/{id}', [UniversityController::class, 'destroy'])->name('universities.destroy');

    // Rute tambahan
    Route::get('/dashboard', [UniversityController::class, 'index'])->name('dashboard');
    Route::get('/range', [UniversityController::class, 'range'])->name('range');
    Route::get('/normalisasi', [UniversityController::class, 'normalisasi'])->name('normalisasi');
    Route::get('/ranking', [UniversityController::class, 'ranking'])->name('ranking');

    // Bobot
    Route::get('/bobot', [BobotController::class, 'index'])->name('bobot.form');
    Route::post('/bobot', [BobotController::class, 'store'])->name('bobot.store');

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Data dengan middleware auth dan verified
Route::get('/data', function () {
    $universities = University::all();
    return view('data', ['universities' => $universities]);
})->middleware(['auth', 'verified'])->name('data');

require __DIR__.'/auth.php';
