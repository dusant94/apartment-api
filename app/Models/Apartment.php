<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $slug = Str::slug($value);
        $i = 1;
        while(static::where('slug', $slug)->exist()){
            $slug = $slug . "_" . $i;
        }
        $this->attributes['slug'] = $slug ;
    }
}
