<?php

namespace App\Http\Controllers\Application;

use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Application;
use App\Http\Requests\Application\ApplicationRequest;

class ApplicationController extends Controller
{
    public function store(ApplicationRequest $request)
    {
        try {
            $this->duplicateApplication($request->job_id, Auth::id());
            Application::create($request->validated());
            return response()->success('Application Submitted');
        } catch (CustomException $e) {
            report($e);
            return response()->errorResponse($e->getMessage(), [], $e->getCode());
        }catch (\Exception $e) {
            report($e);
            return response()->errorResponse('something went wrong');
        }
    }

    public function duplicateApplication($job_id, $user_id)
    {
        $application = Application::where([
            'user_id' => $user_id,
            'job_id' => $job_id
        ])->exists();
        if ($application) {
            throw new CustomException('Sorry, you have applied to this job already.', 400);
        }
    }
}
