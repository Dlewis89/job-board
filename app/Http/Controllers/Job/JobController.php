<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use App\Http\Requests\Job\StoreJobRequest;
use App\Filters\JobFilter\Title;
use App\Filters\JobFilter\Skill;
use App\Models\Job;
use App\Services\Job\JobService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Contracts\PaymentGatewayInterface;
use App\Exceptions\CustomException;
use Exception;

class JobController extends Controller
{
    public function __construct(private JobService $jobService, private PaymentGatewayInterface $paymentGatewayInterface)
    {
    }

    public function index()
    {
        try {
            $jobs = Job::with('skills')->withTrashed()->filterWithPipeline([
                Title::class,
                Skill::class
            ])->get();
            return response()->success('success', $jobs);
        } catch(Exception $e) {
            report($e);
            return response()->errorResponse('something went wrong');
        }

    }

    public function store(StoreJobRequest $request)
    {
        try {
            $job = $this->jobService->create($request->all());
            return response()->success('job created successfully', $job, 201);
        } catch(Exception $e) {
            report($e);
            return response()->errorResponse('something went wrong');
        }
    }

    public function update(StoreJobRequest $request, Job $job)
    {
        try {
            $updated_job = tap($job)->update($request->except(['skills', '_token']));
            $this->jobService->sync_skills($updated_job, $request->skills);
            return response()->success('job updated successfully');
        } catch(Exception $e) {
            report($e);
            return response()->errorResponse('something went wrong');
        }
    }

    public function destroy(Job $job)
    {
        try {
            $job->delete();
            return response()->success('job deleted successfully');
        } catch(Exception $e) {
            report($e);
            return response()->errorResponse('something went wrong');
        }
    }

    public function featureJob(Job $job)
    {
        try {
            $user = Auth::user();

            $response = $this->paymentGatewayInterface
                ->setTransactionInitiator($user)
                ->setTransactionOwner($job)
                ->initializeTransaction($user->email, 0, config('app.feature_job_stripe_price_id'), 'usd', null, ['job_id' => $job->id]);

            return response()->success('checkout link generated', ['url' => $response['url']]);
        } catch(CustomException $e) {
            report($e);
            return response()->errorResponse($e->getMessage(), [], $e->getCode());
        } catch(Exception $e) {
            report($e);
            return response()->errorResponse('something went wrong');
        }

    }
}
