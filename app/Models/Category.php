<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'category';

    public function children()
    {
        return $this->hasMany('App\Models\Category', 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo('App\Models\Category', 'parent_id');
    }

    public function attributes()
    {
        return $this->belongsToMany('App\Models\Attribute', 'attribute_to_category', 'category_id', 'attribute_id');
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product', 'category_id');
    }
}
