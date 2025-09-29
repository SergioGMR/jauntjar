<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Insurance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'name',
        'display',
        'slug',
        'url',
    ];

    public function budgets(): HasMany
    {
        return $this->hasMany(Budget::class);
    }

    // Helper methods
    public function hasWebsite(): bool
    {
        return ! empty($this->url);
    }

    public function getWebsiteHost(): ?string
    {
        if (! $this->hasWebsite()) {
            return null;
        }

        return parse_url($this->url, PHP_URL_HOST);
    }
}
