<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'product';
    protected $fillable = ['id'];

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

    public function msrpCurrency()
    {
        return $this->belongsTo('App\Models\Currency', 'currency_msrp');
    }

    public function currentCurrency()
    {
        return $this->belongsTo('App\Models\Currency', 'currency_current');
    }

    public function releasedWithOS()
    {
        return $this->belongsTo('App\Models\OS', 'released_with_os_id');
    }

    public function targetCountries()
    {
        return $this->belongsToMany('App\Models\Country', 'product_to_country', 'product_id', 'country_id');
    }

    public function updatableToOS()
    {
        return $this->belongsToMany('App\Models\OS', 'product_to_os', 'product_id', 'os_id');
    }

    public function similarProducts()
    {
        return $this->belongsToMany('App\Models\Product', 'similar_products', 'product_id', 'similar_id');
    }

    public function websites()
    {
        return $this->belongsToMany('App\Models\Website', 'product_to_website', 'product_id', 'website_id');
    }

    public function links()
    {
        return $this->hasMany('App\Models\ProductLink', 'product_id');
    }

    public function contents()
    {
        return $this->hasMany('App\Models\ProductContent', 'product_id');
    }

    public function images()
    {
        return $this->hasMany('App\Models\ProductImage', 'product_id')->orderBy('order', 'ASC');
    }

    public function getImageAttribute()
    {
        if ($this->images->count() > 0) {
            return $this->images[0];
        }
        return null;
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
        return (new \DateTime($date))->format("Y-m-d");
    }
}
