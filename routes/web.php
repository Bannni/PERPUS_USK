<?php

use App\Http\Controllers\AdminBookController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

// Public Landing Page
Route::get('/', [BookController::class, 'landing'])->name('home');

// Toko Buku (Shop / Storefront where all books are sold)
Route::get('/shop', [BookController::class, 'shop'])->name('shop');

// Perpustakaan Pribadi (My Library - Shelves grouped by genre for purchased books)
Route::get('/catalog', [BookController::class, 'myLibrary'])->name('catalog');
Route::get('/library', [BookController::class, 'myLibrary'])->name('library');

// API for quick book modal details
Route::get('/api/books/{id}', [BookController::class, 'show'])->name('books.show');

// Authentication Routes (Guest Only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated Routes (User & Admin)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Fitur Beli Buku & Riwayat Pembelian
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/my-orders', [OrderController::class, 'index'])->name('orders.index');
});

// Admin Protected Routes (Cannot be accessed by regular user)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminBookController::class, 'index'])->name('dashboard');
    Route::post('/books', [AdminBookController::class, 'store'])->name('books.store');
    Route::put('/books/{id}', [AdminBookController::class, 'update'])->name('books.update');
    Route::delete('/books/{id}', [AdminBookController::class, 'destroy'])->name('books.destroy');
    Route::get('/users', [AdminBookController::class, 'users'])->name('users');
    Route::get('/orders', [AdminBookController::class, 'orders'])->name('orders');
});
