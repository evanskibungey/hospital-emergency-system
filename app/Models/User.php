<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_on_call',
        'on_call_until',
        'specialty',
        'department',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_on_call' => 'boolean',
        'on_call_until' => 'datetime',
    ];

    /**
     * Set the user's password.
     *
     * @param string $value
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * The roles that belong to the user.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Get visits assigned to this user as a nurse.
     */
    public function assignedVisits()
    {
        return $this->hasMany(Visit::class, 'assigned_to');
    }

    /**
     * Get visits assigned to this user as a doctor.
     */
    public function doctorVisits()
    {
        return $this->hasMany(Visit::class, 'doctor_id');
    }

    /**
     * Get consultation requests assigned to this doctor.
     */
    public function consultationRequests()
    {
        return $this->hasMany(ConsultationRequest::class, 'doctor_id');
    }

    /**
     * Get consultation requests created by this user.
     */
    public function requestedConsultations()
    {
        return $this->hasMany(ConsultationRequest::class, 'requesting_user_id');
    }

    /**
     * Get tasks assigned to this doctor.
     */
    public function doctorTasks()
    {
        return $this->hasMany(DoctorTask::class, 'doctor_id');
    }

    /**
     * Check if the user has a specific role.
     *
     * @param string $role
     * @return bool
     */
    public function hasRole($role)
    {
        return $this->roles()->where('slug', $role)->exists();
    }

    /**
     * Check if the user has any of the given roles.
     *
     * @param array $roles
     * @return bool
     */
    public function hasAnyRole($roles)
    {
        return $this->roles()->whereIn('slug', $roles)->exists();
    }

    /**
     * Check if the user has all of the given roles.
     *
     * @param array $roles
     * @return bool
     */
    public function hasAllRoles($roles)
    {
        return $this->roles()->whereIn('slug', $roles)->count() === count($roles);
    }

    /**
     * Check if the user is a doctor.
     *
     * @return bool
     */
    public function isDoctor()
    {
        return $this->hasRole('doctor');
    }

    /**
     * Check if the user is currently on call.
     *
     * @return bool
     */
    public function isOnCall()
    {
        return $this->is_on_call && 
               ($this->on_call_until === null || $this->on_call_until->isFuture());
    }

    /**
     * Set the user's on-call status.
     *
     * @param bool $status
     * @param \DateTime|null $until
     * @return $this
     */
    public function setOnCallStatus($status, $until = null)
    {
        $this->is_on_call = $status;
        $this->on_call_until = $until;
        $this->save();

        return $this;
    }

    /**
     * Get active doctors who are on call.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnCall($query)
    {
        return $query->where('is_on_call', true)
                     ->where(function($query) {
                         $query->whereNull('on_call_until')
                               ->orWhere('on_call_until', '>', now());
                     });
    }

    /**
     * Get doctors by specialty.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $specialty
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithSpecialty($query, $specialty)
    {
        return $query->where('specialty', $specialty);
    }

    /**
     * Get doctors by department.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $department
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInDepartment($query, $department)
    {
        return $query->where('department', $department);
    }
}