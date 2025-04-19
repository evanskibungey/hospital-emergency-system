<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VitalSign extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'visit_id',
        'user_id',
        'temperature',
        'heart_rate',
        'respiratory_rate',
        'systolic_bp',
        'diastolic_bp',
        'oxygen_saturation',
        'notes',
    ];

    /**
     * Get the visit that owns the vital sign record.
     */
    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    /**
     * Get the user (nurse) who recorded the vital signs.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the patient for this vital sign record (through the visit).
     */
    public function patient()
    {
        return $this->hasOneThrough(Patient::class, Visit::class, 'id', 'id', 'visit_id', 'patient_id');
    }

    /**
     * Get the blood pressure as a formatted string.
     *
     * @return string
     */
    public function getBloodPressureAttribute()
    {
        if ($this->systolic_bp && $this->diastolic_bp) {
            return $this->systolic_bp . '/' . $this->diastolic_bp;
        }
        
        return 'N/A';
    }
}