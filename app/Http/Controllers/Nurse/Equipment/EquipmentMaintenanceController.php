<?php

namespace App\Http\Controllers\Nurse\Equipment;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Models\EquipmentMaintenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EquipmentMaintenanceController extends Controller
{
    /**
     * Display a listing of maintenance requests.
     */
    public function index()
    {
        $activeMaintenance = EquipmentMaintenance::with(['equipment', 'requestedBy'])
                                               ->active()
                                               ->orderBy('priority', 'desc')
                                               ->orderBy('requested_at')
                                               ->paginate(15);
        
        // Get summary counts
        $totalActiveMaintenance = EquipmentMaintenance::active()->count();
        $highPriorityMaintenance = EquipmentMaintenance::active()->highPriority()->count();
        $scheduledToday = EquipmentMaintenance::scheduledForToday()->count();
        $overdueMaintenance = EquipmentMaintenance::overdue()->count();
        $completedThisMonth = EquipmentMaintenance::completed()
                                                 ->whereMonth('completed_at', now()->month)
                                                 ->whereYear('completed_at', now()->year)
                                                 ->count();
        
        return view('nurse.equipment.maintenance.index', compact(
            'activeMaintenance',
            'totalActiveMaintenance',
            'highPriorityMaintenance',
            'scheduledToday',
            'overdueMaintenance',
            'completedThisMonth'
        ));
    }

    /**
     * Show the form for creating a new maintenance request.
     */
    public function create(Request $request)
    {
        $equipment = null;
        
        // Check if equipment_id was provided
        if ($request->has('equipment_id')) {
            $equipment = Equipment::findOrFail($request->equipment_id);
        }
        
        // Get all active equipment for selection
        $allEquipment = Equipment::active()
                                ->orderBy('name')
                                ->get()
                                ->groupBy('category');
        
        $maintenanceTypes = [
            'preventive' => 'Preventive Maintenance',
            'corrective' => 'Corrective Maintenance (Repair)',
            'inspection' => 'Safety Inspection',
            'calibration' => 'Calibration',
            'other' => 'Other'
        ];
        
        $priorities = [
            'low' => 'Low - Can wait, not urgent',
            'medium' => 'Medium - Should be addressed soon',
            'high' => 'High - Urgent attention needed',
            'critical' => 'Critical - Immediate attention required'
        ];
        
        return view('nurse.equipment.maintenance.create', compact(
            'equipment',
            'allEquipment',
            'maintenanceTypes',
            'priorities'
        ));
    }

    /**
     * Store a newly created maintenance request in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'type' => 'required|in:preventive,corrective,inspection,calibration,other',
            'priority' => 'required|in:low,medium,high,critical',
            'issue_description' => 'required|string',
            'scheduled_for' => 'nullable|date',
            'contact_info' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);
        
        // Add current user and timestamp
        $validated['requested_by'] = Auth::id();
        $validated['requested_at'] = now();
        $validated['status'] = 'requested';
        
        // Create the maintenance record
        $maintenance = EquipmentMaintenance::create($validated);
        
        // Update equipment status to maintenance if priority is high or critical
        if (in_array($validated['priority'], ['high', 'critical'])) {
            $equipment = Equipment::findOrFail($validated['equipment_id']);
            $equipment->status = 'maintenance';
            $equipment->save();
        }
        
        return redirect()->route('nurse.equipment-maintenance.show', $maintenance->id)
                         ->with('success', 'Maintenance request created successfully.');
    }

    /**
     * Display the specified maintenance request.
     */
    public function show(EquipmentMaintenance $equipmentMaintenance)
    {
        $equipmentMaintenance->load([
            'equipment', 
            'requestedBy', 
            'completedBy'
        ]);
        
        return view('nurse.equipment.maintenance.show', compact('equipmentMaintenance'));
    }

