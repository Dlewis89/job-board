<?php

namespace App\Http\Requests\Application;

use App\Exceptions\CustomException;
use App\Models\Application;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class ApplicationRequest extends FormRequest
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
            'job_id' => 'integer|required|exists:created_jobs,id',
            'cover_letter' => 'string|sometimes',
            'resume' => 'mimes:doc,pdf,docx|required|file|max:2048'
        ];
    }

    public function validated($key = null, $default = null): array
    {
        $validated = $this->validator->validated();
        $validated['user_id'] = auth()->id();
        $validated['resume'] = $this->file('resume')->storeOnCloudinary('resumes')->getSecurePath();
        return $validated;
    }

    public function withValidator(Validator $validator)
    {
        // if (!$validator->fails()) {
        //     $validator->after(function ($validator) {
        //         $application = Application::where([
        //             'user_id' => auth()->id(),
        //             'job_id' => $this->job_id
        //         ])->exists();
        //         if ($application) {
        //             throw new CustomException('Sorry, you have applied to this job already from form request.', 400);
        //         }
        //     });
        // }
    }
}
