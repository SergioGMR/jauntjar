<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Airline extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'name',
        'display',
        'slug',
        'logo',
        'is_low_cost',
    ];
}
