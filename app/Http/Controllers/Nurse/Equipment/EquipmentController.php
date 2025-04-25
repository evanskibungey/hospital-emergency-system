<?php

namespace App\Http\Controllers\Nurse\Equipment;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    /**
     * Display a listing of equipment.
     */
    public function index()
    {
        $equipment = Equipment::with(['activeCheckouts', 'activeMaintenance'])
                             ->orderBy('name')
                             ->paginate(15);

        // Get summary counts
        $availableCount = Equipment::available()->count();
        $inUseCount = Equipment::where('status', 'in_use')->count();
        $maintenanceCount = Equipment::where('status', 'maintenance')->count();
        $retiredCount = Equipment::where('status', 'retired')->count();
        $needsMaintenanceCount = Equipment::needsMaintenance()->count();

        // Get counts by category
        $diagnosticCount = Equipment::inCategory('diagnostic')->count();
        $therapeuticCount = Equipment::inCategory('therapeutic')->count();
        $monitoringCount = Equipment::inCategory('monitoring')->count();
        $emergencyCount = Equipment::inCategory('emergency')->count();
        $patientCareCount = Equipment::inCategory('patient_care')->count();

        return view('nurse.equipment.index', compact(
            'equipment',
            'availableCount',
            'inUseCount',
            'maintenanceCount',
            'retiredCount',
            'needsMaintenanceCount',
            'diagnosticCount',
            'therapeuticCount',
            'monitoringCount',
            'emergencyCount',
            'patientCareCount'
        ));
    }

    /**
     * Show the form for creating new equipment.
     */
    public function create()
    {
        $types = [
            'portable' => 'Portable',
            'fixed' => 'Fixed',
            'disposable' => 'Disposable',
            'reusable' => 'Reusable'
        ];

        $categories = [
            'diagnostic' => 'Diagnostic',
            'therapeutic' => 'Therapeutic',
            'monitoring' => 'Monitoring',
            'laboratory' => 'Laboratory',
            'surgical' => 'Surgical',
            'emergency' => 'Emergency',
            'life_support' => 'Life Support',
            'patient_care' => 'Patient Care',
            'administrative' => 'Administrative',
            'other' => 'Other'
        ];

        $statuses = [
            'available' => 'Available',
            'in_use' => 'In Use',
            'maintenance' => 'Maintenance',
            'retired' => 'Retired'
        ];

        return view('nurse.equipment.create', compact('types', 'categories', 'statuses'));
    }

    /**
     * Store a newly created equipment in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:255|unique:equipment,serial_number',
            'model' => 'nullable|string|max:255',
            'manufacturer' => 'nullable|string|max:255',
            'type' => 'required|in:portable,fixed,disposable,reusable',
            'category' => 'required|in:diagnostic,therapeutic,monitoring,laboratory,surgical,emergency,life_support,patient_care,administrative,other',
            'quantity' => 'required|integer|min:1',
            'purchase_date' => 'nullable|date',
            'last_maintenance_date' => 'nullable|date',
            'next_maintenance_date' => 'nullable|date',
            'status' => 'required|in:available,in_use,maintenance,retired',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        // Set available quantity to match total quantity if status is available
        if ($validated['status'] === 'available') {
            $validated['available_quantity'] = $validated['quantity'];
        } else {
            // For other statuses, set available quantity to 0 initially
            $validated['available_quantity'] = 0;
        }

        // If is_active wasn't provided in the form, default to true
        if (!isset($validated['is_active'])) {
            $validated['is_active'] = true;
        }

        Equipment::create($validated);

        return redirect()->route('nurse.equipment.index')
                         ->with('success', 'Equipment added successfully.');
    }

    /**
     * Display the specified equipment.
     */
    public function show(Equipment $equipment)
    {
        $equipment->load(['activeCheckouts.checkedOutBy', 'activeCheckouts.visit.patient', 'activeMaintenance.requestedBy']);

        // Get recent checkout history
        $recentCheckouts = $equipment->checkouts()
                                    ->with(['checkedOutBy', 'checkedInBy', 'visit.patient'])
                                    ->orderByDesc('checked_out_at')
                                    ->limit(10)
                                    ->get();

        // Get recent maintenance history
        $recentMaintenance = $equipment->maintenanceRecords()
                                      ->with(['requestedBy', 'completedBy'])
                                      ->orderByDesc('requested_at')
                                      ->limit(10)
                                      ->get();

        return view('nurse.equipment.show', compact('equipment', 'recentCheckouts', 'recentMaintenance'));
    }

    /**
     * Show the form for editing the specified equipment.
     */
    public function edit(Equipment $equipment)
    {
        $types = [
            'portable' => 'Portable',
            'fixed' => 'Fixed',
            'disposable' => 'Disposable',
            'reusable' => 'Reusable'
        ];

        $categories = [
            'diagnostic' => 'Diagnostic',
            'therapeutic' => 'Therapeutic',
            'monitoring' => 'Monitoring',
            'laboratory' => 'Laboratory',
            'surgical' => 'Surgical',
            'emergency' => 'Emergency',
            'life_support' => 'Life Support',
            'patient_care' => 'Patient Care',
            'administrative' => 'Administrative',
            'other' => 'Other'
        ];

        $statuses = [
            'available' => 'Available',
            'in_use' => 'In Use',
            'maintenance' => 'Maintenance',
            'retired' => 'Retired'
        ];

        return view('nurse.equipment.edit', compact('equipment', 'types', 'categories', 'statuses'));
    }

    /**
     * Update the specified equipment in storage.
     */
    public function update(Request $request, Equipment $equipment)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:255|unique:equipment,serial_number,' . $equipment->id,
            'model' => 'nullable|string|max:255',
            'manufacturer' => 'nullable|string|max:255',
            'type' => 'required|in:portable,fixed,disposable,reusable',
            'category' => 'required|in:diagnostic,therapeutic,monitoring,laboratory,surgical,emergency,life_support,patient_care,administrative,other',
            'quantity' => 'required|integer|min:1',
            'purchase_date' => 'nullable|date',
            'last_maintenance_date' => 'nullable|date',
            'next_maintenance_date' => 'nullable|date',
            'status' => 'required|in:available,in_use,maintenance,retired',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        // Calculate the difference in quantity
        $quantityDifference = $validated['quantity'] - $equipment->quantity;
        
        // Update available_quantity based on the change in total quantity
        // but only if the status is available
        if ($validated['status'] === 'available') {
            $newAvailableQuantity = $equipment->available_quantity + $quantityDifference;
            $validated['available_quantity'] = max(0, $newAvailableQuantity);
        } else if ($equipment->status === 'available' && $validated['status'] !== 'available') {
            // If changing from available to another status, set available to 0
            $validated['available_quantity'] = 0;
        }

        // If is_active wasn't provided in the form, use current value
        if (!isset($validated['is_active'])) {
            $validated['is_active'] = $equipment->is_active;
        }

        $equipment->update($validated);

        return redirect()->route('nurse.equipment.show', $equipment->id)
                         ->with('success', 'Equipment updated successfully.');
    }

    /**
     * Search for equipment.
     */
    public function search(Request $request)
    {
        $search = $request->input('search');
        
        $equipment = Equipment::where('name', 'like', "%{$search}%")
                              ->orWhere('serial_number', 'like', "%{$search}%")
                              ->orWhere('model', 'like', "%{$search}%")
                              ->orWhere('manufacturer', 'like', "%{$search}%")
                              ->orWhere('location', 'like', "%{$search}%")
                              ->orderBy('name')
                              ->paginate(15)
                              ->appends(['search' => $search]);
        
        return view('nurse.equipment.search', compact('equipment', 'search'));
    }

    /**
     * Filter equipment by type, category, and status.
     */
    public function filter(Request $request)
    {
        $query = Equipment::query();
        
        // Filter by type if provided
        if ($request->has('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }
        
        // Filter by category if provided
        if ($request->has('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
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
        if (!$request->has('show_inactive')) {
            $query->where('is_active', true);
        }
        
        $equipment = $query->with(['activeCheckouts', 'activeMaintenance'])
                          ->orderBy('name')
                          ->paginate(15)
                          ->appends($request->all());
        
        // Get summary counts (filtered by the same criteria)
        $availableCount = (clone $query)->where('status', 'available')->count();
        $inUseCount = (clone $query)->where('status', 'in_use')->count();
        $maintenanceCount = (clone $query)->where('status', 'maintenance')->count();
        $retiredCount = (clone $query)->where('status', 'retired')->count();
        
        $needsMaintenanceCount = (clone $query)->where(function($q) {
            $q->where('next_maintenance_date', '<=', now())
              ->orWhere('status', 'maintenance');
        })->count();
        
        // Get counts by category 
        $diagnosticCount = (clone $query)->where('category', 'diagnostic')->count();
        $therapeuticCount = (clone $query)->where('category', 'therapeutic')->count();
        $monitoringCount = (clone $query)->where('category', 'monitoring')->count();
        $emergencyCount = (clone $query)->where('category', 'emergency')->count();
        $patientCareCount = (clone $query)->where('category', 'patient_care')->count();
        
        return view('nurse.equipment.index', compact(
            'equipment',
            'availableCount',
            'inUseCount',
            'maintenanceCount',
            'retiredCount',
            'needsMaintenanceCount',
            'diagnosticCount',
            'therapeuticCount',
            'monitoringCount',
            'emergencyCount',
            'patientCareCount'
        ));
    }
}