<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resident extends Model
{
    protected $table = 'residents';

    // =====================
    // MASS ASSIGNMENT
    // =====================
    protected $fillable = [
        // Personal
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'sex',
        'birth_date',
        'civil_status',

        // Location
        'purok',
        'house_number',
        'street',

        // Family
        'household_name',
        'relationship_to_head',
        'father_name',
        'mother_name',
        'spouse_name',
        'guardian_name',

        // Contact
        'contact_number',

        // Socio-economic
        'occupation',

        // Flags
        'is_voter',
        'is_pwd',
        'pwd_type',

        // Status
        'resident_status',
    ];

    // =====================
    // TYPE CASTING
    // =====================
    protected $casts = [
        'birth_date' => 'date',
        'is_voter' => 'boolean',
        'is_pwd' => 'boolean',
    ];

    // =====================
    // APPENDED ATTRIBUTES
    // =====================
    protected $appends = [
        'full_name',
        'age'
    ];

    // =====================
    // FULL NAME ATTRIBUTE
    // =====================
    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->middle_name} {$this->last_name} {$this->suffix}");
    }

    // =====================
    // AGE ATTRIBUTE (VERY IMPORTANT FOR POPULATION)
    // =====================
    public function getAgeAttribute()
    {
        return $this->birth_date ? $this->birth_date->age : null;
    }
}
