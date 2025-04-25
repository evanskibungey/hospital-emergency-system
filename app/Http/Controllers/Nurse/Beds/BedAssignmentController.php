<?php

namespace App\Http\Controllers\Nurse\Beds;

use App\Http\Controllers\Controller;
use App\Models\Bed;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BedAssignmentController extends Controller
{
    /**
     * Show the form for assigning a bed to a visit.
     */
    public function create(Visit $visit)
    {
        // Check if the visit already has a bed assigned
        if ($visit->bed_id) {
            return redirect()->route('nurse.bed-assignments.show', $visit)
                             ->with('warning', 'This patient already has a bed assigned.');
        }
        
        // Get all available beds
        $availableBeds = Bed::available()
                           ->orderBy('location')
                           ->orderBy('bed_number')
                           ->get();
                           
        // Group beds by location for easier selection
        $bedsByLocation = $availableBeds->groupBy('location');
        
        return view('nurse.beds.assignments.create', compact('visit', 'bedsByLocation'));
    }

    /**
     * Store a new bed assignment.
     */
    public function store(Request $request, Visit $visit)
    {
        // Check if the visit already has a bed assigned
        if ($visit->bed_id) {
            return redirect()->route('nurse.bed-assignments.show', $visit)
                             ->with('warning', 'This patient already has a bed assigned.');
        }
        
        $validated = $request->validate([
            'bed_id' => 'required|exists:beds,id',
            'notes' => 'nullable|string',
        ]);
        
        $bed = Bed::findOrFail($validated['bed_id']);
        
        // Check if the bed is still available
        if (!$bed->isAvailable()) {
            return back()->withErrors(['bed_id' => 'This bed is no longer available. Please select another bed.'])
                         ->withInput();
        }
        
        // Update the visit with the bed assignment
        $visit->update([
            'bed_id' => $bed->id,
            'bed_assigned_at' => now(),
            'notes' => $visit->notes . ($visit->notes ? "\n\n" : "") . 
                        "Bed assigned: " . $bed->full_identifier . 
                        " on " . now()->format('Y-m-d H:i') . 
                        " by " . Auth::user()->name .
                        ($validated['notes'] ? "\nAssignment notes: " . $validated['notes'] : "")
        ]);
        
        // Update the bed status to occupied
        $bed->update([
            'status' => 'occupied',
        ]);
        
        return redirect()->route('nurse.dashboard')
                         ->with('success', 'Bed assigned successfully.');
    }

    /**
     * Display the bed assignment for a visit.
     */
    public function show(Visit $visit)
    {
        if (!$visit->bed_id) {
            return redirect()->route('nurse.bed-assignments.create', $visit)
                             ->with('warning', 'No bed assigned to this visit yet.');
        }
        
        $visit->load('bed', 'patient');
        
        return view('nurse.beds.assignments.show', compact('visit'));
    }

    /**
     * Show the form for changing a visit's bed assignment.
     */
    public function edit(Visit $visit)
    {
        if (!$visit->bed_id) {
            return redirect()->route('nurse.bed-assignments.create', $visit)
                             ->with('warning', 'No bed assigned to this visit yet.');
        }
        
        $visit->load('bed', 'patient');
        
        // Get all available beds plus the current bed
        $availableBeds = Bed::where(function ($query) use ($visit) {
                               $query->where('status', 'available')
                                     ->orWhere('id', $visit->bed_id);
                           })
                           ->where('is_active', true)
                           ->orderBy('location')
                           ->orderBy('bed_number')
                           ->get();
                           
        // Group beds by location for easier selection
        $bedsByLocation = $availableBeds->groupBy('location');
        
        return view('nurse.beds.assignments.edit', compact('visit', 'bedsByLocation'));
    }

    /**
     * Update the visit's bed assignment.
     */
    public function update(Request $request, Visit $visit)
    {
        if (!$visit->bed_id) {
            return redirect()->route('nurse.bed-assignments.create', $visit)
                             ->with('warning', 'No bed assigned to this visit yet.');
        }
        
        $validated = $request->validate([
            'bed_id' => 'required|exists:beds,id',
            'notes' => 'nullable|string',
        ]);
        
        $newBed = Bed::findOrFail($validated['bed_id']);
        $oldBed = $visit->bed;
        
        // If selecting the same bed, just return
        if ($newBed->id === $oldBed->id) {
            return redirect()->route('nurse.bed-assignments.show', $visit)
                             ->with('info', 'No change in bed assignment.');
        }
        
        // Check if the new bed is available (unless it's the current bed)
        if ($newBed->id !== $oldBed->id && !$newBed->isAvailable()) {
            return back()->withErrors(['bed_id' => 'This bed is no longer available. Please select another bed.'])
                         ->withInput();
        }
        
        // Update the old bed status to available
        $oldBed->update([
            'status' => 'cleaning', // Mark for cleaning after patient transfer
        ]);
        
        // Update the visit with the new bed assignment
        $visit->update([
            'bed_id' => $newBed->id,
            'bed_assigned_at' => now(),
            'notes' => $visit->notes . "\n\n" . 
                        "Bed transfer: from " . $oldBed->full_identifier . 
                        " to " . $newBed->full_identifier . 
                        " on " . now()->format('Y-m-d H:i') . 
                        " by " . Auth::user()->name .
                        ($validated['notes'] ? "\nTransfer notes: " . $validated['notes'] : "")
        ]);
        
        // Update the new bed status to occupied
        $newBed->update([
            'status' => 'occupied',
        ]);
        
        return redirect()->route('nurse.bed-assignments.show', $visit)
                         ->with('success', 'Bed assignment updated successfully.');
    }

    /**
     * Unassign a bed from a visit.
     */
    public function destroy(Request $request, Visit $visit)
    {
        if (!$visit->bed_id) {
            return redirect()->route('nurse.dashboard')
                             ->with('warning', 'No bed assigned to this visit.');
        }
        
        $bed = $visit->bed;
        $bedIdentifier = $bed->full_identifier;
        
        $validated = $request->validate([
            'notes' => 'nullable|string',
        ]);
        
        // Update the visit to remove the bed assignment
        $visit->update([
            'bed_id' => null,
            'notes' => $visit->notes . "\n\n" . 
                        "Bed unassigned: " . $bedIdentifier . 
                        " on " . now()->format('Y-m-d H:i') . 
                        " by " . Auth::user()->name .
                        ($validated['notes'] ? "\nUnassignment notes: " . $validated['notes'] : "")
        ]);
        
        // Mark the bed for cleaning
        $bed->update([
            'status' => 'cleaning',
            'notes' => $bed->notes . "\nBed vacated on " . now()->format('Y-m-d H:i') . 
                       " by patient " . $visit->patient->full_name
        ]);
        
        return redirect()->route('nurse.dashboard')
                         ->with('success', 'Bed unassigned successfully.');
    }

    /**
     * Display a listing of all current bed assignments.
     */
    public function index()
    {
        $occupiedBeds = Bed::with('currentVisit.patient')
                          ->where('status', 'occupied')
                          ->orderBy('location')
                          ->orderBy('bed_number')
                          ->paginate(20);
        
        return view('nurse.beds.assignments.index', compact('occupiedBeds'));
    }
}
