<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    protected $table = 'attribute';

    public function measure()
    {
        return $this->belongsTo('App\Models\Measure', 'attribute_id');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Models\Category', 'attribute_to_category', 'attribute_id', 'category_id');
    }

    public function options()
    {
        return $this->hasMany('App\Models\AttributeOption', 'attribute_id');
    }

    public function products()
    {
        return $this->belongsToMany('App\Models\Product', 'attribute_to_product', 'attribute_id', 'product_id');
    }

    public function values()
    {
        return $this->hasMany('App\Models\ProductAttributeValue', 'attribute_id');
    }

    public function valueForProduct(int $product_id)
    {
        $values = $this->values()->where('product_id', $product_id)->get();
        if ($values->count() > 0) {
            switch ($this->type) {
                case 0:
                    return $values[0]->value_numeric;
                    break;
                case 1:
                    return $values[0]->value_text;
                    break;
                case 2:
                    return $values[0]->value_boolean;
                    break;
                case 3:
                    return $values[0]->value_date;
                    break;
                case 4:
                    $option = $values[0]->option;
                    if ($option) {
                        return $option->name;
                    }
                    return null;
                    break;
                case 5:
                    return $values->map(function($v) {
                        $option = $v->option;
                        if ($option) {
                            return $option->name;
                        }
                        return null;
                    })->filter(function($v) {
                        return $v !== null;
                    });
                    break;
            }
        }
        return null;
    }
}
