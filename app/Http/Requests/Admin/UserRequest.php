<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $required = $this->isMethod('patch') ? 'nullable' : 'required';

        $rules =  [
            'name'          => 'required|max:191',
            'email'         => 'required|max:191|email|unique:users',
            'phone_code'    => 'required|numeric|digits_between:0,50',
            'phone_number'  => ['required', 'numeric', 'digits_between:0,50', 
                                Rule::unique('users', 'phone')->where(function ($query) {
                                    return $query->where('phone_code', $this->phone_code);
                                })],
            // 'password'      => "{$required}|confirmed",
            'pin'           => "{$required}|confirmed|digits:5",
        ];

        if ($this->isMethod('patch')) {
            if ($this->email == $this->user->email) { 
                $rules['email'] = 'required|max:191|email';
            }

            if ($this->phone_number == $this->user->phone && $this->phone_code == $this->user->phone_code) {
                $rules['phone_number'] = ['required', 'numeric', 'digits_between:0,50'];
            }
        }

        return $rules;
    }
}
