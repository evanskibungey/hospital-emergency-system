<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',
        'email',
        'phone_number',
        'address',
        'city',
        'state',
        'postal_code',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'insurance_provider',
        'insurance_policy_number',
        'medical_history',
        'allergies',
        'current_medications',
        'medical_record_number', // Added medical record number
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_of_birth' => 'date',
    ];

    /**
     * Get the patient's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Get all visits for the patient.
     */
    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    /**
     * Get active visits for the patient.
     */
    public function activeVisits()
    {
        return $this->visits()->where('status', 'active');
    }

    /**
     * Get all vital signs for this patient through visits.
     */
    public function vitalSigns()
    {
        return $this->hasManyThrough(VitalSign::class, Visit::class);
    }

    /**
     * Get the latest vital signs for this patient.
     */
    public function latestVitalSigns()
    {
        return $this->vitalSigns()->latest()->first();
    }
}