<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Insurance extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'display',
        'slug',
        'url',
    ];
}
