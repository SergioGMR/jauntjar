<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BudgetSegment extends Model
{
    /** @use HasFactory<\Database\Factories\BudgetSegmentFactory> */
    use HasFactory;

    protected $fillable = [
        'budget_id',
        'origin_city_id',
        'destination_city_id',
        'stay_days',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'stay_days' => 'integer',
            'order' => 'integer',
        ];
    }

    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class);
    }

    public function originCity(): BelongsTo
    {
        return $this->belongsTo(City::class, 'origin_city_id');
    }

    public function destinationCity(): BelongsTo
    {
        return $this->belongsTo(City::class, 'destination_city_id');
    }

    public function getRouteDescription(): string
    {
        return $this->originCity?->display.' → '.$this->destinationCity?->display;
    }
}
