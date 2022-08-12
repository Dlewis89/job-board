<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\JobController;

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
Route::prefix('auth')->group(function() {
    Route::post('register', [RegisterController::class, 'create']);
    Route::post('login', [LoginController::class, 'login']);
});

Route::controller(JobController::class)->middleware('auth:api')->prefix('jobs')->group(function() {
    Route::post('', 'store')->middleware('can:employer');
});
