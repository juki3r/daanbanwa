<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmergencyContact extends Model
{
    protected $fillable = [
        'name',
        'number',
        'icon',
        'color',
        'priority',
        'is_active',
    ];
}
