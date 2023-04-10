<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\BookController;
use App\Http\Controllers\API\AuthController;

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

Route::middleware('guest')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::get('/user', UserController::class);

Route::group(['prefix' => 'books', 'middleware' => 'auth:sanctum'], function() {
    Route::get('/', [BookController::class,'index']);
    Route::post('add', [BookController::class,'store']);
    Route::post('update/{id}', [BookController::class,'update']);
    Route::get('edit/{id}', [BookController::class,'show']);
    Route::delete('delete/{id}', [BookController::class,'destroy']);
});
