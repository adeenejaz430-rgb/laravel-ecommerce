<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\GoogleAuthController;

use App\Http\Controllers\Admin\AdminDashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;

use App\Http\Controllers\Shop\HomeController;
use App\Http\Controllers\Shop\ProductController as ShopProductController;
use App\Http\Controllers\Shop\OrderController as ShopOrderController;
use App\Http\Controllers\Shop\ProfileController;
use App\Http\Controllers\Shop\WishlistController;
use App\Http\Controllers\Shop\SearchController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\Shop\CartController;

// 🔹 SHOP HOME
Route::get('/', [HomeController::class, 'index'])->name('shop.home');


// 🔹 GUEST (auth) ROUTES
Route::middleware('guest')->group(function () {
    // login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login/send-code', [LoginController::class, 'sendCode'])->name('login.sendCode');
    Route::post('/login/verify-code', [LoginController::class, 'verifyCode'])->name('login.verifyCode');

    // register
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // GOOGLE AUTH
    Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])
        ->name('auth.google.redirect');

    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])
        ->name('auth.google.callback');
});


// 🔹 DEBUG MIDDLEWARE (only while debugging)
Route::get('/debug-middleware', function () {
    dd(app('router')->getMiddleware());
});


// 🔹 ADMIN ROUTES (protected by auth + is_admin)
Route::middleware(['auth', 'is_admin'])
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('products',   AdminProductController::class);
        Route::resource('categories', AdminCategoryController::class);
        Route::resource('orders',     AdminOrderController::class);
        Route::resource('customers',  AdminCustomerController::class);
    });


// 🔹 LOGOUT
Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


// 🔹 SHOP FRONT ROUTES
Route::get('/products', [ShopProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ShopProductController::class, 'show'])->name('products.show');

Route::get('/search', [SearchController::class, 'index'])->name('search');

Route::middleware('auth')->group(function () {
    Route::get('/orders', [ShopOrderController::class, 'index'])->name('orders.index');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::delete('/wishlist/{id}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
      Route::post('/cart/{product}', [CartController::class, 'add'])->name('cart.add');
});

// 🔹 UPLOAD
Route::post('/upload', [UploadController::class, 'store'])->name('upload.store');
