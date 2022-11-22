<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\LoginService;
use App\Exceptions\CustomException;
use App\Http\Resources\User\UserResource;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function __construct(private LoginService $loginService)
    {
    }

    public function login(LoginRequest $request)
    {
        try{
            $user = $this->loginService->authenticate($request->only(['email', 'password']));
            return response()->success('login successful', $user);
        }catch (CustomException $e) {
            report($e);
            return response()->errorResponse($e->getMessage(), [], $e->getCode());
        } catch(\Exception $e) {
            report($e);
            return response()->errorResponse($e->getMessage(), [], 500);
        }
    }
}
