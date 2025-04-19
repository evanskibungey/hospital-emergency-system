<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'patient_id',
        'chief_complaint',
        'priority',
        'department',
        'status',
        'check_in_time',
        'check_out_time',
        'notes',
        'initial_assessment',
        'estimated_wait_time',
        'registered_by',
        'assigned_to',
        'bed_number',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
    ];

    /**
     * Get the patient that owns the visit.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the vital signs for this visit.
     */
    public function vitalSigns()
    {
        return $this->hasMany(VitalSign::class);
    }

    /**
     * Get the latest vital signs for this visit.
     */
    public function latestVitalSigns()
    {
        return $this->hasOne(VitalSign::class)->latest();
    }

    /**
     * Get the visitors associated with this visit.
     */
    public function visitors()
    {
        return $this->hasMany(Visitor::class);
    }

    /**
     * Get the user who registered this visit.
     */
    public function registeredBy()
    {
        return $this->belongsTo(User::class, 'registered_by');
    }

    /**
     * Get the nurse assigned to this visit.
     */
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Check if the visit is active.
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Check if the visit is waiting.
     *
     * @return bool
     */
    public function isWaiting()
    {
        return $this->status === 'waiting';
    }

    /**
     * Assign the visit to a nurse.
     *
     * @param int $nurseId
     * @return void
     */
    public function assignToNurse($nurseId)
    {
        $this->status = 'active';
        $this->assigned_to = $nurseId;
        $this->save();
    }

    /**
     * Mark the visit as completed.
     *
     * @return void
     */
    public function complete()
    {
        $this->status = 'completed';
        $this->check_out_time = now();
        $this->save();
    }

    /**
     * Get the estimated wait time in minutes.
     *
     * @return int
     */
    public function getEstimatedWaitTime()
    {
        // Simple wait time calculation based on priority
        $waitTimes = [
            'low' => 120,     // 2 hours
            'medium' => 60,   // 1 hour
            'high' => 30,     // 30 minutes
            'critical' => 0   // Immediate
        ];

        return $waitTimes[$this->priority] ?? 60;
    }
}