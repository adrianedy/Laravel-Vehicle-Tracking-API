<?php

namespace App\Http\Requests\Api\V2;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $isRegister = currentApiRouteName('register');
        $isUpdate = currentApiRouteName('members.update');
        $pinRequired = $isRegister ? 'required' : 'nullable';

        $rules =  [
            'name'          => 'required|max:191',
            'email'         => 'required|max:191|email',
            'phone'         => 'required|numeric|digits_between:0,50',
            'phone_code'    => 'required|numeric|digits_between:0,50',
            'image'         => [function ($attribute, $value, $fail) {
                                    if (! isBase64Image($value)) {
                                        return $fail(trans('validation.image'));
                                    }
                                },
                                ],
            'pin'           => "{$pinRequired}|digits:5",
        ];

        if ($isUpdate) {
            if ($this->email != $this->user->email) { 
                $rules['email'] = 'required|max:191|email|unique:users';
            }

            if ($this->phone != $this->user->phone || $this->phone_code != $this->user->phone_code) {
                $rules['phone'] = ['required', 'numeric', 'digits_between:0,50', Rule::unique('users')->where(function ($query) {
                    return $query->where('phone_code', $this->phone_code);
                })];
            }
        }

        if ($isRegister) {
            $rules['email'] = 'required|max:191|email|unique:users';
            $rules['phone'] = ['required', 'numeric', 'digits_between:0,50', Rule::unique('users')->where(function ($query) {
                return $query->where('phone_code', $this->phone_code);
            })];
        }

        return $rules;
    }
}
