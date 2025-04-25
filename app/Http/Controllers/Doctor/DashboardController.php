<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\ConsultationRequest;
use App\Models\DoctorTask;
use App\Models\Visit;
use App\Models\Treatment;
use App\Models\LabOrder;
use App\Models\ImagingOrder;
use App\Models\Prescription;
use App\Models\FollowUpAppointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the doctor dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $doctor = Auth::user();
        
        // Get assigned patients
        $assignedVisits = Visit::assignedToDoctor($doctor->id)
                               ->active()
                               ->with(['patient', 'bed', 'latestVitalSigns'])
                               ->orderBy('is_critical', 'desc')
                               ->orderBy('priority', 'desc')
                               ->get();
        
        // Get critical patients
        $criticalVisits = Visit::critical()
                              ->active()
                              ->with(['patient', 'bed', 'latestVitalSigns'])
                              ->get();
        
        // Get pending consultation requests
        $pendingConsultations = ConsultationRequest::where('doctor_id', $doctor->id)
                                                  ->pending()
                                                  ->with(['visit.patient', 'requester'])
                                                  ->get();
        
        // Get tasks that need attention
        $pendingTasks = DoctorTask::where('doctor_id', $doctor->id)
                                  ->where('status', '!=', 'completed')
                                  ->with('visit.patient')
                                  ->orderBy('priority', 'desc')
                                  ->orderBy('due_at', 'asc')
                                  ->get();
        
        // Get overdue tasks
        $overdueTasks = DoctorTask::where('doctor_id', $doctor->id)
                                  ->overdue()
                                  ->with('visit.patient')
                                  ->get();
        
        // Get active treatments created by this doctor
        $activeTreatments = Treatment::where('created_by', $doctor->id)
                                    ->active()
                                    ->with(['visit.patient'])
                                    ->get();
        
        // Get pending lab orders
        $pendingLabOrders = LabOrder::where('ordered_by', $doctor->id)
                                    ->pending()
                                    ->with(['visit.patient'])
                                    ->orderBy('is_stat', 'desc')
                                    ->orderBy('ordered_at', 'asc')
                                    ->get();
        
        // Get pending imaging orders
        $pendingImagingOrders = ImagingOrder::where('ordered_by', $doctor->id)
                                            ->pending()
                                            ->with(['visit.patient'])
                                            ->orderBy('is_stat', 'desc')
                                            ->orderBy('ordered_at', 'asc')
                                            ->get();
        
        // Get recently completed lab and imaging orders
        $recentLabResults = LabOrder::where('ordered_by', $doctor->id)
                                    ->where('status', 'completed')
                                    ->whereNotNull('result_summary')
                                    ->with(['visit.patient'])
                                    ->orderBy('completed_at', 'desc')
                                    ->limit(5)
                                    ->get();
        
        $recentImagingResults = ImagingOrder::where('ordered_by', $doctor->id)
                                            ->where('status', 'completed')
                                            ->whereNotNull('findings')
                                            ->with(['visit.patient'])
                                            ->orderBy('completed_at', 'desc')
                                            ->limit(5)
                                            ->get();
        
        // Get upcoming follow-up appointments
        $upcomingAppointments = FollowUpAppointment::where('doctor_id', $doctor->id)
                                                    ->upcoming()
                                                    ->whereDate('appointment_time', '>=', now())
                                                    ->whereDate('appointment_time', '<=', now()->addDays(3))
                                                    ->with(['patient'])
                                                    ->orderBy('appointment_time')
                                                    ->get();
        
        // Get patients that may be ready for discharge
        $potentialDischarges = Visit::assignedToDoctor($doctor->id)
                                     ->inProgress()
                                     ->with(['patient', 'treatments'])
                                     ->get()
                                     ->filter(function($visit) {
                                         return $visit->isReadyForDischarge();
                                     });
        
        return view('doctor.dashboard', compact(
            'assignedVisits',
            'criticalVisits',
            'pendingConsultations',
            'pendingTasks',
            'overdueTasks',
            'activeTreatments',
            'pendingLabOrders',
            'pendingImagingOrders',
            'recentLabResults',
            'recentImagingResults',
            'upcomingAppointments',
            'potentialDischarges'
        ));
    }

    /**
     * Update the doctor's on-call status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateOnCallStatus(Request $request)
    {
        $request->validate([
            'is_on_call' => 'required|boolean',
            'on_call_until' => 'nullable|date|after:now',
        ]);

        $user = Auth::user();
        $user->is_on_call = $request->is_on_call;
        $user->on_call_until = $request->on_call_until;
        $user->save();

        return redirect()->route('doctor.dashboard')
                         ->with('success', 'On-call status updated successfully');
    }

    /**
     * Mark a visit as critical or non-critical.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Visit  $visit
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleCritical(Request $request, Visit $visit)
    {
        $visit->is_critical = !$visit->is_critical;
        $visit->save();

        $status = $visit->is_critical ? 'critical' : 'non-critical';
        
        return redirect()->back()
                         ->with('success', "Visit marked as {$status} successfully");
    }

    /**
     * Assign a visit to the logged-in doctor.
     *
     * @param  \App\Models\Visit  $visit
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assignVisit(Visit $visit)
    {
        $doctor = Auth::user();
        
        $visit->doctor_id = $doctor->id;
        $visit->status = 'in_progress';
        $visit->save();
        
        return redirect()->back()
                         ->with('success', 'Patient assigned to you successfully');
    }

    /**
     * Release a visit assignment from the logged-in doctor.
     *
     * @param  \App\Models\Visit  $visit
     * @return \Illuminate\Http\RedirectResponse
     */
    public function releaseVisit(Visit $visit)
    {
        $doctor = Auth::user();
        
        // Check if the visit is assigned to the current doctor
        if ($visit->doctor_id !== $doctor->id) {
            return redirect()->back()
                             ->with('error', 'You cannot release a patient that is not assigned to you');
        }
        
        $visit->doctor_id = null;
        $visit->save();
        
        return redirect()->back()
                         ->with('success', 'Patient released successfully');
    }

    /**
     * Show details for a specific visit.
     *
     * @param  \App\Models\Visit  $visit
     * @return \Illuminate\View\View
     */
    public function showVisit(Visit $visit)
    {
        $visit->load([
            'patient', 
            'assignedTo', 
            'doctor',
            'bed',
            'vitalSigns' => function($query) {
                $query->latest()->take(5);
            },
            'treatments' => function($query) {
                $query->orderBy('status')->orderBy('created_at', 'desc');
            },
            'medicalNotes' => function($query) {
                $query->latest()->take(10);
            },
            'labOrders' => function($query) {
                $query->latest('ordered_at')->take(10);
            },
            'imagingOrders' => function($query) {
                $query->latest('ordered_at')->take(10);
            },
            'prescriptions' => function($query) {
                $query->orderBy('status')->orderBy('created_at', 'desc');
            },
            'consultationRequests' => function($query) {
                $query->latest('created_at');
            },
            'doctorTasks' => function($query) {
                $query->whereNotIn('status', ['completed', 'cancelled'])
                      ->orderBy('priority', 'desc')
                      ->orderBy('due_at', 'asc');
            }
        ]);
        
        // Get upcoming medications for the visit
        $dueAndOverdueMedications = $visit->dueAndOverdueMedications()
                                          ->with(['medication'])
                                          ->get();
        
        return view('doctor.visits.show', compact('visit', 'dueAndOverdueMedications'));
    }

    /**
     * Show medical summary for a specific patient.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\View\View
     */
    public function patientSummary($patientId)
    {
        $patient = \App\Models\Patient::with([
            'visits' => function($query) {
                $query->orderBy('check_in_time', 'desc');
            },
            'activePrescriptions',
            'upcomingAppointments'
        ])->findOrFail($patientId);
        
        // Get recent vital signs
        $recentVitalSigns = $patient->vitalSigns()
                                    ->latest()
                                    ->limit(5)
                                    ->get();
        
        // Get medication history (all prescriptions)
        $medicationHistory = $patient->prescriptions()
                                    ->with(['prescribedBy'])
                                    ->orderBy('created_at', 'desc')
                                    ->get();
        
        // Get recent treatments
        $recentTreatments = $patient->treatments()
                                    ->with(['creator'])
                                    ->orderBy('created_at', 'desc')
                                    ->limit(10)
                                    ->get();
        
        // Get recent lab and imaging orders
        $recentLabOrders = $patient->labOrders()
                                    ->with(['orderedBy'])
                                    ->orderBy('ordered_at', 'desc')
                                    ->limit(10)
                                    ->get();
        
        $recentImagingOrders = $patient->imagingOrders()
                                        ->with(['orderedBy'])
                                        ->orderBy('ordered_at', 'desc')
                                        ->limit(10)
                                        ->get();
        
        return view('doctor.patients.summary', compact(
            'patient',
            'recentVitalSigns',
            'medicationHistory',
            'recentTreatments',
            'recentLabOrders',
            'recentImagingOrders'
        ));
    }
}
