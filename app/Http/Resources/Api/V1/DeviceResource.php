<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class DeviceResource extends JsonResource
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
            'id' => $this->device_id,
            'name' => $this->name,
            'license' => $this->license,
            'type' => $this->type,
            'lat' => $this->location->lat ?? 0,
            'lng' => $this->location->lng ?? 0,
        ];

        if (auth()->user()->email == 'mobigps') {
            $data['user'] = $this->user->name ?? '';
        }

        return $data;
    }
}
