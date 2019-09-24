<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Host extends JsonResource
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
            'provider_id' => $this->provider_id,
            'provider_name' => "",,
            'provider_picture' => "",
            'host_id' => $this->id,
            'host_name' => $this->title,
            'description' => $this->description,
            'picture' => $this->picture ?: asset('placeholder,png'),
            'status' => $this->status,
            
        ];

    }
}
