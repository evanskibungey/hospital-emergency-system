<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Recent Medication Administrations') }}
            </h2>
            <div>
                <a href="{{ route('nurse.medication-administrations.due') }}"
                    class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-400 mr-2">
                    {{ __('View Due Medications') }}
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
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ __('Medications Administered in the Last 24 Hours') }}
                    </h3>

                    @if(count($administrations) > 0)
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
                                    Time Administered
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
                                        {{ $administration->medicationSchedule->visit->patient->first_name }} 
                                        {{ $administration->medicationSchedule->visit->patient->last_name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        MRN: {{ $administration->medicationSchedule->visit->patient->medical_record_number ?? 'N/A' }}
                                    </div>
                                </td>
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
                                        $administration->medicationSchedule->visit->id, 
                                        $administration->medicationSchedule->id, 
                                        $administration->id
                                    ]) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                        View Details
                                    </a>
                                    <a href="{{ route('nurse.medication-schedules.index', $administration->medicationSchedule->visit->id) }}" 
                                        class="text-indigo-600 hover:text-indigo-900">
                                        Patient Schedule
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
                                    No medications have been administered in the last 24 hours.
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