<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\MedicalNote;
use App\Models\Treatment;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicalNoteController extends Controller
{
    /**
     * Display a listing of the medical notes for a visit.
     */
    public function index(Visit $visit)
    {
        $notes = MedicalNote::where('visit_id', $visit->id)
                           ->with(['creator'])
                           ->latest()
                           ->paginate(15);
        
        return view('doctor.medical-notes.index', compact('visit', 'notes'));
    }

    /**
     * Show the form for creating a new medical note.
     */
    public function create(Visit $visit)
    {
        $treatments = Treatment::where('visit_id', $visit->id)
                              ->where('status', '!=', 'discontinued')
                              ->get();
        
        return view('doctor.medical-notes.create', compact('visit', 'treatments'));
    }

    /**
     * Store a newly created medical note in storage.
     */
    public function store(Request $request, Visit $visit)
    {
        $validated = $request->validate([
            'treatment_id' => 'nullable|exists:treatments,id',
            'note_type' => 'required|in:examination,progress,consultation,procedure,other',
            'content' => 'required|string',
            'is_private' => 'boolean',
        ]);
        
        $note = new MedicalNote($validated);
        $note->visit_id = $visit->id;
        $note->created_by = Auth::id();
        $note->save();
        
        return redirect()->route('doctor.medical-notes.show', [$visit, $note])
                         ->with('success', 'Medical note created successfully.');
    }

    /**
     * Display the specified medical note.
     */
    public function show(Visit $visit, MedicalNote $medicalNote)
    {
        $medicalNote->load(['creator', 'treatment']);
        
        return view('doctor.medical-notes.show', compact('visit', 'medicalNote'));
    }

    /**
     * Show the form for editing the specified medical note.
     */
    public function edit(Visit $visit, MedicalNote $medicalNote)
    {
        $treatments = Treatment::where('visit_id', $visit->id)
                              ->where('status', '!=', 'discontinued')
                              ->get();
        
        return view('doctor.medical-notes.edit', compact('visit', 'medicalNote', 'treatments'));
    }

    /**
     * Update the specified medical note in storage.
     */
    public function update(Request $request, Visit $visit, MedicalNote $medicalNote)
    {
        $validated = $request->validate([
            'treatment_id' => 'nullable|exists:treatments,id',
            'note_type' => 'required|in:examination,progress,consultation,procedure,other',
            'content' => 'required|string',
            'is_private' => 'boolean',
        ]);
        
        $medicalNote->update($validated);
        
        return redirect()->route('doctor.medical-notes.show', [$visit, $medicalNote])
                         ->with('success', 'Medical note updated successfully.');
    }

    /**
     * Remove the specified medical note from storage.
     */
    public function destroy(Visit $visit, MedicalNote $medicalNote)
    {
        // Only allow deletion if the note was created by the current user
        if ($medicalNote->created_by === Auth::id()) {
            $medicalNote->delete();
            
            return redirect()->route('doctor.medical-notes.index', [$visit])
                             ->with('success', 'Medical note deleted successfully.');
        }
        
        return redirect()->route('doctor.medical-notes.index', [$visit])
                         ->with('error', 'You are not authorized to delete this note.');
    }
}
