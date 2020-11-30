<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return $this->hasMany('App\Models\ProductPriceChange', 'product_id');
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
}
