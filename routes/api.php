<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;

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

// User registration route
Route::post('/register', [UsersController::class, 'register']);

// User authentication routes
Route::post('/login', [UsersController::class, 'login']);
Route::post('/logout', [UsersController::class, 'logout'])->middleware('auth:sanctum');

// Authenticated user routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Other authenticated user routes go here
});

// Password reset routes
Route::post('/password/email', [UsersController::class, 'sendResetLinkEmail']);
Route::post('/password/reset', [UsersController::class, 'reset']);
