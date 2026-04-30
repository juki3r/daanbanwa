<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ordinance extends Model
{
    protected $fillable = [
        'ordinance_no',
        'title',
        'description',
        'date_approved',
        'status',
    ];
}
