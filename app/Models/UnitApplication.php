<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitApplication extends Model
{
    protected $fillable = [
        'name',
        'type',
        'config',
        'description',
        'active',
    ];

    protected $casts = [
        'config' => 'array',
        'active' => 'boolean',
    ];
}
