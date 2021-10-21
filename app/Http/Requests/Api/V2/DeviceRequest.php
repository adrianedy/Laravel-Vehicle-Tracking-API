<?php

namespace App\Http\Requests\Api\V2;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeviceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'id'        => [
                                'required', 
                                Rule::exists('devices', 'device_id')->where(function ($query) {
                                    return $query->whereNull('user_id')->whereNull('deleted_at');
                                })
                            ],
            'name'      => 'required|max:191',
            'license'   => 'required|max:191',
            'type'      => 'required|numeric|in:2,4',
        ];

        if ($this->isMethod('PATCH')) {
            $rules = [
                'name'      => 'required|max:191',
                'license'   => 'required|max:191',
                'type'      => 'required|numeric|in:2,4',
            ];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'id.exists' => 'ID perangkat sudah pernah ditambahkan, tidak bisa ditambahkan ulang.',
        ];
    }
}
