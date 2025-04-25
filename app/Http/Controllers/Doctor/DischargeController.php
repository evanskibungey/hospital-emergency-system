<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Discharge;
use App\Models\Visit;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DischargeController extends Controller
{
    /**
     * Display a listing of the discharges.
     */
    public function index()
    {
        $todayDischarges = Discharge::with(['patient', 'dischargedBy'])
                                    ->today()
                                    ->latest('discharged_at')
                                    ->paginate(10, ['*'], 'today');
        
        $recentDischarges = Discharge::with(['patient', 'dischargedBy'])
                                    ->where('discharged_at', '<', now()->startOfDay())
                                    ->latest('discharged_at')
                                    ->paginate(10, ['*'], 'recent');
        
        return view('doctor.discharges.index', compact('todayDischarges', 'recentDischarges'));
    }

    /**
     * Show the form for creating a new discharge.
     */
    public function create(Visit $visit)
    {
        // Check if the visit already has a discharge
        if ($visit->discharged_at) {
            return redirect()->route('doctor.visits.show', [$visit])
                            ->with('error', 'This visit has already been discharged.');
        }
        
        // Get active treatments for the visit
        $treatments = $visit->treatments()
                            ->whereIn('status', ['active', 'completed'])
                            ->get();
        
        // Get active prescriptions for the visit
        $prescriptions = $visit->prescriptions()
                              ->whereIn('status', ['active', 'on_hold'])
                              ->get();
        
        return view('doctor.discharges.create', compact('visit', 'treatments', 'prescriptions'));
    }

    /**
     * Store a newly created discharge in storage.
     */
    public function store(Request $request, Visit $visit)
    {
        // Check if the visit already has a discharge
        if ($visit->discharged_at) {
            return redirect()->route('doctor.visits.show', [$visit])
                            ->with('error', 'This visit has already been discharged.');
        }
        
        $validated = $request->validate([
            'discharge_diagnosis' => 'required|string',
            'discharge_summary' => 'required|string',
            'discharge_instructions' => 'required|string',
            'medications_at_discharge' => 'nullable|string',
            'activity_restrictions' => 'nullable|string',
            'diet_instructions' => 'nullable|string',
            'follow_up_instructions' => 'nullable|string',
            'discharge_disposition' => 'required|in:home,home_with_services,transfer_to_facility,left_against_medical_advice,other',
            'destination_facility' => 'nullable|required_if:discharge_disposition,transfer_to_facility|string|max:255',
            'instructions_provided' => 'boolean',
            'notes' => 'nullable|string',
        ]);
        
        try {
            DB::transaction(function () use ($visit, $validated) {
                // Create the discharge record
                $discharge = new Discharge($validated);
                $discharge->visit_id = $visit->id;
                $discharge->patient_id = $visit->patient_id;
                $discharge->discharged_by = Auth::id();
                $discharge->discharged_at = now();
                $discharge->save();
                
                // Update the visit status
                $visit->discharged_at = now();
                $visit->status = 'discharged';
                $visit->save();
                
                // Complete any active treatments for this visit
                $visit->treatments()
                      ->where('status', 'active')
                      ->update([
                          'status' => 'completed',
                          'completed_at' => now(),
                          'updated_by' => Auth::id()
                      ]);
            });
            
            return redirect()->route('doctor.discharges.show', [Discharge::where('visit_id', $visit->id)->first()])
                            ->with('success', 'Patient discharged successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'An error occurred during discharge: ' . $e->getMessage())
                            ->withInput();
        }
    }

    /**
     * Display the specified discharge.
     */
    public function show(Discharge $discharge)
    {
        $discharge->load(['visit', 'patient', 'dischargedBy', 'followUpAppointments']);
        
        // Get prescriptions active at the time of discharge
        $prescriptions = Prescription::where('visit_id', $discharge->visit_id)
                                    ->where('status', 'active')
                                    ->where('start_date', '<=', $discharge->discharged_at)
                                    ->where(function($query) use ($discharge) {
                                        $query->whereNull('end_date')
                                              ->orWhere('end_date', '>=', $discharge->discharged_at);
                                    })
                                    ->get();
        
        return view('doctor.discharges.show', compact('discharge', 'prescriptions'));
    }

    /**
     * Show the form for editing the specified discharge.
     */
    public function edit(Discharge $discharge)
    {
        // Only allow editing discharges that happened today
        if ($discharge->discharged_at->startOfDay()->ne(now()->startOfDay())) {
            return redirect()->route('doctor.discharges.show', [$discharge])
                            ->with('error', 'Cannot edit discharges from previous days.');
        }
        
        $visit = $discharge->visit;
        
        return view('doctor.discharges.edit', compact('discharge', 'visit'));
    }

    /**
     * Update the specified discharge in storage.
     */
    public function update(Request $request, Discharge $discharge)
    {
        // Only allow editing discharges that happened today
        if ($discharge->discharged_at->startOfDay()->ne(now()->startOfDay())) {
            return redirect()->route('doctor.discharges.show', [$discharge])
                            ->with('error', 'Cannot edit discharges from previous days.');
        }
        
        $validated = $request->validate([
            'discharge_diagnosis' => 'required|string',
            'discharge_summary' => 'required|string',
            'discharge_instructions' => 'required|string',
            'medications_at_discharge' => 'nullable|string',
            'activity_restrictions' => 'nullable|string',
            'diet_instructions' => 'nullable|string',
            'follow_up_instructions' => 'nullable|string',
            'discharge_disposition' => 'required|in:home,home_with_services,transfer_to_facility,left_against_medical_advice,other',
            'destination_facility' => 'nullable|required_if:discharge_disposition,transfer_to_facility|string|max:255',
            'instructions_provided' => 'boolean',
            'notes' => 'nullable|string',
        ]);
        
        $discharge->update($validated);
        
        return redirect()->route('doctor.discharges.show', [$discharge])
                        ->with('success', 'Discharge information updated successfully.');
    }

    /**
     * Print the discharge instructions.
     */
    public function printInstructions(Discharge $discharge)
    {
        $discharge->load(['visit', 'patient', 'dischargedBy', 'followUpAppointments']);
        
        // Get prescriptions active at the time of discharge
        $prescriptions = Prescription::where('visit_id', $discharge->visit_id)
                                    ->where('status', 'active')
                                    ->where('start_date', '<=', $discharge->discharged_at)
                                    ->where(function($query) use ($discharge) {
                                        $query->whereNull('end_date')
                                              ->orWhere('end_date', '>=', $discharge->discharged_at);
                                    })
                                    ->get();
                                    
        // Mark that instructions were provided
        if (!$discharge->instructions_provided) {
            $discharge->instructions_provided = true;
            $discharge->save();
        }
        
        return view('doctor.discharges.print', compact('discharge', 'prescriptions'));
    }
}
