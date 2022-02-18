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

    public $sortableProperties = ['size', 'balcony_size'];

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

    public function scopeSortAndOrderBy($query, $request){

        if ($request->has('sort')){
            $sorts = explode(',', $request->sort);
            foreach ($sorts as $sort) {
                $params = explode(':', $sort);
                $sortableFields = $this->fillable;
                array_push($sortableFields, 'id');
                if(in_array($params[0], $sortableFields)){
                    $query->orderBy($params[0], $params[1]);
                }
                if(in_array($params[0], $this->sortableProperties)){
                    // $query->orderBy($params[0], $params[1]);
                    $query = "cast(properties->'$." . $params[0] ."' as float)". $params[1];
                    $query->orderByRaw($query);

                }
             }
        }

    }


}
