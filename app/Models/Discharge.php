<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discharge extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'visit_id',
        'patient_id',
        'discharged_by',
        'discharge_diagnosis',
        'discharge_summary',
        'discharge_instructions',
        'medications_at_discharge',
        'activity_restrictions',
        'diet_instructions',
        'follow_up_instructions',
        'discharge_disposition',
        'destination_facility',
        'discharged_at',
        'instructions_provided',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'discharged_at' => 'datetime',
        'instructions_provided' => 'boolean',
    ];

    /**
     * Get the visit associated with this discharge.
     */
    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    /**
     * Get the patient associated with this discharge.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the doctor who discharged the patient.
     */
    public function dischargedBy()
    {
        return $this->belongsTo(User::class, 'discharged_by');
    }

    /**
     * Get the follow-up appointments associated with this discharge.
     */
    public function followUpAppointments()
    {
        return $this->hasMany(FollowUpAppointment::class);
    }

    /**
     * Scope a query to only include discharges with provided instructions.
     */
    public function scopeWithInstructions($query)
    {
        return $query->where('instructions_provided', true);
    }

    /**
     * Scope a query to only include discharges by disposition type.
     */
    public function scopeByDisposition($query, $disposition)
    {
        return $query->where('discharge_disposition', $disposition);
    }

    /**
     * Scope a query to only include discharges for today.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('discharged_at', now()->toDateString());
    }
}
