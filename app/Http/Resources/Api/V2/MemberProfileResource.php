<?php

namespace App\Http\Resources\Api\V2;

use Illuminate\Http\Resources\Json\JsonResource;

class MemberProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            "user_id" => $this->id,
            "email" => $this->email,
            "phone" => $this->phone,
            "phone_code" => $this->phone_code,
            "name" => $this->name,
            "image_url" => $this->image_url,
        ];

        if (currentApiRouteName('register') || currentApiRouteName('login-user')) {
            $data['is_admin'] = $this->is_admin;
            $data['access_token'] = $this->token();
        }

        return $data;
    }
}
