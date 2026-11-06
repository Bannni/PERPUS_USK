<?php

use App\Http\Controllers\Admin\BookController as AdminBookResourceController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderResourceController;
use App\Http\Controllers\Admin\UserController as AdminUserResourceController;
use App\Http\Controllers\AdminBookController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public & Reader Routes (Protected from Admin access via 'user' middleware)
|--------------------------------------------------------------------------
*/
Route::middleware('user')->group(function () {
    // GET / -> BookController@landing (Landing page & Editorial catalog)
    Route::get('/', [BookController::class, 'landing'])->name('home');

    // GET /about -> PageController@about (Profil Toko BookStore)
    Route::get('/about', [PageController::class, 'about'])->name('about');

    // GET|POST /contact -> ContactController@form / store (Form Kontak Pengunjung)
    Route::get('/contact', [ContactController::class, 'form'])->name('contact');
    Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

    // Toko Buku (Shop Storefront)
    Route::get('/shop', [BookController::class, 'shop'])->name('shop');

    // Perpustakaan Pribadi (My Library - Rak 3D)
    Route::get('/catalog', [BookController::class, 'myLibrary'])->name('catalog');
    Route::get('/library', [BookController::class, 'myLibrary'])->name('library');

    // API for quick modal details
    Route::get('/api/books/{id}', [BookController::class, 'show'])->name('books.show');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes (Guest Only)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| Common Authenticated Routes (Accessible by any logged-in user / admin)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Authenticated Reader Only Routes (Auth + UserOnly middleware)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'user'])->group(function () {
    // GET|POST /cart -> CartController@index / store (Keranjang Belanja)
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::patch('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');

    // POST /checkout -> OrderController@checkout (Payment at Delivery / COD)
    Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout');

    // Riwayat Pesanan & 1-Click Purchase
    Route::get('/my-orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
});

/*
|--------------------------------------------------------------------------
| Admin Protected Routes (Role: Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Unified Dashboard
    Route::get('/', [AdminBookController::class, 'index'])->name('dashboard');

    // RESOURCE /admin/categories -> Admin\CategoryController (CRUD Kategori)
    Route::resource('categories', AdminCategoryController::class);

    // RESOURCE /admin/books -> Admin\BookController (CRUD Data Buku & Stok)
    Route::resource('books', AdminBookResourceController::class);

    // GET|PUT /admin/orders -> Admin\OrderController@index / updateStatus
    Route::get('/orders', [AdminOrderResourceController::class, 'index'])->name('orders.index');
    Route::put('/orders/{id}', [AdminOrderResourceController::class, 'updateStatus'])->name('orders.updateStatus');

    // GET /admin/users -> Admin\UserController@index
    Route::get('/users', [AdminUserResourceController::class, 'index'])->name('users.index');

    // Contact messages actions in dashboard
    Route::patch('/contacts/{id}/read', [AdminBookController::class, 'markContactAsRead'])->name('contacts.read');
    Route::delete('/contacts/{id}', [AdminBookController::class, 'destroyContact'])->name('contacts.destroy');
});
