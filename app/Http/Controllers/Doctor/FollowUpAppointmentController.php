<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Discharge;
use App\Models\FollowUpAppointment;
use App\Models\Patient;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowUpAppointmentController extends Controller
{
    /**
     * Display a listing of the follow-up appointments.
     */
    public function index()
    {
        $upcomingAppointments = FollowUpAppointment::with(['patient', 'doctor', 'scheduledBy'])
                                                  ->upcoming()
                                                  ->orderBy('appointment_time')
                                                  ->paginate(10, ['*'], 'upcoming');
        
        $todayAppointments = FollowUpAppointment::with(['patient', 'doctor', 'scheduledBy'])
                                               ->today()
                                               ->orderBy('appointment_time')
                                               ->paginate(10, ['*'], 'today');
        
        $completedAppointments = FollowUpAppointment::with(['patient', 'doctor', 'scheduledBy'])
                                                   ->where('status', 'completed')
                                                   ->latest('appointment_time')
                                                   ->paginate(10, ['*'], 'completed');
        
        return view('doctor.follow-up-appointments.index', compact('upcomingAppointments', 'todayAppointments', 'completedAppointments'));
    }

    /**
     * Show the form for creating a new follow-up appointment.
     */
    public function create(Request $request)
    {
        $visit = null;
        $discharge = null;
        $patient = null;
        
        if ($request->has('visit_id')) {
            $visit = Visit::findOrFail($request->input('visit_id'));
            $patient = $visit->patient;
        } elseif ($request->has('discharge_id')) {
            $discharge = Discharge::findOrFail($request->input('discharge_id'));
            $visit = $discharge->visit;
            $patient = $discharge->patient;
        } elseif ($request->has('patient_id')) {
            $patient = Patient::findOrFail($request->input('patient_id'));
        }
        
        // Get doctors for dropdown
        $doctors = User::whereHas('roles', function($query) {
                         $query->where('name', 'doctor');
                     })
                     ->orderBy('name')
                     ->get();
        
        // Common departments and specialties for dropdown
        $departments = [
            'Emergency',
            'Cardiology',
            'Neurology',
            'Orthopedics',
            'Internal Medicine',
            'Pediatrics',
            'Obstetrics/Gynecology',
            'Surgery',
            'Radiology',
            'Psychiatry',
            'Dermatology',
            'Ophthalmology',
            'Oncology',
            'Urology',
            'ENT',
        ];
        
        return view('doctor.follow-up-appointments.create', compact('visit', 'discharge', 'patient', 'doctors', 'departments'));
    }

    /**
     * Store a newly created follow-up appointment in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'visit_id' => 'nullable|exists:visits,id',
            'discharge_id' => 'nullable|exists:discharges,id',
            'doctor_id' => 'nullable|exists:users,id',
            'specialty' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'reason_for_visit' => 'required|string',
            'appointment_time' => 'required|date|after:now',
            'estimated_duration_minutes' => 'required|integer|min:15|max:240',
            'is_urgent' => 'boolean',
            'special_instructions' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        
        $appointment = new FollowUpAppointment($validated);
        $appointment->scheduled_by = Auth::id();
        $appointment->status = 'scheduled';
        $appointment->save();
        
        return redirect()->route('doctor.follow-up-appointments.show', [$appointment])
                        ->with('success', 'Follow-up appointment scheduled successfully.');
    }

    /**
     * Display the specified follow-up appointment.
     */
    public function show(FollowUpAppointment $followUpAppointment)
    {
        $followUpAppointment->load(['patient', 'visit', 'discharge', 'doctor', 'scheduledBy']);
        
        return view('doctor.follow-up-appointments.show', compact('followUpAppointment'));
    }

    /**
     * Show the form for editing the specified follow-up appointment.
     */
    public function edit(FollowUpAppointment $followUpAppointment)
    {
        if (!in_array($followUpAppointment->status, ['scheduled', 'confirmed'])) {
            return redirect()->route('doctor.follow-up-appointments.show', [$followUpAppointment])
                            ->with('error', 'Cannot edit an appointment that has already been completed or cancelled.');
        }
        
        // Get doctors for dropdown
        $doctors = User::whereHas('roles', function($query) {
                         $query->where('name', 'doctor');
                     })
                     ->orderBy('name')
                     ->get();
        
        // Common departments and specialties for dropdown
        $departments = [
            'Emergency',
            'Cardiology',
            'Neurology',
            'Orthopedics',
            'Internal Medicine',
            'Pediatrics',
            'Obstetrics/Gynecology',
            'Surgery',
            'Radiology',
            'Psychiatry',
            'Dermatology',
            'Ophthalmology',
            'Oncology',
            'Urology',
            'ENT',
        ];
        
        return view('doctor.follow-up-appointments.edit', compact('followUpAppointment', 'doctors', 'departments'));
    }

    /**
     * Update the specified follow-up appointment in storage.
     */
    public function update(Request $request, FollowUpAppointment $followUpAppointment)
    {
        if (!in_array($followUpAppointment->status, ['scheduled', 'confirmed'])) {
            return redirect()->route('doctor.follow-up-appointments.show', [$followUpAppointment])
                            ->with('error', 'Cannot update an appointment that has already been completed or cancelled.');
        }
        
        $validated = $request->validate([
            'doctor_id' => 'nullable|exists:users,id',
            'specialty' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'reason_for_visit' => 'required|string',
            'appointment_time' => 'required|date|after:now',
            'estimated_duration_minutes' => 'required|integer|min:15|max:240',
            'is_urgent' => 'boolean',
            'special_instructions' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        
        $followUpAppointment->update($validated);
        
        return redirect()->route('doctor.follow-up-appointments.show', [$followUpAppointment])
                        ->with('success', 'Follow-up appointment updated successfully.');
    }

    /**
     * Confirm the specified follow-up appointment.
     */
    public function confirm(FollowUpAppointment $followUpAppointment)
    {
        if ($followUpAppointment->status === 'scheduled') {
            $followUpAppointment->status = 'confirmed';
            $followUpAppointment->save();
            
            return redirect()->route('doctor.follow-up-appointments.show', [$followUpAppointment])
                            ->with('success', 'Appointment confirmed successfully.');
        }
        
        return redirect()->route('doctor.follow-up-appointments.show', [$followUpAppointment])
                        ->with('error', 'Can only confirm appointments that are in scheduled status.');
    }

    /**
     * Complete the specified follow-up appointment.
     */
    public function complete(FollowUpAppointment $followUpAppointment)
    {
        if (in_array($followUpAppointment->status, ['scheduled', 'confirmed'])) {
            $followUpAppointment->status = 'completed';
            $followUpAppointment->save();
            
            return redirect()->route('doctor.follow-up-appointments.show', [$followUpAppointment])
                            ->with('success', 'Appointment marked as completed.');
        }
        
        return redirect()->route('doctor.follow-up-appointments.show', [$followUpAppointment])
                        ->with('error', 'Can only complete appointments that are scheduled or confirmed.');
    }

    /**
     * Mark the specified follow-up appointment as no-show.
     */
    public function noShow(FollowUpAppointment $followUpAppointment)
    {
        if (in_array($followUpAppointment->status, ['scheduled', 'confirmed'])) {
            $followUpAppointment->status = 'no_show';
            $followUpAppointment->save();
            
            return redirect()->route('doctor.follow-up-appointments.show', [$followUpAppointment])
                            ->with('success', 'Appointment marked as no-show.');
        }
        
        return redirect()->route('doctor.follow-up-appointments.show', [$followUpAppointment])
                        ->with('error', 'Can only mark scheduled or confirmed appointments as no-show.');
    }

    /**
     * Cancel the specified follow-up appointment.
     */
    public function cancel(FollowUpAppointment $followUpAppointment)
    {
        if (in_array($followUpAppointment->status, ['scheduled', 'confirmed'])) {
            $followUpAppointment->status = 'cancelled';
            $followUpAppointment->save();
            
            return redirect()->route('doctor.follow-up-appointments.index')
                            ->with('success', 'Appointment cancelled successfully.');
        }
        
        return redirect()->route('doctor.follow-up-appointments.show', [$followUpAppointment])
                        ->with('error', 'Can only cancel scheduled or confirmed appointments.');
    }

    /**
     * Print the specified follow-up appointment details.
     */
    public function print(FollowUpAppointment $followUpAppointment)
    {
        $followUpAppointment->load(['patient', 'visit', 'doctor', 'scheduledBy']);
        
        return view('doctor.follow-up-appointments.print', compact('followUpAppointment'));
    }
}
