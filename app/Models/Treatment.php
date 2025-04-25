<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Treatment extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'visit_id',
        'created_by',
        'updated_by',
        'diagnosis',
        'treatment_plan',
        'notes',
        'status',
        'started_at',
        'completed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the visit that owns the treatment.
     */
    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    /**
     * Get the doctor who created the treatment.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the doctor who last updated the treatment.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the patient through the visit relationship.
     */
    public function patient()
    {
        return $this->hasOneThrough(Patient::class, Visit::class, 'id', 'id', 'visit_id', 'patient_id');
    }

    /**
     * Get the medical notes associated with this treatment.
     */
    public function medicalNotes()
    {
        return $this->hasMany(MedicalNote::class);
    }

    /**
     * Get the lab orders associated with this treatment.
     */
    public function labOrders()
    {
        return $this->hasMany(LabOrder::class);
    }

    /**
     * Get the imaging orders associated with this treatment.
     */
    public function imagingOrders()
    {
        return $this->hasMany(ImagingOrder::class);
    }

    /**
     * Get the prescriptions associated with this treatment.
     */
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    /**
     * Scope a query to only include active treatments.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include completed treatments.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
