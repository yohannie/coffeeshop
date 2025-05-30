<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\GuestCheckoutController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\RegisterCheckoutController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public Routes
Route::get('/', [MenuController::class, 'index'])->name('home');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Cart Routes (Public)
Route::post('/cart/add/{product}', [CartController::class, 'addToCart']);
Route::post('/cart/remove/{productId}', [CartController::class, 'removeFromCart']);
Route::post('/cart/update/{productId}', [CartController::class, 'updateCart']);

// Guest Checkout Routes
Route::get('/checkout/guest', [GuestCheckoutController::class, 'show'])->name('checkout.guest');
Route::post('/checkout/guest/process', [GuestCheckoutController::class, 'processCheckout'])->name('checkout.guest.process');
Route::get('/checkout/success/{order}', [GuestCheckoutController::class, 'showSuccess'])->name('checkout.success');

// Registration Checkout Routes
Route::get('/checkout/register', [RegisterCheckoutController::class, 'showCheckoutForm'])->name('checkout.register');
Route::post('/checkout/register/process', [RegisterCheckoutController::class, 'processCheckout'])->name('checkout.register.process');
Route::get('/checkout/success/{order}', [RegisterCheckoutController::class, 'showSuccess'])->name('checkout.success');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // User Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Authenticated Cart Routes
    Route::post('/cart/checkout', [CartController::class, 'checkout']);
    
    // Admin Routes
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Product Routes
        Route::resource('products', ProductController::class);
        Route::patch('/products/{product}/toggle', [ProductController::class, 'toggle'])->name('products.toggle');
        
        // User Management Routes
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        
        // Order Routes
        Route::get('/orders/{order}', [OrderController::class, 'show']);
        Route::post('/orders/{order}/status', [OrderController::class, 'updateStatus']);
    });

    // New admin routes
    Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('users', UserController::class);
        Route::resource('products', ProductController::class);
        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::post('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::get('reports', [ReportController::class, 'index'])->name('reports');
    });
});
