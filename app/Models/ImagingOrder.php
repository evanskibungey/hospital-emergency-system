<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImagingOrder extends Model
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
        'ordered_by',
        'imaging_type',
        'body_part',
        'clinical_information',
        'reason_for_exam',
        'is_stat',
        'requires_contrast',
        'status',
        'ordered_at',
        'scheduled_for',
        'completed_at',
        'findings',
        'impression',
        'radiologist_id',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_stat' => 'boolean',
        'requires_contrast' => 'boolean',
        'ordered_at' => 'datetime',
        'scheduled_for' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the visit that owns the imaging order.
     */
    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    /**
     * Get the treatment associated with this imaging order.
     */
    public function treatment()
    {
        return $this->belongsTo(Treatment::class);
    }

    /**
     * Get the doctor who ordered the imaging.
     */
    public function orderedBy()
    {
        return $this->belongsTo(User::class, 'ordered_by');
    }

    /**
     * Get the radiologist who read the imaging.
     */
    public function radiologist()
    {
        return $this->belongsTo(User::class, 'radiologist_id');
    }

    /**
     * Get the patient through the visit relationship.
     */
    public function patient()
    {
        return $this->hasOneThrough(Patient::class, Visit::class, 'id', 'id', 'visit_id', 'patient_id');
    }

    /**
     * Scope a query to only include STAT orders.
     */
    public function scopeStat($query)
    {
        return $query->where('is_stat', true);
    }

    /**
     * Scope a query to only include orders with a specific status.
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include pending orders (ordered, scheduled, or in progress).
     */
    public function scopePending($query)
    {
        return $query->whereIn('status', ['ordered', 'scheduled', 'in_progress']);
    }

    /**
     * Scope a query to only include completed orders.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include orders for the current day.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('ordered_at', now()->toDateString());
    }

    /**
     * Get the full name of the imaging.
     */
    public function getFullNameAttribute()
    {
        return "{$this->imaging_type} of {$this->body_part}";
    }
}
