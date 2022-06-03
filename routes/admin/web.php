<?php

use App\Http\Controllers\Admin\CategoryResourceController;
use App\Http\Controllers\Admin\PermissionsResourceController;
use App\Http\Controllers\Admin\RolesResourceController;
use App\Http\Controllers\Admin\UserResourceController;
use App\Http\Controllers\Admin\UserTokenGeneratorController;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
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


Route::domain(config('custom.admin_domain'))->get('/', function () {
    return Inertia::render('Admin/Welcome', [
        'canLogin' => Route::has('admin.login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});
Route::domain(config('custom.admin_domain'))->middleware(['auth', 'verified', 'check_permission'])->group(function() {
    Route::get('/dashboard', function () {
        return Inertia::render('Admin/Dashboard');
    })->name('admin.dashboard');


    Route::middleware(['role:Super Admin'])->name('admin.')->group(function () {
        Route::resource('categories', CategoryResourceController::class);
        Route::resource('users', UserResourceController::class);
        Route::post('users/{user}/generate-token', UserTokenGeneratorController::class)->name('users.generate_token');
        Route::resource('permissions', PermissionsResourceController::class);
        Route::resource('roles', RolesResourceController::class);
    });
});

Route::domain(config('custom.admin_domain'))->get('login', [AuthenticatedSessionController::class, 'create'])->name('admin.login');