    /**
     * Show the form for editing the specified maintenance request.
     */
    public function edit(EquipmentMaintenance $equipmentMaintenance)
    {
        // If maintenance is already completed, redirect to show
        if ($equipmentMaintenance->isCompleted()) {
            return redirect()->route('nurse.equipment-maintenance.show', $equipmentMaintenance->id)
                             ->with('warning', 'Completed maintenance requests cannot be edited.');
        }
        
        $equipmentMaintenance->load('equipment', 'requestedBy');
        
        $maintenanceTypes = [
            'preventive' => 'Preventive Maintenance',
            'corrective' => 'Corrective Maintenance (Repair)',
            'inspection' => 'Safety Inspection',
            'calibration' => 'Calibration',
            'other' => 'Other'
        ];
        
        $priorities = [
            'low' => 'Low - Can wait, not urgent',
            'medium' => 'Medium - Should be addressed soon',
            'high' => 'High - Urgent attention needed',
            'critical' => 'Critical - Immediate attention required'
        ];
        
        $statuses = [
            'requested' => 'Requested',
            'scheduled' => 'Scheduled',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled'
        ];
        
        return view('nurse.equipment.maintenance.edit', compact(
            'equipmentMaintenance',
            'maintenanceTypes',
            'priorities',
            'statuses'
        ));
    }

