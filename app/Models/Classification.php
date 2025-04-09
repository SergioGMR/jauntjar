<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\City;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classification extends Model
{
    use SoftDeletes;
    public $timestamps = false;

    public $fillable = [
        'uuid',
        'city_id',
        'cost',
        'culture',
        'weather',
        'food',
        'total',
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
