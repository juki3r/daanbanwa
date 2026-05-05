<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    protected $fillable = [
        'user_id',
        'full_name',
        'age',
        'gender',
        'address',
        'document_type',
        'purpose',
        'company_name',
        'business_nature',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'admin_read' => 'boolean',
    ];
}
