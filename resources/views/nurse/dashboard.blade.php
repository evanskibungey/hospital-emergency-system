<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Nurse Dashboard') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('nurse.vital-signs.search-patient') }}" 
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
                <!-- Available Beds Card -->
                <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-green-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 rounded-full p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Available Beds</dt>
                                <dd>
                                    <div class="text-lg font-semibold text-gray-900">{{ \App\Models\Bed::where('status', 'available')->where('is_active', true)->count() }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('nurse.beds.index', ['status' => 'available']) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 flex items-center">
                            View all beds
                            <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
                
                <!-- Medications Due Card -->
                <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-yellow-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-100 rounded-full p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Medications Due</dt>
                                <dd>
                                    <div class="text-lg font-semibold text-gray-900">{{ \App\Models\MedicationSchedule::where('status', 'scheduled')->where('scheduled_time', '<=', \Carbon\Carbon::now()->addHour())->where('scheduled_time', '>=', \Carbon\Carbon::now()->subMinutes(30))->count() }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('nurse.medication-administrations.due') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 flex items-center">
                            View medications
                            <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
                
                <!-- Overdue Medications Card -->
                <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-red-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-red-100 rounded-full p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Overdue Medications</dt>
                                <dd>
                                    <div class="text-lg font-semibold text-gray-900">{{ \App\Models\MedicationSchedule::where('status', 'scheduled')->where('scheduled_time', '<', \Carbon\Carbon::now()->subMinutes(30))->count() }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('nurse.medication-administrations.due') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 flex items-center">
                            View overdue
                            <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
                
                <!-- Patients Needing Vitals Card -->
                <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-blue-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 rounded-full p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Patients Needing Vitals</dt>
                                <dd>
                                    <div class="text-lg font-semibold text-gray-900">{{ isset($needVitals) ? count($needVitals) : 0 }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="#patients-needing-vitals" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 flex items-center">
                            View patients
                            <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Equipment Management Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Equipment Maintenance -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="border-b border-gray-200 bg-gray-50 p-4">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ __('Equipment Maintenance') }}
                            </h3>
                            <a href="{{ route('nurse.equipment-maintenance.index') }}"
                                class="inline-flex items-center px-3 py-1.5 bg-yellow-600 border border-transparent rounded-md font-medium text-xs text-white hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors">
                                {{ __('View All') }}
                            </a>
                        </div>
                    </div>
                    <div class="p-4">
                        <!-- Maintenance Quick Stats -->
                        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3">
                            <a href="{{ route('nurse.equipment-maintenance.overdue') }}" class="block group">
                                <div class="bg-white shadow-sm p-3 rounded-lg text-center border border-red-100 hover:bg-red-50 transition-colors">
                                    <div class="text-xl font-bold text-red-600">{{ \App\Models\EquipmentMaintenance::query()->whereNull('completed_at')->where('status', '!=', 'cancelled')->where('scheduled_for', '<', now())->count() }}</div>
                                    <div class="text-sm font-medium text-gray-600 group-hover:text-red-700">Overdue</div>
                                </div>
                            </a>
                            <a href="{{ route('nurse.equipment-maintenance.scheduled-today') }}" class="block group">
                                <div class="bg-white shadow-sm p-3 rounded-lg text-center border border-green-100 hover:bg-green-50 transition-colors">
                                    <div class="text-xl font-bold text-green-600">{{ \App\Models\EquipmentMaintenance::query()->whereDate('scheduled_for', now()->toDateString())->whereNull('completed_at')->where('status', '!=', 'cancelled')->count() }}</div>
                                    <div class="text-sm font-medium text-gray-600 group-hover:text-green-700">Due Today</div>
                                </div>
                            </a>
                            <a href="{{ route('nurse.equipment-maintenance.equipment-needing-maintenance') }}" class="block group">
                                <div class="bg-white shadow-sm p-3 rounded-lg text-center border border-orange-100 hover:bg-orange-50 transition-colors">
                                    <div class="text-xl font-bold text-orange-600">{{ \App\Models\Equipment::query()->where(function($query) { $query->where('next_maintenance_date', '<=', now())->orWhere('status', 'maintenance'); })->count() }}</div>
                                    <div class="text-sm font-medium text-gray-600 group-hover:text-orange-700">Need Service</div>
                                </div>
                            </a>
                            <a href="{{ route('nurse.equipment-maintenance.create') }}" class="block group">
                                <div class="bg-white shadow-sm p-3 rounded-lg text-center border border-blue-100 hover:bg-blue-50 transition-colors">
                                    <div class="text-xl">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                    </div>
                                    <div class="text-sm font-medium text-gray-600 group-hover:text-blue-700">Request Service</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Equipment Checkouts -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="border-b border-gray-200 bg-gray-50 p-4">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                </svg>
                                {{ __('Equipment Checkouts') }}
                            </h3>
                            <a href="{{ route('nurse.equipment-checkouts.index') }}"
                                class="inline-flex items-center px-3 py-1.5 bg-indigo-600 border border-transparent rounded-md font-medium text-xs text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                {{ __('Manage All') }}
                            </a>
                        </div>
                    </div>
                    <div class="p-4">
                        <!-- Checkout Quick Stats -->
                        <div class="grid grid-cols-3 gap-3">
                            <a href="{{ route('nurse.equipment-checkouts.overdue') }}" class="block group">
                                <div class="bg-white shadow-sm p-3 rounded-lg text-center border border-red-100 hover:bg-red-50 transition-colors">
                                    <div class="text-xl font-bold text-red-600">{{ \App\Models\EquipmentCheckout::query()->whereNull('checked_in_at')->where('expected_return_at', '<', now())->count() }}</div>
                                    <div class="text-sm font-medium text-gray-600 group-hover:text-red-700">Overdue</div>
                                </div>
                            </a>
                            <a href="{{ route('nurse.equipment-checkouts.index') }}" class="block group">
                                <div class="bg-white shadow-sm p-3 rounded-lg text-center border border-blue-100 hover:bg-blue-50 transition-colors">
                                    <div class="text-xl font-bold text-blue-600">{{ \App\Models\EquipmentCheckout::query()->whereNull('checked_in_at')->where('status', '!=', 'lost')->count() }}</div>
                                    <div class="text-sm font-medium text-gray-600 group-hover:text-blue-700">Active</div>
                                </div>
                            </a>
                            <a href="{{ route('nurse.equipment-checkouts.create') }}" class="block group">
                                <div class="bg-white shadow-sm p-3 rounded-lg text-center border border-green-100 hover:bg-green-50 transition-colors">
                                    <div class="text-xl">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                    </div>
                                    <div class="text-sm font-medium text-gray-600 group-hover:text-green-700">New Checkout</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Medication Alerts Section -->
            @if(isset($dueMedications) && count($dueMedications) > 0)
            <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
                <div class="border-b border-gray-200 bg-gray-50 p-4">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            {{ __('Medications Due') }}
                        </h3>
                        <a href="{{ route('nurse.medication-administrations.due') }}"
                            class="inline-flex items-center px-3 py-1.5 bg-yellow-600 border border-transparent rounded-md font-medium text-xs text-white hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors">
                            {{ __('View All Due') }}
                        </a>
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
                                    Medication
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Dosage
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Scheduled Time
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
                            @foreach($dueMedications as $schedule)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $schedule->visit->patient->first_name }} {{ $schedule->visit->patient->last_name }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        MRN: {{ $schedule->visit->patient->medical_record_number ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $schedule->medication->name }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $schedule->medication->dosage_form }}
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $schedule->dosage }}
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $schedule->scheduled_time->format('M d, Y H:i') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $schedule->scheduled_time->diffForHumans() }}
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if($schedule->isDue())
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Due Now
                                    </span>
                                    @elseif($schedule->isOverdue())
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Overdue
                                    </span>
                                    @else
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Upcoming
                                    </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('nurse.medication-administrations.create', [$schedule->visit_id, $schedule->id]) }}"
                                        class="text-white bg-green-600 hover:bg-green-700 px-2 py-1 rounded-md text-xs mr-2 inline-flex items-center transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Administer
                                    </a>
                                    <a href="{{ route('nurse.medication-schedules.show', [$schedule->visit_id, $schedule->id]) }}"
                                        class="text-indigo-600 hover:text-indigo-900 text-xs">
                                        View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Overdue Medications Section -->
            @if(isset($overdueMedications) && count($overdueMedications) > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ __('Overdue Medications') }}
                    </h3>

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Patient
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Medication
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Dosage
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Scheduled Time
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($overdueMedications as $schedule)
                            <tr class="bg-red-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $schedule->visit->patient->first_name }} {{ $schedule->visit->patient->last_name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        MRN: {{ $schedule->visit->patient->medical_record_number ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $schedule->medication->name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $schedule->medication->dosage_form }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $schedule->dosage }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-red-600 font-semibold">
                                        {{ $schedule->scheduled_time->format('M d, Y H:i') }}
                                    </div>
                                    <div class="text-sm text-red-600">
                                        {{ $schedule->scheduled_time->diffForHumans() }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('nurse.medication-administrations.create', [$schedule->visit_id, $schedule->id]) }}"
                                        class="text-red-600 hover:text-red-900 mr-3 font-bold">
                                        Administer Now
                                    </a>
                                    <a href="{{ route('nurse.medication-schedules.show', [$schedule->visit_id, $schedule->id]) }}"
                                        class="text-indigo-600 hover:text-indigo-900">
                                        View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Bed Management Shortcuts -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">
            {{ __('Bed Management') }}
            </h3>
            <div>
            <a href="{{ route('nurse.beds.create') }}"
                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 mr-2">
                    {{ __('Add New Bed') }}
                    </a>
                            <a href="{{ route('nurse.beds.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('Manage Beds') }}
                            </a>
                        </div>
                    </div>
                    
                    <!-- Bed Status Quick View -->
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                        <a href="{{ route('nurse.beds.index', ['status' => 'available']) }}" class="block">
                            <div class="bg-green-100 p-4 rounded-lg text-center hover:bg-green-200 transition-colors">
                                <div class="text-xl font-bold text-green-800">{{ \App\Models\Bed::where('status', 'available')->where('is_active', true)->count() }}</div>
                                <div class="text-sm text-green-700">Available Beds</div>
                            </div>
                        </a>
                        <a href="{{ route('nurse.bed-assignments.index') }}" class="block">
                            <div class="bg-red-100 p-4 rounded-lg text-center hover:bg-red-200 transition-colors">
                                <div class="text-xl font-bold text-red-800">{{ \App\Models\Bed::where('status', 'occupied')->count() }}</div>
                                <div class="text-sm text-red-700">Occupied Beds</div>
                            </div>
                        </a>
                        <a href="{{ route('nurse.beds.index', ['status' => 'cleaning']) }}" class="block">
                            <div class="bg-yellow-100 p-4 rounded-lg text-center hover:bg-yellow-200 transition-colors">
                                <div class="text-xl font-bold text-yellow-800">{{ \App\Models\Bed::where('status', 'cleaning')->count() }}</div>
                                <div class="text-sm text-yellow-700">Cleaning</div>
                            </div>
                        </a>
                        <a href="{{ route('nurse.beds.index', ['status' => 'maintenance']) }}" class="block">
                            <div class="bg-gray-100 p-4 rounded-lg text-center hover:bg-gray-200 transition-colors">
                                <div class="text-xl font-bold text-gray-800">{{ \App\Models\Bed::where('status', 'maintenance')->count() }}</div>
                                <div class="text-sm text-gray-700">Maintenance</div>
                            </div>
                        </a>
                        <a href="{{ route('nurse.beds.index', ['status' => 'reserved']) }}" class="block">
                            <div class="bg-blue-100 p-4 rounded-lg text-center hover:bg-blue-200 transition-colors">
                                <div class="text-xl font-bold text-blue-800">{{ \App\Models\Bed::where('status', 'reserved')->count() }}</div>
                                <div class="text-sm text-blue-700">Reserved</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

                <!-- Active and Waiting Patients Section -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
                <div class="border-b border-gray-200 bg-gray-50 p-4">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            {{ __('Active Patients') }}
                        </h3>
                        <div class="flex space-x-2">
                            <a href="{{ route('nurse.beds.index') }}" 
                               class="inline-flex items-center px-3 py-1.5 bg-blue-600 border border-transparent rounded-md font-medium text-xs text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" />
                                </svg>
                                {{ __('Manage Beds') }}
                            </a>
                        </div>
                    </div>
                </div>

                @if(count($activeVisits) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Patient
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Chief Complaint
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Check-in
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Priority
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Vitals
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Bed/Doctor
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($activeVisits as $visit)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $visit->patient->first_name }} {{ $visit->patient->last_name }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        MRN: {{ $visit->patient->medical_record_number ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $visit->chief_complaint ?? 'Not recorded' }}
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $visit->check_in_time ? $visit->check_in_time->format('M d, H:i') : 'N/A' }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $visit->check_in_time ? $visit->check_in_time->diffForHumans() : '' }}
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($visit->priority == 'high' || $visit->priority == 'critical') bg-red-100 text-red-800 
                                                @elseif($visit->priority == 'medium') bg-yellow-100 text-yellow-800 
                                                @else bg-green-100 text-green-800 @endif">
                                        {{ ucfirst($visit->priority) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($visit->status == 'waiting') bg-blue-100 text-blue-800 
                                    @elseif($visit->status == 'in_progress') bg-green-100 text-green-800 
                                    @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $visit->status)) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="text-sm">
                                        @if($visit->vitalSigns->count() > 0)
                                        <span class="text-green-600">{{ $visit->vitalSigns->first()->created_at->diffForHumans() }}</span>
                                        @else
                                        <span class="text-red-500 font-medium">Not recorded</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="mb-1">
                                        @if($visit->bed_id)
                                            <div class="flex items-center mb-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                <span class="text-xs font-medium">{{ $visit->bed->location }} - {{ $visit->bed->bed_number }}</span>
                                            </div>
                                        @else
                                        <a href="{{ route('nurse.bed-assignments.create', $visit->id) }}" class="text-blue-600 hover:text-blue-900 text-xs flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                            Assign Bed
                                        </a>
                                        @endif
                                    </div>
                                    <div>
                                        @if($visit->doctor_id)
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            <span class="text-xs font-medium">Dr. {{ optional($visit->doctor)->name }}</span>
                                        </div>
                                        @elseif($visit->status == 'in_progress')
                                        <a href="{{ route('nurse.assign-doctor', $visit->id) }}" class="text-blue-600 hover:text-blue-900 text-xs flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                            Assign Doctor
                                        </a>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm">
                                    <div class="flex flex-col space-y-1">
                                        @if($visit->status == 'waiting')
                                        <form method="POST" action="{{ route('nurse.assign-visit', $visit->id) }}">
                                            @csrf
                                            <button type="submit" class="text-white bg-indigo-600 hover:bg-indigo-700 px-2 py-1 rounded-md text-xs inline-flex items-center transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                </svg>
                                                Assign to Me
                                            </button>
                                        </form>
                                        @endif
                                        
                                        <div class="flex space-x-1">
                                            <a href="{{ route('nurse.vital-signs.create', $visit->id) }}" class="px-2 py-1 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded text-xs inline-flex items-center transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                                </svg>
                                                Vitals
                                            </a>
                                            
                                            <a href="{{ route('nurse.medication-schedules.index', $visit->id) }}" class="px-2 py-1 bg-green-100 text-green-700 hover:bg-green-200 rounded text-xs inline-flex items-center transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                </svg>
                                                Meds
                                            </a>
                                            
                                            @if($visit->vitalSigns->count() > 0)
                                            <a href="{{ route('nurse.vital-signs.index', $visit->id) }}" class="px-2 py-1 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded text-xs inline-flex items-center transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                                </svg>
                                                History
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="p-4">
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-md">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-yellow-700 font-medium">
                                No patients currently in the system. Patients will appear here after reception registers them.
                            </p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Patients Needing Vitals Section -->
            @if(isset($needVitals) && count($needVitals) > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ __('Patients Needing Vital Signs') }}
                    </h3>

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Patient
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Check-in Time
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Priority
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($needVitals as $visit)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $visit->patient->first_name }} {{ $visit->patient->last_name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $visit->check_in_time ? $visit->check_in_time->format('M d, Y H:i') : 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($visit->priority == 'high' || $visit->priority == 'critical') bg-red-100 text-red-800 
                                                @elseif($visit->priority == 'medium') bg-yellow-100 text-yellow-800 
                                                @else bg-green-100 text-green-800 @endif">
                                        {{ ucfirst($visit->priority) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($visit->status == 'waiting') bg-blue-100 text-blue-800 
                                                @elseif($visit->status == 'in_progress') bg-green-100 text-green-800 
                                                @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $visit->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if($visit->status == 'waiting')
                                    <form method="POST" action="{{ route('nurse.assign-visit', $visit->id) }}"
                                        class="inline">
                                        @csrf
                                        <button type="submit" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            Assign to Me
                                        </button>
                                    </form>
                                    @endif
                                    <a href="{{ route('nurse.vital-signs.create', $visit->id) }}"
                                        class="text-indigo-600 hover:text-indigo-900">
                                        Record Vitals
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Recently Registered Patients -->
            @if(isset($recentPatients) && count($recentPatients) > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ __('Recently Registered Patients') }}
                    </h3>

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Patient Name
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    MRN
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date of Birth
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Gender
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Registration Date
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentPatients as $patient)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $patient->first_name }} {{ $patient->last_name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $patient->medical_record_number ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $patient->date_of_birth ? $patient->date_of_birth->format('M d, Y') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ ucfirst($patient->gender ?? 'N/A') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $patient->created_at->format('M d, Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('nurse.vital-signs.patient-visits', $patient->id) }}"
                                        class="text-indigo-600 hover:text-indigo-900">
                                        View Visits
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>