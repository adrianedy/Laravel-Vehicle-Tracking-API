<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V2\ProfileRequest;
use App\Http\Resources\Api\V2\MemberProfileResource;
use App\Http\Resources\Api\V2\MemberResource;
use App\Models\User;
use App\Traits\ImageHandling;
use Illuminate\Support\Facades\Validator;

class MembershipController extends Controller
{
    use ImageHandling;

    public function deactivate(User $user)
    {
        Validator::make(request()->only(['is_active']), [
            'is_active' => 'required|in:0,1'
        ])->validate();

        $user->is_active = request()->is_active;
        $user->save();

        return response(['data' => new \stdClass()]);
    }

    public function members()
    {
        $isActive = request()->isActive ?? [];

        $members = User::with('devices')
            ->whereIn('is_active', $isActive)
            ->when($search = request()->q, function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest()->paginate(10);
        
        return MemberResource::collection($members);
    }

    public function profile(User $user)
    {
        return response(['data' => new MemberProfileResource($user)]);
    }

    public function update(ProfileRequest $request, User $user)
    {
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->phone_code = $request->phone_code;

        if ($request->image) {
            $user->deleteImage();

            $image = $this->storeImage(
                $request->image,
                User::IMAGE_FOLDER
            );

            $user->image = $image;
        }

        $user->save();

        return response(['data' => new \stdClass()]);
    }
}
