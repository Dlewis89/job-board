<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterUserRequest;
use Exception;
use App\Services\Auth\RegisterService;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function __construct(private RegisterService $registerService)
    {
    }

    public function create(RegisterUserRequest $request)
    {
        try {
            $user = $this->registerService->create($request->validated());
            return response()->success('user created', $user, 201);
        } catch(Exception $e) {
            report($e);
            return response()->errorResponse('something went wrong');
        }
    }
}
