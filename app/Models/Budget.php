<?php

namespace App\Models;

use App\Models\Airline;
use App\Models\City;
use App\Models\Insurance;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Budget extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'city_id',
        'airline_id',
        'insurance_id',
        'name',
        'display',
        'slug',
        'departed_at',
        'arrived_at',
        'flight_ticket_price',
        'insurance_price',
        'accommodation_stars',
        'accommodation_price',
        'transport_type',
        'transport_price',
        'total_price',
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function airline(): BelongsTo
    {
        return $this->belongsTo(Airline::class);
    }

    public function insurance(): BelongsTo
    {
        return $this->belongsTo(Insurance::class);
    }
}
