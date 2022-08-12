<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    public function store(Request $request)
    {
        try {
            $job = auth()->user()->jobs()->create($request->all());
            return response()->success('job created successfully', $job, 201);
        } catch(\Exception $e) {
            report($e);
            return response()->errorResponse('something went wrong');
        }
    }
}
