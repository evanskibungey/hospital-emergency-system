<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Reception Dashboard') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('reception.patients.create') }}" 
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-md text-white text-sm font-medium transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Register New Patient
                </a>
                <a href="{{ route('reception.patients.search') }}" 
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 rounded-md text-white text-sm font-medium transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Find Patient
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm flex items-center" role="alert">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            
            <!-- Status Overview Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <!-- Waiting Patients Card -->
                <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-yellow-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-100 rounded-full p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Waiting Patients</dt>
                                <dd>
                                    <div class="text-lg font-semibold text-gray-900">{{ $waitingPatients->count() }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="#waiting-patients" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 flex items-center">
                            View patients
                            <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
                
                <!-- Recent Arrivals Card -->
                <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-blue-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 rounded-full p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Recent Arrivals (4h)</dt>
                                <dd>
                                    <div class="text-lg font-semibold text-gray-900">{{ $recentArrivals->count() }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="#recent-arrivals" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 flex items-center">
                            View arrivals
                            <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
                
                <!-- Bed Availability Card -->
                <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-green-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 rounded-full p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Bed Availability</dt>
                                <dd>
                                    <div class="text-lg font-semibold text-gray-900">{{ $availableBeds }} / {{ $totalBeds }}</div>
                                    <div class="mt-1 h-2 bg-gray-200 rounded-full">
                                        <div 
                                            class="h-2 bg-green-600 rounded-full" 
                                            style="width: {{ ($availableBeds / $totalBeds) * 100 }}%">
                                        </div>
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                    <div class="mt-2">
                        <a href="#resource-status" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 flex items-center">
                            View details
                            <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
                
                <!-- Equipment Alerts Card -->
                <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-red-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-red-100 rounded-full p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Equipment Alerts</dt>
                                <dd>
                                    <div class="text-lg font-semibold text-gray-900">
                                        {{ \App\Models\Equipment::query()->whereIn('status', ['out_of_order', 'maintenance'])->count() }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        <span class="text-red-600 font-medium">{{ \App\Models\Equipment::query()->where('status', 'out_of_order')->count() }} Out of Order</span> • 
                                        <span class="text-yellow-600 font-medium">{{ \App\Models\Equipment::query()->where('status', 'maintenance')->count() }} In Maintenance</span>
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                    <div class="mt-2">
                        <a href="#" onclick="alert('Please contact Nurse staff for equipment details')" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 flex items-center">
                            Contact nurse
                            <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Waiting Patients Panel -->
                <div id="waiting-patients" class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="border-b border-gray-200 bg-gray-50 p-4">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ __('Waiting Patients') }}
                            </h3>
                            <a href="{{ route('reception.patients.create') }}"
                                class="inline-flex items-center px-3 py-1.5 bg-blue-600 border border-transparent rounded-md font-medium text-xs text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                {{ __('Register New Patient') }}
                            </a>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Priority
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Patient
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Wait Time
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($waitingPatients as $visit)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            @if ($visit->priority === 'critical')
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Critical
                                                </span>
                                            @elseif ($visit->priority === 'high')
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                                    High
                                                </span>
                                            @elseif ($visit->priority === 'medium')
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Medium
                                                </span>
                                            @else
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Low
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $visit->patient->full_name }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                MRN: {{ $visit->patient->medical_record_number ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $visit->estimated_wait_time }} minutes
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                Check-in: {{ $visit->check_in_time->format('H:i') }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('reception.patients.show', $visit->patient) }}" 
                                               class="text-white bg-indigo-600 hover:bg-indigo-700 px-2 py-1 rounded-md text-xs inline-flex items-center transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-4 text-center text-sm text-gray-500">
                                            <div class="flex flex-col items-center py-5">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                                </svg>
                                                <p>No waiting patients at the moment.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Recent Arrivals Panel -->
                <div id="recent-arrivals" class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="border-b border-gray-200 bg-gray-50 p-4">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                {{ __('Recent Arrivals') }}
                            </h3>
                            <form action="{{ route('reception.patients.search') }}" method="GET" class="flex">
                                <input type="text" name="search" placeholder="Search patients..." class="rounded-l-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm">
                                <button type="submit" class="px-3 py-1.5 bg-blue-600 text-white rounded-r-md hover:bg-blue-700 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Patient
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Check-in
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($recentArrivals as $visit)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $visit->patient->full_name }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                MRN: {{ $visit->patient->medical_record_number ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $visit->check_in_time->format('H:i') }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $visit->check_in_time->diffForHumans() }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            @if ($visit->status === 'waiting')
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Waiting
                                                </span>
                                            @elseif ($visit->status === 'in_progress')
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    In Progress
                                                </span>
                                            @elseif ($visit->status === 'treated')
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Treated
                                                </span>
                                            @else
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    Discharged
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('reception.patients.show', $visit->patient) }}" 
                                               class="text-white bg-indigo-600 hover:bg-indigo-700 px-2 py-1 rounded-md text-xs inline-flex items-center transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-4 text-center text-sm text-gray-500">
                                            <div class="flex flex-col items-center py-5">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                                </svg>
                                                <p>No recent arrivals in the last 4 hours.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Department Status Panel -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Resource Status Panel -->
                <div id="resource-status" class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="border-b border-gray-200 bg-gray-50 p-4">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ __('Resource Status') }}
                        </h3>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Bed Types -->
                            <div>
                                <h4 class="font-medium text-gray-700 mb-3">Bed Availability by Type</h4>
                                <div class="space-y-3">
                                    <div>
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="text-sm font-medium">Regular Beds</span>
                                            <span class="text-sm font-medium">
                                                {{ \App\Models\Bed::query()->where('type', 'regular')->where('status', 'available')->count() }} / 
                                                {{ \App\Models\Bed::query()->where('type', 'regular')->count() }}
                                            </span>
                                        </div>
                                        <div class="h-2 bg-gray-200 rounded-full">
                                            <div class="h-2 bg-green-600 rounded-full" 
                                                style="width: {{ (\App\Models\Bed::query()->where('type', 'regular')->where('status', 'available')->count() / 
                                                    max(1, \App\Models\Bed::query()->where('type', 'regular')->count())) * 100 }}%">
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="text-sm font-medium">ICU Beds</span>
                                            <span class="text-sm font-medium">
                                                {{ \App\Models\Bed::query()->where('type', 'icu')->where('status', 'available')->count() }} / 
                                                {{ \App\Models\Bed::query()->where('type', 'icu')->count() }}
                                            </span>
                                        </div>
                                        <div class="h-2 bg-gray-200 rounded-full">
                                            <div class="h-2 bg-blue-600 rounded-full" 
                                                style="width: {{ (\App\Models\Bed::query()->where('type', 'icu')->where('status', 'available')->count() / 
                                                    max(1, \App\Models\Bed::query()->where('type', 'icu')->count())) * 100 }}%">
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="text-sm font-medium">Pediatric Beds</span>
                                            <span class="text-sm font-medium">
                                                {{ \App\Models\Bed::query()->where('type', 'pediatric')->where('status', 'available')->count() }} / 
                                                {{ \App\Models\Bed::query()->where('type', 'pediatric')->count() }}
                                            </span>
                                        </div>
                                        <div class="h-2 bg-gray-200 rounded-full">
                                            <div class="h-2 bg-yellow-600 rounded-full" 
                                                style="width: {{ (\App\Models\Bed::query()->where('type', 'pediatric')->where('status', 'available')->count() / 
                                                    max(1, \App\Models\Bed::query()->where('type', 'pediatric')->count())) * 100 }}%">
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="text-sm font-medium">Maternity Beds</span>
                                            <span class="text-sm font-medium">
                                                {{ \App\Models\Bed::query()->where('type', 'maternity')->where('status', 'available')->count() }} / 
                                                {{ \App\Models\Bed::query()->where('type', 'maternity')->count() }}
                                            </span>
                                        </div>
                                        <div class="h-2 bg-gray-200 rounded-full">
                                            <div class="h-2 bg-pink-600 rounded-full" 
                                                style="width: {{ (\App\Models\Bed::query()->where('type', 'maternity')->where('status', 'available')->count() / 
                                                    max(1, \App\Models\Bed::query()->where('type', 'maternity')->count())) * 100 }}%">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Equipment Status -->
                            <div>
                                <h4 class="font-medium text-gray-700 mb-3">Equipment Status</h4>
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="bg-white shadow-sm p-3 rounded-lg text-center border border-green-100">
                                        <div class="text-xl font-bold text-green-600">
                                            {{ \App\Models\Equipment::query()->where('status', 'available')->count() }}
                                        </div>
                                        <div class="text-sm font-medium text-gray-600">Available</div>
                                    </div>
                                    <div class="bg-white shadow-sm p-3 rounded-lg text-center border border-blue-100">
                                        <div class="text-xl font-bold text-blue-600">
                                            {{ \App\Models\Equipment::query()->where('status', 'in_use')->count() }}
                                        </div>
                                        <div class="text-sm font-medium text-gray-600">In Use</div>
                                    </div>
                                    <div class="bg-white shadow-sm p-3 rounded-lg text-center border border-yellow-100">
                                        <div class="text-xl font-bold text-yellow-600">
                                            {{ \App\Models\Equipment::query()->where('status', 'maintenance')->count() }}
                                        </div>
                                        <div class="text-sm font-medium text-gray-600">Maintenance</div>
                                    </div>
                                    <div class="bg-white shadow-sm p-3 rounded-lg text-center border border-red-100">
                                        <div class="text-xl font-bold text-red-600">
                                            {{ \App\Models\Equipment::query()->where('status', 'out_of_order')->count() }}
                                        </div>
                                        <div class="text-sm font-medium text-gray-600">Out of Order</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Department Status Panel -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="border-b border-gray-200 bg-gray-50 p-4">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            {{ __('Department Status') }}
                        </h3>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Patient Load by Department -->
                            <div>
                                <h4 class="font-medium text-gray-700 mb-3">Patient Load</h4>
                                <div class="space-y-3">
                                    <div>
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="text-sm font-medium">Emergency</span>
                                            <span class="text-sm font-medium 
                                                @if(\App\Models\Visit::query()->whereNull('discharged_at')->where('department', 'emergency')->count() > 10) text-red-600 
                                                @elseif(\App\Models\Visit::query()->whereNull('discharged_at')->where('department', 'emergency')->count() > 5) text-yellow-600 
                                                @else text-green-600 @endif">
                                                {{ \App\Models\Visit::query()->whereNull('discharged_at')->where('department', 'emergency')->count() }} patients
                                            </span>
                                        </div>
                                        <div class="h-2 bg-gray-200 rounded-full">
                                            <div class="h-2 
                                                @if(\App\Models\Visit::query()->whereNull('discharged_at')->where('department', 'emergency')->count() > 10) bg-red-600 
                                                @elseif(\App\Models\Visit::query()->whereNull('discharged_at')->where('department', 'emergency')->count() > 5) bg-yellow-600 
                                                @else bg-green-600 @endif rounded-full" 
                                                style="width: {{ min(100, (\App\Models\Visit::query()->whereNull('discharged_at')->where('department', 'emergency')->count() / 15) * 100) }}%">
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="text-sm font-medium">ICU</span>
                                            <span class="text-sm font-medium 
                                                @if(\App\Models\Visit::query()->whereNull('discharged_at')->where('department', 'icu')->count() > 8) text-red-600 
                                                @elseif(\App\Models\Visit::query()->whereNull('discharged_at')->where('department', 'icu')->count() > 4) text-yellow-600 
                                                @else text-green-600 @endif">
                                                {{ \App\Models\Visit::query()->whereNull('discharged_at')->where('department', 'icu')->count() }} patients
                                            </span>
                                        </div>
                                        <div class="h-2 bg-gray-200 rounded-full">
                                            <div class="h-2 
                                                @if(\App\Models\Visit::query()->whereNull('discharged_at')->where('department', 'icu')->count() > 8) bg-red-600 
                                                @elseif(\App\Models\Visit::query()->whereNull('discharged_at')->where('department', 'icu')->count() > 4) bg-yellow-600 
                                                @else bg-green-600 @endif rounded-full" 
                                                style="width: {{ min(100, (\App\Models\Visit::query()->whereNull('discharged_at')->where('department', 'icu')->count() / 10) * 100) }}%">
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="text-sm font-medium">General Medicine</span>
                                            <span class="text-sm font-medium 
                                                @if(\App\Models\Visit::query()->whereNull('discharged_at')->where('department', 'general')->count() > 15) text-red-600 
                                                @elseif(\App\Models\Visit::query()->whereNull('discharged_at')->where('department', 'general')->count() > 8) text-yellow-600 
                                                @else text-green-600 @endif">
                                                {{ \App\Models\Visit::query()->whereNull('discharged_at')->where('department', 'general')->count() }} patients
                                            </span>
                                        </div>
                                        <div class="h-2 bg-gray-200 rounded-full">
                                            <div class="h-2 
                                                @if(\App\Models\Visit::query()->whereNull('discharged_at')->where('department', 'general')->count() > 15) bg-red-600 
                                                @elseif(\App\Models\Visit::query()->whereNull('discharged_at')->where('department', 'general')->count() > 8) bg-yellow-600 
                                                @else bg-green-600 @endif rounded-full" 
                                                style="width: {{ min(100, (\App\Models\Visit::query()->whereNull('discharged_at')->where('department', 'general')->count() / 20) * 100) }}%">
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="text-sm font-medium">Pediatrics</span>
                                            <span class="text-sm font-medium 
                                                @if(\App\Models\Visit::query()->whereNull('discharged_at')->where('department', 'pediatrics')->count() > 12) text-red-600 
                                                @elseif(\App\Models\Visit::query()->whereNull('discharged_at')->where('department', 'pediatrics')->count() > 6) text-yellow-600 
                                                @else text-green-600 @endif">
                                                {{ \App\Models\Visit::query()->whereNull('discharged_at')->where('department', 'pediatrics')->count() }} patients
                                            </span>
                                        </div>
                                        <div class="h-2 bg-gray-200 rounded-full">
                                            <div class="h-2 
                                                @if(\App\Models\Visit::query()->whereNull('discharged_at')->where('department', 'pediatrics')->count() > 12) bg-red-600 
                                                @elseif(\App\Models\Visit::query()->whereNull('discharged_at')->where('department', 'pediatrics')->count() > 6) bg-yellow-600 
                                                @else bg-green-600 @endif rounded-full" 
                                                style="width: {{ min(100, (\App\Models\Visit::query()->whereNull('discharged_at')->where('department', 'pediatrics')->count() / 15) * 100) }}%">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                            <!-- Status by Priority -->
                            <div>
                                <h4 class="font-medium text-gray-700 mb-3">Patients by Priority</h4>
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="bg-white shadow-sm p-3 rounded-lg text-center border border-red-100">
                                        <div class="text-xl font-bold text-red-600">
                                            {{ \App\Models\Visit::query()->where('status', 'waiting')->where('priority', 'critical')->count() }}
                                        </div>
                                        <div class="text-sm font-medium text-gray-600">Critical</div>
                                    </div>
                                    <div class="bg-white shadow-sm p-3 rounded-lg text-center border border-orange-100">
                                        <div class="text-xl font-bold text-orange-600">
                                            {{ \App\Models\Visit::query()->where('status', 'waiting')->where('priority', 'high')->count() }}
                                        </div>
                                        <div class="text-sm font-medium text-gray-600">High</div>
                                    </div>
                                    <div class="bg-white shadow-sm p-3 rounded-lg text-center border border-yellow-100">
                                        <div class="text-xl font-bold text-yellow-600">
                                            {{ \App\Models\Visit::query()->where('status', 'waiting')->where('priority', 'medium')->count() }}
                                        </div>
                                        <div class="text-sm font-medium text-gray-600">Medium</div>
                                    </div>
                                    <div class="bg-white shadow-sm p-3 rounded-lg text-center border border-green-100">
                                        <div class="text-xl font-bold text-green-600">
                                            {{ \App\Models\Visit::query()->where('status', 'waiting')->where('priority', 'low')->count() }}
                                        </div>
                                        <div class="text-sm font-medium text-gray-600">Low</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Visitor Management Section -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
                <div class="border-b border-gray-200 bg-gray-50 p-4">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            {{ __('Visitor Management') }}
                        </h3>
                        <a href="{{ route('reception.visitors.index') }}"
                            class="inline-flex items-center px-3 py-1.5 bg-purple-600 border border-transparent rounded-md font-medium text-xs text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                            </svg>
                            {{ __('View All Visitors') }}
                        </a>
                    </div>
                </div>
                
                <div class="p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Register New Visitor -->
                        <div class="bg-white shadow-sm p-4 rounded-lg border border-purple-100 hover:border-purple-300 transition-colors">
                            <div class="flex items-center mb-3">
                                <div class="flex-shrink-0 bg-purple-100 rounded-full p-2 mr-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                    </svg>
                                </div>
                                <h4 class="font-medium text-gray-800">Register New Visitor</h4>
                            </div>
                            <p class="text-sm text-gray-600 mb-4">Register a new visitor for a patient currently in the hospital</p>
                            <form action="{{ route('reception.patients.search') }}" method="GET" class="flex">
                                <input type="text" name="search" placeholder="Search patient..." class="flex-grow rounded-l-md border-gray-300 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50 text-sm">
                                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-r-md hover:bg-purple-700 transition-colors">
                                    Find
                                </button>
                            </form>
                        </div>
                        
                        <!-- Check Out Visitor -->
                        <div class="bg-white shadow-sm p-4 rounded-lg border border-purple-100 hover:border-purple-300 transition-colors">
                            <div class="flex items-center mb-3">
                                <div class="flex-shrink-0 bg-purple-100 rounded-full p-2 mr-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                </div>
                                <h4 class="font-medium text-gray-800">Check Out Visitor</h4>
                            </div>
                            <p class="text-sm text-gray-600 mb-4">Check out a visitor when they leave the hospital</p>
                            <form action="{{ route('reception.visitors.search') }}" method="GET" class="flex">
                                <input type="text" name="search" placeholder="Search visitor pass..." class="flex-grow rounded-l-md border-gray-300 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50 text-sm">
                                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-r-md hover:bg-purple-700 transition-colors">
                                    Find
                                </button>
                            </form>
                        </div>

                        <!-- Visitor Stats -->
                        <div class="md:col-span-2 bg-gray-50 p-4 rounded-lg">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="bg-white shadow-sm p-3 rounded-lg text-center">
                                    <div class="text-xl font-bold text-indigo-600">
                                        {{ \App\Models\Visitor::whereNull('check_out_time')->count() }}
                                    </div>
                                    <div class="text-sm font-medium text-gray-600">Active Visitors</div>
                                </div>
                                <div class="bg-white shadow-sm p-3 rounded-lg text-center">
                                    <div class="text-xl font-bold text-green-600">
                                        {{ \App\Models\Visitor::whereDate('created_at', \Carbon\Carbon::today())->count() }}
                                    </div>
                                    <div class="text-sm font-medium text-gray-600">Today's Visitors</div>
                                </div>
                                <div class="bg-white shadow-sm p-3 rounded-lg text-center">
                                    <div class="text-xl font-bold text-blue-600">
                                        {{ \App\Models\Visitor::whereDate('check_out_time', \Carbon\Carbon::today())->count() }}
                                    </div>
                                    <div class="text-sm font-medium text-gray-600">Today's Checked Out</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Action Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Patient Registration -->
                <a href="{{ route('reception.patients.create') }}" class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                    <div class="p-5 flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 rounded-full p-3 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Register New Patient</h3>
                            <p class="text-sm text-gray-600">Create a new patient record or check-in</p>
                        </div>
                    </div>
                </a>
                
                <!-- Find Patient -->
                <a href="{{ route('reception.patients.search') }}" class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                    <div class="p-5 flex items-center">
                        <div class="flex-shrink-0 bg-indigo-100 rounded-full p-3 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Find Patient</h3>
                            <p class="text-sm text-gray-600">Search for existing patient records</p>
                        </div>
                    </div>
                </a>
                
                <!-- Visitor Management -->
                <a href="{{ route('reception.visitors.index') }}" class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                    <div class="p-5 flex items-center">
                        <div class="flex-shrink-0 bg-purple-100 rounded-full p-3 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Visitor Management</h3>
                            <p class="text-sm text-gray-600">Manage visitor passes and check-outs</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>