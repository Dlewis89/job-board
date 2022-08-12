<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use Exception;

class RegisterController extends Controller
{
    public function create(RegisterUserRequest $request)
    {
        try {
            $user = User::create($request->validated());
            $user->syncPermissions($request->permission);
            return response()->success('user created', $user, 201);
        } catch(Exception $e) {
            report($e);
            return response()->errorResponse('something went wrong');
        }
    }
}
