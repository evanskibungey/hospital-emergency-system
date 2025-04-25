<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use App\Models\Visit;
use App\Models\Patient;
use App\Models\VitalSign;
use App\Models\MedicationSchedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the nurse dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get both waiting and active patient visits
        $activeVisits = Visit::with(['patient', 'vitalSigns' => function ($query) {
            $query->latest()->limit(1);
        }])
        ->whereIn('status', ['waiting', 'in_progress']) // Use in_progress instead of active
        ->latest('check_in_time')
        ->limit(10)
        ->get();
            
        // Get recently registered patients
        $recentPatients = Patient::latest()
            ->limit(5)
            ->get();
            
        // Get patients needing vital signs (those without recent vitals)
        $needVitals = Visit::with('patient')
            ->whereIn('status', ['waiting', 'in_progress']) // Include waiting patients
            ->whereDoesntHave('vitalSigns', function ($query) {
                $query->where('created_at', '>=', Carbon::now()->subHours(4));
            })
            ->latest('check_in_time')
            ->limit(5)
            ->get();
        
        // Get medications due in the next hour
        $dueMedications = MedicationSchedule::with(['medication', 'visit.patient'])
            ->where('status', 'scheduled')
            ->where('scheduled_time', '<=', Carbon::now()->addHour())
            ->where('scheduled_time', '>=', Carbon::now()->subMinutes(30))
            ->orderBy('scheduled_time')
            ->limit(10)
            ->get();
            
        // Get overdue medications
        $overdueMedications = MedicationSchedule::with(['medication', 'visit.patient'])
            ->where('status', 'scheduled')
            ->where('scheduled_time', '<', Carbon::now()->subMinutes(30))
            ->orderBy('scheduled_time')
            ->limit(5)
            ->get();
            
        return view('nurse.dashboard', compact(
            'activeVisits', 
            'recentPatients',
            'needVitals',
            'dueMedications',
            'overdueMedications'
        ));
    }
    
    /**
     * Update a visit status to "active" when nurse begins care.
     *
     * @param  \App\Models\Visit  $visit
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assignVisit(Visit $visit)
    {
        // Update the visit status to in_progress (not active)
        $visit->status = 'in_progress'; // This value is in your ENUM
        $visit->assigned_to = Auth::id();
        $visit->save();
        
        return redirect()->route('nurse.vital-signs.create', $visit->id)
            ->with('success', 'Patient assigned to you. Please record vital signs.');
    }
    
    /**
     * Show form to assign a doctor to a visit.
     *
     * @param  \App\Models\Visit  $visit
     * @return \Illuminate\View\View
     */
    public function showAssignDoctorForm(Visit $visit)
    {
        // Get all doctors that are available (users with doctor role)
        $doctors = User::whereHas('roles', function($query) {
            $query->where('name', 'doctor');
        })->get();
        
        return view('nurse.visits.assign-doctor', compact('visit', 'doctors'));
    }
    
    /**
     * Assign a doctor to a visit.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Visit  $visit
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assignDoctor(Request $request, Visit $visit)
    {
        $request->validate([
            'doctor_id' => 'required|exists:users,id'
        ]);
        
        // Update the visit with the assigned doctor
        $visit->doctor_id = $request->doctor_id;
        $visit->save();
        
        return redirect()->route('nurse.dashboard')
            ->with('success', 'Doctor assigned successfully.');
    }
}