<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeviceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'Device_name' => $this->name,
            'Device_room' => $this->room_id,
            'isActive' => $this->is_active,
            'images_url'=>$this->images
        ];
    }
}
