<?php

namespace App\Http\Resources\Api\V2;

use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "phone" => $this->phone,
            "phone_code" => $this->phone_code,
            "email" => $this->email,
            "image_url" => $this->image_url ?? '',
            "is_active" => $this->is_active,
            "device_number" => $this->devices->count(),
        ];
    }
}
