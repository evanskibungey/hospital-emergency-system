<?php

namespace App\Http\Controllers\Nurse\Equipment;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Models\EquipmentCheckout;
use App\Models\Visit;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EquipmentCheckoutController extends Controller
{
    /**
     * Display a listing of all current checkouts.
     */
    public function index()
    {
        $activeCheckouts = EquipmentCheckout::with(['equipment', 'visit.patient', 'checkedOutBy'])
                                          ->active()
                                          ->orderByDesc('checked_out_at')
                                          ->paginate(15);
        
        // Get summary counts
        $totalActiveCheckouts = EquipmentCheckout::active()->count();
        $overdueCheckouts = EquipmentCheckout::overdue()->count();
        $checkedOutToday = EquipmentCheckout::whereDate('checked_out_at', today())->count();
        $checkedInToday = EquipmentCheckout::whereDate('checked_in_at', today())->count();
        
        return view('nurse.equipment.checkouts.index', compact(
            'activeCheckouts',
            'totalActiveCheckouts',
            'overdueCheckouts',
            'checkedOutToday',
            'checkedInToday'
        ));
    }

    /**
     * Show the form for checking out equipment.
     */
    public function create(Request $request)
    {
        $equipment = null;
        $visit = null;
        $patient = null;
        
        // Check if equipment_id was provided
        if ($request->has('equipment_id')) {
            $equipment = Equipment::findOrFail($request->equipment_id);
        }
        
        // Check if visit_id was provided
        if ($request->has('visit_id')) {
            $visit = Visit::findOrFail($request->visit_id);
            $patient = $visit->patient;
        }
        
        // If no specific equipment was selected, get all available equipment
        $availableEquipment = Equipment::available()
                                      ->orderBy('name')
                                      ->get()
                                      ->groupBy('category');
        
        // If no specific visit was selected, get active visits
        $activeVisits = Visit::with('patient')
                            ->active()
                            ->orderByDesc('check_in_time')
                            ->get();
        
        return view('nurse.equipment.checkouts.create', compact(
            'equipment',
            'visit',
            'patient',
            'availableEquipment',
            'activeVisits'
        ));
    }

    /**
     * Store a newly created checkout in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'visit_id' => 'nullable|exists:visits,id',
            'quantity' => 'required|integer|min:1',
            'purpose' => 'required|string',
            'expected_return_at' => 'nullable|date',
            'checkout_notes' => 'nullable|string',
            'condition_at_checkout' => 'nullable|string',
        ]);
        
        $equipment = Equipment::findOrFail($validated['equipment_id']);
        
        // Check if enough quantity is available
        if ($equipment->available_quantity < $validated['quantity']) {
            return back()->withErrors(['quantity' => 'Not enough units available. Only ' . $equipment->available_quantity . ' available.'])
                         ->withInput();
        }
        
        // Add current user and timestamp
        $validated['checked_out_by'] = Auth::id();
        $validated['checked_out_at'] = now();
        $validated['status'] = 'checked_out';
        
        // Create the checkout record
        $checkout = EquipmentCheckout::create($validated);
        
        // Update equipment available quantity and status
        $equipment->available_quantity -= $validated['quantity'];
        
        if ($equipment->available_quantity <= 0) {
            $equipment->status = 'in_use';
        }
        
        $equipment->save();
        
        return redirect()->route('nurse.equipment-checkouts.show', $checkout->id)
                         ->with('success', 'Equipment checked out successfully.');
    }

    /**
     * Display the specified checkout.
     */
    public function show(EquipmentCheckout $equipmentCheckout)
    {
        $equipmentCheckout->load([
            'equipment', 
            'visit.patient', 
            'checkedOutBy', 
            'checkedInBy'
        ]);
        
        return view('nurse.equipment.checkouts.show', compact('equipmentCheckout'));
    }

    /**
     * Show the form for checking in equipment.
     */
    public function checkin(EquipmentCheckout $equipmentCheckout)
    {
        if ($equipmentCheckout->checked_in_at) {
            return redirect()->route('nurse.equipment-checkouts.show', $equipmentCheckout->id)
                             ->with('warning', 'This equipment has already been checked in.');
        }
        
        $equipmentCheckout->load(['equipment', 'visit.patient', 'checkedOutBy']);
        
        $conditions = [
            'excellent' => 'Excellent - Like new condition',
            'good' => 'Good - Minor wear, fully functional',
            'fair' => 'Fair - Noticeable wear, still functional',
            'poor' => 'Poor - Significant wear, functionality affected',
            'damaged' => 'Damaged - Repairs needed',
            'unusable' => 'Unusable - Cannot be repaired',
        ];
        
        return view('nurse.equipment.checkouts.checkin', compact('equipmentCheckout', 'conditions'));
    }

    /**
     * Process the check-in.
     */
    public function processCheckin(Request $request, EquipmentCheckout $equipmentCheckout)
    {
        if ($equipmentCheckout->checked_in_at) {
            return redirect()->route('nurse.equipment-checkouts.show', $equipmentCheckout->id)
                             ->with('warning', 'This equipment has already been checked in.');
        }
        
        $validated = $request->validate([
            'checkin_notes' => 'nullable|string',
            'condition_at_checkin' => 'required|string',
            'create_maintenance_request' => 'boolean',
            'maintenance_issue' => 'nullable|required_if:create_maintenance_request,1|string',
            'maintenance_priority' => 'nullable|required_if:create_maintenance_request,1|in:low,medium,high,critical',
        ]);
        
        // Update checkout record
        $equipmentCheckout->update([
            'checked_in_by' => Auth::id(),
            'checked_in_at' => now(),
            'status' => 'checked_in',
            'checkin_notes' => $validated['checkin_notes'],
            'condition_at_checkin' => $validated['condition_at_checkin'],
        ]);
        
        // Update equipment status and available quantity
        $equipment = $equipmentCheckout->equipment;
        
        // Should we create a maintenance request?
        $createMaintenance = $request->has('create_maintenance_request') && $request->create_maintenance_request;
        
        if ($createMaintenance) {
            // Create maintenance request
            $equipment->maintenanceRecords()->create([
                'requested_by' => Auth::id(),
                'requested_at' => now(),
                'type' => 'corrective',
                'priority' => $validated['maintenance_priority'],
                'status' => 'requested',
                'issue_description' => $validated['maintenance_issue'],
                'notes' => 'Created during equipment check-in. ' . 
                           'Condition reported as: ' . $validated['condition_at_checkin'],
            ]);
            
            // Mark equipment for maintenance
            $equipment->status = 'maintenance';
            $equipment->save();
        } else {
            // Return equipment to available inventory
            $equipment->available_quantity += $equipmentCheckout->quantity;
            
            // If all units are available, set status to available
            if ($equipment->available_quantity >= $equipment->quantity) {
                $equipment->status = 'available';
            } elseif ($equipment->available_quantity > 0) {
                $equipment->status = 'in_use'; // Some available, some in use
            }
            
            $equipment->save();
        }
        
        return redirect()->route('nurse.equipment-checkouts.show', $equipmentCheckout->id)
                         ->with('success', 'Equipment checked in successfully.');
    }

    /**
     * Search for visits to check out equipment to.
     */
    public function searchVisits(Request $request)
    {
        $search = $request->input('search');
        
        $visits = Visit::whereHas('patient', function($query) use ($search) {
            $query->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('medical_record_number', 'like', "%{$search}%");
        })
        ->with('patient')
        ->active()
        ->orderByDesc('check_in_time')
        ->limit(10)
        ->get();
        
        return response()->json($visits);
    }

    /**
     * Display checkouts for a specific patient visit.
     */
    public function visitCheckouts(Visit $visit)
    {
        $checkouts = EquipmentCheckout::with(['equipment', 'checkedOutBy', 'checkedInBy'])
                                     ->where('visit_id', $visit->id)
                                     ->orderByDesc('checked_out_at')
                                     ->paginate(15);
        
        return view('nurse.equipment.checkouts.visit', compact('visit', 'checkouts'));
    }

    /**
     * Display currently overdue checkouts.
     */
    public function overdue()
    {
        $overdueCheckouts = EquipmentCheckout::with(['equipment', 'visit.patient', 'checkedOutBy'])
                                           ->overdue()
                                           ->orderBy('expected_return_at')
                                           ->paginate(15);
        
        return view('nurse.equipment.checkouts.overdue', compact('overdueCheckouts'));
    }

    /**
     * Display equipment checkout history.
     */
    public function history()
    {
        $checkouts = EquipmentCheckout::with(['equipment', 'visit.patient', 'checkedOutBy', 'checkedInBy'])
                                     ->orderByDesc('checked_out_at')
                                     ->paginate(20);
        
        return view('nurse.equipment.checkouts.history', compact('checkouts'));
    }

    /**
     * Mark an equipment checkout as lost.
     */
    public function markAsLost(EquipmentCheckout $equipmentCheckout)
    {
        if ($equipmentCheckout->checked_in_at) {
            return redirect()->route('nurse.equipment-checkouts.show', $equipmentCheckout->id)
                             ->with('error', 'Cannot mark as lost - this equipment has already been checked in.');
        }
        
        // Update checkout status to lost
        $equipmentCheckout->update([
            'status' => 'lost',
            'checkin_notes' => ($equipmentCheckout->checkin_notes ? $equipmentCheckout->checkin_notes . "\n" : '') . 
                               'Marked as lost on ' . now()->format('Y-m-d H:i') . ' by ' . Auth::user()->name,
        ]);
        
        // Update equipment inventory count (reduce quantity)
        $equipment = $equipmentCheckout->equipment;
        $equipment->quantity -= $equipmentCheckout->quantity;
        
        // If quantity is now 0, mark as retired
        if ($equipment->quantity <= 0) {
            $equipment->status = 'retired';
            $equipment->is_active = false;
        } else {
            // Otherwise adjust available quantity and status if needed
            $equipment->available_quantity = max(0, $equipment->available_quantity);
            
            if ($equipment->available_quantity <= 0) {
                $equipment->status = 'in_use';
            } elseif ($equipment->available_quantity == $equipment->quantity) {
                $equipment->status = 'available';
            }
        }
        
        $equipment->notes = ($equipment->notes ? $equipment->notes . "\n" : '') . 
                            $equipmentCheckout->quantity . ' units marked as lost on ' . 
                            now()->format('Y-m-d H:i') . ' by ' . Auth::user()->name;
        
        $equipment->save();
        
        return redirect()->route('nurse.equipment-checkouts.show', $equipmentCheckout->id)
                         ->with('success', 'Equipment marked as lost.');
    }
}