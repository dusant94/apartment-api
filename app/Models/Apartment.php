<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apartment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'currency',
        'description',
        'properties',
        'category_id',
    ];

    protected $casts = [
        'properties' => 'array'
    ];

    protected $attributes = [
        'properties' => '{
            "size": "",
            "balcony_size": "",
            "location": ""
        }'
    ];
}
