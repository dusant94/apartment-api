<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Http;

class ApartmentCollection extends ResourceCollection
{

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($request->header('CURRENCY')) {
            $response = $this->callAPI($request->header('CURRENCY'));
            if ($response) {
                $rates = $response['rates'];
                $currency = $response['base'];
                $this->collection->map(function ($resource) use ($rates, $currency) {
                    if ($currency != $resource->currency) {
                        $resource['price'] = $resource['price'] / $rates[$resource['currency']];
                        $resource['currency'] = $currency;
                    }
                    return $resource;
                });
            }
        }
        return [
            'data' => ApartmentResource::collection($this->collection)
        ];
    }

    public function callAPI($currency)
    {
        $response = Http::get('https://api.exchangerate.host/latest?base=' . $currency)->json();
        if ($response['success']) {
            return $response;
        } else {
            return false;
        }
    }
}
