<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'is_open_jaw',
        'origin_city_id',
        'flight_ticket_price',
        'insurance_price',
        'total_price',
    ];

    protected function casts(): array
    {
        return [
            'departed_at' => 'datetime',
            'arrived_at' => 'datetime',
            'is_open_jaw' => 'boolean',
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

    public function originCity(): BelongsTo
    {
        return $this->belongsTo(City::class, 'origin_city_id');
    }

    public function segments(): HasMany
    {
        return $this->hasMany(BudgetSegment::class)->orderBy('order');
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
        return ! is_null($this->insurance_id);
    }

    public function hasAirline(): bool
    {
        return ! is_null($this->airline_id);
    }

    public function isOpenJaw(): bool
    {
        return $this->is_open_jaw;
    }

    public function getTotalStayDays(): int
    {
        return $this->segments->sum('stay_days');
    }

    /**
     * Calcula la fecha de vuelta basándose en la fecha de salida y los días de estancia de cada segmento.
     */
    public function getCalculatedReturnDate(): ?Carbon
    {
        if (! $this->departed_at) {
            return null;
        }

        $totalDays = $this->getTotalStayDays();

        return $this->departed_at->copy()->addDays($totalDays);
    }

    /**
     * Obtiene la descripción del itinerario completo.
     *
     * @return string Ejemplo: "LPA → LON (4 días) → CDG (3 días) → LPA"
     */
    public function getItineraryDescription(): string
    {
        if (! $this->is_open_jaw || $this->segments->isEmpty()) {
            return '';
        }

        $parts = [];
        $lastDestination = null;

        foreach ($this->segments as $segment) {
            if (empty($parts)) {
                $parts[] = $segment->originCity?->display ?? '?';
            }

            $destination = $segment->destinationCity?->display ?? '?';
            $stayDays = $segment->stay_days;

            if ($stayDays > 0) {
                $parts[] = "{$destination} ({$stayDays} días)";
            } else {
                $parts[] = $destination;
            }

            $lastDestination = $segment->destinationCity;
        }

        return implode(' → ', $parts);
    }
}
