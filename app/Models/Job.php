<?php

namespace App\Models;

use App\Models\Skill;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\AddPipelineToModelTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
    use AddPipelineToModelTrait, HasFactory, SoftDeletes;

    protected $table = 'created_jobs';

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'created_job_skill', 'created_job_id', 'skill_id')->withPivot('years_of_experience');
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
