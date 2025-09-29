<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = false;

    public $fillable = [
        'uuid',
        'country_id',
        'name',
        'display',
        'slug',
        'days',
        'stops',
        'visited',
        'visited_at',
        'coordinates',
    ];

    public $casts = [
        'visited' => 'boolean',
        'visited_at' => 'datetime',
        'coordinates' => 'array',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function classification(): HasOne
    {
        return $this->hasOne(Classification::class);
    }
}
