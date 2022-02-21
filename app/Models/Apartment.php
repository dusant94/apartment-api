<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Apartment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'price',
        'currency',
        'description',
        'properties',
        'category_id',
        'rating',
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

    protected $defined_params = [
        'name',
        'price',
        'rating',
        'currency',
        'description',
        'category_id',
        'size',
        'balcony_size',
        'location',
        'sort',
    ];

    protected $category_ids = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function rates()
    {
        return $this->hasMany(Rate::class);
    }

    public function children_ids($category)
    {
        $childrens = $category->childrens;

        foreach ($childrens as $child) {
            array_push($this->category_ids, $child->id);
            $this->children_ids($child);
        }
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $first_slug = Str::slug($value);
        $slug = $first_slug;
        $i = 1;
        while (static::where('slug', $slug)->exists()) {
            $slug = $first_slug . "_" . $i;
            $i++;
        }
        $this->attributes['slug'] = $slug;
    }

    public function scopeSortAndOrderBy($query, $request)
    {

        if ($request->has('sort')) {
            $sorts = explode(',', $request->sort);
            $sortableFields = $this->fillable;
            array_push($sortableFields, ['id', 'created_at']);
            foreach ($sorts as $sort) {
                $params = explode(':', $sort);
                if (in_array($params[0], $sortableFields)) {
                    $query->orderBy($params[0], $params[1]);
                }
                if (in_array($params[0], ['size', 'balcony_size'])) {
                    $query->orderByRaw("cast(properties->'$." . $params[0] . "' as float)" . $params[1]);
                }
            }
        }
    }

    public function scopeFilterBy($query, $request)
    {
        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->get('name') . '%');
        }
        if ($request->has('price')) {
            $params = explode(':', $request->get('price'));
            $query->where('price', $params[1], $params[0]);
        }
        if ($request->has('currency')) {
            $query->where('currency', $request->get('currency'));
        }
        if ($request->has('description')) {
            $query->where('description', 'like', '%' . $request->get('description') . '%');
        }
        if ($request->has('rating')) {
            $params = explode(':', $request->get('rating'));
            $query->where('rating', $params[1], $params[0]);
        }
        if ($request->has('size')) {
            $params = explode(':', $request->get('size'));
            $query->where('properties->size', $params[1], $params[0]);
        }
        if ($request->has('balcony_size')) {
            $params = explode(':', $request->get('balcony_size'));
            $query->where('properties->balcony_size', $params[1], $params[0]);
        }
        if ($request->has('location')) {
            $query->where('properties->location', $request->get('location'));
        }
        if ($request->has('category_id')) {
            $category = Category::where('id', $request->get('category_id'))->first();
            array_push($this->category_ids, $category->id);
            $this->children_ids($category);
            $query->whereIn('category_id', $this->category_ids);
        }
        foreach ($request->all() as $key => $param) {
            if (!in_array($key, $this->defined_params)) {
                $query->where('properties->' . $key, $param);
            }
        }
    }

    public function updateRating(Apartment $apartment)
    {
        $apartment_rates = $apartment->rates()->pluck('rating')->toArray();
        $rating = array_sum($apartment_rates) / count($apartment_rates);
        $apartment->update(['rating' => $rating]);
    }
}
