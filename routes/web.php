<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\PageSeoController;
use App\Http\Controllers\DeveloperApiController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\WpOrderController;
use App\Http\Controllers\OrderController;


// Route::get('/', [HomeController::class, 'welcome'])->name('home');
Route::get('/', [AdminController::class, 'dashboard'])->middleware(['auth', 'verified']);

// Admin dashboard
Route::get('/dashboard', [AdminController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/change-password', [ProfileController::class, 'editPassword'])->name('password.change');
    Route::put('/change-password', [ProfileController::class, 'updatePassword'])->name('password.update');
});


Route::middleware('auth')->group(function () {
    // Developer API
    Route::get('/developer-api', [DeveloperApiController::class, 'index'])->name('developer-api.index');
    Route::post('/developer-api/generate-token', [DeveloperApiController::class, 'generateToken'])->name('developer-api.generate-token');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');

    // WooComerce
    Route::get('/wp/orders-live', [WpOrderController::class, 'wpOrderLive'])->name('wp.orders-live');
    Route::post('/wp/orders-sync', [WpOrderController::class, 'syncOrders'])->name('wp.orders-sync');

    // Store Routes
    Route::get('/stores', [StoreController::class, 'index'])->name('stores.index');
    Route::post('/stores', [StoreController::class, 'store'])->name('stores.store');
    Route::post('/stores/update', [StoreController::class, 'update'])->name('stores.update');
    Route::delete('/stores/{id}', [StoreController::class, 'destroy'])->name('stores.destroy');

    /**----------------------------------------------------------------------------------------------
     * ----------------------------------------------------------------------------------------------
     * BACKEND TEMPLATE
     * ----------------------------------------------------------------------------------------------
     * ----------------------------------------------------------------------------------------------
     */
    Route::resource('roles', RoleController::class);
    // User Management
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users/update', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('setting.index');
    Route::post('/settings-update', [SettingController::class, 'update'])->name('setting.update');
    // SEO settings
    Route::get('/seo-pages',[PageSeoController::class,'index'])->name('settings.seo.index');
    Route::post('/seo-pages/{page}',[PageSeoController::class,'update'])->name('settings.seo.update');
});

require __DIR__.'/auth.php';
