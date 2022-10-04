<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ProfileController;


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

Route::get('/invites/{token}', [InviteController::class, 'findByToken']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', [UserController::class, 'find']);
    Route::get('/roles', [RoleController::class, 'all']);

    Route::prefix('schools')->group(function () {
        Route::get('/', [SchoolController::class, 'all']);
        Route::get('/{school}', [SchoolController::class, 'find']);
        Route::post('/create', [SchoolController::class, 'create']);
        Route::delete('/{school}/delete', [SchoolController::class, 'delete']);
        Route::put('/{school}/edit', [SchoolController::class, 'update']);
    });

    Route::post('/invites', [InviteController::class, 'create']);

    Route::prefix('profiles')->group(function () {
        Route::get('/', [ProfileController::class, 'get']);
        Route::post('/', [ProfileController::class, 'create']);
        Route::put('/', [ProfileController::class, 'update']);
    });

});

