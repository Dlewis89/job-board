<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Job\JobController;
use App\Http\Controllers\Payment\PaymentController;

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

Route::controller(JobController::class)->middleware(['auth:api', 'can:employer'])->prefix('jobs')->group(function() {
    Route::get('', 'index')->withoutMiddleware(['auth:api', 'can:employer']);
    Route::post('', 'store');
    Route::patch('{job}', 'update');
    Route::post('feature/{job}', 'featureJob');
    Route::delete('{job}', 'destroy');
});

Route::post('/stripe-webhook', [PaymentController::class, 'webhook']);
