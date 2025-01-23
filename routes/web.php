<!-- <?php

use App\Http\Controllers\ProfileController;
use App\Models\University;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UniversityController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        // Jika pengguna sudah terautentikasi, logout dan redirect ke login
        if (Auth::check()) {
            Auth::logout();
            return redirect()->route('login')->with('message', 'Anda telah logout.');
        }

        // Jika pengguna belum terautentikasi, tampilkan halaman login
        return view('auth.login'); // Ganti dengan view login Anda
    })->name('login');

    Route::post('/', [AuthenticatedSessionController::class, 'store']);

    Route::get('/logout', function () {
        Auth::logout();
        return redirect()->route('login');
    })->name('logout');
});

Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [UniversityController::class, 'index'])->name('dashboard');
});


Route::get('/logout', function () {
    Auth::logout();
    return redirect('/login');
});

Route::middleware('auth')->group(function () {
    Route::get('/range', [UniversityController::class, 'range'])->name('range');
});

Route::middleware('auth')->group(function () {
    Route::get('/normalisasi', [UniversityController::class, 'normalisasi'])->name('normalisasi');
});


Route::middleware('auth')->group(function () {
    Route::get('/ranking', [UniversityController::class, 'ranking'])->name('ranking');
});

Route::get('/data', function () {
    // Ambil data universitas dari database
    $universities = University::all();

    // Kirim data ke view
    return view('data', ['universities' => $universities]);
})->middleware(['auth', 'verified'])->name('data');



Route::get('/tambah', [UniversityController::class, 'create'])->name('universities.create');
Route::post('/tambah', [UniversityController::class, 'store'])->name('universities.store');

Route::get('/universitas/{id}/edit', [UniversityController::class, 'edit'])->name('universities.edit');
Route::put('/universitas/{id}', [UniversityController::class, 'update'])->name('universities.update');

Route::delete('/universitas/{id}', [UniversityController::class, 'destroy'])->name('universities.destroy');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
