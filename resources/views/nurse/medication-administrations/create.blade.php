<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Administer Medication') }}
            </h2>
            <a href="{{ route('nurse.medication-schedules.index', $visit->id) }}"
                class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400">
                {{ __('Back to Schedule') }}
            </a>
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
                                <p class="text-sm text-gray-600">Allergies: <span class="font-semibold {{ $visit->patient->allergies ? 'text-red-600 font-bold' : '' }}">{{ $visit->patient->allergies ?: 'None reported' }}</span></p>
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

                    <!-- Medication Information Card -->
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                        <h3 class="text-lg font-medium text-blue-900 mb-2">Medication Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-blue-800">Medication: <span class="font-semibold">{{ $medicationSchedule->medication->name }}</span></p>
                                <p class="text-sm text-blue-800">Dosage Form: <span class="font-semibold">{{ $medicationSchedule->medication->dosage_form }}</span></p>
                                <p class="text-sm text-blue-800">Strength: <span class="font-semibold">{{ $medicationSchedule->medication->strength }}</span></p>
                                <p class="text-sm text-blue-800">Prescribed Dosage: <span class="font-semibold">{{ $medicationSchedule->dosage }}</span></p>
                                <p class="text-sm text-blue-800">Scheduled Time: <span class="font-semibold">{{ $medicationSchedule->scheduled_time->format('M d, Y H:i') }}</span> ({{ $medicationSchedule->scheduled_time->diffForHumans() }})</p>
                            </div>
                            <div>
                                <p class="text-sm text-blue-800">Frequency: <span class="font-semibold">{{ ucfirst(str_replace('_', ' ', $medicationSchedule->frequency)) }}</span></p>
                                @if($medicationSchedule->frequency_notes)
                                <p class="text-sm text-blue-800">Frequency Notes: <span class="font-semibold">{{ $medicationSchedule->frequency_notes }}</span></p>
                                @endif
                                <p class="text-sm text-blue-800">Controlled Substance: <span class="font-semibold">{{ $medicationSchedule->medication->is_controlled_substance ? 'Yes' : 'No' }}</span></p>
                                @if($medicationSchedule->medication->instructions)
                                <p class="text-sm text-blue-800">Instructions: <span class="font-semibold">{{ $medicationSchedule->medication->instructions }}</span></p>
                                @endif
                                @if($medicationSchedule->notes)
                                <p class="text-sm text-blue-800">Schedule Notes: <span class="font-semibold">{{ $medicationSchedule->notes }}</span></p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Administration Form -->
                    <form method="POST" action="{{ route('nurse.medication-administrations.store', [$visit->id, $medicationSchedule->id]) }}">
                        @csrf

                        <!-- Administration Time -->
                        <div class="mb-4">
                            <x-label for="administered_at" :value="__('Administration Time')" />
                            <x-input id="administered_at" type="datetime-local" name="administered_at" :value="old('administered_at', now()->format('Y-m-d\TH:i'))" required class="block mt-1 w-full" />
                            @error('administered_at')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Actual Dosage -->
                        <div class="mb-4">
                            <x-label for="actual_dosage" :value="__('Actual Dosage (if different from prescribed)')" />
                            <x-input id="actual_dosage" type="text" name="actual_dosage" :value="old('actual_dosage')" class="block mt-1 w-full" placeholder="{{ $medicationSchedule->dosage }}" />
                            <p class="text-sm text-gray-500 mt-1">Leave blank if you administered the exact prescribed dosage.</p>
                            @error('actual_dosage')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <x-label for="status" :value="__('Administration Status')" />
                            <select id="status" name="status" required
                                class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 w-full">
                                @foreach($statuses as $value => $label)
                                    <option value="{{ $value }}" {{ old('status') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div class="mb-4">
                            <x-label for="notes" :value="__('Administration Notes')" />
                            <textarea id="notes" name="notes" rows="3"
                                class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 w-full">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6 p-4 bg-yellow-50 rounded-lg border-l-4 border-yellow-500">
                            <h3 class="text-md font-medium text-yellow-900 mb-2">Administration Verification</h3>
                            <p class="text-sm text-yellow-700 mb-2">By submitting this form, you are confirming that:</p>
                            <ul class="text-sm text-yellow-700 list-disc list-inside mb-2">
                                <li>You have verified this is the correct patient (using two identifiers)</li>
                                <li>You have verified this is the correct medication</li>
                                <li>You have verified the correct dosage</li>
                                <li>You have verified the correct route of administration</li>
                                <li>You have verified this is the correct time for administration</li>
                            </ul>
                            <p class="text-sm text-yellow-700 font-semibold">This record cannot be edited after submission.</p>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('nurse.medication-schedules.index', $visit->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
                                {{ __('Cancel') }}
                            </a>
                            <x-button class="ml-3 bg-green-600 hover:bg-green-700">
                                {{ __('Record Administration') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>