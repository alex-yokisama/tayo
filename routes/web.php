<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers as Controllers;

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
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::prefix('admin')->middleware(['can:use admin panel'])->group(function () {
    Route::middleware(['can:view roles'])->group(function() {
        Route::get('roles', [Controllers\RoleController::class, 'list']);
        Route::middleware(['can:update roles'])->group(function() {
            Route::get('role', [Controllers\RoleController::class, 'form']);
            Route::post('role', [Controllers\RoleController::class, 'save']);
        });
        Route::middleware(['can:delete roles'])->post('delete_roles', [Controllers\RoleController::class, 'delete']);
    });

    Route::middleware(['can:view users'])->group(function() {
        Route::get('users', [Controllers\UserController::class, 'list']);
        Route::middleware(['can:update users'])->group(function() {
            Route::get('user', [Controllers\UserController::class, 'form']);
            Route::post('user', [Controllers\UserController::class, 'save']);
        });
    });
});
