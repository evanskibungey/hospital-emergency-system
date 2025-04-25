<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'dosage_form',
        'strength',
        'unit',
        'instructions',
        'is_controlled_substance',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_controlled_substance' => 'boolean',
    ];

    /**
     * Get the medication schedules for this medication.
     */
    public function schedules()
    {
        return $this->hasMany(MedicationSchedule::class);
    }

    /**
     * Get the medication administrations for this medication through schedules.
     */
    public function administrations()
    {
        return $this->hasManyThrough(MedicationAdministration::class, MedicationSchedule::class);
    }

    /**
     * Get the full medication name with strength.
     */
    public function getFullNameAttribute()
    {
        return "{$this->name} {$this->strength}" . ($this->unit ? " {$this->unit}" : "");
    }
}