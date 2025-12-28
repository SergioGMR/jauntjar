<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'uuid',
        'country_id',
        'name',
        'display',
        'slug',
        'stops',
        'visited',
        'visited_at',
        'coordinates',
    ];

    protected function casts(): array
    {
        return [
            'visited' => 'boolean',
            'coordinates' => 'array',
            'visited_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function classification(): HasOne
    {
        return $this->hasOne(Classification::class);
    }

    public function destinations(): HasMany
    {
        return $this->hasMany(Destination::class);
    }

    // Scopes
    public function scopeVisited($query)
    {
        return $query->where('visited', true);
    }

    public function scopePlanned($query)
    {
        return $query->where('visited', false);
    }

    public function scopeWithoutStops($query)
    {
        return $query->where('stops', 0);
    }

    // Helper methods
    public function isVisited(): bool
    {
        return $this->visited;
    }

    public function isPlanned(): bool
    {
        return ! $this->visited;
    }

    public function hasStops(): bool
    {
        return $this->stops > 0;
    }

    public function getStopsDescription(): string
    {
        return $this->stops === 0 ? 'Directo' : $this->stops.' paradas';
    }

    public function getClassificationScore(): ?int
    {
        return $this->classification?->total;
    }

    public function getClassificationDisplay(): string
    {
        return $this->classification
            ? $this->classification->total.'/100'
            : 'Sin clasificar';
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getCoordinates(): ?array
    {
        return $this->coordinates;
    }

    public function hasCoordinates(): bool
    {
        return !empty($this->coordinates) && 
               isset($this->coordinates['lat']) && 
               isset($this->coordinates['lng']);
    }

    public function getLatitude(): ?float
    {
        return $this->hasCoordinates() ? $this->coordinates['lat'] : null;
    }

    public function getLongitude(): ?float
    {
        return $this->hasCoordinates() ? $this->coordinates['lng'] : null;
    }

    public function hasClassification(): bool
    {
        return !is_null($this->classification);
    }
}
