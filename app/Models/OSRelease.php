<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OSRelease extends Model
{
    use HasFactory;

    protected $table = 'os_release';
    protected $fillable = ['id', 'name', 'version', 'added_features', 'release_date'];
}
