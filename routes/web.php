<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

// Public Landing Page
Route::get('/', [BookController::class, 'landing'])->name('home');

// Bookstore Catalog / Library (Matches reference design)
Route::get('/catalog', [BookController::class, 'catalog'])->name('catalog');
Route::get('/library', [BookController::class, 'catalog'])->name('library'); // Alias

// API for quick book modal details
Route::get('/api/books/{id}', [BookController::class, 'show'])->name('books.show');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
