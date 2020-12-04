<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    protected $table = 'attribute';
    protected $fillable = ['id', 'name', 'type'];

    public function measure()
    {
        return $this->belongsTo('App\Models\Measure', 'measure_id');
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

    public function valueForProduct($product_id)
    {
        if (!$product_id) {
            return null;
        }
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
                    return $values[0]->option;
                    break;
                case 5:
                    return $values->map(function($v) {
                        return $v->option;
                    })->filter(function($v) {
                        return $v !== null;
                    });
                    break;
            }
        }
        return null;
    }

    static public function orderByColumn($column, $order = 'ASC')
    {
        $order = strtoupper($order) == 'ASC' ? 'ASC' : 'DESC';
        if (self::ownSortableColumns()->contains($column)) {
            return self::orderBy($column, $order);
        } else {
            return self::orderByRelation($column, $order);
        }
    }

    static protected function orderByRelation($column, $order)
    {
        if ($column == 'measure') {
            return self::select('attribute.*')
                        ->join('measure', 'measure.id', '=', 'attribute.measure_id')
                        ->orderBy('measure.name', $order);
        } else {
            return self::select('attribute.*');
        }
    }

    static protected function ownSortableColumns()
    {
        return collect(['id', 'name', 'type']);
    }
}
