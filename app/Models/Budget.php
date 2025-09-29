<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Budget extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'airline_id',
        'insurance_id',
        'name',
        'display',
        'slug',
        'flight_ticket_price',
        'insurance_price',
        'total_price',
    ];

    protected function casts(): array
    {
        return [
            'departed_at' => 'datetime',
            'arrived_at' => 'datetime',
        ];
    }

    public function airline(): BelongsTo
    {
        return $this->belongsTo(Airline::class);
    }

    public function insurance(): BelongsTo
    {
        return $this->belongsTo(Insurance::class);
    }

    // Helper methods
    public function getName(): string
    {
        return $this->name;
    }

    public function getDisplay(): string
    {
        return $this->display;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getTotalPrice(): int
    {
        return $this->total_price;
    }

    public function getFlightTicketPrice(): int
    {
        return $this->flight_ticket_price;
    }

    public function getInsurancePrice(): int
    {
        return $this->insurance_price;
    }

    public function hasInsurance(): bool
    {
        return !is_null($this->insurance_id);
    }

    public function hasAirline(): bool
    {
        return !is_null($this->airline_id);
    }
}
