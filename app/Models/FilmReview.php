<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilmReview extends Model
{
    use HasFactory;

    protected $table = 'film_review';
    protected $fillable = ['id'];

    public function film()
    {
        return $this->belongsTo('App\Models\Film', 'film_id');
    }

    public function recomendations()
    {
        return $this->belongsToMany('App\Models\Film', 'film_review_recomendation', 'film_review_id', 'film_id');
    }
}
