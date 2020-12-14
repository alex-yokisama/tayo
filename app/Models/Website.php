<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Website extends Model
{
    use HasFactory;

    protected $table = 'website';
    protected $fillable = ['id', 'name', 'logo', 'url', 'description'];

    public function products()
    {
        return $this->belongsToMany('App\Models\Product', 'product_to_website', 'website_id', 'product_id');
    }

    public function getLogoUrlAttribute()
    {
        if ($this->logo === null) {
            return null;
        }

        if (!Storage::disk('images')->exists($this->logo)) {
            return null;
        }

        return Storage::disk('images')->url($this->logo);
    }
}
