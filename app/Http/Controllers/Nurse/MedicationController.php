<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use App\Models\Medication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicationController extends Controller
{
    /**
     * Display a listing of medications.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $query = Medication::query();
        
        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }
        
        $medications = $query->orderBy('name')->paginate(10);
        
        return view('nurse.medications.index', compact('medications', 'search'));
    }

    /**
     * Show the form for creating a new medication.
     */
    public function create()
    {
        return view('nurse.medications.create');
    }

    /**
     * Store a newly created medication in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'dosage_form' => 'required|string|max:50',
            'strength' => 'required|string|max:50',
            'unit' => 'nullable|string|max:20',
            'instructions' => 'nullable|string',
            'is_controlled_substance' => 'boolean',
        ]);

        $medication = Medication::create($validated);

        return redirect()->route('nurse.medications.show', $medication->id)
            ->with('success', 'Medication created successfully');
    }

    /**
     * Display the specified medication.
     */
    public function show(Medication $medication)
    {
        return view('nurse.medications.show', compact('medication'));
    }

    /**
     * Show the form for editing the specified medication.
     */
    public function edit(Medication $medication)
    {
        return view('nurse.medications.edit', compact('medication'));
    }

    /**
     * Update the specified medication in storage.
     */
    public function update(Request $request, Medication $medication)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'dosage_form' => 'required|string|max:50',
            'strength' => 'required|string|max:50',
            'unit' => 'nullable|string|max:20',
            'instructions' => 'nullable|string',
            'is_controlled_substance' => 'boolean',
        ]);

        $medication->update($validated);

        return redirect()->route('nurse.medications.show', $medication->id)
            ->with('success', 'Medication updated successfully');
    }

    /**
     * Search for medications to administer.
     */
    public function search(Request $request)
    {
        $search = $request->input('search');
        $medications = [];
        
        if ($search) {
            $medications = Medication::where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orderBy('name')
                ->get();
        }
        
        return view('nurse.medications.search', compact('medications', 'search'));
    }
}