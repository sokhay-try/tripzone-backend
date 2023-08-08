<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\PlaceController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ProvinceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

/**
 * ======================== AuthController ==========================
 */
Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/auth/login', [AuthController::class, 'loginUser']);



Route::middleware('auth:sanctum')->group( function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/user/profile', [AuthController::class, 'userProfile']);

    /**
     * ==================== User =========================
     */
    Route::resource('users', UserController::class);
    Route::post('/users/status/active', [UserController::class, 'updateUserStatusToActive']);
    Route::post('/users/status/inactive', [UserController::class, 'updateUserStatusToInactive']);


    /**
     * ==================== Category =========================
     */
    Route::resource('categories', CategoryController::class);

    /**
     * ==================== Province =========================
     */
    Route::resource('provinces', ProvinceController::class);

    /**
     * ==================== Place =========================
     */
    Route::resource('places', PlaceController::class);
    Route::group(['prefix' => 'places'], function () {
        Route::post('/status/update', [PlaceController::class, 'updateStatus']);
        Route::post('/visitor/update', [PlaceController::class, 'updateVisitor']);
    });

});
