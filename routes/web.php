<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Modul Buku (Hanya dapat diakses oleh pengguna yang sudah login)
    Route::resource('books', BookController::class);

    // Modul Kategori
    Route::resource('categories', CategoryController::class);

    // Modul Anggota
    Route::resource('members', MemberController::class);

    // Modul Peminjaman & Pengembalian
    Route::resource('borrowings', BorrowingController::class)->only(['index', 'create', 'store', 'show']);
    Route::post('/borrowings/{borrowing}/return', [BorrowingController::class, 'return'])
        ->name('borrowings.return');

    // Modul Pengelolaan Pengguna (Dibatasi dengan Gate manage-users hanya untuk admin)
    Route::resource('users', UserController::class)->middleware('can:manage-users');
});
