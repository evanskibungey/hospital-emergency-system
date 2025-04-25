<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipment extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'equipment';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'serial_number',
        'model',
        'manufacturer',
        'type',
        'category',
        'quantity',
        'available_quantity',
        'purchase_date',
        'last_maintenance_date',
        'next_maintenance_date',
        'status',
        'location',
        'notes',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'purchase_date' => 'date',
        'last_maintenance_date' => 'date',
        'next_maintenance_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get all checkouts for this equipment.
     */
    public function checkouts()
    {
        return $this->hasMany(EquipmentCheckout::class);
    }

    /**
     * Get active (not checked in) checkouts for this equipment.
     */
    public function activeCheckouts()
    {
        return $this->checkouts()->whereNull('checked_in_at');
    }

    /**
     * Get all maintenance records for this equipment.
     */
    public function maintenanceRecords()
    {
        return $this->hasMany(EquipmentMaintenance::class);
    }

    /**
     * Get active (not completed) maintenance records for this equipment.
     */
    public function activeMaintenance()
    {
        return $this->maintenanceRecords()->whereNull('completed_at');
    }

    /**
     * Scope a query to only include available equipment.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available')
                     ->where('is_active', true)
                     ->where('available_quantity', '>', 0);
    }

    /**
     * Scope a query to only include active equipment.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include equipment needing maintenance.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNeedsMaintenance($query)
    {
        return $query->where(function($query) {
            $query->where('next_maintenance_date', '<=', now())
                  ->orWhere('status', 'maintenance');
        });
    }

    /**
     * Scope a query to only include equipment of a specific type.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope a query to only include equipment of a specific category.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $category
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Check if equipment is available for checkout.
     *
     * @return bool
     */
    public function isAvailable()
    {
        return $this->status === 'available' && $this->is_active && $this->available_quantity > 0;
    }

    /**
     * Get the number of items currently checked out.
     *
     * @return int
     */
    public function getCheckedOutCountAttribute()
    {
        return $this->quantity - $this->available_quantity;
    }

    /**
     * Check if maintenance is due.
     *
     * @return bool
     */
    public function isMaintenanceDue()
    {
        return $this->next_maintenance_date && $this->next_maintenance_date->lte(now());
    }
}