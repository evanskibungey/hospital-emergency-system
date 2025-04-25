<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\ConsultationRequest;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConsultationRequestController extends Controller
{
    /**
     * Display a listing of consultation requests.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $doctor = Auth::user();
        
        $pendingConsultations = ConsultationRequest::where('doctor_id', $doctor->id)
                                                  ->pending()
                                                  ->with(['visit.patient', 'requester'])
                                                  ->latest()
                                                  ->get();
        
        $activeConsultations = ConsultationRequest::where('doctor_id', $doctor->id)
                                                 ->accepted()
                                                 ->with(['visit.patient', 'requester'])
                                                 ->latest()
                                                 ->get();
        
        $completedConsultations = ConsultationRequest::where('doctor_id', $doctor->id)
                                                    ->completed()
                                                    ->with(['visit.patient', 'requester'])
                                                    ->latest()
                                                    ->limit(10)
                                                    ->get();
        
        return view('doctor.consultations.index', compact(
            'pendingConsultations',
            'activeConsultations',
            'completedConsultations'
        ));
    }

    /**
     * Show the form for creating a new consultation request.
     *
     * @param  \App\Models\Visit  $visit
     * @return \Illuminate\View\View
     */
    public function create(Visit $visit)
    {
        $doctors = User::whereHas('roles', function($query) {
                        $query->where('slug', 'doctor');
                    })
                    ->where('id', '!=', Auth::id())
                    ->get();
        
        return view('doctor.consultations.create', compact('visit', 'doctors'));
    }

    /**
     * Store a newly created consultation request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Visit  $visit
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Visit $visit)
    {
        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'reason' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);
        
        ConsultationRequest::create([
            'requesting_user_id' => Auth::id(),
            'doctor_id' => $request->doctor_id,
            'visit_id' => $visit->id,
            'status' => 'pending',
            'reason' => $request->reason,
            'notes' => $request->notes,
            'requested_at' => now(),
        ]);
        
        return redirect()->route('doctor.consultations.index')
                         ->with('success', 'Consultation request created successfully');
    }

    /**
     * Display the specified consultation request.
     *
     * @param  \App\Models\ConsultationRequest  $consultationRequest
     * @return \Illuminate\View\View
     */
    public function show(ConsultationRequest $consultationRequest)
    {
        $consultationRequest->load(['visit.patient', 'requester', 'doctor', 'visit.vitalSigns']);
        
        return view('doctor.consultations.show', compact('consultationRequest'));
    }

    /**
     * Accept a consultation request.
     *
     * @param  \App\Models\ConsultationRequest  $consultationRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function accept(ConsultationRequest $consultationRequest)
    {
        $doctor = Auth::user();
        
        // Check if the consultation is assigned to the current doctor
        if ($consultationRequest->doctor_id !== $doctor->id) {
            return redirect()->back()
                             ->with('error', 'You cannot accept a consultation that is not assigned to you');
        }
        
        // Check if the consultation is in pending status
        if (!$consultationRequest->isPending()) {
            return redirect()->back()
                             ->with('error', 'This consultation request has already been processed');
        }
        
        $consultationRequest->status = 'accepted';
        $consultationRequest->accepted_at = now();
        $consultationRequest->save();
        
        return redirect()->route('doctor.consultations.show', $consultationRequest)
                         ->with('success', 'Consultation request accepted successfully');
    }

    /**
     * Complete a consultation request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ConsultationRequest  $consultationRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function complete(Request $request, ConsultationRequest $consultationRequest)
    {
        $request->validate([
            'response' => 'required|string|max:2000',
        ]);
        
        $doctor = Auth::user();
        
        // Check if the consultation is assigned to the current doctor
        if ($consultationRequest->doctor_id !== $doctor->id) {
            return redirect()->back()
                             ->with('error', 'You cannot complete a consultation that is not assigned to you');
        }
        
        // Check if the consultation is in accepted status
        if (!$consultationRequest->isAccepted()) {
            return redirect()->back()
                             ->with('error', 'This consultation request must be accepted before it can be completed');
        }
        
        $consultationRequest->status = 'completed';
        $consultationRequest->response = $request->response;
        $consultationRequest->completed_at = now();
        $consultationRequest->save();
        
        return redirect()->route('doctor.consultations.index')
                         ->with('success', 'Consultation request completed successfully');
    }
}
