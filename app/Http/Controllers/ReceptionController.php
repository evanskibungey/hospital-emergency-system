<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReceptionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'role:reception,admin']);
    }

    /**
     * Display the reception dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // Get waiting patients
        $waitingPatients = Visit::with('patient')
            ->where('status', 'waiting')
            ->orderBy('priority', 'desc')
            ->orderBy('check_in_time', 'asc')
            ->get();

        // Get recent arrivals
        $recentArrivals = Visit::with('patient')
            ->where('check_in_time', '>=', Carbon::now()->subHours(4))
            ->orderBy('check_in_time', 'desc')
            ->take(10)
            ->get();

        // Get bed availability
        $totalBeds = 50; // This should come from configuration or DB
        $occupiedBeds = Visit::whereNotNull('bed_number')
            ->whereIn('status', ['in_progress', 'treated'])
            ->count();
        $availableBeds = $totalBeds - $occupiedBeds;

        return view('reception.dashboard', compact(
            'waitingPatients',
            'recentArrivals',
            'totalBeds',
            'occupiedBeds',
            'availableBeds'
        ));
    }

    /**
     * Show the form to register a new patient.
     *
     * @return \Illuminate\View\View
     */
    public function createPatient()
    {
        return view('reception.patients.create');
    }

    /**
     * Store a new patient in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storePatient(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'phone_number' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relationship' => 'nullable|string|max:255',
            'insurance_provider' => 'nullable|string|max:255',
            'insurance_policy_number' => 'nullable|string|max:255',
            'medical_history' => 'nullable|string',
            'allergies' => 'nullable|string',
            'current_medications' => 'nullable|string',
        ]);

        $patient = Patient::create($validated);
        
        // Redirect to visit creation page instead of auto-creating a visit
        return redirect()->route('reception.visits.create', $patient)
            ->with('success', 'Patient registered successfully. Please check them in now to add them to the dashboard.');
    }

    /**
     * Display the specified patient.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\View\View
     */
    public function showPatient(Patient $patient)
    {
        $patient->load('visits');
        return view('reception.patients.show', compact('patient'));
    }

    /**
     * Search for patients.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function searchPatients(Request $request)
    {
        $search = $request->input('search');
        
        $patients = Patient::where('first_name', 'like', "%{$search}%")
            ->orWhere('last_name', 'like', "%{$search}%")
            ->orWhere('phone_number', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%")
            ->paginate(10);

        return view('reception.patients.search', compact('patients', 'search'));
    }

    /**
     * Show the form to register a patient visit.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\View\View
     */
    public function createVisit(Patient $patient)
    {
        return view('reception.visits.create', compact('patient'));
    }

    /**
     * Store a new patient visit in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeVisit(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'chief_complaint' => 'required|string|max:255',
            'priority' => 'required|in:low,medium,high,critical',
            'initial_assessment' => 'nullable|string',
            'department' => 'nullable|string|max:255',
        ]);

        $visit = new Visit($validated);
        $visit->patient_id = $patient->id;
        $visit->registered_by = auth()->id();
        $visit->check_in_time = Carbon::now();
        $visit->status = 'waiting';
        
        // Calculate estimated wait time based on priority
        switch ($validated['priority']) {
            case 'critical':
                $visit->estimated_wait_time = 0; // Immediate
                break;
            case 'high':
                $visit->estimated_wait_time = 15; // 15 minutes
                break;
            case 'medium':
                $visit->estimated_wait_time = 30; // 30 minutes
                break;
            default:
                $visit->estimated_wait_time = 60; // 1 hour
                break;
        }
        
        $visit->save();

        return redirect()->route('reception.dashboard')
            ->with('success', 'Patient checked in successfully.');
    }
}