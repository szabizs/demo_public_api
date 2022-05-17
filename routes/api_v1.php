<?php

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->prefix('user')->group(function() {
    Route::get('/{id}', [\App\Http\Controllers\UserController::class, 'show'])->name('api.v1.user.show');
    Route::put('/', [\App\Http\Controllers\UserController::class, 'store'])->name('api.v1.user.store');
    Route::delete('/{id}', [\App\Http\Controllers\UserController::class, 'destroy'])->name('api.v1.user.destroy');
    Route::patch('/{id}', [\App\Http\Controllers\UserController::class, 'update'])->name('api.v1.user.update');
});
