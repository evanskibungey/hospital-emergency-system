<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\LabOrder;
use App\Models\Treatment;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LabOrderController extends Controller
{
    /**
     * Display a listing of the lab orders.
     */
    public function index()
    {
        $pendingOrders = LabOrder::with(['visit.patient', 'orderedBy'])
                                 ->pending()
                                 ->orderBy('is_stat', 'desc')
                                 ->orderBy('ordered_at', 'asc')
                                 ->paginate(10, ['*'], 'pending');
        
        $completedOrders = LabOrder::with(['visit.patient', 'orderedBy'])
                                   ->completed()
                                   ->latest('completed_at')
                                   ->paginate(10, ['*'], 'completed');
        
        return view('doctor.lab-orders.index', compact('pendingOrders', 'completedOrders'));
    }

    /**
     * Show the form for creating a new lab order.
     */
    public function create(Visit $visit)
    {
        $treatments = Treatment::where('visit_id', $visit->id)
                              ->where('status', '!=', 'discontinued')
                              ->get();
        
        return view('doctor.lab-orders.create', compact('visit', 'treatments'));
    }

    /**
     * Store a newly created lab order in storage.
     */
    public function store(Request $request, Visit $visit)
    {
        $validated = $request->validate([
            'treatment_id' => 'nullable|exists:treatments,id',
            'test_name' => 'required|string|max:255',
            'test_details' => 'nullable|string',
            'reason_for_test' => 'nullable|string',
            'is_stat' => 'boolean',
            'scheduled_for' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);
        
        $labOrder = new LabOrder($validated);
        $labOrder->visit_id = $visit->id;
        $labOrder->ordered_by = Auth::id();
        $labOrder->ordered_at = now();
        $labOrder->status = 'ordered';
        $labOrder->save();
        
        return redirect()->route('doctor.lab-orders.show', [$labOrder])
                         ->with('success', 'Lab order created successfully.');
    }

    /**
     * Display the specified lab order.
     */
    public function show(LabOrder $labOrder)
    {
        $labOrder->load(['visit.patient', 'orderedBy', 'treatment']);
        
        return view('doctor.lab-orders.show', compact('labOrder'));
    }

    /**
     * Show the form for editing the specified lab order.
     */
    public function edit(LabOrder $labOrder)
    {
        if ($labOrder->status !== 'ordered') {
            return redirect()->route('doctor.lab-orders.show', [$labOrder])
                             ->with('error', 'Cannot edit a lab order that has already been processed.');
        }
        
        $visit = $labOrder->visit;
        $treatments = Treatment::where('visit_id', $visit->id)
                              ->where('status', '!=', 'discontinued')
                              ->get();
        
        return view('doctor.lab-orders.edit', compact('labOrder', 'visit', 'treatments'));
    }

    /**
     * Update the specified lab order in storage.
     */
    public function update(Request $request, LabOrder $labOrder)
    {
        if ($labOrder->status !== 'ordered') {
            return redirect()->route('doctor.lab-orders.show', [$labOrder])
                             ->with('error', 'Cannot update a lab order that has already been processed.');
        }
        
        $validated = $request->validate([
            'treatment_id' => 'nullable|exists:treatments,id',
            'test_name' => 'required|string|max:255',
            'test_details' => 'nullable|string',
            'reason_for_test' => 'nullable|string',
            'is_stat' => 'boolean',
            'scheduled_for' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);
        
        $labOrder->update($validated);
        
        return redirect()->route('doctor.lab-orders.show', [$labOrder])
                         ->with('success', 'Lab order updated successfully.');
    }

    /**
     * Cancel the specified lab order.
     */
    public function cancel(LabOrder $labOrder)
    {
        if (in_array($labOrder->status, ['ordered', 'collected'])) {
            $labOrder->status = 'cancelled';
            $labOrder->save();
            
            return redirect()->route('doctor.lab-orders.index')
                             ->with('success', 'Lab order cancelled successfully.');
        }
        
        return redirect()->route('doctor.lab-orders.show', [$labOrder])
                         ->with('error', 'Cannot cancel a lab order that is already in progress or completed.');
    }

    /**
     * Update the lab order results.
     */
    public function updateResults(Request $request, LabOrder $labOrder)
    {
        if (!in_array($labOrder->status, ['in_progress', 'completed'])) {
            return redirect()->route('doctor.lab-orders.show', [$labOrder])
                             ->with('error', 'Cannot update results for an order that is not in progress or completed.');
        }
        
        $validated = $request->validate([
            'result_summary' => 'required|string',
            'result_details' => 'nullable|string',
        ]);
        
        $labOrder->update($validated);
        
        if ($request->has('mark_as_completed') && $labOrder->status !== 'completed') {
            $labOrder->status = 'completed';
            $labOrder->completed_at = now();
            $labOrder->save();
            
            return redirect()->route('doctor.lab-orders.show', [$labOrder])
                             ->with('success', 'Lab order results updated and marked as completed.');
        }
        
        return redirect()->route('doctor.lab-orders.show', [$labOrder])
                         ->with('success', 'Lab order results updated successfully.');
    }
}
