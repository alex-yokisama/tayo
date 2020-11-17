<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $table = 'brand';

    public function country()
    {
        return $this->belongsTo('App\Models\Country', 'country_id');
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product', 'brand_id');
    }
}
