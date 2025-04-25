<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicationAdministration extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'medication_schedule_id',
        'administered_by',
        'administered_at',
        'actual_dosage',
        'status',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'administered_at' => 'datetime',
    ];

    /**
     * Get the medication schedule for this administration.
     */
    public function medicationSchedule()
    {
        return $this->belongsTo(MedicationSchedule::class);
    }

    /**
     * Get the nurse who administered the medication.
     */
    public function administeredBy()
    {
        return $this->belongsTo(User::class, 'administered_by');
    }

    /**
     * Get the medication through the schedule relationship.
     */
    public function medication()
    {
        return $this->hasOneThrough(
            Medication::class, 
            MedicationSchedule::class, 
            'id', 
            'id', 
            'medication_schedule_id', 
            'medication_id'
        );
    }

    /**
     * Get the visit through the schedule relationship.
     */
    public function visit()
    {
        return $this->hasOneThrough(
            Visit::class, 
            MedicationSchedule::class, 
            'id', 
            'id', 
            'medication_schedule_id', 
            'visit_id'
        );
    }

    /**
     * Get the patient through the visit and schedule relationship.
     */
    public function patient()
    {
        return $this->medicationSchedule->visit->patient();
    }
}