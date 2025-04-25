<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquipmentCheckout extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'equipment_id',
        'visit_id',
        'checked_out_by',
        'checked_in_by',
        'checked_out_at',
        'expected_return_at',
        'checked_in_at',
        'quantity',
        'purpose',
        'status',
        'checkout_notes',
        'checkin_notes',
        'condition_at_checkout',
        'condition_at_checkin',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'checked_out_at' => 'datetime',
        'expected_return_at' => 'datetime',
        'checked_in_at' => 'datetime',
    ];

    /**
     * Get the equipment that was checked out.
     */
    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    /**
     * Get the visit associated with this checkout.
     */
    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    /**
     * Get the user who checked out the equipment.
     */
    public function checkedOutBy()
    {
        return $this->belongsTo(User::class, 'checked_out_by');
    }

    /**
     * Get the user who checked in the equipment.
     */
    public function checkedInBy()
    {
        return $this->belongsTo(User::class, 'checked_in_by');
    }

    /**
     * Get the patient through the visit relationship.
     */
    public function patient()
    {
        return $this->hasOneThrough(
            Patient::class,
            Visit::class,
            'id', // Foreign key on visits table
            'id', // Foreign key on patients table
            'visit_id', // Local key on equipment_checkouts table
            'patient_id' // Local key on visits table
        );
    }

    /**
     * Scope a query to only include active checkouts (not checked in).
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->whereNull('checked_in_at');
    }

    /**
     * Scope a query to only include checked out items.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCheckedOut($query)
    {
        return $query->where('status', 'checked_out');
    }

    /**
     * Scope a query to only include overdue items.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOverdue($query)
    {
        return $query->whereNull('checked_in_at')
                    ->where(function($query) {
                        $query->where('expected_return_at', '<', now())
                              ->orWhere('status', 'overdue');
                    });
    }

    /**
     * Check if the checkout is overdue.
     *
     * @return bool
     */
    public function isOverdue()
    {
        return $this->expected_return_at && 
               $this->expected_return_at->lt(now()) && 
               !$this->checked_in_at;
    }

    /**
     * Check if the equipment has been checked in.
     *
     * @return bool
     */
    public function isCheckedIn()
    {
        return $this->checked_in_at !== null;
    }

    /**
     * Get the duration of the checkout in hours.
     *
     * @return float|null
     */
    public function getDurationAttribute()
    {
        if ($this->checked_in_at) {
            return $this->checked_out_at->diffInHours($this->checked_in_at, false);
        } elseif ($this->expected_return_at) {
            return $this->checked_out_at->diffInHours($this->expected_return_at, false);
        }
        
        return null;
    }

    /**
     * Handle status updates automatically when checked in.
     */
    public static function boot()
    {
        parent::boot();

        static::saving(function ($checkout) {
            // Auto-update status when checked in
            if ($checkout->checked_in_at && !$checkout->isDirty('status')) {
                $checkout->status = 'checked_in';
            }

            // Auto-update status to overdue if past expected return date
            if (!$checkout->checked_in_at && 
                $checkout->expected_return_at && 
                $checkout->expected_return_at->lt(now()) &&
                $checkout->status !== 'overdue' &&
                $checkout->status !== 'lost') {
                $checkout->status = 'overdue';
            }
        });
    }
}