<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPriceChange extends Model
{
    use HasFactory;

    protected $table = 'product_price_change';

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }
}
