<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UtilityController extends Controller
{
    public function checkEmail()
    {
        Validator::make(request()->only(['email']), [
            'email' => ['required', 'unique:users'],
        ])->validate();
        
        return response(['data' => new \stdClass()]);
    }

    public function checkPhone()
    {
        Validator::make(request()->only(['phone_code', 'phone']), [
            'phone_code' => 'required',
            'phone' => ['required', Rule::unique('users')->where(function ($query) {
                return $query->where('phone_code', request()->phone_code);
            })],
        ])->validate();
        
        return response(['data' => new \stdClass()]);
    }
}
