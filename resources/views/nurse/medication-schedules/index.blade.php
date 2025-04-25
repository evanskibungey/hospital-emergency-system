<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Medication Schedule for Patient') }}: {{ $visit->patient->first_name }} {{ $visit->patient->last_name }}
            </h2>
            <div>
                <a href="{{ route('nurse.medication-schedules.create', $visit->id) }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ __('Schedule New Medication') }}
                </a>
                <a href="{{ route('nurse.medication-administrations.visit-history', $visit->id) }}"
                    class="ml-2 inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400">
                    {{ __('Administration History') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p>{{ session('success') }}</p>
            </div>
            @endif

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
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Actions</h3>
                            <div class="space-y-2">
                                <a href="{{ route('nurse.vital-signs.create', $visit->id) }}"
                                    class="text-indigo-600 hover:text-indigo-900 block">
                                    Record Vital Signs
                                </a>
                                <a href="{{ route('nurse.vital-signs.index', $visit->id) }}"
                                    class="text-indigo-600 hover:text-indigo-900 block">
                                    View Vital Signs History
                                </a>
                                <a href="{{ route('nurse.medication-schedules.create', $visit->id) }}"
                                    class="text-indigo-600 hover:text-indigo-900 block">
                                    Schedule Medication
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scheduled Medications Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ __('Scheduled Medications') }}
                    </h3>

                    @if(count($scheduledMedications) > 0)
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
                                    Frequency
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
                            @foreach($scheduledMedications as $schedule)
                            <tr class="@if($schedule->isOverdue()) bg-red-50 @elseif($schedule->isDue()) bg-yellow-50 @endif">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $schedule->medication->name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $schedule->medication->dosage_form }} - {{ $schedule->medication->strength }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $schedule->dosage }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $schedule->scheduled_time->format('M d, Y H:i') }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $schedule->scheduled_time->diffForHumans() }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ ucfirst(str_replace('_', ' ', $schedule->frequency)) }}
                                    </div>
                                    @if($schedule->frequency_notes)
                                    <div class="text-sm text-gray-500">
                                        {{ $schedule->frequency_notes }}
                                    </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($schedule->isDue())
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Due Now
                                    </span>
                                    @elseif($schedule->isOverdue())
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Overdue
                                    </span>
                                    @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Scheduled
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('nurse.medication-administrations.create', [$visit->id, $schedule->id]) }}"
                                        class="text-indigo-600 hover:text-indigo-900 mr-3">
                                        Administer
                                    </a>
                                    <a href="{{ route('nurse.medication-schedules.show', [$visit->id, $schedule->id]) }}"
                                        class="text-indigo-600 hover:text-indigo-900 mr-3">
                                        View
                                    </a>
                                    <a href="{{ route('nurse.medication-schedules.edit', [$visit->id, $schedule->id]) }}"
                                        class="text-indigo-600 hover:text-indigo-900 mr-3">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('nurse.medication-schedules.cancel', [$visit->id, $schedule->id]) }}"
                                        class="inline"
                                        onsubmit="return confirm('Are you sure you want to cancel this medication?');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            Cancel
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                        <div class="flex">
                            <div>
                                <p class="text-yellow-700">
                                    No medications currently scheduled for this patient.
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Administered Medications Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ __('Administered Medications') }}
                    </h3>

                    @if(count($administeredMedications) > 0)
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
                            @foreach($administeredMedications as $schedule)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $schedule->medication->name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $schedule->medication->dosage_form }} - {{ $schedule->medication->strength }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $schedule->dosage }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $schedule->scheduled_time->format('M d, Y H:i') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        @if($schedule->administrations->count() > 0)
                                            {{ $schedule->administrations->first()->administered_at->format('M d, Y H:i') }}
                                        @else
                                            N/A
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        @if($schedule->administrations->count() > 0)
                                            {{ $schedule->administrations->first()->administeredBy->name ?? 'Unknown' }}
                                        @else
                                            N/A
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($schedule->administrations->count() > 0)
                                        @php $status = $schedule->administrations->first()->status; @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($status == 'completed') bg-green-100 text-green-800 
                                            @elseif($status == 'partial') bg-yellow-100 text-yellow-800 
                                            @elseif($status == 'refused') bg-red-100 text-red-800 
                                            @elseif($status == 'held') bg-blue-100 text-blue-800 
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($status) }}
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Administered
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if($schedule->administrations->count() > 0)
                                        <a href="{{ route('nurse.medication-administrations.show', [$visit->id, $schedule->id, $schedule->administrations->first()->id]) }}"
                                            class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            View Details
                                        </a>
                                    @else
                                        <span class="text-gray-400">No details</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="bg-gray-50 border-l-4 border-gray-400 p-4">
                        <div class="flex">
                            <div>
                                <p class="text-gray-700">
                                    No medications have been administered for this patient yet.
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Missed/Cancelled Medications Section -->
            @if(count($missedMedications) > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ __('Missed/Cancelled Medications') }}
                    </h3>

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
                                    Status
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Notes
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($missedMedications as $schedule)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $schedule->medication->name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $schedule->medication->dosage_form }} - {{ $schedule->medication->strength }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $schedule->dosage }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $schedule->scheduled_time->format('M d, Y H:i') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($schedule->status == 'missed') bg-red-100 text-red-800 
                                        @elseif($schedule->status == 'cancelled') bg-gray-100 text-gray-800 
                                        @endif">
                                        {{ ucfirst($schedule->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        {{ $schedule->notes ?: 'No notes' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('nurse.medication-schedules.show', [$visit->id, $schedule->id]) }}"
                                        class="text-indigo-600 hover:text-indigo-900">
                                        View Details
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