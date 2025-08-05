<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitRoute extends Model
{
    protected $fillable = [
        'name',
        'config',
        'description',
        'active',
    ];

    protected $casts = [
        'config' => 'array',
        'active' => 'boolean',
    ];
}
