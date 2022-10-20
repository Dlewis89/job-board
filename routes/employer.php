<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Application\ApplicationController;
use App\Http\Controllers\Job\JobController;

Route::middleware(['auth:api', 'can:employer'])->group(function () {
    Route::get('job/employer', function () {
        return response()->json([
            'ok' => true
        ]);
    }
    );

    Route::controller(JobController::class)->prefix('jobs')->group(function () {
        Route::post('', 'store');
        Route::patch('{job}', 'update');
        Route::post('feature/{job}', 'featureJob');
        Route::delete('{job}', 'destroy');
    }
    );

    Route::controller(ApplicationController::class)->prefix('applications')->group(function() {
        Route::get('{id}', 'index');
    });
});
