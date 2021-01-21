<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppLink extends Model
{
    use HasFactory;

    protected $table = 'app_links';
    protected $fillable = ['app_id', 'os_id', 'url', 'price', 'app_store_name'];
    public $timestamps = false;

    public function os()
    {
        return $this->belongsTo('App\Models\OS', 'os_id');
    }
}
