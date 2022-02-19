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

    protected $category_ids = [];

    public function children_ids($category){
        $childrens = $category->children;

        foreach($childrens as $child){
            array_push($this->category_ids, $child->id);
            $this->children_ids($child);
        }
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $slug = Str::slug($value);
        $i = 1;
        while (static::where('slug', $slug)->exists()) {
            $slug = $slug . "_" . $i;
        }
        $this->attributes['slug'] = $slug;
    }

    public function scopeSortAndOrderBy($query, $request)
    {

        if ($request->has('sort')) {
            $sorts = explode(',', $request->sort);
            $sortableFields = $this->fillable;
            array_push($sortableFields, 'id');
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
            $query->where('price', $request->get('price'));
        }
        if ($request->has('currency')) {
            $query->where('currency', $request->get('currency'));
        }
        if ($request->has('description')) {
            $query->where('description', 'like', '%' . $request->get('description') . '%');
        }
        if ($request->has('rating')) {
            $query->where('rating', '>=', $request->get('rating'));
        }
        if ($request->has('size')) {
            $query->where('properties->size', '<=', $request->get('size'));
        }
        if ($request->has('balcony_size')) {
            $query->where('properties->balcony_size', '<=', $request->get('balcony_size'));
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
    }
}
