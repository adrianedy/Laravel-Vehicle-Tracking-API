<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

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
            'name' => 'max:191',
            'license' => 'max:191',
            'type' => 'nullable|numeric|in:2,4',
        ];

        if ($this->isMethod('patch')) {
            $rules = [
                'name' => 'required|max:191',
                'license' => 'required|max:191',
                'type' => 'required|numeric|in:2,4',
            ];
        }

        return $rules;
    }
}
