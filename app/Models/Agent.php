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

    public static function types()
    {
        return collect([
            0 => 'legal entity',
            1 => 'individual'
        ]);
    }

    public function links()
    {
        return $this->hasMany('App\Models\ProductLink', 'agent_id');
    }

    public function products()
    {
        return $this->belongsToMany('App\Models\Product', 'product_link', 'agent_id', 'product_id');
    }

    public function getTypeAttribute()
    {
        return (object)[
            'id' => (int)$this->type_id,
            'name' => self::types()[(int)$this->type_id]
        ];
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
