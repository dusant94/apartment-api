<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApartmentUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['nullable', 'string', 'max:50'],
            'price' => ['nullable','numeric'],
            'currency' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'properties.size' => ['nullable', 'numeric'],
            'properties.balcony_size' => ['nullable', 'numeric'],
            'properties.location' => ['nullable', 'string'],
            'category_id' => ['nullable', 'integer'],
        ];
    }

    public function all($keys = null)
    {
        $data = parent::all();
        $data['properties'] = json_decode($data['properties'], true);

        return $data;
    }
}
