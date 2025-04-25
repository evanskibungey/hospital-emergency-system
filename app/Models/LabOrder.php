<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LabOrder extends Model
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
        'test_name',
        'test_details',
        'reason_for_test',
        'is_stat',
        'status',
        'ordered_at',
        'scheduled_for',
        'collected_at',
        'completed_at',
        'result_summary',
        'result_details',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_stat' => 'boolean',
        'ordered_at' => 'datetime',
        'scheduled_for' => 'datetime',
        'collected_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the visit that owns the lab order.
     */
    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    /**
     * Get the treatment associated with this lab order.
     */
    public function treatment()
    {
        return $this->belongsTo(Treatment::class);
    }

    /**
     * Get the doctor who ordered the test.
     */
    public function orderedBy()
    {
        return $this->belongsTo(User::class, 'ordered_by');
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
     * Scope a query to only include pending orders (ordered or collected).
     */
    public function scopePending($query)
    {
        return $query->whereIn('status', ['ordered', 'collected', 'in_progress']);
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
     * Format the result summary for display.
     */
    public function getFormattedResultSummaryAttribute()
    {
        if (empty($this->result_summary)) {
            return 'Pending';
        }

        return $this->result_summary;
    }
}
