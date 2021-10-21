<?php

namespace App\Http\Controllers\Api\V2\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V2\LoginRequest;
use App\Http\Resources\Api\V2\MemberProfileResource;
use App\Models\User;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->credential)
                    ->first() ?? New User;

        $token = $user->attemptLogin($request)->token();

        return response(['data' => ['access_token' => $token]]);
    }

    public function loginUser(LoginRequest $request)
    {
        $user = User::where('phone', $request->phone)
                    ->where('phone_code', $request->phone_code)
                    ->first() ?? New User;

        $user = $user->attemptLogin($request);

        return response(['data' => new MemberProfileResource($user)]);
    }
}
