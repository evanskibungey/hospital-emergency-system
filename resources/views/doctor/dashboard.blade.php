<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Doctor Dashboard') }}
            </h2>
            <div class="flex space-x-2">
                <form method="POST" action="{{ route('doctor.on-call-status.update') }}" class="inline-flex items-center">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="is_on_call" value="{{ Auth::user()->is_on_call ? 0 : 1 }}">
                    <button type="submit" 
                        class="px-4 py-2 rounded-md font-medium text-sm transition-colors {{ Auth::user()->is_on_call ? 'bg-green-500 hover:bg-green-600 text-white' : 'bg-gray-200 hover:bg-gray-300 text-gray-700' }} flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.636 18.364a9 9 0 010-12.728m12.728 0a9 9 0 010 12.728m-9.9-2.829a5 5 0 010-7.07m7.072 0a5 5 0 010 7.07M13 12a1 1 0 11-2 0 1 1 0 012 0z" />
                        </svg>
                        {{ Auth::user()->is_on_call ? 'On Call' : 'Off Call' }}
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-md shadow-sm flex items-center" role="alert">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-md shadow-sm flex items-center" role="alert">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif
            
            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <!-- Assigned Patients Card -->
                <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-blue-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 rounded-full p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">My Patients</dt>
                                <dd>
                                    <div class="text-lg font-semibold text-gray-900">{{ $assignedVisits->count() }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                
                <!-- Critical Patients Card -->
                <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-red-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-red-100 rounded-full p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Critical Patients</dt>
                                <dd>
                                    <div class="text-lg font-semibold text-gray-900">{{ $criticalVisits->count() }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                
                <!-- Pending Tasks Card -->
                <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-yellow-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-100 rounded-full p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Pending Tasks</dt>
                                <dd>
                                    <div class="text-lg font-semibold text-gray-900">{{ $pendingTasks->count() }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                
                <!-- Pending Consultations Card -->
                <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-indigo-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-indigo-100 rounded-full p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Pending Consultations</dt>
                                <dd>
                                    <div class="text-lg font-semibold text-gray-900">{{ $pendingConsultations->count() }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Critical Patients Section -->
            @if ($criticalVisits->count() > 0)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6 border-l-4 border-red-500">
                    <div class="border-b border-gray-200 bg-gray-50 p-4">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            Critical Patients ({{ $criticalVisits->count() }})
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chief Complaint</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-in Time</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned To</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($criticalVisits as $visit)
                                    <tr class="hover:bg-red-50">
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $visit->patient->full_name }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        {{ $visit->patient->date_of_birth->age }} yrs, {{ $visit->patient->gender }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $visit->chief_complaint }}</div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $visit->check_in_time->format('M d, g:i A') }}</div>
                                            <div class="text-xs text-gray-500">{{ $visit->check_in_time->diffForHumans() }}</div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            @if ($visit->doctor)
                                                <div class="text-sm text-gray-900 flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                    Dr. {{ $visit->doctor->name }}
                                                </div>
                                            @else
                                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 font-medium">
                                                    Unassigned
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('doctor.visits.show', $visit) }}" class="px-2 py-1 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded text-xs inline-flex items-center transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    View
                                                </a>
                                                
                                                @if (!$visit->doctor_id)
                                                    <form method="POST" action="{{ route('doctor.visits.assign', $visit) }}" class="inline-block">
                                                        @csrf
                                                        <button type="submit" class="px-2 py-1 bg-green-100 text-green-700 hover:bg-green-200 rounded text-xs inline-flex items-center transition-colors">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                            </svg>
                                                            Assign to me
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Assigned Patients Section -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
                <div class="border-b border-gray-200 bg-gray-50 p-4">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        My Patients ({{ $assignedVisits->count() }})
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    @if ($assignedVisits->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chief Complaint</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vitals</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($assignedVisits as $visit)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $visit->patient->full_name }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        {{ $visit->patient->date_of_birth->age }} yrs, {{ $visit->patient->gender }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $visit->chief_complaint }}</div>
                                            @if ($visit->is_critical)
                                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800 font-medium">CRITICAL</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            @if ($visit->latestVitalSigns)
                                                <div class="text-xs">
                                                    <div class="flex items-center text-gray-700 mb-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                        </svg>
                                                        HR: {{ $visit->latestVitalSigns->heart_rate }} bpm
                                                    </div>
                                                    <div class="flex items-center text-gray-700 mb-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                        </svg>
                                                        BP: {{ $visit->latestVitalSigns->blood_pressure }}
                                                    </div>
                                                    <div class="flex items-center text-gray-700">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                                        </svg>
                                                        Temp: {{ $visit->latestVitalSigns->temperature }}°F
                                                    </div>
                                                </div>
                                            @else
                                                <div class="text-xs text-gray-500">No vitals recorded</div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            @if ($visit->bed)
                                                <div class="text-sm text-gray-900 flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                    </svg>
                                                    {{ $visit->bed->name }}
                                                </div>
                                                <div class="text-xs text-gray-500 ml-5">{{ $visit->bed->location }}</div>
                                            @else
                                                <div class="text-xs text-gray-500">No bed assigned</div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            @if($visit->hasActiveTreatments())
                                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 font-medium">Active Treatment</span>
                                            @elseif($visit->isReadyForDischarge())
                                                <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 font-medium">Ready for Discharge</span>
                                            @else
                                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 font-medium">Needs Treatment</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('doctor.visits.show', $visit) }}" class="px-2 py-1 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded text-xs inline-flex items-center transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    View
                                                </a>
                                                
                                                <form method="POST" action="{{ route('doctor.visits.release', $visit) }}" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Are you sure you want to release this patient?')" class="px-2 py-1 bg-red-100 text-red-700 hover:bg-red-200 rounded text-xs inline-flex items-center transition-colors">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                                        </svg>
                                                        Release
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="p-4">
                            <p class="text-gray-500 italic">You don't have any patients assigned to you at the moment.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tasks and Consultations Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Pending Tasks Section -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="border-b border-gray-200 bg-gray-50 p-4">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Pending Tasks ({{ $pendingTasks->count() }})
                            </h3>
                            <a href="{{ route('doctor.tasks.index') }}" class="inline-flex items-center px-3 py-1.5 bg-yellow-600 border border-transparent rounded-md font-medium text-xs text-white hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors">
                                {{ __('View All') }}
                            </a>
                        </div>
                    </div>
                    <div class="p-4 max-h-64 overflow-y-auto">
                        @if ($pendingTasks->count() > 0)
                            <ul class="divide-y divide-gray-200">
                                @foreach ($pendingTasks as $task)
                                    <li class="py-3 flex justify-between items-start hover:bg-gray-50 px-2 rounded transition-colors">
                                        <div>
                                            <div class="flex items-center">
                                                <span class="px-2 py-1 rounded-full text-xs mr-2 {{ $task->priority === 'high' ? 'bg-red-100 text-red-800' : ($task->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }} font-medium">{{ ucfirst($task->priority) }}</span>
                                                <span class="text-sm font-medium text-gray-900">{{ $task->title }}</span>
                                            </div>
                                            <p class="text-sm text-gray-500 mt-1">
                                                {{ Str::limit($task->description, 75) }}
                                            </p>
                                            <div class="mt-1 text-xs text-gray-500 flex items-center space-x-3">
                                                @if ($task->visit)
                                                    <span class="flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                        </svg>
                                                        {{ $task->visit->patient->full_name }}
                                                    </span>
                                                @endif
                                                <span class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    {{ $task->due_at->format('M d, g:i A') }}
                                                </span>
                                            </div>
                                        </div>
                                        <a href="{{ route('doctor.tasks.show', $task) }}" class="text-indigo-600 hover:text-indigo-900 text-sm whitespace-nowrap px-2 py-1 inline-flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            View
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500 italic">You don't have any pending tasks.</p>
                        @endif
                    </div>
                </div>

                <!-- Pending Consultations Section -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="border-b border-gray-200 bg-gray-50 p-4">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                </svg>
                                Pending Consultations ({{ $pendingConsultations->count() }})
                            </h3>
                            <a href="{{ route('doctor.consultations.index') }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 border border-transparent rounded-md font-medium text-xs text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                {{ __('View All') }}
                            </a>
                        </div>
                    </div>
                    <div class="p-4 max-h-64 overflow-y-auto">
                        @if ($pendingConsultations->count() > 0)
                            <ul class="divide-y divide-gray-200">
                                @foreach ($pendingConsultations as $consult)
                                    <li class="py-3 hover:bg-gray-50 px-2 rounded transition-colors">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <span class="text-sm font-medium text-gray-900 flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                    {{ $consult->visit->patient->full_name }}
                                                </span>
                                                <p class="text-sm text-gray-500 mt-1">{{ Str::limit($consult->reason, 100) }}</p>
                                                <div class="mt-1 text-xs text-gray-500 flex items-center space-x-3">
                                                    <span class="flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                        </svg>
                                                        {{ $consult->requester->name }}
                                                    </span>
                                                    <span class="flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        {{ $consult->created_at->format('M d, g:i A') }}
                                                    </span>
                                                </div>
                                            </div>
                                            <a href="{{ route('doctor.consultations.show', $consult) }}" class="text-indigo-600 hover:text-indigo-900 text-sm whitespace-nowrap px-2 py-1 inline-flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                View
                                            </a>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500 italic">You don't have any pending consultation requests.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Treatment, Lab Orders and Imaging Orders Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Active Treatments Section -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            Active Treatments ({{ $activeTreatments->count() }})
                        </h3>
                    </div>
                    <div class="p-4 max-h-64 overflow-y-auto">
                        @if ($activeTreatments->count() > 0)
                            <ul class="divide-y divide-gray-200">
                                @foreach ($activeTreatments as $treatment)
                                    <li class="py-3">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <span class="text-sm font-medium text-gray-900">
                                                    {{ $treatment->visit->patient->full_name }}
                                                </span>
                                                <p class="text-sm text-gray-500">Diagnosis: {{ Str::limit($treatment->diagnosis, 100) }}</p>
                                                <div class="mt-1 text-xs text-gray-500">
                                                    <span>Started: {{ $treatment->started_at->format('M d, g:i A') }}</span>
                                                </div>
                                            </div>
                                            <a href="{{ route('doctor.treatments.show', $treatment) }}" class="text-indigo-600 hover:text-indigo-900 text-sm whitespace-nowrap">View</a>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500 italic">You don't have any active treatments.</p>
                        @endif
                    </div>
                </div>

                <!-- Pending Orders Section -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Pending Orders ({{ $pendingLabOrders->count() + $pendingImagingOrders->count() }})
                        </h3>
                    </div>
                    <div class="p-4 max-h-64 overflow-y-auto">
                        @if ($pendingLabOrders->count() > 0 || $pendingImagingOrders->count() > 0)
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Lab Orders</h4>
                            @if ($pendingLabOrders->count() > 0)
                                <ul class="divide-y divide-gray-200 mb-4">
                                    @foreach ($pendingLabOrders as $order)
                                        <li class="py-2">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <span class="text-sm font-medium text-gray-900">
                                                        {{ $order->test_name }} 
                                                        @if($order->is_stat) <span class="text-xs text-red-600">(STAT)</span> @endif
                                                    </span>
                                                    <p class="text-xs text-gray-500">Patient: {{ $order->visit->patient->full_name }}</p>
                                                    <div class="text-xs text-gray-500">
                                                        <span>Status: {{ ucfirst($order->status) }}</span>
                                                        <span class="ml-2">Ordered: {{ $order->ordered_at->format('M d') }}</span>
                                                    </div>
                                                </div>
                                                <a href="{{ route('doctor.lab-orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-900 text-sm whitespace-nowrap">View</a>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-gray-500 italic text-sm mb-4">No pending lab orders.</p>
                            @endif

                            <h4 class="text-sm font-medium text-gray-700 mb-2">Imaging Orders</h4>
                            @if ($pendingImagingOrders->count() > 0)
                                <ul class="divide-y divide-gray-200">
                                    @foreach ($pendingImagingOrders as $order)
                                        <li class="py-2">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <span class="text-sm font-medium text-gray-900">
                                                        {{ $order->imaging_type }} - {{ $order->body_part }}
                                                        @if($order->is_stat) <span class="text-xs text-red-600">(STAT)</span> @endif
                                                    </span>
                                                    <p class="text-xs text-gray-500">Patient: {{ $order->visit->patient->full_name }}</p>
                                                    <div class="text-xs text-gray-500">
                                                        <span>Status: {{ ucfirst($order->status) }}</span>
                                                        <span class="ml-2">Ordered: {{ $order->ordered_at->format('M d') }}</span>
                                                    </div>
                                                </div>
                                                <a href="{{ route('doctor.imaging-orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-900 text-sm whitespace-nowrap">View</a>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-gray-500 italic text-sm">No pending imaging orders.</p>
                            @endif
                        @else
                            <p class="text-gray-500 italic">You don't have any pending orders.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Results and Discharge Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Recent Results Section -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Recent Results ({{ $recentLabResults->count() + $recentImagingResults->count() }})
                        </h3>
                    </div>
                    <div class="p-4 max-h-64 overflow-y-auto">
                        @if ($recentLabResults->count() > 0 || $recentImagingResults->count() > 0)
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Lab Results</h4>
                            @if ($recentLabResults->count() > 0)
                                <ul class="divide-y divide-gray-200 mb-4">
                                    @foreach ($recentLabResults as $result)
                                        <li class="py-2">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <span class="text-sm font-medium text-gray-900">
                                                        {{ $result->test_name }}
                                                    </span>
                                                    <p class="text-xs text-gray-500">Patient: {{ $result->visit->patient->full_name }}</p>
                                                    <p class="text-xs text-gray-500">Result: {{ Str::limit($result->result_summary, 50) }}</p>
                                                    <div class="text-xs text-gray-500">
                                                        <span>Completed: {{ $result->completed_at->format('M d, g:i A') }}</span>
                                                    </div>
                                                </div>
                                                <a href="{{ route('doctor.lab-orders.show', $result) }}" class="text-indigo-600 hover:text-indigo-900 text-sm whitespace-nowrap">View</a>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-gray-500 italic text-sm mb-4">No recent lab results.</p>
                            @endif

                            <h4 class="text-sm font-medium text-gray-700 mb-2">Imaging Results</h4>
                            @if ($recentImagingResults->count() > 0)
                                <ul class="divide-y divide-gray-200">
                                    @foreach ($recentImagingResults as $result)
                                        <li class="py-2">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <span class="text-sm font-medium text-gray-900">
                                                        {{ $result->imaging_type }} - {{ $result->body_part }}
                                                    </span>
                                                    <p class="text-xs text-gray-500">Patient: {{ $result->visit->patient->full_name }}</p>
                                                    <p class="text-xs text-gray-500">Impression: {{ Str::limit($result->impression, 50) }}</p>
                                                    <div class="text-xs text-gray-500">
                                                        <span>Completed: {{ $result->completed_at->format('M d, g:i A') }}</span>
                                                    </div>
                                                </div>
                                                <a href="{{ route('doctor.imaging-orders.show', $result) }}" class="text-indigo-600 hover:text-indigo-900 text-sm whitespace-nowrap">View</a>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-gray-500 italic text-sm">No recent imaging results.</p>
                            @endif
                        @else
                            <p class="text-gray-500 italic">No recent results available.</p>
                        @endif
                    </div>
                </div>

                <!-- Potential Discharges Section -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Ready for Discharge ({{ $potentialDischarges->count() }})
                        </h3>
                    </div>
                    <div class="p-4 max-h-64 overflow-y-auto">
                        @if ($potentialDischarges->count() > 0)
                            <ul class="divide-y divide-gray-200">
                                @foreach ($potentialDischarges as $visit)
                                    <li class="py-3">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <span class="text-sm font-medium text-gray-900">
                                                    {{ $visit->patient->full_name }}
                                                </span>
                                                <p class="text-sm text-gray-500">{{ $visit->chief_complaint }}</p>
                                                <div class="mt-1 text-xs text-gray-500">
                                                    <span>Checked in: {{ $visit->check_in_time->format('M d, g:i A') }}</span>
                                                    <span class="ml-2">{{ $visit->check_in_time->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                            <div>
                                                <a href="{{ route('doctor.visits.show', $visit) }}" class="text-indigo-600 hover:text-indigo-900 mr-2 text-sm">View</a>
                                                <a href="{{ route('doctor.discharges.create', $visit) }}" class="text-green-600 hover:text-green-900 text-sm">Discharge</a>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500 italic">No patients ready for discharge.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Upcoming Appointments Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Upcoming Appointments ({{ $upcomingAppointments->count() }})
                    </h3>
                </div>
                <div class="p-4">
                    @if ($upcomingAppointments->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($upcomingAppointments as $appointment)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $appointment->appointment_time->format('M d, Y g:i A') }}
                                                <div class="text-xs">{{ $appointment->appointment_time->diffForHumans() }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $appointment->patient->full_name }}</div>
                                                <div class="text-xs text-gray-500">{{ $appointment->patient->date_of_birth->age }} yrs, {{ $appointment->patient->gender }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ Str::limit($appointment->reason_for_visit, 50) }}
                                                @if ($appointment->is_urgent)
                                                    <span class="ml-2 px-2 py-0.5 bg-red-100 text-red-800 rounded-full text-xs">Urgent</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <span class="px-2 py-1 rounded-full text-xs {{ $appointment->status === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                    {{ ucfirst($appointment->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <a href="{{ route('doctor.follow-up-appointments.show', $appointment) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 italic">No upcoming appointments in the next 3 days.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
