<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('job/employer', function() {
    return response()->json([
        'ok' => true
    ]);
});