    /**
     * Update the specified maintenance request in storage.
     */
    public function update(Request $request, EquipmentMaintenance $equipmentMaintenance)
    {
        // If maintenance is already completed, redirect to show
        if ($equipmentMaintenance->isCompleted()) {
            return redirect()->route('nurse.equipment-maintenance.show', $equipmentMaintenance->id)
                             ->with('warning', 'Completed maintenance requests cannot be edited.');
        }
        
        $validated = $request->validate([
            'type' => 'required|in:preventive,corrective,inspection,calibration,other',
            'priority' => 'required|in:low,medium,high,critical',
            'status' => 'required|in:requested,scheduled,in_progress,completed,cancelled',
            'issue_description' => 'required|string',
            'scheduled_for' => 'nullable|date',
            'contact_info' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);
        
        // Handle status transitions
        if ($validated['status'] === 'completed' && $equipmentMaintenance->status !== 'completed') {
            $validated['completed_at'] = now();
            $validated['completed_by'] = Auth::id();
        } elseif ($validated['status'] === 'cancelled' && $equipmentMaintenance->status !== 'cancelled') {
            // If cancelling, do nothing special
        }
        
        // Save the updated maintenance record
        $equipmentMaintenance->update($validated);
        
        // Update equipment status based on maintenance status
        $equipment = $equipmentMaintenance->equipment;
        
        if ($validated['status'] === 'completed') {
            // Equipment should now be available unless there are other active maintenance requests
            $otherActiveMaintenance = $equipment->activeMaintenance()
                                               ->where('id', '!=', $equipmentMaintenance->id)
                                               ->exists();
            
            if (!$otherActiveMaintenance) {
                $equipment->status = 'available';
                $equipment->save();
            }
        } elseif ($validated['status'] === 'cancelled' && $equipment->status === 'maintenance') {
            // If cancelling a maintenance request, check if this was the only one
            $otherActiveMaintenance = $equipment->activeMaintenance()
                                               ->where('id', '!=', $equipmentMaintenance->id)
                                               ->exists();
            
            if (!$otherActiveMaintenance) {
                $equipment->status = 'available';
                $equipment->save();
            }
        } elseif (in_array($validated['priority'], ['high', 'critical']) && $equipment->status !== 'maintenance') {
            // For high priority maintenance, update equipment status
            $equipment->status = 'maintenance';
            $equipment->save();
        }
        
        return redirect()->route('nurse.equipment-maintenance.show', $equipmentMaintenance->id)
                         ->with('success', 'Maintenance request updated successfully.');
    }

    /**
     * Show the form for completing a maintenance request.
     */
    public function complete(EquipmentMaintenance $equipmentMaintenance)
    {
        // If maintenance is already completed, redirect to show
        if ($equipmentMaintenance->isCompleted()) {
            return redirect()->route('nurse.equipment-maintenance.show', $equipmentMaintenance->id)
                             ->with('warning', 'This maintenance request is already completed.');
        }
        
        $equipmentMaintenance->load('equipment', 'requestedBy');
        
        return view('nurse.equipment.maintenance.complete', compact('equipmentMaintenance'));
    }

    /**
     * Process the completion of a maintenance request.
     */
    public function processComplete(Request $request, EquipmentMaintenance $equipmentMaintenance)
    {
        // If maintenance is already completed, redirect to show
        if ($equipmentMaintenance->isCompleted()) {
            return redirect()->route('nurse.equipment-maintenance.show', $equipmentMaintenance->id)
                             ->with('warning', 'This maintenance request is already completed.');
        }
        
        $validated = $request->validate([
            'work_performed' => 'required|string',
            'cost' => 'nullable|numeric|min:0',
            'service_provider' => 'nullable|string|max:255',
            'contact_info' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'calculate_next_maintenance' => 'boolean',
        ]);
        
        // Update maintenance record
        $equipmentMaintenance->update([
            'completed_by' => Auth::id(),
            'completed_at' => now(),
            'status' => 'completed',
            'work_performed' => $validated['work_performed'],
            'cost' => $validated['cost'],
            'service_provider' => $validated['service_provider'],
            'contact_info' => $validated['contact_info'],
            'notes' => $validated['notes'],
        ]);
        
        // Update equipment status and maintenance dates
        $equipment = $equipmentMaintenance->equipment;
        $equipment->last_maintenance_date = now();
        
        // Set the next maintenance date based on type if requested
        if ($request->has('calculate_next_maintenance') && $request->calculate_next_maintenance) {
            if ($equipmentMaintenance->type === 'preventive') {
                // Preventive maintenance typically every 6 months
                $equipment->next_maintenance_date = now()->addMonths(6);
            } elseif (in_array($equipmentMaintenance->type, ['inspection', 'calibration'])) {
                // Inspections and calibrations typically every 12 months
                $equipment->next_maintenance_date = now()->addYear();
            }
        }
        
        // Check if this was the only active maintenance request
        $otherActiveMaintenance = $equipment->activeMaintenance()
                                           ->where('id', '!=', $equipmentMaintenance->id)
                                           ->exists();
        
        if (!$otherActiveMaintenance) {
            $equipment->status = 'available';
        }
        
        $equipment->save();
        
        return redirect()->route('nurse.equipment-maintenance.show', $equipmentMaintenance->id)
                         ->with('success', 'Maintenance request completed successfully.');
    }

    /**
     * Display overdue maintenance requests.
     */
    public function overdue()
    {
        $overdueMaintenance = EquipmentMaintenance::with(['equipment', 'requestedBy'])
                                                 ->overdue()
                                                 ->orderBy('scheduled_for')
                                                 ->paginate(15);
        
        return view('nurse.equipment.maintenance.overdue', compact('overdueMaintenance'));
    }

    /**
     * Display scheduled maintenance for today.
     */
    public function scheduledToday()
    {
        $todayMaintenance = EquipmentMaintenance::with(['equipment', 'requestedBy'])
                                               ->scheduledForToday()
                                               ->orderBy('scheduled_for')
                                               ->paginate(15);
        
        return view('nurse.equipment.maintenance.scheduled-today', compact('todayMaintenance'));
    }

    /**
     * Display maintenance history.
     */
    public function history()
    {
        $maintenanceHistory = EquipmentMaintenance::with(['equipment', 'requestedBy', 'completedBy'])
                                                 ->orderByDesc('requested_at')
                                                 ->paginate(20);
        
        return view('nurse.equipment.maintenance.history', compact('maintenanceHistory'));
    }

    /**
     * Display equipment needing maintenance.
     */
    public function equipmentNeedingMaintenance()
    {
        $equipmentNeedingMaintenance = Equipment::needsMaintenance()
                                               ->with(['activeMaintenance'])
                                               ->orderBy('next_maintenance_date')
                                               ->paginate(15);
        
        return view('nurse.equipment.maintenance.equipment-needing-maintenance', compact('equipmentNeedingMaintenance'));
    }
}