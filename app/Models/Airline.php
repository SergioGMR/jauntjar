<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

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
