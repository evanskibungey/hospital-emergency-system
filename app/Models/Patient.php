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
        return $this->visits()->whereIn('status', ['waiting', 'in_progress']);
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

    /**
     * Get all treatments for this patient through visits.
     */
    public function treatments()
    {
        return $this->hasManyThrough(Treatment::class, Visit::class);
    }

    /**
     * Get all medical notes for this patient through visits.
     */
    public function medicalNotes()
    {
        return $this->hasManyThrough(MedicalNote::class, Visit::class);
    }

    /**
     * Get all lab orders for this patient through visits.
     */
    public function labOrders()
    {
        return $this->hasManyThrough(LabOrder::class, Visit::class);
    }

    /**
     * Get all imaging orders for this patient through visits.
     */
    public function imagingOrders()
    {
        return $this->hasManyThrough(ImagingOrder::class, Visit::class);
    }

    /**
     * Get all prescriptions for this patient.
     */
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    /**
     * Get active prescriptions for this patient.
     */
    public function activePrescriptions()
    {
        return $this->prescriptions()
                    ->where('status', 'active')
                    ->where(function($query) {
                        $query->whereNull('end_date')
                              ->orWhere('end_date', '>=', now());
                    });
    }

    /**
     * Get all discharges for this patient.
     */
    public function discharges()
    {
        return $this->hasMany(Discharge::class);
    }

    /**
     * Get all follow-up appointments for this patient.
     */
    public function followUpAppointments()
    {
        return $this->hasMany(FollowUpAppointment::class);
    }

    /**
     * Get upcoming follow-up appointments for this patient.
     */
    public function upcomingAppointments()
    {
        return $this->followUpAppointments()
                    ->whereIn('status', ['scheduled', 'confirmed'])
                    ->where('appointment_time', '>=', now())
                    ->orderBy('appointment_time');
    }

    /**
     * Get the patient's age.
     *
     * @return int
     */
    public function getAgeAttribute()
    {
        return $this->date_of_birth->age;
    }

    /**
     * Scope a query to search patients by name or medical record number.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
              ->orWhere('last_name', 'like', "%{$search}%")
              ->orWhere('medical_record_number', 'like', "%{$search}%");
        });
    }
}