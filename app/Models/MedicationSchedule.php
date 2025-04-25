<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicationSchedule extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'visit_id',
        'medication_id',
        'dosage',
        'frequency',
        'frequency_notes',
        'scheduled_time',
        'status',
        'created_by',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'scheduled_time' => 'datetime',
    ];

    /**
     * Get the visit that owns the medication schedule.
     */
    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    /**
     * Get the medication for this schedule.
     */
    public function medication()
    {
        return $this->belongsTo(Medication::class);
    }

    /**
     * Get the user who created this schedule.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the patient through the visit relationship.
     */
    public function patient()
    {
        return $this->hasOneThrough(Patient::class, Visit::class, 'id', 'id', 'visit_id', 'patient_id');
    }

    /**
     * Get the administrations for this schedule.
     */
    public function administrations()
    {
        return $this->hasMany(MedicationAdministration::class);
    }

    /**
     * Check if the medication is due based on the current time.
     *
     * @return bool
     */
    public function isDue()
    {
        $now = now();
        return $this->status === 'scheduled' && 
               $this->scheduled_time->lte($now) && 
               $this->scheduled_time->addMinutes(30)->gte($now);
    }

    /**
     * Check if the medication is overdue based on the current time.
     *
     * @return bool
     */
    public function isOverdue()
    {
        return $this->status === 'scheduled' && 
               $this->scheduled_time->addMinutes(30)->lt(now());
    }

    /**
     * Scope a query to only include scheduled medications.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    /**
     * Scope a query to only include upcoming medications.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUpcoming($query)
    {
        return $query->where('status', 'scheduled')
                    ->where('scheduled_time', '>=', now())
                    ->where('scheduled_time', '<=', now()->addHours(4));
    }

    /**
     * Scope a query to only include due medications.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDue($query)
    {
        return $query->where('status', 'scheduled')
                    ->where('scheduled_time', '<=', now())
                    ->where('scheduled_time', '>=', now()->subMinutes(30));
    }

    /**
     * Scope a query to only include overdue medications.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'scheduled')
                    ->where('scheduled_time', '<', now()->subMinutes(30));
    }
}