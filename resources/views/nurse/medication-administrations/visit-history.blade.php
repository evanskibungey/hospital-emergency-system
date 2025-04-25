<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Medication Administration History for Patient') }}: {{ $visit->patient->first_name }} {{ $visit->patient->last_name }}
            </h2>
            <div>
                <a href="{{ route('nurse.medication-schedules.index', $visit->id) }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-2">
                    {{ __('Current Medication Schedule') }}
                </a>
                <a href="{{ route('nurse.dashboard') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400">
                    {{ __('Back to Dashboard') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Patient Information Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Patient Information</h3>
                            <p class="text-sm text-gray-600">Name: <span class="font-semibold">{{ $visit->patient->first_name }} {{ $visit->patient->last_name }}</span></p>
                            <p class="text-sm text-gray-600">MRN: <span class="font-semibold">{{ $visit->patient->medical_record_number ?? 'N/A' }}</span></p>
                            <p class="text-sm text-gray-600">DOB: <span class="font-semibold">{{ $visit->patient->date_of_birth ? $visit->patient->date_of_birth->format('M d, Y') : 'N/A' }}</span></p>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Visit Information</h3>
                            <p class="text-sm text-gray-600">Check-in: <span class="font-semibold">{{ $visit->check_in_time ? $visit->check_in_time->format('M d, Y H:i') : 'N/A' }}</span></p>
                            <p class="text-sm text-gray-600">Status: 
                                <span class="font-semibold px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($visit->status == 'waiting') bg-blue-100 text-blue-800 
                                    @elseif($visit->status == 'in_progress') bg-green-100 text-green-800 
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $visit->status)) }}
                                </span>
                            </p>
                            <p class="text-sm text-gray-600">Priority: 
                                <span class="font-semibold px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($visit->priority == 'high' || $visit->priority == 'critical') bg-red-100 text-red-800 
                                    @elseif($visit->priority == 'medium') bg-yellow-100 text-yellow-800 
                                    @else bg-green-100 text-green-800 @endif">
                                    {{ ucfirst($visit->priority) }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Allergies</h3>
                            <p class="text-sm {{ $visit->patient->allergies ? 'text-red-600 font-bold' : 'text-gray-600' }}">
                                {{ $visit->patient->allergies ?: 'No known allergies' }}
                            </p>
                            <h3 class="text-lg font-medium text-gray-900 mb-2 mt-3">Chief Complaint</h3>
                            <p class="text-sm text-gray-600">{{ $visit->chief_complaint ?? 'Not recorded' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Medication History -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ __('Medication Administration History') }}
                    </h3>

                    @if(count($administrations) > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
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
                                    Administered Time
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Administered By
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
                            @foreach($administrations as $administration)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $administration->medicationSchedule->medication->name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $administration->medicationSchedule->medication->dosage_form }} - 
                                        {{ $administration->medicationSchedule->medication->strength }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $administration->actual_dosage ?: $administration->medicationSchedule->dosage }}
                                    </div>
                                    @if($administration->actual_dosage && $administration->actual_dosage !== $administration->medicationSchedule->dosage)
                                    <div class="text-xs text-red-600">
                                        Different from prescribed
                                    </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $administration->medicationSchedule->scheduled_time->format('M d, Y H:i') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $administration->administered_at->format('M d, Y H:i') }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $administration->administered_at->diffForHumans() }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $administration->administeredBy->name ?? 'Unknown' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($administration->status == 'completed') bg-green-100 text-green-800 
                                        @elseif($administration->status == 'partial') bg-yellow-100 text-yellow-800 
                                        @elseif($administration->status == 'refused') bg-red-100 text-red-800 
                                        @elseif($administration->status == 'held') bg-blue-100 text-blue-800 
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($administration->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('nurse.medication-administrations.show', [
                                        $visit->id, 
                                        $administration->medicationSchedule->id, 
                                        $administration->id
                                    ]) }}" class="text-indigo-600 hover:text-indigo-900">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $administrations->links() }}
                    </div>
                    @else
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                        <div class="flex">
                            <div>
                                <p class="text-yellow-700">
                                    No medications have been administered to this patient yet.
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Daily Medication Summary (if needed) -->
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ __('Medication Summary') }}
                    </h3>

                    @if(count($administrations) > 0)
                        @php
                            // Group administrations by medication
                            $medicationGroups = $administrations->groupBy(function($item) {
                                return $item->medicationSchedule->medication->name;
                            });
                            
                            // Count total medications administered
                            $totalAdministered = $administrations->count();
                            
                            // Count completed vs. other statuses
                            $completed = $administrations->where('status', 'completed')->count();
                            $partial = $administrations->where('status', 'partial')->count();
                            $refused = $administrations->where('status', 'refused')->count();
                            $held = $administrations->where('status', 'held')->count();
                            $error = $administrations->where('status', 'error')->count();
                        @endphp

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-md font-medium text-gray-800 mb-2">Administration Statistics</h4>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    <li>Total administrations: <span class="font-semibold">{{ $totalAdministered }}</span></li>
                                    <li>Completed: <span class="font-semibold text-green-600">{{ $completed }}</span></li>
                                    @if($partial > 0)
                                    <li>Partial: <span class="font-semibold text-yellow-600">{{ $partial }}</span></li>
                                    @endif
                                    @if($refused > 0)
                                    <li>Refused: <span class="font-semibold text-red-600">{{ $refused }}</span></li>
                                    @endif
                                    @if($held > 0)
                                    <li>Held: <span class="font-semibold text-blue-600">{{ $held }}</span></li>
                                    @endif
                                    @if($error > 0)
                                    <li>Error: <span class="font-semibold text-red-600">{{ $error }}</span></li>
                                    @endif
                                </ul>
                            </div>
                            
                            <div>
                                <h4 class="text-md font-medium text-gray-800 mb-2">Medications Administered</h4>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    @foreach($medicationGroups as $medicationName => $group)
                                    <li>{{ $medicationName }}: <span class="font-semibold">{{ $group->count() }} times</span></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @else
                        <p class="text-gray-600">No medication summary available.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>