<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Agent extends Model
{
    use HasFactory;

    protected $table = 'agent';
    protected $fillable = ['id', 'name', 'website', 'is_retailer'];

    public function links()
    {
        return $this->hasMany('App\Models\ProductLink', 'agent_id');
    }

    public function products()
    {
        return $this->belongsToMany('App\Models\Product', 'product_link', 'agent_id', 'product_id');
    }

    public function getImageUrlAttribute()
    {
        if ($this->image === null) {
            return null;
        }

        if (!Storage::disk('images')->exists($this->image)) {
            return null;
        }

        return Storage::disk('images')->url($this->image);
    }
}
