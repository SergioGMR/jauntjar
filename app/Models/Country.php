<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Country extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false;

    public $fillable = [
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
        'roaming'
    ];
}
