<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;

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
