<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Destination extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'city_id',
        'accommodation_stars',
        'accommodation_price',
        'transport_type',
        'transport_price',
        'arrival_date',
        'duration_days',
        'displacement',
        'displacement_price',
    ];

    protected $casts = [
        'arrival_date' => 'datetime',
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
