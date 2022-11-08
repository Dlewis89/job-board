<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Exceptions\CustomException;

class LoginService
{

    public function authenticate(array $request)
    {
        $email = $request['email'];
        $user = User::with('permissions')->firstWhere('email', $email);

        if (!$user) {
            throw new CustomException('Invalid Credentials', 401);
        }

        if (!auth('web')->attempt(['email' => $email, 'password' => $request['password']])){
            throw new CustomException('Invalid Credentials', 401);
        }

        $logged_in_user = auth('web')->user();

        $token = $this->create_token($logged_in_user);

        return $this->append_token_to_user($token, $logged_in_user);
    }

    public function create_token(User $user)
    {
        dd( $user->createToken('job board'));
        return $user->createToken('job board');
    }

    public function append_token_to_user($token, User $user)
    {
        if(!is_object($token) || !property_exists($token, 'accessToken')) {
            throw new CustomException('Invalid data', 400);
        }
        return array_merge(['token' => $token->accessToken], $user->toArray());
    }

}
