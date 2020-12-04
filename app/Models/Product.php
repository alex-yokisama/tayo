<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Product extends Model
{
    use HasFactory;

    protected $table = 'product';

    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }

    public function brand()
    {
        return $this->belongsTo('App\Models\Brand', 'brand_id');
    }

    public function country()
    {
        return $this->belongsTo('App\Models\Country', 'country_id');
    }

    public function targetCountries()
    {
        return $this->belongsToMany('App\Models\Country', 'product_to_country', 'product_id', 'country_id');
    }

    public function links()
    {
        return $this->hasMany('App\Models\ProductLink', 'product_id');
    }

    public function agents()
    {
        return $this->belongsToMany('App\Models\Agent', 'product_link', 'product_id', 'agent_id');
    }

    public function priceChanges()
    {
        return $this->hasMany('App\Models\ProductPriceChange', 'product_id')->orderBy('created_at', 'ASC');
    }

    public function attributes()
    {
        return $this->belongsToMany('App\Models\Attribute', 'attribute_to_product', 'product_id', 'attribute_id')->groupBy('attribute_id', 'product_id');
    }

    public function attributeValue(int $attribute_id)
    {
        $attribute = $this->attributes()->where('attribute_id', $attribute_id)->first();
        if ($attribute) {
            return $attribute->valueForProduct($this->id);
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
        if ($column == 'country') {
            return self::select('product.*')
                        ->join('country', 'country.id', '=', 'product.country_id')
                        ->orderBy('country.name', $order);
        } elseif ($column == 'category') {
            return self::select('product.*')
                        ->join('category', 'category.id', '=', 'product.category_id')
                        ->orderBy('category.name', $order);
        } elseif ($column == 'brand') {
            return self::select('product.*')
                        ->join('brand', 'brand.id', '=', 'product.brand_id')
                        ->orderBy('brand.name', $order);
        } else {
            return self::select('product.*');
        }
    }

    static protected function ownSortableColumns()
    {
        return collect(['id', 'name', 'sku', 'model', 'model_family', 'created_at', 'date_publish', 'price_msrp', 'price_current', 'is_promote']);
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y-m-d');
    }
}
