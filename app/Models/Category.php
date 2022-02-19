<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'parent_id',
    ];

    protected static function boot() {
        parent::boot();

        static::deleted(function($category) {
            $category->apartments()->delete();
            $category->childrens()->delete();
        });
    }

    public function apartments()
    {
        return $this->hasMany(Apartment::class, 'category_id', 'id');
    }

    public function childrens()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
}
