<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DoctorTask extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'doctor_id',
        'visit_id',
        'title',
        'description',
        'priority',
        'status',
        'due_at',
        'completed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'due_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the doctor for this task.
     */
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    /**
     * Get the visit for this task, if associated.
     */
    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    /**
     * Get the patient through the visit relationship.
     */
    public function patient()
    {
        return $this->visit ? $this->visit->patient : null;
    }

    /**
     * Scope a query to only include tasks with high priority.
     */
    public function scopeHighPriority($query)
    {
        return $query->whereIn('priority', ['high', 'urgent']);
    }

    /**
     * Scope a query to only include pending tasks.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include in-progress tasks.
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    /**
     * Scope a query to only include completed tasks.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include overdue tasks.
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', '!=', 'completed')
                    ->where('due_at', '<', now());
    }

    /**
     * Scope a query to only include tasks due today.
     */
    public function scopeDueToday($query)
    {
        return $query->where('status', '!=', 'completed')
                    ->whereDate('due_at', '=', now()->toDateString());
    }

    /**
     * Check if the task is high priority.
     */
    public function isHighPriority()
    {
        return in_array($this->priority, ['high', 'urgent']);
    }

    /**
     * Check if the task is pending.
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if the task is in progress.
     */
    public function isInProgress()
    {
        return $this->status === 'in_progress';
    }

    /**
     * Check if the task is completed.
     */
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    /**
     * Check if the task is overdue.
     */
    public function isOverdue()
    {
        return !$this->isCompleted() && $this->due_at && $this->due_at->isPast();
    }

    /**
     * Check if the task is due today.
     */
    public function isDueToday()
    {
        return !$this->isCompleted() && $this->due_at && $this->due_at->isToday();
    }
}
