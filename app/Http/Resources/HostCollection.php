<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class HostCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'success' => true,
            'code' => 200,
            'data' => $this->collection,
        ];
    }

    /**
     * Transform the resource collection into an array.
     * 
     * @param  \Illuminate\Http\Request  $request - 
     * @return only collection of details
     */
    public function getData() {

        return $this->collection;
        
    }
}
