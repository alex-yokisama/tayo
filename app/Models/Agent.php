<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    protected $table = 'agent';

    public function links()
    {
        return $this->hasMany('App\Models\ProductLink', 'agent_id');
    }

    public function products()
    {
        return $this->belongsToMany('App\Models\Product', 'product_link', 'agent_id', 'product_id');
    }
}
