<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class ProductPriceChange extends Model
{
    use HasFactory;

    protected $table = 'product_price_change';
    protected $fillable = ['product_id', 'price_type', 'currency_old_id', 'currency_new_id', 'price_old', 'price_new', 'reason'];

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }

    public function oldCurrency()
    {
        return $this->belongsTo('App\Models\Currency', 'currency_old_id');
    }

    public function newCurrency()
    {
        return $this->belongsTo('App\Models\Currency', 'currency_new_id');
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y-m-d');
    }
}
