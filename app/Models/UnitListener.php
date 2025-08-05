<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitListener extends Model
{
    protected $fillable = [
        'address',
        'pass',
        'config',
        'description',
        'active',
    ];

    protected $casts = [
        'config' => 'array',
        'active' => 'boolean',
    ];
}
