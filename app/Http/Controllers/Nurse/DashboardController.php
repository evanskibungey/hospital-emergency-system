<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use App\Models\Visit;
use App\Models\Patient;
use App\Models\VitalSign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the nurse dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get both waiting and active patient visits
        $activeVisits = Visit::with(['patient', 'vitalSigns' => function ($query) {
            $query->latest()->limit(1);
        }])
        ->whereIn('status', ['waiting', 'in_progress']) // Use in_progress instead of active
        ->latest('check_in_time')
        ->limit(10)
        ->get();
            
        // Get recently registered patients
        $recentPatients = Patient::latest()
            ->limit(5)
            ->get();
            
        // Get patients needing vital signs (those without recent vitals)
        $needVitals = Visit::with('patient')
            ->whereIn('status', ['waiting', 'active']) // Include waiting patients
            ->whereDoesntHave('vitalSigns', function ($query) {
                $query->where('created_at', '>=', Carbon::now()->subHours(4));
            })
            ->latest('check_in_time')
            ->limit(5)
            ->get();
            
        // For now, we'll use a placeholder for pending tasks
        $pendingTasks = [];
        
        return view('nurse.dashboard', compact(
            'activeVisits', 
            'recentPatients',
            'needVitals',
            'pendingTasks'
        ));
    }
    
    /**
     * Update a visit status to "active" when nurse begins care.
     *
     * @param  \App\Models\Visit  $visit
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assignVisit(Visit $visit)
    {
        // Update the visit status to in_progress (not active)
        $visit->status = 'in_progress'; // This value is in your ENUM
        $visit->assigned_to = Auth::id();
        $visit->save();
        
        return redirect()->route('nurse.vital-signs.create', $visit->id)
            ->with('success', 'Patient assigned to you. Please record vital signs.');
    }
}