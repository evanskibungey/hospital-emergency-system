<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Treatment;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TreatmentController extends Controller
{
    /**
     * Display a listing of the treatments.
     */
    public function index()
    {
        $treatments = Treatment::with(['visit.patient', 'creator'])
                              ->where('created_by', Auth::id())
                              ->latest()
                              ->paginate(15);
        
        return view('doctor.treatments.index', compact('treatments'));
    }

    /**
     * Show the form for creating a new treatment.
     */
    public function create(Visit $visit)
    {
        return view('doctor.treatments.create', compact('visit'));
    }

    /**
     * Store a newly created treatment in storage.
     */
    public function store(Request $request, Visit $visit)
    {
        $validated = $request->validate([
            'diagnosis' => 'required|string',
            'treatment_plan' => 'required|string',
            'notes' => 'nullable|string',
            'status' => 'required|in:draft,active,completed,discontinued',
        ]);
        
        $treatment = new Treatment($validated);
        $treatment->visit_id = $visit->id;
        $treatment->created_by = Auth::id();
        
        if ($validated['status'] === 'active') {
            $treatment->started_at = now();
        }
        
        $treatment->save();
        
        return redirect()->route('doctor.treatments.show', [$treatment])
                         ->with('success', 'Treatment plan created successfully.');
    }

    /**
     * Display the specified treatment.
     */
    public function show(Treatment $treatment)
    {
        $treatment->load(['visit.patient', 'creator', 'medicalNotes', 'labOrders', 'imagingOrders', 'prescriptions']);
        
        return view('doctor.treatments.show', compact('treatment'));
    }

    /**
     * Show the form for editing the specified treatment.
     */
    public function edit(Treatment $treatment)
    {
        return view('doctor.treatments.edit', compact('treatment'));
    }

    /**
     * Update the specified treatment in storage.
     */
    public function update(Request $request, Treatment $treatment)
    {
        $validated = $request->validate([
            'diagnosis' => 'required|string',
            'treatment_plan' => 'required|string',
            'notes' => 'nullable|string',
            'status' => 'required|in:draft,active,completed,discontinued',
        ]);
        
        // Track status changes
        $oldStatus = $treatment->status;
        $newStatus = $validated['status'];
        
        if ($oldStatus !== 'active' && $newStatus === 'active') {
            $treatment->started_at = now();
        }
        
        if ($newStatus === 'completed' && !$treatment->completed_at) {
            $treatment->completed_at = now();
        }
        
        $treatment->fill($validated);
        $treatment->updated_by = Auth::id();
        $treatment->save();
        
        return redirect()->route('doctor.treatments.show', [$treatment])
                         ->with('success', 'Treatment plan updated successfully.');
    }

    /**
     * Complete the specified treatment.
     */
    public function complete(Treatment $treatment)
    {
        if ($treatment->status !== 'completed') {
            $treatment->status = 'completed';
            $treatment->completed_at = now();
            $treatment->updated_by = Auth::id();
            $treatment->save();
            
            return redirect()->route('doctor.treatments.show', [$treatment])
                             ->with('success', 'Treatment marked as completed.');
        }
        
        return redirect()->route('doctor.treatments.show', [$treatment])
                         ->with('info', 'Treatment is already marked as completed.');
    }

    /**
     * Discontinue the specified treatment.
     */
    public function discontinue(Treatment $treatment)
    {
        if (in_array($treatment->status, ['draft', 'active'])) {
            $treatment->status = 'discontinued';
            $treatment->updated_by = Auth::id();
            $treatment->save();
            
            return redirect()->route('doctor.treatments.show', [$treatment])
                             ->with('success', 'Treatment marked as discontinued.');
        }
        
        return redirect()->route('doctor.treatments.show', [$treatment])
                         ->with('info', 'Cannot discontinue a completed or already discontinued treatment.');
    }
}
