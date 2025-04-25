<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\ImagingOrder;
use App\Models\Treatment;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImagingOrderController extends Controller
{
    /**
     * Display a listing of the imaging orders.
     */
    public function index()
    {
        $pendingOrders = ImagingOrder::with(['visit.patient', 'orderedBy'])
                                    ->pending()
                                    ->orderBy('is_stat', 'desc')
                                    ->orderBy('ordered_at', 'asc')
                                    ->paginate(10, ['*'], 'pending');
        
        $completedOrders = ImagingOrder::with(['visit.patient', 'orderedBy'])
                                      ->completed()
                                      ->latest('completed_at')
                                      ->paginate(10, ['*'], 'completed');
        
        return view('doctor.imaging-orders.index', compact('pendingOrders', 'completedOrders'));
    }

    /**
     * Show the form for creating a new imaging order.
     */
    public function create(Visit $visit)
    {
        $treatments = Treatment::where('visit_id', $visit->id)
                              ->where('status', '!=', 'discontinued')
                              ->get();
        
        // Define common imaging types and body parts for the form
        $imagingTypes = [
            'X-Ray',
            'CT Scan',
            'MRI',
            'Ultrasound',
            'Nuclear Medicine',
            'PET Scan',
            'Mammogram',
            'Fluoroscopy',
            'Angiography',
        ];
        
        return view('doctor.imaging-orders.create', compact('visit', 'treatments', 'imagingTypes'));
    }

    /**
     * Store a newly created imaging order in storage.
     */
    public function store(Request $request, Visit $visit)
    {
        $validated = $request->validate([
            'treatment_id' => 'nullable|exists:treatments,id',
            'imaging_type' => 'required|string|max:255',
            'body_part' => 'required|string|max:255',
            'clinical_information' => 'nullable|string',
            'reason_for_exam' => 'nullable|string',
            'is_stat' => 'boolean',
            'requires_contrast' => 'boolean',
            'scheduled_for' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);
        
        $imagingOrder = new ImagingOrder($validated);
        $imagingOrder->visit_id = $visit->id;
        $imagingOrder->ordered_by = Auth::id();
        $imagingOrder->ordered_at = now();
        $imagingOrder->status = 'ordered';
        $imagingOrder->save();
        
        return redirect()->route('doctor.imaging-orders.show', [$imagingOrder])
                        ->with('success', 'Imaging order created successfully.');
    }

    /**
     * Display the specified imaging order.
     */
    public function show(ImagingOrder $imagingOrder)
    {
        $imagingOrder->load(['visit.patient', 'orderedBy', 'treatment', 'radiologist']);
        
        return view('doctor.imaging-orders.show', compact('imagingOrder'));
    }

    /**
     * Show the form for editing the specified imaging order.
     */
    public function edit(ImagingOrder $imagingOrder)
    {
        if (!in_array($imagingOrder->status, ['ordered', 'scheduled'])) {
            return redirect()->route('doctor.imaging-orders.show', [$imagingOrder])
                            ->with('error', 'Cannot edit an imaging order that is already in progress or completed.');
        }
        
        $visit = $imagingOrder->visit;
        $treatments = Treatment::where('visit_id', $visit->id)
                              ->where('status', '!=', 'discontinued')
                              ->get();
        
        // Define common imaging types for the form
        $imagingTypes = [
            'X-Ray',
            'CT Scan',
            'MRI',
            'Ultrasound',
            'Nuclear Medicine',
            'PET Scan',
            'Mammogram',
            'Fluoroscopy',
            'Angiography',
        ];
        
        return view('doctor.imaging-orders.edit', compact('imagingOrder', 'visit', 'treatments', 'imagingTypes'));
    }

    /**
     * Update the specified imaging order in storage.
     */
    public function update(Request $request, ImagingOrder $imagingOrder)
    {
        if (!in_array($imagingOrder->status, ['ordered', 'scheduled'])) {
            return redirect()->route('doctor.imaging-orders.show', [$imagingOrder])
                            ->with('error', 'Cannot update an imaging order that is already in progress or completed.');
        }
        
        $validated = $request->validate([
            'treatment_id' => 'nullable|exists:treatments,id',
            'imaging_type' => 'required|string|max:255',
            'body_part' => 'required|string|max:255',
            'clinical_information' => 'nullable|string',
            'reason_for_exam' => 'nullable|string',
            'is_stat' => 'boolean',
            'requires_contrast' => 'boolean',
            'scheduled_for' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);
        
        $imagingOrder->update($validated);
        
        return redirect()->route('doctor.imaging-orders.show', [$imagingOrder])
                        ->with('success', 'Imaging order updated successfully.');
    }

    /**
     * Cancel the specified imaging order.
     */
    public function cancel(ImagingOrder $imagingOrder)
    {
        if (in_array($imagingOrder->status, ['ordered', 'scheduled'])) {
            $imagingOrder->status = 'cancelled';
            $imagingOrder->save();
            
            return redirect()->route('doctor.imaging-orders.index')
                            ->with('success', 'Imaging order cancelled successfully.');
        }
        
        return redirect()->route('doctor.imaging-orders.show', [$imagingOrder])
                        ->with('error', 'Cannot cancel an imaging order that is already in progress or completed.');
    }

    /**
     * Update the imaging order results.
     */
    public function updateResults(Request $request, ImagingOrder $imagingOrder)
    {
        $validated = $request->validate([
            'findings' => 'required|string',
            'impression' => 'required|string',
        ]);
        
        $imagingOrder->update($validated);
        
        if ($request->has('mark_as_completed') && $imagingOrder->status !== 'completed') {
            $imagingOrder->status = 'completed';
            $imagingOrder->completed_at = now();
            $imagingOrder->radiologist_id = Auth::id(); // Assuming the doctor entering results is the radiologist
            $imagingOrder->save();
            
            return redirect()->route('doctor.imaging-orders.show', [$imagingOrder])
                            ->with('success', 'Imaging order results updated and marked as completed.');
        }
        
        return redirect()->route('doctor.imaging-orders.show', [$imagingOrder])
                        ->with('success', 'Imaging order results updated successfully.');
    }
}
