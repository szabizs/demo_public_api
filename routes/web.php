<?php

use App\Http\Controllers\Admin\CategoryResourceController;
use App\Http\Controllers\Admin\PermissionsResourceController;
use App\Http\Controllers\Admin\RolesResourceController;
use App\Http\Controllers\Admin\UserResourceController;
use App\Http\Controllers\Admin\UserTokenGeneratorController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
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

Route::domain(config('custom.app_domain'))->get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});
Route::domain(config('custom.app_domain'))->middleware(['auth', 'verified', 'check_permission'])->group(function() {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

require __DIR__.'/auth.php';
