<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\DoctorTask;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorTaskController extends Controller
{
    /**
     * Display a listing of the doctor's tasks.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $doctor = Auth::user();
        
        $pendingTasks = DoctorTask::where('doctor_id', $doctor->id)
                                  ->pending()
                                  ->with('visit.patient')
                                  ->orderBy('priority', 'desc')
                                  ->orderBy('due_at', 'asc')
                                  ->get();
        
        $inProgressTasks = DoctorTask::where('doctor_id', $doctor->id)
                                     ->inProgress()
                                     ->with('visit.patient')
                                     ->get();
        
        $overdueTasks = DoctorTask::where('doctor_id', $doctor->id)
                                  ->overdue()
                                  ->with('visit.patient')
                                  ->get();
        
        $completedTasks = DoctorTask::where('doctor_id', $doctor->id)
                                    ->completed()
                                    ->with('visit.patient')
                                    ->latest('completed_at')
                                    ->limit(10)
                                    ->get();
        
        return view('doctor.tasks.index', compact(
            'pendingTasks',
            'inProgressTasks',
            'overdueTasks',
            'completedTasks'
        ));
    }

    /**
     * Show the form for creating a new task.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $doctor = Auth::user();
        $visits = Visit::assignedToDoctor($doctor->id)
                       ->active()
                       ->with('patient')
                       ->get();
        
        return view('doctor.tasks.create', compact('visits'));
    }

    /**
     * Store a newly created task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'priority' => 'required|in:low,medium,high,urgent',
            'visit_id' => 'nullable|exists:visits,id',
            'due_at' => 'nullable|date',
        ]);
        
        DoctorTask::create([
            'doctor_id' => Auth::id(),
            'visit_id' => $request->visit_id,
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'due_at' => $request->due_at,
            'status' => 'pending',
        ]);
        
        return redirect()->route('doctor.tasks.index')
                         ->with('success', 'Task created successfully');
    }

    /**
     * Display the specified task.
     *
     * @param  \App\Models\DoctorTask  $task
     * @return \Illuminate\View\View
     */
    public function show(DoctorTask $task)
    {
        // Ensure the task belongs to the current doctor
        if ($task->doctor_id !== Auth::id()) {
            return redirect()->route('doctor.tasks.index')
                             ->with('error', 'You do not have permission to view this task');
        }
        
        $task->load('visit.patient');
        
        return view('doctor.tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified task.
     *
     * @param  \App\Models\DoctorTask  $task
     * @return \Illuminate\View\View
     */
    public function edit(DoctorTask $task)
    {
        // Ensure the task belongs to the current doctor
        if ($task->doctor_id !== Auth::id()) {
            return redirect()->route('doctor.tasks.index')
                             ->with('error', 'You do not have permission to edit this task');
        }
        
        $doctor = Auth::user();
        $visits = Visit::assignedToDoctor($doctor->id)
                       ->active()
                       ->with('patient')
                       ->get();
        
        return view('doctor.tasks.edit', compact('task', 'visits'));
    }

    /**
     * Update the specified task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DoctorTask  $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, DoctorTask $task)
    {
        // Ensure the task belongs to the current doctor
        if ($task->doctor_id !== Auth::id()) {
            return redirect()->route('doctor.tasks.index')
                             ->with('error', 'You do not have permission to update this task');
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'priority' => 'required|in:low,medium,high,urgent',
            'visit_id' => 'nullable|exists:visits,id',
            'due_at' => 'nullable|date',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
        ]);
        
        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'visit_id' => $request->visit_id,
            'due_at' => $request->due_at,
            'status' => $request->status,
            'completed_at' => $request->status === 'completed' ? now() : $task->completed_at,
        ]);
        
        return redirect()->route('doctor.tasks.index')
                         ->with('success', 'Task updated successfully');
    }

    /**
     * Update the status of the specified task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DoctorTask  $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, DoctorTask $task)
    {
        // Ensure the task belongs to the current doctor
        if ($task->doctor_id !== Auth::id()) {
            return redirect()->route('doctor.tasks.index')
                             ->with('error', 'You do not have permission to update this task');
        }
        
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
        ]);
        
        $task->status = $request->status;
        
        if ($request->status === 'completed') {
            $task->completed_at = now();
        }
        
        $task->save();
        
        return redirect()->back()
                         ->with('success', 'Task status updated successfully');
    }

    /**
     * Start the specified task.
     *
     * @param  \App\Models\DoctorTask  $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function startTask(DoctorTask $task)
    {
        // Ensure the task belongs to the current doctor
        if ($task->doctor_id !== Auth::id()) {
            return redirect()->route('doctor.tasks.index')
                             ->with('error', 'You do not have permission to start this task');
        }
        
        if ($task->status !== 'pending') {
            return redirect()->route('doctor.tasks.index')
                             ->with('error', 'Only pending tasks can be started');
        }
        
        $task->status = 'in_progress';
        $task->save();
        
        return redirect()->back()
                         ->with('success', 'Task started successfully');
    }

    /**
     * Complete the specified task.
     *
     * @param  \App\Models\DoctorTask  $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function completeTask(DoctorTask $task)
    {
        // Ensure the task belongs to the current doctor
        if ($task->doctor_id !== Auth::id()) {
            return redirect()->route('doctor.tasks.index')
                             ->with('error', 'You do not have permission to complete this task');
        }
        
        if (!in_array($task->status, ['pending', 'in_progress'])) {
            return redirect()->route('doctor.tasks.index')
                             ->with('error', 'Only pending or in-progress tasks can be completed');
        }
        
        $task->status = 'completed';
        $task->completed_at = now();
        $task->save();
        
        return redirect()->back()
                         ->with('success', 'Task completed successfully');
    }

    /**
     * Remove the specified task.
     *
     * @param  \App\Models\DoctorTask  $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(DoctorTask $task)
    {
        // Ensure the task belongs to the current doctor
        if ($task->doctor_id !== Auth::id()) {
            return redirect()->route('doctor.tasks.index')
                             ->with('error', 'You do not have permission to delete this task');
        }
        
        $task->delete();
        
        return redirect()->route('doctor.tasks.index')
                         ->with('success', 'Task deleted successfully');
    }
}
