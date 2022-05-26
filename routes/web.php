<?php

use App\Http\Controllers\Admin\CategoryResourceController;
use App\Http\Controllers\Admin\PermissionsResourceController;
use App\Http\Controllers\Admin\RolesResourceController;
use App\Http\Controllers\Admin\UserResourceController;
use App\Http\Controllers\Admin\UserTokenGeneratorController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth', 'verified', 'check_permission'])->group(function() {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');


    Route::middleware(['role:Super Admin'])->name('admin.')->prefix('admin')->group(function () {
        Route::middleware(['permission:can manage categories'])->resource('categories', CategoryResourceController::class);
        Route::resource('users', UserResourceController::class);
        Route::post('users/{user}/generate-token', UserTokenGeneratorController::class)->name('users.generate_token');
        Route::resource('permissions', PermissionsResourceController::class);
        Route::resource('roles', RolesResourceController::class);
    });
});


require __DIR__.'/auth.php';
