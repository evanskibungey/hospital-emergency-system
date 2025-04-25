<?php

namespace App\Http\Controllers\Nurse\Beds;

use App\Http\Controllers\Controller;
use App\Models\Bed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BedController extends Controller
{
    /**
     * Display a listing of the beds.
     */
    public function index()
    {
        $beds = Bed::with('currentVisit.patient')
                    ->orderBy('location')
                    ->orderBy('bed_number')
                    ->paginate(20);
        
        // Get summary counts for bed status
        $availableBeds = Bed::available()->count();
        $occupiedBeds = Bed::occupied()->count();
        $cleaningBeds = Bed::where('status', 'cleaning')->count();
        $maintenanceBeds = Bed::where('status', 'maintenance')->count();
        $reservedBeds = Bed::where('status', 'reserved')->count();
        
        // Get counts by bed type
        $regularBeds = Bed::where('type', 'regular')->count();
        $icuBeds = Bed::where('type', 'icu')->count();
        $pediatricBeds = Bed::where('type', 'pediatric')->count();
        $maternityBeds = Bed::where('type', 'maternity')->count();
        $isolationBeds = Bed::where('type', 'isolation')->count();
        
        return view('nurse.beds.index', compact(
            'beds', 
            'availableBeds', 
            'occupiedBeds', 
            'cleaningBeds', 
            'maintenanceBeds', 
            'reservedBeds',
            'regularBeds',
            'icuBeds',
            'pediatricBeds',
            'maternityBeds',
            'isolationBeds'
        ));
    }

    /**
     * Show the form for creating a new bed.
     */
    public function create()
    {
        $bedTypes = [
            'regular' => 'Regular',
            'icu' => 'ICU',
            'pediatric' => 'Pediatric',
            'maternity' => 'Maternity',
            'isolation' => 'Isolation'
        ];
        
        return view('nurse.beds.create', compact('bedTypes'));
    }

    /**
     * Store a newly created bed in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bed_number' => 'required|string|max:10',
            'location' => 'required|string|max:50',
            'type' => 'required|in:regular,icu,pediatric,maternity,isolation',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        
        // Check for duplicate bed number in the same location
        $existingBed = Bed::where('bed_number', $validated['bed_number'])
                          ->where('location', $validated['location'])
                          ->first();
                          
        if ($existingBed) {
            return back()->withErrors(['bed_number' => 'A bed with this number already exists in this location.'])
                         ->withInput();
        }
        
        // Set default status to available
        $validated['status'] = 'available';
        
        // Set is_active to true if not provided
        $validated['is_active'] = $request->has('is_active') ? $validated['is_active'] : true;
        
        Bed::create($validated);
        
        return redirect()->route('nurse.beds.index')
                         ->with('success', 'Bed created successfully.');
    }

    /**
     * Display the specified bed.
     */
    public function show(Bed $bed)
    {
        $bed->load('currentVisit.patient', 'visits.patient');
        
        return view('nurse.beds.show', compact('bed'));
    }

    /**
     * Show the form for editing the specified bed.
     */
    public function edit(Bed $bed)
    {
        $bedTypes = [
            'regular' => 'Regular',
            'icu' => 'ICU',
            'pediatric' => 'Pediatric',
            'maternity' => 'Maternity',
            'isolation' => 'Isolation'
        ];
        
        $bedStatuses = [
            'available' => 'Available',
            'occupied' => 'Occupied',
            'cleaning' => 'Cleaning Required',
            'maintenance' => 'Maintenance Required',
            'reserved' => 'Reserved'
        ];
        
        return view('nurse.beds.edit', compact('bed', 'bedTypes', 'bedStatuses'));
    }

    /**
     * Update the specified bed in storage.
     */
    public function update(Request $request, Bed $bed)
    {
        $validated = $request->validate([
            'bed_number' => 'required|string|max:10',
            'location' => 'required|string|max:50',
            'status' => 'required|in:available,occupied,cleaning,maintenance,reserved',
            'type' => 'required|in:regular,icu,pediatric,maternity,isolation',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        
        // Check for duplicate bed number in the same location (excluding this bed)
        $existingBed = Bed::where('bed_number', $validated['bed_number'])
                          ->where('location', $validated['location'])
                          ->where('id', '!=', $bed->id)
                          ->first();
                          
        if ($existingBed) {
            return back()->withErrors(['bed_number' => 'A bed with this number already exists in this location.'])
                         ->withInput();
        }
        
        // Set is_active to current value if not provided
        $validated['is_active'] = $request->has('is_active') ? $validated['is_active'] : $bed->is_active;
        
        // If changing from occupied to another status, check if there's a current visit
        if ($bed->status === 'occupied' && $validated['status'] !== 'occupied') {
            if ($bed->currentVisit) {
                return back()->withErrors(['status' => 'This bed is currently occupied by a patient. Please reassign or discharge the patient first.'])
                             ->withInput();
            }
        }
        
        $bed->update($validated);
        
        return redirect()->route('nurse.beds.show', $bed->id)
                         ->with('success', 'Bed updated successfully.');
    }

    /**
     * Mark a bed as requiring cleaning.
     */
    public function markForCleaning(Bed $bed)
    {
        // Only allow marking for cleaning if the bed is not occupied
        if ($bed->status === 'occupied' && $bed->currentVisit) {
            return back()->withErrors(['status' => 'Cannot mark an occupied bed for cleaning.']);
        }
        
        $bed->update([
            'status' => 'cleaning',
            'notes' => $bed->notes . "\nMarked for cleaning on " . now()->format('Y-m-d H:i') . " by " . Auth::user()->name
        ]);
        
        return back()->with('success', 'Bed marked for cleaning.');
    }

    /**
     * Mark a bed as requiring maintenance.
     */
    public function markForMaintenance(Request $request, Bed $bed)
    {
        // Only allow marking for maintenance if the bed is not occupied
        if ($bed->status === 'occupied' && $bed->currentVisit) {
            return back()->withErrors(['status' => 'Cannot mark an occupied bed for maintenance. Please reassign the patient first.']);
        }
        
        $validated = $request->validate([
            'maintenance_notes' => 'required|string',
        ]);
        
        $bed->update([
            'status' => 'maintenance',
            'notes' => $bed->notes . "\nMaintenance required: " . $validated['maintenance_notes'] . 
                       "\nReported on " . now()->format('Y-m-d H:i') . " by " . Auth::user()->name
        ]);
        
        return back()->with('success', 'Bed marked for maintenance.');
    }

    /**
     * Mark a bed as clean and available.
     */
    public function markAsClean(Bed $bed)
    {
        $bed->update([
            'status' => 'available',
            'notes' => $bed->notes . "\nMarked as clean on " . now()->format('Y-m-d H:i') . " by " . Auth::user()->name
        ]);
        
        return back()->with('success', 'Bed marked as clean and available.');
    }

    /**
     * Filter beds by type and status.
     */
    public function filter(Request $request)
    {
        $query = Bed::with('currentVisit.patient');
        
        // Filter by type if provided
        if ($request->has('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }
        
        // Filter by status if provided
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        // Filter by location if provided
        if ($request->has('location') && $request->location) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }
        
        // Filter by active status
        if ($request->has('show_inactive')) {
            // Show all beds
        } else {
            $query->where('is_active', true);
        }
        
        $beds = $query->orderBy('location')
                      ->orderBy('bed_number')
                      ->paginate(20)
                      ->appends($request->all());
        
        // Get summary counts (filtered by the same criteria)
        $availableBeds = (clone $query)->where('status', 'available')->count();
        $occupiedBeds = (clone $query)->where('status', 'occupied')->count();
        $cleaningBeds = (clone $query)->where('status', 'cleaning')->count();
        $maintenanceBeds = (clone $query)->where('status', 'maintenance')->count();
        $reservedBeds = (clone $query)->where('status', 'reserved')->count();
        
        // Get counts by bed type (filtered by the same criteria except type)
        $baseTypeQuery = clone $query;
        if ($request->has('type') && $request->type !== 'all') {
            $baseTypeQuery = Bed::query(); // Reset the type filter
            
            // Re-apply other filters
            if ($request->has('status') && $request->status !== 'all') {
                $baseTypeQuery->where('status', $request->status);
            }
            
            if ($request->has('location') && $request->location) {
                $baseTypeQuery->where('location', 'like', '%' . $request->location . '%');
            }
            
            if (!$request->has('show_inactive')) {
                $baseTypeQuery->where('is_active', true);
            }
        }
        
        $regularBeds = (clone $baseTypeQuery)->where('type', 'regular')->count();
        $icuBeds = (clone $baseTypeQuery)->where('type', 'icu')->count();
        $pediatricBeds = (clone $baseTypeQuery)->where('type', 'pediatric')->count();
        $maternityBeds = (clone $baseTypeQuery)->where('type', 'maternity')->count();
        $isolationBeds = (clone $baseTypeQuery)->where('type', 'isolation')->count();
        
        return view('nurse.beds.index', compact(
            'beds', 
            'availableBeds', 
            'occupiedBeds', 
            'cleaningBeds', 
            'maintenanceBeds', 
            'reservedBeds',
            'regularBeds',
            'icuBeds',
            'pediatricBeds',
            'maternityBeds',
            'isolationBeds'
        ));
    }
}
