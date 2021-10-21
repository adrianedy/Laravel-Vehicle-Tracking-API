<?php

namespace App\Http\Resources\Api\V2;

use Illuminate\Http\Resources\Json\JsonResource;

class DeviceHistoryResource extends JsonResource
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
            'lat' => $this->lat ?? 0,
            'lng' => $this->lng ?? 0,
            'heading' => $this->heading ?? 0,
            'created_at' => $this->timestamp ?? 0,
        ];
    }
}
