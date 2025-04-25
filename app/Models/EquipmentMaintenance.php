<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquipmentMaintenance extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'equipment_maintenance';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'equipment_id',
        'requested_by',
        'completed_by',
        'requested_at',
        'scheduled_for',
        'completed_at',
        'type',
        'priority',
        'status',
        'issue_description',
        'work_performed',
        'cost',
        'service_provider',
        'contact_info',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'requested_at' => 'datetime',
        'scheduled_for' => 'datetime',
        'completed_at' => 'datetime',
        'cost' => 'decimal:2',
    ];

    /**
     * Get the equipment that is being maintained.
     */
    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    /**
     * Get the user who requested the maintenance.
     */
    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    /**
     * Get the user who completed the maintenance.
     */
    public function completedBy()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    /**
     * Scope a query to only include active maintenance requests.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->whereNull('completed_at')
                     ->where('status', '!=', 'cancelled');
    }

    /**
     * Scope a query to only include completed maintenance.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCompleted($query)
    {
        return $query->whereNotNull('completed_at')
                     ->where('status', 'completed');
    }

    /**
     * Scope a query to only include high priority maintenance.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHighPriority($query)
    {
        return $query->whereIn('priority', ['high', 'critical']);
    }

    /**
     * Scope a query to only include scheduled maintenance for today.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeScheduledForToday($query)
    {
        return $query->whereDate('scheduled_for', now()->toDateString())
                     ->whereNull('completed_at')
                     ->where('status', '!=', 'cancelled');
    }

    /**
     * Scope a query to only include overdue maintenance.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOverdue($query)
    {
        return $query->whereNull('completed_at')
                     ->where('status', '!=', 'cancelled')
                     ->where('scheduled_for', '<', now());
    }

    /**
     * Check if the maintenance is completed.
     *
     * @return bool
     */
    public function isCompleted()
    {
        return $this->completed_at !== null && $this->status === 'completed';
    }

    /**
     * Check if the maintenance is overdue.
     *
     * @return bool
     */
    public function isOverdue()
    {
        return $this->scheduled_for && 
               $this->scheduled_for->lt(now()) && 
               !$this->completed_at &&
               $this->status !== 'cancelled';
    }

    /**
     * Handle status updates automatically.
     */
    public static function boot()
    {
        parent::boot();

        static::saving(function ($maintenance) {
            // Auto-update status when completed
            if ($maintenance->completed_at && !$maintenance->isDirty('status')) {
                $maintenance->status = 'completed';
            }

            // Auto-update equipment's last maintenance date when completed
            if ($maintenance->completed_at && $maintenance->isDirty('completed_at')) {
                $equipment = Equipment::find($maintenance->equipment_id);
                if ($equipment) {
                    $equipment->last_maintenance_date = $maintenance->completed_at;
                    
                    // Calculate next maintenance date based on type
                    if ($maintenance->type === 'preventive') {
                        // For preventive maintenance, schedule next in 6 months
                        $equipment->next_maintenance_date = $maintenance->completed_at->addMonths(6);
                    } elseif (in_array($maintenance->type, ['inspection', 'calibration'])) {
                        // For inspections and calibrations, schedule next in 12 months
                        $equipment->next_maintenance_date = $maintenance->completed_at->addYear();
                    }
                    
                    // Update equipment status if it was in maintenance
                    if ($equipment->status === 'maintenance') {
                        $equipment->status = 'available';
                    }
                    
                    $equipment->save();
                }
            }
        });
    }
}