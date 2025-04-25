<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use App\Models\Medication;
use App\Models\MedicationSchedule;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MedicationScheduleController extends Controller
{
    /**
     * Display a listing of medication schedules for a visit.
     */
    public function index(Visit $visit)
    {
        $scheduledMedications = $visit->medicationSchedules()
            ->with('medication')
            ->where('status', 'scheduled')
            ->orderBy('scheduled_time')
            ->get();
            
        $administeredMedications = $visit->medicationSchedules()
            ->with('medication', 'administrations')
            ->where('status', 'administered')
            ->orderBy('scheduled_time', 'desc')
            ->get();
            
        $missedMedications = $visit->medicationSchedules()
            ->with('medication')
            ->whereIn('status', ['missed', 'cancelled'])
            ->orderBy('scheduled_time', 'desc')
            ->get();
            
        return view('nurse.medication-schedules.index', compact(
            'visit', 
            'scheduledMedications', 
            'administeredMedications', 
            'missedMedications'
        ));
    }

    /**
     * Show the form for creating a new medication schedule.
     */
    public function create(Visit $visit)
    {
        $medications = Medication::orderBy('name')->get();
        
        // Initialize with some common frequencies
        $frequencies = [
            'once' => 'Once',
            'daily' => 'Daily',
            'twice_daily' => 'Twice Daily',
            'three_times_daily' => 'Three Times Daily',
            'four_times_daily' => 'Four Times Daily',
            'as_needed' => 'As Needed (PRN)',
            'other' => 'Other (Specify in Notes)'
        ];
        
        return view('nurse.medication-schedules.create', compact('visit', 'medications', 'frequencies'));
    }

    /**
     * Store a newly created medication schedule in storage.
     */
    public function store(Request $request, Visit $visit)
    {
        $validated = $request->validate([
            'medication_id' => 'required|exists:medications,id',
            'dosage' => 'required|string|max:50',
            'frequency' => 'required|string|in:once,daily,twice_daily,three_times_daily,four_times_daily,as_needed,other',
            'frequency_notes' => 'nullable|string',
            'scheduled_time' => 'required|date',
            'status' => 'required|in:scheduled,administered,missed,cancelled',
            'notes' => 'nullable|string',
        ]);

        // Add created_by and visit_id
        $validated['created_by'] = Auth::id();
        $validated['visit_id'] = $visit->id;

        // Create the schedule
        $medicationSchedule = MedicationSchedule::create($validated);

        // Handle recurring schedules if needed
        if (in_array($request->frequency, ['daily', 'twice_daily', 'three_times_daily', 'four_times_daily'])) {
            $this->createRecurringSchedules($medicationSchedule, $request->frequency);
        }

        return redirect()->route('nurse.medication-schedules.index', $visit->id)
            ->with('success', 'Medication scheduled successfully');
    }

    /**
     * Display the specified medication schedule.
     */
    public function show(Visit $visit, MedicationSchedule $medicationSchedule)
    {
        // Ensure the medication schedule belongs to the visit
        if ($medicationSchedule->visit_id !== $visit->id) {
            abort(404);
        }

        $medicationSchedule->load('medication', 'administrations');
        
        return view('nurse.medication-schedules.show', compact('visit', 'medicationSchedule'));
    }

    /**
     * Show the form for editing the specified medication schedule.
     */
    public function edit(Visit $visit, MedicationSchedule $medicationSchedule)
    {
        // Ensure the medication schedule belongs to the visit
        if ($medicationSchedule->visit_id !== $visit->id) {
            abort(404);
        }

        $medications = Medication::orderBy('name')->get();
        
        $frequencies = [
            'once' => 'Once',
            'daily' => 'Daily',
            'twice_daily' => 'Twice Daily',
            'three_times_daily' => 'Three Times Daily',
            'four_times_daily' => 'Four Times Daily',
            'as_needed' => 'As Needed (PRN)',
            'other' => 'Other (Specify in Notes)'
        ];
        
        return view('nurse.medication-schedules.edit', compact('visit', 'medicationSchedule', 'medications', 'frequencies'));
    }

    /**
     * Update the specified medication schedule in storage.
     */
    public function update(Request $request, Visit $visit, MedicationSchedule $medicationSchedule)
    {
        // Ensure the medication schedule belongs to the visit
        if ($medicationSchedule->visit_id !== $visit->id) {
            abort(404);
        }

        $validated = $request->validate([
            'medication_id' => 'required|exists:medications,id',
            'dosage' => 'required|string|max:50',
            'frequency' => 'required|string|in:once,daily,twice_daily,three_times_daily,four_times_daily,as_needed,other',
            'frequency_notes' => 'nullable|string',
            'scheduled_time' => 'required|date',
            'status' => 'required|in:scheduled,administered,missed,cancelled',
            'notes' => 'nullable|string',
        ]);

        // Update the schedule
        $medicationSchedule->update($validated);

        return redirect()->route('nurse.medication-schedules.show', [$visit->id, $medicationSchedule->id])
            ->with('success', 'Medication schedule updated successfully');
    }

    /**
     * Cancel the specified medication schedule.
     */
    public function cancel(Request $request, Visit $visit, MedicationSchedule $medicationSchedule)
    {
        // Ensure the medication schedule belongs to the visit
        if ($medicationSchedule->visit_id !== $visit->id) {
            abort(404);
        }

        $validated = $request->validate([
            'notes' => 'nullable|string',
        ]);

        // Update the schedule status to cancelled
        $medicationSchedule->status = 'cancelled';
        $medicationSchedule->notes = $validated['notes'] ?? $medicationSchedule->notes;
        $medicationSchedule->save();

        return redirect()->route('nurse.medication-schedules.index', $visit->id)
            ->with('success', 'Medication schedule cancelled successfully');
    }

    /**
     * Create recurring schedules based on frequency.
     */
    private function createRecurringSchedules(MedicationSchedule $initialSchedule, $frequency)
    {
        $recurringCount = 0;
        $intervalHours = 0;
        
        // Set number of additional doses and hours between doses based on frequency
        switch ($frequency) {
            case 'daily':
                $recurringCount = 2; // Schedule for 3 days total
                $intervalHours = 24;
                break;
            case 'twice_daily':
                $recurringCount = 5; // Schedule for 3 days total (6 doses)
                $intervalHours = 12;
                break;
            case 'three_times_daily':
                $recurringCount = 8; // Schedule for 3 days total (9 doses)
                $intervalHours = 8;
                break;
            case 'four_times_daily':
                $recurringCount = 11; // Schedule for 3 days total (12 doses)
                $intervalHours = 6;
                break;
        }
        
        // Create recurring schedules
        $scheduledTime = Carbon::parse($initialSchedule->scheduled_time);
        
        for ($i = 1; $i <= $recurringCount; $i++) {
            $scheduledTime = $scheduledTime->addHours($intervalHours);
            
            MedicationSchedule::create([
                'visit_id' => $initialSchedule->visit_id,
                'medication_id' => $initialSchedule->medication_id,
                'dosage' => $initialSchedule->dosage,
                'frequency' => $initialSchedule->frequency,
                'frequency_notes' => $initialSchedule->frequency_notes,
                'scheduled_time' => $scheduledTime,
                'status' => 'scheduled',
                'created_by' => $initialSchedule->created_by,
                'notes' => $initialSchedule->notes,
            ]);
        }
    }
}