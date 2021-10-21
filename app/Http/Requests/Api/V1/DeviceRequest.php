<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeviceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'id' => [
                'required', 
                Rule::exists('devices', 'device_id')->where(function ($query) {
                    return $query->whereNull('user_id')->whereNull('deleted_at');
                })
            ],
        ];

        if ($this->isMethod('PATCH')) {
            $rules = [
                'name' => 'required|max:191',
                'license' => 'required|max:191',
                'type' => 'required|numeric',
            ];
        }

        return $rules;
    }
}
