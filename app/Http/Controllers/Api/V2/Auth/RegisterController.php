<?php

namespace App\Http\Controllers\Api\V2\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V2\ProfileRequest;
use App\Http\Resources\Api\V2\MemberProfileResource;
use App\Models\User;
use App\Traits\ImageHandling;

class RegisterController extends Controller
{
    use ImageHandling;

    public function register(ProfileRequest $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->phone_code = $request->phone_code;
        $user->pin = bcrypt($request->pin);
        $user->password = bcrypt($request->pin);

        if ($request->image) {
            $image = $this->storeImage(
                $request->image,
                User::IMAGE_FOLDER
            );
            $user->image = $image;
        }
        
        $user->save();
        $user = $user->requestAccessToken();

        return response(['data' => new MemberProfileResource($user)]);
    }
}
