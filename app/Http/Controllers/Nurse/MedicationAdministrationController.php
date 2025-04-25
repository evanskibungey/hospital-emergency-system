<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use App\Models\MedicationAdministration;
use App\Models\MedicationSchedule;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MedicationAdministrationController extends Controller
{
    /**
     * Show the form for creating a new administration record.
     */
    public function create(Visit $visit, MedicationSchedule $medicationSchedule)
    {
        // Ensure the medication schedule belongs to the visit
        if ($medicationSchedule->visit_id !== $visit->id) {
            abort(404);
        }

        $medicationSchedule->load('medication');
        
        $statuses = [
            'completed' => 'Completed - Full dose given',
            'partial' => 'Partial - Only part of dose given',
            'refused' => 'Refused - Patient refused medication',
            'held' => 'Held - Medication held for medical reason',
            'error' => 'Error - Medication error occurred'
        ];
        
        return view('nurse.medication-administrations.create', compact('visit', 'medicationSchedule', 'statuses'));
    }

    /**
     * Store a newly created administration record in storage.
     */
    public function store(Request $request, Visit $visit, MedicationSchedule $medicationSchedule)
    {
        // Ensure the medication schedule belongs to the visit
        if ($medicationSchedule->visit_id !== $visit->id) {
            abort(404);
        }

        $validated = $request->validate([
            'administered_at' => 'required|date',
            'actual_dosage' => 'nullable|string|max:50',
            'status' => 'required|in:completed,partial,refused,held,error',
            'notes' => 'nullable|string',
        ]);

        // Add administered_by (current user) and medication_schedule_id
        $validated['administered_by'] = Auth::id();
        $validated['medication_schedule_id'] = $medicationSchedule->id;
        
        // If actual_dosage is not provided, use the scheduled dosage
        if (empty($validated['actual_dosage'])) {
            $validated['actual_dosage'] = $medicationSchedule->dosage;
        }

        // Create the administration record
        $medicationAdministration = MedicationAdministration::create($validated);

        // Update the medication schedule status to administered
        $medicationSchedule->status = 'administered';
        $medicationSchedule->save();

        return redirect()->route('nurse.medication-schedules.index', $visit->id)
            ->with('success', 'Medication administration recorded successfully');
    }

    /**
     * Display the specified administration record.
     */
    public function show(Visit $visit, MedicationSchedule $medicationSchedule, MedicationAdministration $medicationAdministration)
    {
        // Ensure the medication schedule belongs to the visit
        if ($medicationSchedule->visit_id !== $visit->id) {
            abort(404);
        }

        // Ensure the administration belongs to the schedule
        if ($medicationAdministration->medication_schedule_id !== $medicationSchedule->id) {
            abort(404);
        }

        $medicationAdministration->load('medicationSchedule.medication', 'administeredBy');
        
        return view('nurse.medication-administrations.show', compact('visit', 'medicationSchedule', 'medicationAdministration'));
    }

    /**
     * Show a list of all medications due for administration.
     */
    public function dueMedications()
    {
        // Get all visits with due medications
        $visitsWithDueMeds = Visit::with(['patient', 'medicationSchedules' => function ($query) {
            $query->with('medication')
                  ->where('status', 'scheduled')
                  ->where('scheduled_time', '<=', now())
                  ->orderBy('scheduled_time');
        }])
        ->whereHas('medicationSchedules', function ($query) {
            $query->where('status', 'scheduled')
                  ->where('scheduled_time', '<=', now());
        })
        ->whereIn('status', ['waiting', 'in_progress'])
        ->get();

        return view('nurse.medication-administrations.due', compact('visitsWithDueMeds'));
    }

    /**
     * Show a list of all medication administrations for a visit.
     */
    public function visitAdministrations(Visit $visit)
    {
        $administrations = $visit->medicationAdministrations()
            ->with('medicationSchedule.medication', 'administeredBy')
            ->latest('administered_at')
            ->paginate(15);
            
        return view('nurse.medication-administrations.visit-history', compact('visit', 'administrations'));
    }

    /**
     * Show a list of all medication administrations for the last 24 hours.
     */
    public function recentAdministrations()
    {
        $administrations = MedicationAdministration::with('medicationSchedule.medication', 'medicationSchedule.visit.patient', 'administeredBy')
            ->where('administered_at', '>=', Carbon::now()->subHours(24))
            ->latest('administered_at')
            ->paginate(20);
            
        return view('nurse.medication-administrations.recent', compact('administrations'));
    }
}