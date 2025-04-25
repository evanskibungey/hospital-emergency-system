<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Medication Schedule') }}
            </h2>
            <div>
                <a href="{{ route('nurse.medication-schedules.show', [$visit->id, $medicationSchedule->id]) }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 mr-2">
                    {{ __('View Details') }}
                </a>
                <a href="{{ route('nurse.medication-schedules.index', $visit->id) }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400">
                    {{ __('Back to Schedule') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Patient Information Card -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Patient Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Name: <span class="font-semibold">{{ $visit->patient->first_name }} {{ $visit->patient->last_name }}</span></p>
                                <p class="text-sm text-gray-600">MRN: <span class="font-semibold">{{ $visit->patient->medical_record_number ?? 'N/A' }}</span></p>
                                <p class="text-sm text-gray-600">Gender: <span class="font-semibold">{{ ucfirst($visit->patient->gender ?? 'N/A') }}</span></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">DOB: <span class="font-semibold">{{ $visit->patient->date_of_birth ? $visit->patient->date_of_birth->format('M d, Y') : 'N/A' }}</span></p>
                                <p class="text-sm text-gray-600">Age: <span class="font-semibold">{{ $visit->patient->date_of_birth ? $visit->patient->date_of_birth->age : 'N/A' }}</span></p>
                                <p class="text-sm text-gray-600">Allergies: <span class="font-semibold">{{ $visit->patient->allergies ?: 'None reported' }}</span></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Visit Status: 
                                    <span class="font-semibold px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($visit->status == 'waiting') bg-blue-100 text-blue-800 
                                        @elseif($visit->status == 'in_progress') bg-green-100 text-green-800 
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $visit->status)) }}
                                    </span>
                                </p>
                                <p class="text-sm text-gray-600">Chief Complaint: <span class="font-semibold">{{ $visit->chief_complaint ?? 'Not recorded' }}</span></p>
                            </div>
                        </div>
                    </div>

                    <!-- Current Medication Information -->
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                        <h3 class="text-lg font-medium text-blue-900 mb-2">Current Medication Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-blue-800">Medication: <span class="font-semibold">{{ $medicationSchedule->medication->name }}</span></p>
                                <p class="text-sm text-blue-800">Dosage Form: <span class="font-semibold">{{ $medicationSchedule->medication->dosage_form }}</span></p>
                                <p class="text-sm text-blue-800">Strength: <span class="font-semibold">{{ $medicationSchedule->medication->strength }}</span></p>
                                <p class="text-sm text-blue-800">Current Dosage: <span class="font-semibold">{{ $medicationSchedule->dosage }}</span></p>
                            </div>
                            <div>
                                <p class="text-sm text-blue-800">Current Frequency: <span class="font-semibold">{{ ucfirst(str_replace('_', ' ', $medicationSchedule->frequency)) }}</span></p>
                                <p class="text-sm text-blue-800">Scheduled Time: <span class="font-semibold">{{ $medicationSchedule->scheduled_time->format('M d, Y H:i') }}</span></p>
                                <p class="text-sm text-blue-800">Current Status: <span class="font-semibold">{{ ucfirst($medicationSchedule->status) }}</span></p>
                                @if($medicationSchedule->notes)
                                <p class="text-sm text-blue-800">Notes: <span class="font-semibold">{{ $medicationSchedule->notes }}</span></p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Medication Schedule Form -->
                    <form method="POST" action="{{ route('nurse.medication-schedules.update', [$visit->id, $medicationSchedule->id]) }}">
                        @csrf
                        @method('PUT')

                        <!-- Medication Selection -->
                        <div class="mb-4">
                            <x-label for="medication_id" :value="__('Medication')" />
                            
                            <select id="medication_id" name="medication_id" required
                                class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 w-full">
                                @foreach($medications as $medication)
                                    <option value="{{ $medication->id }}" {{ old('medication_id', $medicationSchedule->medication_id) == $medication->id ? 'selected' : '' }}>
                                        {{ $medication->name }} ({{ $medication->dosage_form }} - {{ $medication->strength }})
                                    </option>
                                @endforeach
                            </select>

                            @error('medication_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Dosage -->
                        <div class="mb-4">
                            <x-label for="dosage" :value="__('Dosage')" />
                            <x-input id="dosage" type="text" name="dosage" :value="old('dosage', $medicationSchedule->dosage)" required class="block mt-1 w-full" />
                            @error('dosage')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Frequency -->
                        <div class="mb-4">
                            <x-label for="frequency" :value="__('Frequency')" />
                            <select id="frequency" name="frequency" required
                                class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 w-full">
                                @foreach($frequencies as $value => $label)
                                    <option value="{{ $value }}" {{ old('frequency', $medicationSchedule->frequency) == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('frequency')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Frequency Notes (conditional) -->
                        <div id="frequency_notes_container" class="mb-4 {{ !in_array($medicationSchedule->frequency, ['other', 'as_needed']) ? 'hidden' : '' }}">
                            <x-label for="frequency_notes" :value="__('Frequency Notes')" />
                            <textarea id="frequency_notes" name="frequency_notes" rows="2"
                                class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 w-full">{{ old('frequency_notes', $medicationSchedule->frequency_notes) }}</textarea>
                            @error('frequency_notes')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Scheduled Time -->
                        <div class="mb-4">
                            <x-label for="scheduled_time" :value="__('Scheduled Time')" />
                            <x-input id="scheduled_time" type="datetime-local" name="scheduled_time" 
                                :value="old('scheduled_time', $medicationSchedule->scheduled_time->format('Y-m-d\TH:i'))" 
                                required class="block mt-1 w-full" />
                            @error('scheduled_time')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <x-label for="status" :value="__('Status')" />
                            <select id="status" name="status" required
                                class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 w-full">
                                <option value="scheduled" {{ old('status', $medicationSchedule->status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                <option value="administered" {{ old('status', $medicationSchedule->status) == 'administered' ? 'selected' : '' }}>Administered</option>
                                <option value="missed" {{ old('status', $medicationSchedule->status) == 'missed' ? 'selected' : '' }}>Missed</option>
                                <option value="cancelled" {{ old('status', $medicationSchedule->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div class="mb-4">
                            <x-label for="notes" :value="__('Notes')" />
                            <textarea id="notes" name="notes" rows="3"
                                class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 w-full">{{ old('notes', $medicationSchedule->notes) }}</textarea>
                            @error('notes')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Warning Message -->
                        <div class="mb-6 p-4 bg-yellow-50 rounded-lg border-l-4 border-yellow-500">
                            <h3 class="text-md font-medium text-yellow-900 mb-2">Important Note</h3>
                            <p class="text-sm text-yellow-700 mb-2">Editing this medication schedule only affects this specific dose. If this is part of a recurring medication schedule, other doses will not be affected.</p>
                            <p class="text-sm text-yellow-700">For major changes to medication regimen, consider cancelling this schedule and creating a new one.</p>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('nurse.medication-schedules.index', $visit->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
                                {{ __('Cancel') }}
                            </a>
                            <x-button class="ml-3">
                                {{ __('Update Schedule') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const frequencySelect = document.getElementById('frequency');
            const frequencyNotesContainer = document.getElementById('frequency_notes_container');
            
            // Show/hide frequency notes based on selection
            function toggleFrequencyNotes() {
                if (frequencySelect.value === 'other' || frequencySelect.value === 'as_needed') {
                    frequencyNotesContainer.classList.remove('hidden');
                } else {
                    frequencyNotesContainer.classList.add('hidden');
                }
            }
            
            // Add event listener
            frequencySelect.addEventListener('change', toggleFrequencyNotes);
        });
    </script>
</x-app-layout>