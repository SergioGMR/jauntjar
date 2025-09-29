<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'uuid',
        'name',
        'display',
        'slug',
        'code',
        'currency',
        'pibpc',
        'womens_rights',
        'lgtb_rights',
        'visa',
        'language',
        'roaming',
    ];

    protected function casts(): array
    {
        return [
            'pibpc' => 'integer',
            'womens_rights' => 'integer',
            'lgtb_rights' => 'integer',
        ];
    }

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }

    public function visitedCities(): HasMany
    {
        return $this->cities()->visited();
    }

    public function plannedCities(): HasMany
    {
        return $this->cities()->planned();
    }

    // Helper methods
    public function requiresVisa(): bool
    {
        return $this->visa === 'Sí';
    }

    public function hasRoamingIncluded(): bool
    {
        return $this->roaming === 'Incluido';
    }

    public function getPibpcFormatted(): string
    {
        return '$'.number_format($this->pibpc);
    }

    public function getRightsScore(): float
    {
        return ($this->womens_rights + $this->lgtb_rights) / 2;
    }
}
