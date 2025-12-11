<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    if (session()->has('ADMIN_LOGIN')) {
        return redirect('/dashboard');
    }
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])
        ->name('user.dashboard')
        ->middleware('auth');

    Route::get('/admin/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users');

    // Creative AI - Styles
    Route::prefix('admin/creative-ai')->name('admin.creative-ai.')->group(function () {
        Route::resource('industries', App\Http\Controllers\Admin\IndustryController::class);
        Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);
        Route::resource('product-types', App\Http\Controllers\Admin\ProductTypeController::class);
        Route::resource('styles', App\Http\Controllers\Admin\StyleController::class);
        Route::resource('model-designs', App\Http\Controllers\Admin\ModelDesignController::class);

        // AJAX Routes
        Route::get('get-categories-by-industry/{industry_id}', [App\Http\Controllers\Admin\CategoryController::class, 'getByIndustry'])
            ->name('get-categories-by-industry');
        Route::get('get-product-types-by-category/{category_id}', [App\Http\Controllers\Admin\ProductTypeController::class, 'getByCategory'])
            ->name('get-product-types-by-category');
    });

    // ✅ Logout
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});
