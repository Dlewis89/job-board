<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Services\Auth\LoginService;

class RegisterService
{
    public function __construct(private LoginService $loginService)
    {
    }
    public function create(array $request)
    {
        $user = User::create($request);
        $user->syncPermissions($request['permission']);

        $token = $this->loginService->create_token($user);

        return $this->loginService->append_token_to_user($token, $user);
    }
}
