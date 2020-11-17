<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttributeValue extends Model
{
    use HasFactory;

    protected $table = 'attribute_to_product';

    public function option()
    {
        return $this->belongsTo('App\Models\AttributeOption', 'attribute_option_id');
    }
}
