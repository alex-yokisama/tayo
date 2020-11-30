<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $table = 'currency';
    protected $fillable = ['id', 'name', 'symbol'];

    public function country()
    {
        return $this->belongsTo('App\Models\Country', 'country_id');
    }

    static public function orderByColumn($column, $order = 'ASC')
    {
        $order = strtoupper($order) == 'ASC' ? 'ASC' : 'DESC';
        if (self::ownSortableColumns()->contains($column)) {
            return self::orderBy($column, $order);
        } else {
            return self::orderByRelation($column, $order);
        }
    }

    static protected function orderByRelation($column, $order)
    {
        if ($column == 'country') {
            return self::select('currency.*')
                        ->join('country', 'country.id', '=', 'currency.country_id')
                        ->orderBy('country.name', $order);
        } else {
            return self::select('currency.*');
        }
    }

    static protected function ownSortableColumns()
    {
        return collect(['id', 'name']);
    }
}
