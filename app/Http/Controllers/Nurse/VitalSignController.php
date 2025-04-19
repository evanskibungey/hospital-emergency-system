<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use App\Models\VitalSign;
use App\Models\Visit;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VitalSignController extends Controller
{
    /**
     * Display a listing of vital signs for a specific visit.
     */
    public function index(Visit $visit)
    {
        $vitalSigns = $visit->vitalSigns()->latest()->get();
        
        return view('nurse.vital-signs.index', compact('visit', 'vitalSigns'));
    }

    /**
     * Show the form for creating a new vital sign record.
     */
    public function create(Visit $visit)
    {
        return view('nurse.vital-signs.create', compact('visit'));
    }

    /**
     * Store a newly created vital sign record in storage.
     */
    public function store(Request $request, Visit $visit)
    {
        $validated = $request->validate([
            'temperature' => 'nullable|numeric|between:30,45',
            'heart_rate' => 'nullable|integer|between:0,300',
            'respiratory_rate' => 'nullable|integer|between:0,100',
            'systolic_bp' => 'nullable|integer|between:0,300',
            'diastolic_bp' => 'nullable|integer|between:0,200',
            'oxygen_saturation' => 'nullable|integer|between:0,100',
            'notes' => 'nullable|string',
        ]);

        // Add the visit_id and user_id to the validated data
        $validated['visit_id'] = $visit->id;
        $validated['user_id'] = Auth::id();

        // Create the vital sign record
        $vitalSign = VitalSign::create($validated);

        return redirect()->route('nurse.vital-signs.show', [$visit->id, $vitalSign->id])
            ->with('success', 'Vital signs recorded successfully');
    }

    /**
     * Display the specified vital sign record.
     */
    public function show(Visit $visit, VitalSign $vitalSign)
    {
        // Ensure the vital sign belongs to the visit
        if ($vitalSign->visit_id !== $visit->id) {
            abort(404);
        }

        return view('nurse.vital-signs.show', compact('visit', 'vitalSign'));
    }

    /**
     * Show the form for editing the specified vital sign record.
     */
    public function edit(Visit $visit, VitalSign $vitalSign)
    {
        // Ensure the vital sign belongs to the visit
        if ($vitalSign->visit_id !== $visit->id) {
            abort(404);
        }

        return view('nurse.vital-signs.edit', compact('visit', 'vitalSign'));
    }

    /**
     * Update the specified vital sign record in storage.
     */
    public function update(Request $request, Visit $visit, VitalSign $vitalSign)
    {
        // Ensure the vital sign belongs to the visit
        if ($vitalSign->visit_id !== $visit->id) {
            abort(404);
        }

        $validated = $request->validate([
            'temperature' => 'nullable|numeric|between:30,45',
            'heart_rate' => 'nullable|integer|between:0,300',
            'respiratory_rate' => 'nullable|integer|between:0,100',
            'systolic_bp' => 'nullable|integer|between:0,300',
            'diastolic_bp' => 'nullable|integer|between:0,200',
            'oxygen_saturation' => 'nullable|integer|between:0,100',
            'notes' => 'nullable|string',
        ]);

        // Update the vital sign record
        $vitalSign->update($validated);

        return redirect()->route('nurse.vital-signs.show', [$visit->id, $vitalSign->id])
            ->with('success', 'Vital signs updated successfully');
    }

    /**
     * Search for a patient to record their vital signs.
     */
    public function searchPatient(Request $request)
    {
        $search = $request->get('search');
        $patients = [];

        if ($search) {
            $patients = Patient::where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('medical_record_number', 'like', "%{$search}%")
                ->get();
        }

        return view('nurse.vital-signs.search', compact('patients', 'search'));
    }

    /**
     * List active visits for a specific patient.
     */
    public function patientVisits(Patient $patient)
    {
        $activeVisits = $patient->visits()->where('status', 'active')->get();
        
        return view('nurse.vital-signs.patient-visits', compact('patient', 'activeVisits'));
    }
}