<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('job/employee', function() {
    return response()->json([
        'ok' => true
    ]);
});
