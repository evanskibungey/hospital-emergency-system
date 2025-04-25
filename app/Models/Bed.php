<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bed extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'bed_number',
        'location',
        'status',
        'type',
        'notes',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the visits currently assigned to this bed.
     */
    public function currentVisit()
    {
        return $this->hasOne(Visit::class)->where('status', '!=', 'discharged');
    }

    /**
     * Get all visits that have ever been assigned to this bed.
     */
    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    /**
     * Get the full bed identifier.
     */
    public function getFullIdentifierAttribute()
    {
        return "{$this->location} - {$this->bed_number}";
    }

    /**
     * Scope a query to only include available beds.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available')->where('is_active', true);
    }

    /**
     * Scope a query to only include occupied beds.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOccupied($query)
    {
        return $query->where('status', 'occupied');
    }

    /**
     * Scope a query to only include beds of a specific type.
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
     * Scope a query to only include active beds.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Check if the bed is available for assignment.
     *
     * @return bool
     */
    public function isAvailable()
    {
        return $this->status === 'available' && $this->is_active;
    }

    /**
     * Check if the bed is currently occupied.
     *
     * @return bool
     */
    public function isOccupied()
    {
        return $this->status === 'occupied';
    }
}
