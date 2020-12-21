<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeGroup extends Model
{
    use HasFactory;

    protected $table = 'attribute_group';
    protected $fillable = ['id', 'name', 'sort_order'];


    public function attributes()
    {
        return $this->hasMany('App\Models\Attribute', 'attribute_group_id')->orderBy('sort_order', 'ASC');
    }
}
