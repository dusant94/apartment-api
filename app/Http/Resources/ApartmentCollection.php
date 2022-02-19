<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ApartmentCollection extends ResourceCollection
{
    protected $rate = null;

    public function __construct(Request $request)
    {
        if ($request->has()->header('CURRENCY')) {
            $this->rate = api();
        }
    }
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $rate = $this->rate;
        $currency = $request->header('CURRENCY');

        if ($rate != null) {
            $this->collection->map(function ($resource) use ($rate, $currency) {
                if ($currency != $resource->currency) {
                    $resource['price'] = $resource['price'] * $rate;
                    $resource['currency'] = $currency;
                }
                return $resource;
            });
        }
        return [
            'data' => ApartmentResource::collection($this->collection)
        ];
    }
}
