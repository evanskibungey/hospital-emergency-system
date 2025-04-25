<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Discharge Patient') }}
            </h2>
            <a href="{{ route('doctor.visits.show', $visit) }}" class="px-4 py-2 bg-gray-200 rounded-md text-gray-700 hover:bg-gray-300">
                {{ __('Back to Visit') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                        <h3 class="font-semibold text-lg mb-2">Patient Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p><span class="font-medium">Name:</span> {{ $visit->patient->full_name }}</p>
                                <p><span class="font-medium">Age:</span> {{ $visit->patient->date_of_birth->age }} years</p>
                                <p><span class="font-medium">MRN:</span> {{ $visit->patient->medical_record_number }}</p>
                            </div>
                            <div>
                                <p><span class="font-medium">Chief Complaint:</span> {{ $visit->chief_complaint }}</p>
                                <p><span class="font-medium">Visit Status:</span> 
                                    <span class="px-2 py-1 rounded-full text-xs {{ $visit->is_critical ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $visit->is_critical ? 'CRITICAL' : 'STANDARD' }}
                                    </span>
                                </p>
                                <p><span class="font-medium">Check-in Time:</span> {{ $visit->check_in_time->format('M d, Y g:i A') }}</p>
                            </div>
                        </div>
                    </div>

                    @if(!$visit->isReadyForDischarge())
                    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
                        <p class="font-bold">Warning</p>
                        <p>This patient has active treatments that have not been completed. It is recommended to complete or discontinue all active treatments before discharge.</p>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('doctor.discharges.store', $visit) }}">
                        @csrf

                        <div class="mb-4">
                            <x-input-label for="discharge_diagnosis" :value="__('Discharge Diagnosis')" />
                            <textarea id="discharge_diagnosis" name="discharge_diagnosis" rows="3" 
                                class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                required>{{ old('discharge_diagnosis') }}</textarea>
                            <x-input-error :messages="$errors->get('discharge_diagnosis')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="discharge_summary" :value="__('Discharge Summary')" />
                            <textarea id="discharge_summary" name="discharge_summary" rows="5" 
                                class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                required>{{ old('discharge_summary') }}</textarea>
                            <x-input-error :messages="$errors->get('discharge_summary')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="discharge_instructions" :value="__('Discharge Instructions')" />
                            <textarea id="discharge_instructions" name="discharge_instructions" rows="5" 
                                class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                required>{{ old('discharge_instructions') }}</textarea>
                            <x-input-error :messages="$errors->get('discharge_instructions')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="medications_at_discharge" :value="__('Medications at Discharge')" />
                            <textarea id="medications_at_discharge" name="medications_at_discharge" rows="3" 
                                class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('medications_at_discharge') }}</textarea>
                            <x-input-error :messages="$errors->get('medications_at_discharge')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="mb-4">
                                <x-input-label for="activity_restrictions" :value="__('Activity Restrictions')" />
                                <textarea id="activity_restrictions" name="activity_restrictions" rows="3" 
                                    class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('activity_restrictions') }}</textarea>
                                <x-input-error :messages="$errors->get('activity_restrictions')" class="mt-2" />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="diet_instructions" :value="__('Diet Instructions')" />
                                <textarea id="diet_instructions" name="diet_instructions" rows="3" 
                                    class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('diet_instructions') }}</textarea>
                                <x-input-error :messages="$errors->get('diet_instructions')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="follow_up_instructions" :value="__('Follow-up Instructions')" />
                            <textarea id="follow_up_instructions" name="follow_up_instructions" rows="3" 
                                class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('follow_up_instructions') }}</textarea>
                            <x-input-error :messages="$errors->get('follow_up_instructions')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="discharge_disposition" :value="__('Discharge Disposition')" />
                            <select id="discharge_disposition" name="discharge_disposition" 
                                class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="home" {{ old('discharge_disposition') == 'home' ? 'selected' : '' }}>Home</option>
                                <option value="home_with_services" {{ old('discharge_disposition') == 'home_with_services' ? 'selected' : '' }}>Home with Services</option>
                                <option value="transfer_to_facility" {{ old('discharge_disposition') == 'transfer_to_facility' ? 'selected' : '' }}>Transfer to Another Facility</option>
                                <option value="left_against_medical_advice" {{ old('discharge_disposition') == 'left_against_medical_advice' ? 'selected' : '' }}>Left Against Medical Advice</option>
                                <option value="other" {{ old('discharge_disposition') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            <x-input-error :messages="$errors->get('discharge_disposition')" class="mt-2" />
                        </div>

                        <div id="facility_section" class="mb-4 {{ old('discharge_disposition') == 'transfer_to_facility' ? '' : 'hidden' }}">
                            <x-input-label for="destination_facility" :value="__('Destination Facility')" />
                            <x-text-input id="destination_facility" type="text" name="destination_facility" class="mt-1 block w-full" 
                                value="{{ old('destination_facility') }}" />
                            <x-input-error :messages="$errors->get('destination_facility')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="notes" :value="__('Additional Notes')" />
                            <textarea id="notes" name="notes" rows="3" 
                                class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('notes') }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>
                        
                        <div class="mb-4">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="instructions_provided" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="1" {{ old('instructions_provided') ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600">{{ __('Instructions provided to patient/family') }}</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('doctor.visits.show', $visit) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 mr-3">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button>
                                {{ __('Discharge Patient') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Show/hide destination facility field based on discharge disposition
        document.getElementById('discharge_disposition').addEventListener('change', function() {
            var facilitySection = document.getElementById('facility_section');
            if (this.value === 'transfer_to_facility') {
                facilitySection.classList.remove('hidden');
            } else {
                facilitySection.classList.add('hidden');
            }
        });
    </script>
</x-app-layout>
