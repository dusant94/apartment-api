<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ApartmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $properties = $this->formatProperties($request, $this->properties);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'currency' => $this->currency,
            'description' => $this->when($request->has('description'), $this->description),
            'category_id' => $this->category_id,
            'properties' => $properties,
            'rating' => $this->rating,
        ];
    }

    public function formatProperties($request, $properties){
        if(!$request->has('size')){
            unset($properties['size']);
        }
        if(!$request->has('balcony_size')){
            unset($properties['balcony_size']);
        }
        return $properties;
    }
}
