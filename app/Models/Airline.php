<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Airline extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'name',
        'display',
        'slug',
        'logo',
        'is_low_cost',
    ];

    protected function casts(): array
    {
        return [
            'is_low_cost' => 'boolean',
        ];
    }

    public function budgets(): HasMany
    {
        return $this->hasMany(Budget::class);
    }

    // Helper methods
    public function isLowCost(): bool
    {
        return $this->is_low_cost;
    }

    public function getTypeDisplay(): string
    {
        return $this->is_low_cost ? 'Low Cost' : 'Tradicional';
    }
}
