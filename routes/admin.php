<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\Admin\BoqController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

// ==================== AUTH ====================
Route::get('/admin/login',   [AuthController::class, 'showLogin'])->name('login');
Route::post('/admin/login',  [AuthController::class, 'login'])->name('login.post');
Route::post('/admin/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ==================== ADMIN AREA (wajib login) ====================
Route::middleware('admin.auth')->group(function () {

    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('admin/produk', ProdukController::class)
    ->names('admin.produk');
    Route::resource('admin/produk', ProdukController::class)
    ->names('admin.produk');
    Route::resource('admin/boq', BoqController::class)
    ->only(['index', 'show', 'destroy'])
    ->names('admin.boq');
});