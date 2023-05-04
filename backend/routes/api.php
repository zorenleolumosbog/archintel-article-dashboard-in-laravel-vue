<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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
Route::post('/register', [UserController::class, 'store']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware(['auth:api', 'verify.permission'])->group(function() {
    Route::post('/logout', [UserController::class, 'logout']);
    Route::apiResource('/users', UserController::class);
    Route::apiResource('/companies', CompanyController::class);
    Route::apiResource('/articles', ArticleController::class);
});
