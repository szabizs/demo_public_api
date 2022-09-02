<?php

use App\Http\Controllers\Admin\CategoryResourceController;
use App\Http\Controllers\Admin\PermissionsResourceController;
use App\Http\Controllers\Admin\RolesResourceController;
use App\Http\Controllers\Admin\UserResourceController;
use App\Http\Controllers\Admin\UserTokenGeneratorController;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\AttributeValueController;
use App\Http\Controllers\ProductResourceController;
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

        Route::resource('attributes', AttributeController::class);
        Route::prefix('attribute-values')->name('attribute-values')->group(function() {
            Route::post('/get-values', [AttributeValueController::class, 'getValues'])->name('.get-values');
            Route::post('/add-values', [AttributeValueController::class, 'addValues'])->name('.add-values');
            Route::post('/update-values', [AttributeValueController::class, 'updateValues'])->name('.update-values');
            Route::delete('/delete-values/{attributeValue}', [AttributeValueController::class, 'destroyValues'])->name('.delete-values');
        });


        Route::resource('categories', CategoryResourceController::class);
        Route::resource('products', ProductResourceController::class);
        Route::resource('users', UserResourceController::class);
        Route::post('users/{user}/generate-token', UserTokenGeneratorController::class)->name('users.generate_token');
        Route::resource('permissions', PermissionsResourceController::class);
        Route::resource('roles', RolesResourceController::class);
    });
});

Route::domain(config('custom.admin_domain'))->get('login', [AuthenticatedSessionController::class, 'create'])->name('admin.login');
