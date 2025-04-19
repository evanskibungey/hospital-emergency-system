<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;

class VisitorController extends Controller
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
     * Display a listing of the visitors.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $activeVisitors = Visitor::with('patient')
            ->where('is_active', true)
            ->orderBy('check_in_time', 'desc')
            ->paginate(15);

        return view('reception.visitors.index', compact('activeVisitors'));
    }

    /**
     * Show the form for creating a new visitor.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\View\View
     */
    public function create(Patient $patient)
    {
        return view('reception.visitors.create', compact('patient'));
    }

    /**
     * Store a newly created visitor in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'relationship_to_patient' => 'required|string|max:255',
            'id_type' => 'nullable|string|max:255',
            'id_number' => 'nullable|string|max:255',
        ]);

        // Generate a unique pass number
        $passNumber = 'V-' . strtoupper(Str::random(8));

        $visitor = new Visitor($validated);
        $visitor->patient_id = $patient->id;
        $visitor->check_in_time = Carbon::now();
        $visitor->pass_number = $passNumber;
        $visitor->is_active = true;
        $visitor->registered_by = auth()->id();
        $visitor->save();

        return redirect()->route('reception.visitors.show', $visitor)
            ->with('success', 'Visitor registered successfully.');
    }

    /**
     * Display the specified visitor.
     *
     * @param  \App\Models\Visitor  $visitor
     * @return \Illuminate\View\View
     */
    public function show(Visitor $visitor)
    {
        $visitor->load('patient');
        return view('reception.visitors.show', compact('visitor'));
    }

    /**
     * Check out a visitor.
     *
     * @param  \App\Models\Visitor  $visitor
     * @return \Illuminate\Http\RedirectResponse
     */
    public function checkout(Visitor $visitor)
    {
        $visitor->check_out_time = Carbon::now();
        $visitor->is_active = false;
        $visitor->save();

        return redirect()->route('reception.visitors.index')
            ->with('success', 'Visitor checked out successfully.');
    }

    /**
     * Search for visitors.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function search(Request $request)
    {
        $search = $request->input('search');
        
        $visitors = Visitor::where(function ($query) use ($search) {
                $query->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%")
                    ->orWhere('pass_number', 'like', "%{$search}%");
            })
            ->orderBy('check_in_time', 'desc')
            ->paginate(15);

        return view('reception.visitors.search', compact('visitors', 'search'));
    }
}