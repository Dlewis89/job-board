<?php

namespace App\Http\Requests\Job;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "title" => ["string", "required"],
            "description" => ["string", "required"],
            "deadline" => ["after_or_equal:" . now()->toDateString(), "date", "required"],
            "no_of_hires" => ["integer", "required"],
            "responsibilities" => ["string", "required"],
            "benefits" => ["string", "required"],
            "skills" => ["min:1", "array", "required"],
            "skills.*.id" => ["exists:skills,id"],
            "skills.*.years_of_experience" => ["integer", "required"],
            "campaign_amount" => ["integer", "nullable"]
        ];
    }

    public function messages()
    {
        return [
            "skills.*.id.exists" => "Skill doesn't exists"
        ];
    }
}
