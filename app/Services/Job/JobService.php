<?php

namespace App\Services\Job;

use DB;
use Illuminate\Support\Arr;

class JobService
{
    public function create(array $request)
    {
        $job = DB::transaction(function() use ($request) {
            $job = auth()->user()->jobs()->create($request);
            $this->sync_skills($job, $request['skills']);
        });
        return $job;
    }

    public function sync_skills($job, $job_skills)
    {
        $skills = array();
        Arr::map($job_skills, function($value, $key) use (&$skills) {
            return $skills[$value['id']] = ['years_of_experience' => $value['years_of_experience']];
        });
        $job->skills()->sync($skills);
    }
}
