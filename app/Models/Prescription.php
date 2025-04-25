<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prescription extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'visit_id',
        'treatment_id',
        'patient_id',
        'prescribed_by',
        'medication_id',
        'medication_name',
        'dosage',
        'frequency',
        'route',
        'instructions',
        'quantity',
        'refills',
        'is_controlled_substance',
        'status',
        'notes',
        'start_date',
        'end_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_controlled_substance' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * Get the visit associated with this prescription.
     */
    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    /**
     * Get the treatment associated with this prescription.
     */
    public function treatment()
    {
        return $this->belongsTo(Treatment::class);
    }

    /**
     * Get the patient this prescription is for.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the doctor who prescribed the medication.
     */
    public function prescribedBy()
    {
        return $this->belongsTo(User::class, 'prescribed_by');
    }

    /**
     * Get the medication reference, if available.
     */
    public function medication()
    {
        return $this->belongsTo(Medication::class);
    }

    /**
     * Scope a query to only include active prescriptions.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                    ->where(function($q) {
                        $q->whereNull('end_date')
                          ->orWhere('end_date', '>=', now());
                    });
    }

    /**
     * Scope a query to only include controlled substances.
     */
    public function scopeControlledSubstances($query)
    {
        return $query->where('is_controlled_substance', true);
    }

    /**
     * Scope a query to only include prescriptions with a specific status.
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Check if the prescription is active.
     */
    public function isActive()
    {
        return $this->status === 'active' && 
               ($this->end_date === null || $this->end_date->gte(now()));
    }

    /**
     * Get the full prescription details.
     */
    public function getFullDetailsAttribute()
    {
        return "{$this->medication_name} {$this->dosage}, {$this->frequency}, {$this->route}";
    }
}
