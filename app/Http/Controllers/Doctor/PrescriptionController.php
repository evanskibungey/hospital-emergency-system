<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Medication;
use App\Models\Prescription;
use App\Models\Treatment;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrescriptionController extends Controller
{
    /**
     * Display a listing of the prescriptions.
     */
    public function index()
    {
        $activePrescriptions = Prescription::with(['patient', 'visit', 'prescribedBy'])
                                          ->active()
                                          ->latest('start_date')
                                          ->paginate(10, ['*'], 'active');
        
        $completedPrescriptions = Prescription::with(['patient', 'visit', 'prescribedBy'])
                                             ->withStatus('completed')
                                             ->latest('end_date')
                                             ->paginate(10, ['*'], 'completed');
        
        return view('doctor.prescriptions.index', compact('activePrescriptions', 'completedPrescriptions'));
    }

    /**
     * Show the form for creating a new prescription.
     */
    public function create(Visit $visit)
    {
        $treatments = Treatment::where('visit_id', $visit->id)
                              ->where('status', '!=', 'discontinued')
                              ->get();
        
        $medications = Medication::orderBy('name')->get();
        
        return view('doctor.prescriptions.create', compact('visit', 'treatments', 'medications'));
    }

    /**
     * Store a newly created prescription in storage.
     */
    public function store(Request $request, Visit $visit)
    {
        $validated = $request->validate([
            'treatment_id' => 'nullable|exists:treatments,id',
            'medication_id' => 'nullable|exists:medications,id',
            'medication_name' => 'required|string|max:255',
            'dosage' => 'required|string|max:255',
            'frequency' => 'required|string|max:255',
            'route' => 'required|string|max:255',
            'instructions' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'refills' => 'required|integer|min:0',
            'is_controlled_substance' => 'boolean',
            'notes' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);
        
        $prescription = new Prescription($validated);
        $prescription->visit_id = $visit->id;
        $prescription->patient_id = $visit->patient_id;
        $prescription->prescribed_by = Auth::id();
        $prescription->status = 'active';
        $prescription->save();
        
        return redirect()->route('doctor.prescriptions.show', [$prescription])
                        ->with('success', 'Prescription created successfully.');
    }

    /**
     * Display the specified prescription.
     */
    public function show(Prescription $prescription)
    {
        $prescription->load(['visit', 'patient', 'prescribedBy', 'treatment', 'medication']);
        
        return view('doctor.prescriptions.show', compact('prescription'));
    }

    /**
     * Show the form for editing the specified prescription.
     */
    public function edit(Prescription $prescription)
    {
        if ($prescription->status !== 'active') {
            return redirect()->route('doctor.prescriptions.show', [$prescription])
                            ->with('error', 'Cannot edit a prescription that is not active.');
        }
        
        $visit = $prescription->visit;
        $treatments = Treatment::where('visit_id', $visit->id)
                              ->where('status', '!=', 'discontinued')
                              ->get();
        
        $medications = Medication::orderBy('name')->get();
        
        return view('doctor.prescriptions.edit', compact('prescription', 'visit', 'treatments', 'medications'));
    }

    /**
     * Update the specified prescription in storage.
     */
    public function update(Request $request, Prescription $prescription)
    {
        if ($prescription->status !== 'active') {
            return redirect()->route('doctor.prescriptions.show', [$prescription])
                            ->with('error', 'Cannot update a prescription that is not active.');
        }
        
        $validated = $request->validate([
            'treatment_id' => 'nullable|exists:treatments,id',
            'medication_id' => 'nullable|exists:medications,id',
            'medication_name' => 'required|string|max:255',
            'dosage' => 'required|string|max:255',
            'frequency' => 'required|string|max:255',
            'route' => 'required|string|max:255',
            'instructions' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'refills' => 'required|integer|min:0',
            'is_controlled_substance' => 'boolean',
            'notes' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);
        
        $prescription->update($validated);
        
        return redirect()->route('doctor.prescriptions.show', [$prescription])
                        ->with('success', 'Prescription updated successfully.');
    }

    /**
     * Complete the specified prescription.
     */
    public function complete(Prescription $prescription)
    {
        if ($prescription->status === 'active') {
            $prescription->status = 'completed';
            $prescription->end_date = now();
            $prescription->save();
            
            return redirect()->route('doctor.prescriptions.show', [$prescription])
                            ->with('success', 'Prescription marked as completed.');
        }
        
        return redirect()->route('doctor.prescriptions.show', [$prescription])
                        ->with('error', 'Cannot complete a prescription that is not active.');
    }

    /**
     * Cancel the specified prescription.
     */
    public function cancel(Prescription $prescription)
    {
        if ($prescription->status === 'active') {
            $prescription->status = 'cancelled';
            $prescription->end_date = now();
            $prescription->save();
            
            return redirect()->route('doctor.prescriptions.show', [$prescription])
                            ->with('success', 'Prescription cancelled successfully.');
        }
        
        return redirect()->route('doctor.prescriptions.show', [$prescription])
                        ->with('error', 'Cannot cancel a prescription that is not active.');
    }

    /**
     * Put the specified prescription on hold.
     */
    public function hold(Prescription $prescription)
    {
        if ($prescription->status === 'active') {
            $prescription->status = 'on_hold';
            $prescription->save();
            
            return redirect()->route('doctor.prescriptions.show', [$prescription])
                            ->with('success', 'Prescription placed on hold.');
        }
        
        return redirect()->route('doctor.prescriptions.show', [$prescription])
                        ->with('error', 'Cannot place a prescription on hold that is not active.');
    }

    /**
     * Reactivate a prescription that was on hold.
     */
    public function reactivate(Prescription $prescription)
    {
        if ($prescription->status === 'on_hold') {
            $prescription->status = 'active';
            $prescription->save();
            
            return redirect()->route('doctor.prescriptions.show', [$prescription])
                            ->with('success', 'Prescription reactivated successfully.');
        }
        
        return redirect()->route('doctor.prescriptions.show', [$prescription])
                        ->with('error', 'Can only reactivate prescriptions that are on hold.');
    }
}
