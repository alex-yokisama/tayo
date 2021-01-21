<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class App extends Model
{
    use HasFactory;

    protected $table = 'app';
    protected $fillable = ['id'];

    public static function types()
    {
        return collect([
            0 => 'App',
            1 => 'Game'
        ]);
    }

    public function brand()
    {
        return $this->belongsTo('App\Models\Brand', 'brand_id');
    }

    public function countries()
    {
        return $this->belongsToMany('App\Models\Country', 'app_to_country', 'app_id', 'country_id');
    }

    public function os()
    {
        return $this->belongsToMany('App\Models\OS', 'app_to_os', 'app_id', 'os_id');
    }

    public function links()
    {
        return $this->hasMany('App\Models\AppLink', 'app_id');
    }

    public function images()
    {
        return $this->hasMany('App\Models\AppImage', 'app_id')->orderBy('order', 'ASC');
    }

    public function getTypeAttribute()
    {
        return (object)[
            'id' => (int)$this->type_id,
            'name' => self::types()[(int)$this->type_id]
        ];
    }
}
