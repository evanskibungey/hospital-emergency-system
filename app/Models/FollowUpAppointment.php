<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FollowUpAppointment extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'patient_id',
        'visit_id',
        'discharge_id',
        'scheduled_by',
        'doctor_id',
        'specialty',
        'department',
        'reason_for_visit',
        'appointment_time',
        'estimated_duration_minutes',
        'is_urgent',
        'special_instructions',
        'status',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'appointment_time' => 'datetime',
        'is_urgent' => 'boolean',
    ];

    /**
     * Get the patient associated with this appointment.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the visit associated with this appointment.
     */
    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    /**
     * Get the discharge associated with this appointment.
     */
    public function discharge()
    {
        return $this->belongsTo(Discharge::class);
    }

    /**
     * Get the user who scheduled the appointment.
     */
    public function scheduledBy()
    {
        return $this->belongsTo(User::class, 'scheduled_by');
    }

    /**
     * Get the doctor assigned to the appointment.
     */
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    /**
     * Scope a query to only include scheduled appointments.
     */
    public function scopeScheduled($query)
    {
        return $query->whereIn('status', ['scheduled', 'confirmed']);
    }

    /**
     * Scope a query to only include upcoming appointments.
     */
    public function scopeUpcoming($query)
    {
        return $query->whereIn('status', ['scheduled', 'confirmed'])
                    ->where('appointment_time', '>=', now());
    }

    /**
     * Scope a query to only include appointments for today.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('appointment_time', now()->toDateString());
    }

    /**
     * Scope a query to only include urgent appointments.
     */
    public function scopeUrgent($query)
    {
        return $query->where('is_urgent', true);
    }

    /**
     * Check if the appointment is upcoming.
     */
    public function isUpcoming()
    {
        return in_array($this->status, ['scheduled', 'confirmed']) && 
               $this->appointment_time->gte(now());
    }
}
