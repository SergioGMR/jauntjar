<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classification extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'uuid',
        'city_id',
        'cost',
        'culture',
        'weather',
        'food',
        'total',
    ];

    protected function casts(): array
    {
        return [
            'cost' => 'integer',
            'culture' => 'integer',
            'weather' => 'integer',
            'food' => 'integer',
            'total' => 'integer',
        ];
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    // Helper methods
    public function getGrade(): string
    {
        return match (true) {
            $this->total >= 90 => 'Excelente',
            $this->total >= 80 => 'Muy Bueno',
            $this->total >= 70 => 'Bueno',
            $this->total >= 60 => 'Regular',
            default => 'Mejorable',
        };
    }

    public function isExcellent(): bool
    {
        return $this->total >= 90;
    }

    public function isGood(): bool
    {
        return $this->total >= 70;
    }

    public function calculateTotal(): void
    {
        $this->total = round(($this->cost + $this->culture + $this->weather + $this->food) / 4);
    }
}
