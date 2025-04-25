<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Patient Visit') }} #{{ $visit->id }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('doctor.dashboard') }}" class="px-4 py-2 bg-gray-200 rounded-md text-gray-700 hover:bg-gray-300">
                    {{ __('Back to Dashboard') }}
                </a>
                @if (!$visit->discharged_at && $visit->isReadyForDischarge())
                    <a href="{{ route('doctor.discharges.create', $visit) }}" class="px-4 py-2 bg-green-500 rounded-md text-white hover:bg-green-600">
                        {{ __('Discharge Patient') }}
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Patient Information Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-lg font-semibold">Patient Information</h3>
                        <div class="flex space-x-2">
                            <!-- Toggle Critical Status Button -->
                            <form method="POST" action="{{ route('doctor.visits.toggle-critical', $visit) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-3 py-1 rounded-md text-sm {{ $visit->is_critical ? 'bg-red-100 text-red-800 hover:bg-red-200' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                                    {{ $visit->is_critical ? 'Critical Patient' : 'Mark as Critical' }}
                                </button>
                            </form>
                            
                            <!-- Assign/Release Button -->
                            @if ($visit->doctor_id === Auth::id())
                                <form method="POST" action="{{ route('doctor.visits.release', $visit) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 bg-red-100 text-red-800 rounded-md hover:bg-red-200 text-sm">
                                        Release Patient
                                    </button>
                                </form>
                            @elseif (!$visit->doctor_id)
                                <form method="POST" action="{{ route('doctor.visits.assign', $visit) }}">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 bg-green-100 text-green-800 rounded-md hover:bg-green-200 text-sm">
                                        Assign to Me
                                    </button>
                                </form>
                            @endif
                            
                            <!-- Patient Summary Button -->
                            <a href="{{ route('doctor.patients.summary', $visit->patient_id) }}" class="px-3 py-1 bg-blue-100 text-blue-800 rounded-md hover:bg-blue-200 text-sm">
                                Patient Summary
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="col-span-1">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-700 mb-2">Demographics</h4>
                                <p><span class="font-medium">Name:</span> {{ $visit->patient->full_name }}</p>
                                <p><span class="font-medium">Age:</span> {{ $visit->patient->date_of_birth->age }} years</p>
                                <p><span class="font-medium">Gender:</span> {{ $visit->patient->gender }}</p>
                                <p><span class="font-medium">MRN:</span> {{ $visit->patient->medical_record_number }}</p>
                                
                                <div class="mt-3 pt-3 border-t border-gray-200">
                                    <p><span class="font-medium">Allergies:</span> 
                                        <span class="{{ empty($visit->patient->allergies) ? 'text-green-600' : 'text-red-600' }}">
                                            {{ empty($visit->patient->allergies) ? 'No Known Allergies' : $visit->patient->allergies }}
                                        </span>
                                    </p>
                                </div>
                                
                                <div class="mt-3 pt-3 border-t border-gray-200">
                                    <p><span class="font-medium">Insurance:</span> {{ $visit->patient->insurance_provider }}</p>
                                    <p><span class="font-medium">Policy #:</span> {{ $visit->patient->insurance_policy_number }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-span-1">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-700 mb-2">Visit Details</h4>
                                <p><span class="font-medium">Chief Complaint:</span> {{ $visit->chief_complaint }}</p>
                                <p>
                                    <span class="font-medium">Status:</span>
                                    <span class="px-2 py-0.5 rounded-full text-xs {{ $visit->is_critical ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $visit->is_critical ? 'CRITICAL' : 'STANDARD' }}
                                    </span>
                                    <span class="px-2 py-0.5 rounded-full text-xs {{ $visit->status === 'waiting' ? 'bg-yellow-100 text-yellow-800' : ($visit->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : ($visit->status === 'discharged' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')) }}">
                                        {{ strtoupper($visit->status) }}
                                    </span>
                                </p>
                                <p><span class="font-medium">Check-in Time:</span> {{ $visit->check_in_time->format('M d, Y g:i A') }}</p>
                                @if ($visit->discharged_at)
                                    <p><span class="font-medium">Discharged:</span> {{ $visit->discharged_at->format('M d, Y g:i A') }}</p>
                                @endif
                                
                                <div class="mt-3 pt-3 border-t border-gray-200">
                                    <p>
                                        <span class="font-medium">Assigned To:</span> 
                                        @if ($visit->doctor)
                                            Dr. {{ $visit->doctor->name }}
                                        @else
                                            <span class="text-yellow-600">Unassigned</span>
                                        @endif
                                    </p>
                                    
                                    @if ($visit->bed)
                                        <p>
                                            <span class="font-medium">Bed:</span> {{ $visit->bed->name }} ({{ $visit->bed->location }})
                                        </p>
                                    @else
                                        <p><span class="font-medium">Bed:</span> <span class="text-yellow-600">Not assigned</span></p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-span-1">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-700 mb-2">Vital Signs</h4>
                                @if ($visit->vitalSigns->count() > 0)
                                    @php $latestVitals = $visit->vitalSigns->first(); @endphp
                                    <div class="text-sm">
                                        <p><span class="font-medium">Taken:</span> {{ $latestVitals->created_at->format('M d, Y g:i A') }}</p>
                                        <div class="grid grid-cols-2 gap-2 mt-2">
                                            <p><span class="font-medium">HR:</span> {{ $latestVitals->heart_rate }} bpm</p>
                                            <p><span class="font-medium">BP:</span> {{ $latestVitals->blood_pressure }}</p>
                                            <p><span class="font-medium">Temp:</span> {{ $latestVitals->temperature }}°F</p>
                                            <p><span class="font-medium">RR:</span> {{ $latestVitals->respiratory_rate }}/min</p>
                                            <p><span class="font-medium">SpO2:</span> {{ $latestVitals->oxygen_saturation }}%</p>
                                            <p><span class="font-medium">Pain:</span> {{ $latestVitals->pain_level }}/10</p>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-yellow-600">No vital signs recorded</p>
                                @endif
                                
                                <div class="mt-3 pt-3 border-t border-gray-200">
                                    <h4 class="font-medium text-gray-700 mb-2">Current Medications</h4>
                                    @if (!empty($visit->patient->current_medications))
                                        <p class="text-sm">{{ $visit->patient->current_medications }}</p>
                                    @else
                                        <p class="text-gray-500 italic">No medications listed</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mb-6 flex flex-wrap gap-3">
                <a href="{{ route('doctor.treatments.create', $visit) }}" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    New Treatment Plan
                </a>
                
                <a href="{{ route('doctor.medical-notes.create', $visit) }}" class="px-4 py-2 bg-indigo-500 text-white rounded-md hover:bg-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Add Medical Note
                </a>
                
                <a href="{{ route('doctor.lab-orders.create', $visit) }}" class="px-4 py-2 bg-purple-500 text-white rounded-md hover:bg-purple-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                    Order Lab Test
                </a>
                
                <a href="{{ route('doctor.imaging-orders.create', $visit) }}" class="px-4 py-2 bg-pink-500 text-white rounded-md hover:bg-pink-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Order Imaging
                </a>
                
                <a href="{{ route('doctor.prescriptions.create', $visit) }}" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Write Prescription
                </a>
                
                <a href="{{ route('doctor.tasks.create') }}?visit_id={{ $visit->id }}" class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    Create Task
                </a>
                
                <a href="{{ route('doctor.consultations.create', $visit) }}" class="px-4 py-2 bg-teal-500 text-white rounded-md hover:bg-teal-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                    </svg>
                    Request Consultation
                </a>
                
                @if ($visit->isReadyForDischarge() && !$visit->discharged_at)
                    <a href="{{ route('doctor.discharges.create', $visit) }}" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Discharge Patient
                    </a>
                @endif
            </div>

            <!-- Treatment Plans Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold">Treatment Plans</h3>
                        <a href="{{ route('doctor.treatments.create', $visit) }}" class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 text-sm">
                            New Plan
                        </a>
                    </div>
                </div>
                <div class="p-4">
                    @if ($visit->treatments->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Created</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diagnosis</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Provider</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($visit->treatments as $treatment)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $treatment->created_at->format('M d, Y g:i A') }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                                {{ Str::limit($treatment->diagnosis, 100) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <span class="px-2 py-1 rounded-full text-xs 
                                                    {{ $treatment->status === 'active' ? 'bg-green-100 text-green-800' : 
                                                       ($treatment->status === 'completed' ? 'bg-blue-100 text-blue-800' : 
                                                       ($treatment->status === 'discontinued' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                                    {{ ucfirst($treatment->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                Dr. {{ $treatment->creator->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <a href="{{ route('doctor.treatments.show', $treatment) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                                @if (in_array($treatment->status, ['draft', 'active']) && $treatment->created_by === Auth::id())
                                                    <a href="{{ route('doctor.treatments.edit', $treatment) }}" class="ml-3 text-blue-600 hover:text-blue-900">Edit</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 italic">No treatment plans have been created for this visit yet.</p>
                    @endif
                </div>
            </div>

            <!-- Medical Notes Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold">Medical Notes</h3>
                        <a href="{{ route('doctor.medical-notes.create', $visit) }}" class="px-3 py-1 bg-indigo-500 text-white rounded-md hover:bg-indigo-600 text-sm">
                            Add Note
                        </a>
                    </div>
                </div>
                <div class="p-4">
                    @if ($visit->medicalNotes->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date/Time</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Content</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($visit->medicalNotes as $note)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $note->created_at->format('M d, Y g:i A') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ ucfirst($note->note_type) }}
                                                @if ($note->is_private)
                                                    <span class="ml-1 px-2 py-0.5 bg-red-100 text-red-800 rounded-full text-xs">Private</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                Dr. {{ $note->creator->name }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500 max-w-md truncate">
                                                {{ Str::limit($note->content, 100) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <a href="{{ route('doctor.medical-notes.show', ['visit' => $visit->id, 'medicalNote' => $note->id]) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                                @if ($note->created_by === Auth::id())
                                                    <a href="{{ route('doctor.medical-notes.edit', ['visit' => $visit->id, 'medicalNote' => $note->id]) }}" class="ml-3 text-blue-600 hover:text-blue-900">Edit</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 italic">No medical notes have been added for this visit yet.</p>
                    @endif
                </div>
            </div>

            <!-- Lab and Imaging Orders Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Lab Orders -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold">Lab Orders</h3>
                            <a href="{{ route('doctor.lab-orders.create', $visit) }}" class="px-3 py-1 bg-purple-500 text-white rounded-md hover:bg-purple-600 text-sm">
                                Order Lab
                            </a>
                        </div>
                    </div>
                    <div class="p-4">
                        @if ($visit->labOrders->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Test</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Result</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($visit->labOrders as $order)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                                                    {{ $order->ordered_at->format('M d, Y') }}
                                                </td>
                                                <td class="px-6 py-4 text-xs text-gray-500">
                                                    <a href="{{ route('doctor.lab-orders.show', $order) }}" class="text-blue-600 hover:text-blue-900 font-medium">
                                                        {{ $order->test_name }}
                                                    </a>
                                                    @if ($order->is_stat)
                                                        <span class="ml-1 px-2 py-0.5 bg-red-100 text-red-800 rounded-full text-xs">STAT</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-xs">
                                                    <span class="px-2 py-1 rounded-full text-xs 
                                                        {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                                           ($order->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 
                                                           ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                                        {{ ucfirst($order->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-xs text-gray-500 max-w-xs truncate">
                                                    {{ $order->result_summary ?? 'Pending' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500 italic">No lab orders have been created for this visit yet.</p>
                        @endif
                    </div>
                </div>

                <!-- Imaging Orders -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold">Imaging Orders</h3>
                            <a href="{{ route('doctor.imaging-orders.create', $visit) }}" class="px-3 py-1 bg-pink-500 text-white rounded-md hover:bg-pink-600 text-sm">
                                Order Imaging
                            </a>
                        </div>
                    </div>
                    <div class="p-4">
                        @if ($visit->imagingOrders->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Exam</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Result</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($visit->imagingOrders as $order)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                                                    {{ $order->ordered_at->format('M d, Y') }}
                                                </td>
                                                <td class="px-6 py-4 text-xs text-gray-500">
                                                    <a href="{{ route('doctor.imaging-orders.show', $order) }}" class="text-blue-600 hover:text-blue-900 font-medium">
                                                        {{ $order->imaging_type }} - {{ $order->body_part }}
                                                    </a>
                                                    @if ($order->is_stat)
                                                        <span class="ml-1 px-2 py-0.5 bg-red-100 text-red-800 rounded-full text-xs">STAT</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-xs">
                                                    <span class="px-2 py-1 rounded-full text-xs 
                                                        {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                                           ($order->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 
                                                           ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                                        {{ ucfirst($order->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-xs text-gray-500 max-w-xs truncate">
                                                    {{ $order->impression ?? 'Pending' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500 italic">No imaging orders have been created for this visit yet.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Medications and Prescriptions Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Medications Due -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold">Medications Due</h3>
                    </div>
                    <div class="p-4">
                        @if ($dueAndOverdueMedications->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scheduled</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Medication</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dosage</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($dueAndOverdueMedications as $med)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                                                    {{ $med->scheduled_time->format('M d, g:i A') }}
                                                    @if ($med->isOverdue())
                                                        <span class="ml-1 px-2 py-0.5 bg-red-100 text-red-800 rounded-full text-xs">Overdue</span>
                                                    @elseif ($med->isDue())
                                                        <span class="ml-1 px-2 py-0.5 bg-yellow-100 text-yellow-800 rounded-full text-xs">Due</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-xs text-gray-500">
                                                    {{ $med->medication->name }}
                                                </td>
                                                <td class="px-6 py-4 text-xs text-gray-500">
                                                    {{ $med->dosage }} {{ $med->medication->dosage_form }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                                                    @if ($med->status === 'scheduled')
                                                        <a href="{{ route('nurse.medication-administrations.create', ['visit' => $visit->id, 'medicationSchedule' => $med->id]) }}" class="text-blue-600 hover:text-blue-900">
                                                            Administer
                                                        </a>
                                                    @else
                                                        {{ ucfirst($med->status) }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500 italic">No medications are currently due for this patient.</p>
                        @endif
                    </div>
                </div>

                <!-- Prescriptions -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold">Prescriptions</h3>
                            <a href="{{ route('doctor.prescriptions.create', $visit) }}" class="px-3 py-1 bg-green-500 text-white rounded-md hover:bg-green-600 text-sm">
                                Write Prescription
                            </a>
                        </div>
                    </div>
                    <div class="p-4">
                        @if ($visit->prescriptions->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Medication</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dosage</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($visit->prescriptions as $prescription)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                                                    {{ $prescription->created_at->format('M d, Y') }}
                                                </td>
                                                <td class="px-6 py-4 text-xs text-gray-500">
                                                    {{ $prescription->medication_name }}
                                                    @if ($prescription->is_controlled_substance)
                                                        <span class="ml-1 px-2 py-0.5 bg-yellow-100 text-yellow-800 rounded-full text-xs">Controlled</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-xs text-gray-500">
                                                    {{ $prescription->dosage }}, {{ $prescription->frequency }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-xs">
                                                    <span class="px-2 py-1 rounded-full text-xs 
                                                        {{ $prescription->status === 'active' ? 'bg-green-100 text-green-800' : 
                                                           ($prescription->status === 'completed' ? 'bg-blue-100 text-blue-800' : 
                                                           ($prescription->status === 'cancelled' ? 'bg-red-100 text-red-800' : 
                                                           ($prescription->status === 'on_hold' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800'))) }}">
                                                        {{ ucfirst($prescription->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-xs">
                                                    <a href="{{ route('doctor.prescriptions.show', $prescription) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500 italic">No prescriptions have been written for this visit yet.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Tasks and Consultations Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tasks -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold">Tasks</h3>
                            <a href="{{ route('doctor.tasks.create') }}?visit_id={{ $visit->id }}" class="px-3 py-1 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 text-sm">
                                Create Task
                            </a>
                        </div>
                    </div>
                    <div class="p-4">
                        @if ($visit->doctorTasks->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Task</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($visit->doctorTasks as $task)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                                                    {{ $task->due_at->format('M d, g:i A') }}
                                                </td>
                                                <td class="px-6 py-4 text-xs text-gray-500">
                                                    {{ $task->title }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-xs">
                                                    <span class="px-2 py-1 rounded-full text-xs {{ $task->priority === 'high' ? 'bg-red-100 text-red-800' : ($task->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                                                        {{ ucfirst($task->priority) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-xs">
                                                    <span class="px-2 py-1 rounded-full text-xs 
                                                        {{ $task->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                                           ($task->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 
                                                           ($task->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                                        {{ ucfirst($task->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-xs">
                                                    <a href="{{ route('doctor.tasks.show', $task) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500 italic">No tasks have been created for this visit yet.</p>
                        @endif
                    </div>
                </div>

                <!-- Consultations -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold">Consultations</h3>
                            <a href="{{ route('doctor.consultations.create', $visit) }}" class="px-3 py-1 bg-teal-500 text-white rounded-md hover:bg-teal-600 text-sm">
                                Request Consult
                            </a>
                        </div>
                    </div>
                    <div class="p-4">
                        @if ($visit->consultationRequests->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">From</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">To</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($visit->consultationRequests as $consult)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                                                    {{ $consult->created_at->format('M d, Y') }}
                                                </td>
                                                <td class="px-6 py-4 text-xs text-gray-500">
                                                    Dr. {{ $consult->requester->name }}
                                                </td>
                                                <td class="px-6 py-4 text-xs text-gray-500">
                                                    Dr. {{ $consult->doctor->name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-xs">
                                                    <span class="px-2 py-1 rounded-full text-xs 
                                                        {{ $consult->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                                           ($consult->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 
                                                           ($consult->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                                        {{ ucfirst($consult->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-xs">
                                                    <a href="{{ route('doctor.consultations.show', $consult) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500 italic">No consultation requests have been created for this visit yet.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
