<?php

namespace App\Http\Requests\Api\V2;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'credential'    => 'required',
            'password'      => 'required',
        ];
        
        $isLoginUser = currentApiRouteName('login-user');

        if ($isLoginUser) {
            $rules = [
                'phone'    => 'required',
                'phone_code'    => 'required',
                'pin'    => 'required',
            ];
        }

        return $rules;
    }
}
