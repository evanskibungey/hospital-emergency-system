<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'patient_id',
        'chief_complaint',
        'status',
        'is_critical',
        'priority',
        'department',
        'check_in_time',
        'check_out_time',
        'discharged_at',
        'assigned_to',
        'doctor_id',
        'bed_id',
        'bed_assigned_at',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
        'discharged_at' => 'datetime',
        'bed_assigned_at' => 'datetime',
        'is_critical' => 'boolean',
    ];

    /**
     * Get the patient that owns the visit.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the user (staff) that is assigned to this visit.
     */
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the doctor assigned to this visit.
     */
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    /**
     * Get the bed assigned to this visit.
     */
    public function bed()
    {
        return $this->belongsTo(Bed::class);
    }

    /**
     * Get all vital signs for this visit.
     */
    public function vitalSigns()
    {
        return $this->hasMany(VitalSign::class);
    }

    /**
     * Get the most recent vital signs for this visit.
     */
    public function latestVitalSigns()
    {
        return $this->hasOne(VitalSign::class)->latest();
    }

    /**
     * Get all medication schedules for this visit.
     */
    public function medicationSchedules()
    {
        return $this->hasMany(MedicationSchedule::class);
    }

    /**
     * Get all consultation requests for this visit.
     */
    public function consultationRequests()
    {
        return $this->hasMany(ConsultationRequest::class);
    }

    /**
     * Get doctor tasks associated with this visit.
     */
    public function doctorTasks()
    {
        return $this->hasMany(DoctorTask::class);
    }

    /**
     * Get treatments associated with this visit.
     */
    public function treatments()
    {
        return $this->hasMany(Treatment::class);
    }

    /**
     * Get the medical notes associated with this visit.
     */
    public function medicalNotes()
    {
        return $this->hasMany(MedicalNote::class);
    }

    /**
     * Get lab orders associated with this visit.
     */
    public function labOrders()
    {
        return $this->hasMany(LabOrder::class);
    }

    /**
     * Get imaging orders associated with this visit.
     */
    public function imagingOrders()
    {
        return $this->hasMany(ImagingOrder::class);
    }

    /**
     * Get prescriptions associated with this visit.
     */
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    /**
     * Get the discharge record associated with this visit.
     */
    public function discharge()
    {
        return $this->hasOne(Discharge::class);
    }

    /**
     * Get follow-up appointments associated with this visit.
     */
    public function followUpAppointments()
    {
        return $this->hasMany(FollowUpAppointment::class);
    }

    /**
     * Get scheduled medications that need to be administered.
     */
    public function dueAndOverdueMedications()
    {
        return $this->medicationSchedules()
                    ->where('status', 'scheduled')
                    ->where('scheduled_time', '<=', now())
                    ->orderBy('scheduled_time', 'asc');
    }

    /**
     * Get all medication administrations for this visit through schedules.
     */
    public function medicationAdministrations()
    {
        return $this->hasManyThrough(
            MedicationAdministration::class, 
            MedicationSchedule::class
        );
    }

    /**
     * Scope a query to only include active visits.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['waiting', 'in_progress']);
    }

    /**
     * Scope a query to only include in-progress visits.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    /**
     * Scope a query to only include waiting visits.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWaiting($query)
    {
        return $query->where('status', 'waiting');
    }

    /**
     * Scope a query to only include discharged visits.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDischarged($query)
    {
        return $query->where('status', 'discharged')
                     ->whereNotNull('discharged_at');
    }

    /**
     * Scope a query to only include non-discharged visits.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotDischarged($query)
    {
        return $query->where(function($q) {
            $q->where('status', '!=', 'discharged')
              ->orWhereNull('discharged_at');
        });
    }

    /**
     * Scope a query to only include visits assigned to a specific user.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    /**
     * Scope a query to only include visits assigned to a specific doctor.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $doctorId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAssignedToDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    /**
     * Scope a query to only include critical visits.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCritical($query)
    {
        return $query->where('is_critical', true);
    }

    /**
     * Check if the visit has any active treatments.
     *
     * @return bool
     */
    public function hasActiveTreatments()
    {
        return $this->treatments()->where('status', 'active')->exists();
    }

    /**
     * Check if the visit is ready for discharge.
     *
     * @return bool
     */
    public function isReadyForDischarge()
    {
        // Check if already discharged
        if ($this->status === 'discharged' || $this->discharged_at !== null) {
            return false;
        }

        // Must have at least one treatment that is completed or no treatments at all
        $hasTreatments = $this->treatments()->exists();
        $hasCompletedTreatments = $this->treatments()->where('status', 'completed')->exists();
        $hasActiveTreatments = $this->treatments()->where('status', 'active')->exists();

        // Ready if: no treatments OR has completed treatments AND no active treatments
        return !$hasTreatments || ($hasCompletedTreatments && !$hasActiveTreatments);
    }
}