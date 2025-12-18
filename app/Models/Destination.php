<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Destination extends Model
{
    use HasFactory, SoftDeletes;

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

    protected function casts(): array
    {
        return [
            'accommodation_price' => 'float',
            'transport_price' => 'float',
            'displacement_price' => 'float',
            'accommodation_stars' => 'integer',
            'duration_days' => 'integer',
            'arrival_date' => 'datetime',
        ];
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    // Helper methods
    public function getTotalCost(): float
    {
        return $this->accommodation_price + $this->transport_price + $this->displacement_price;
    }

    public function getTotalCostFormatted(): string
    {
        return '€'.number_format($this->getTotalCost(), 2);
    }

    public function getAccommodationStarsDisplay(): string
    {
        return str_repeat('⭐', $this->accommodation_stars);
    }

    public function getDepartureDate(): ?\DateTime
    {
        if (! $this->arrival_date) {
            return null;
        }

        return $this->arrival_date->copy()->subDays($this->duration_days);
    }
}
