<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SchoolController;

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

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', [UserController::class, 'find']);

    Route::prefix('schools')->group(function () {
        Route::get('/', [SchoolController::class, 'all']);
        Route::get('/{school}', [SchoolController::class, 'find']);
        Route::post('/create', [SchoolController::class, 'create']);
        Route::delete('/{school}/delete', [SchoolController::class, 'delete']);
        Route::put('/{school}/edit', [SchoolController::class, 'edit']);
    });

});

