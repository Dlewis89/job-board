<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use App\Models\User;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::firstWhere('email', $request->email);

        if (!$user) {
            return response()->errorResponse('invalid credentials', [], 401);
        }

        if (!auth('web')->attempt(['email' => $request->email, 'password' => $request->password])){
            return response()->errorResponse('invalid credentials', [], 401);
        }

        $token = auth('web')->user()->createToken('job board');

        $data = array_merge(['token' => $token->accessToken], auth()->user()->toArray());

        return response()->success('login successful', $data);
    }
}
