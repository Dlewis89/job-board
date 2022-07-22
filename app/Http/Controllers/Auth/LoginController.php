<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class LoginController extends Controller
{
    public function login(Request $request)
    {   
        $user = User::firstWhere('email', $request->email);

        if (!$user) {
            return response()->errorResponse('no user found, please register.', [], 401);
        }

        if (!auth()->attempt(['email' => $request->email, 'password' => $request->password])){
            return response()->errorResponse('password is incorrect.', [], 401);
        }
    }
}
