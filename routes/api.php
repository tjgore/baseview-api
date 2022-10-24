<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\OverviewController;
use App\Models\Role;

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
        Route::post('/create', [SchoolController::class, 'create'])->middleware('role:' . Role::ADMIN);

        Route::group(['prefix' => '{school}', 'middleware' => 'attends.school'], function () {
            Route::get('/', [SchoolController::class, 'find'])->middleware('role:' . implode(',', Role::SCHOOL_ROLES));
            Route::delete('/delete', [SchoolController::class, 'delete'])->middleware('role:' . Role::ADMIN);
            Route::put('/edit', [SchoolController::class, 'update'])->middleware('role:' . Role::ADMIN);

            Route::get('/accounts', [AccountController::class, 'getAll'])->middleware('role:' . implode(',', Role::SCHOOL_ROLES));
            Route::post('/accounts', [AccountController::class, 'create'])->middleware('role:' . sprintf('%s,%s', Role::ADMIN, Role::TEACHER));
            Route::get('/accounts/{user}', [AccountController::class, 'find'])->middleware('can:view,user');
            Route::put('/profiles/{profile}', [ProfileController::class, 'updateUserProfile'])->middleware('can:update,profile,school');;

            Route::put('/accounts/{user}/role', [RoleController::class, 'update'])->middleware('role:' . sprintf('%s,%s', Role::ADMIN, Role::TEACHER));

            Route::get('/overview/count', [OverviewController::class, 'getCount'])->middleware('role:' . sprintf('%s,%s', Role::ADMIN, Role::TEACHER));
            Route::post('/invites', [InviteController::class, 'create'])->middleware('role:' . sprintf('%s,%s', Role::ADMIN, Role::TEACHER));
        });
    });

    Route::prefix('profiles')->group(function () {
        Route::get('/', [ProfileController::class, 'get']);
        // @TODO this should/will be removed soon
        Route::post('/', [ProfileController::class, 'create']);
        Route::put('/', [ProfileController::class, 'update']);
    });

});

