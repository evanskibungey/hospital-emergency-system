<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Medications Due for Administration') }}
            </h2>
            <a href="{{ route('nurse.dashboard') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400">
                {{ __('Back to Dashboard') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p>{{ session('success') }}</p>
            </div>
            @endif

            <!-- Due Medications Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ __('Medications Due Now') }}
                        </h3>
                        <a href="{{ route('nurse.medication-administrations.recent') }}"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Recent Administrations') }}
                        </a>
                    </div>

                    @if(count($visitsWithDueMeds) > 0)
                        @foreach($visitsWithDueMeds as $visit)
                            <div class="mb-6 border rounded-lg overflow-hidden">
                                <!-- Visit Header -->
                                <div class="bg-gray-100 px-4 py-3 border-b">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h4 class="text-md font-semibold text-gray-900">
                                                {{ $visit->patient->first_name }} {{ $visit->patient->last_name }}
                                            </h4>
                                            <p class="text-sm text-gray-600">
                                                MRN: {{ $visit->patient->medical_record_number ?? 'N/A' }} | 
                                                DOB: {{ $visit->patient->date_of_birth ? $visit->patient->date_of_birth->format('M d, Y') : 'N/A' }} |
                                                Priority: 
                                                <span class="font-semibold px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($visit->priority == 'high' || $visit->priority == 'critical') bg-red-100 text-red-800 
                                                @elseif($visit->priority == 'medium') bg-yellow-100 text-yellow-800 
                                                @else bg-green-100 text-green-800 @endif">
                                                    {{ ucfirst($visit->priority) }}
                                                </span>
                                            </p>
                                        </div>
                                        <div>
                                            <a href="{{ route('nurse.medication-schedules.index', $visit->id) }}"
                                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                {{ __('View All Medications') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Due Medications Table -->
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
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($visit->medicationSchedules as $schedule)
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
                                                <div class="text-sm @if($schedule->isOverdue()) text-red-600 font-semibold @else text-gray-500 @endif">
                                                    {{ $schedule->scheduled_time->diffForHumans() }}
                                                </div>
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
                                                    class="text-indigo-600 hover:text-indigo-900 mr-3 @if($schedule->isOverdue()) font-bold text-red-600 hover:text-red-800 @endif">
                                                    Administer
                                                </a>
                                                <a href="{{ route('nurse.medication-schedules.show', [$visit->id, $schedule->id]) }}"
                                                    class="text-indigo-600 hover:text-indigo-900">
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    @else
                        <div class="bg-green-50 border-l-4 border-green-400 p-4">
                            <div class="flex">
                                <div>
                                    <p class="text-green-700">
                                        No medications are currently due for administration. All scheduled medications have been administered.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>