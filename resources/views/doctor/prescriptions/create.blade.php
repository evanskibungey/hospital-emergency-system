<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Write Prescription') }}
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
                                <p><span class="font-medium">Allergies:</span> 
                                    <span class="{{ empty($visit->patient->allergies) ? 'text-green-600' : 'text-red-600 font-medium' }}">
                                        {{ empty($visit->patient->allergies) ? 'No Known Allergies' : $visit->patient->allergies }}
                                    </span>
                                </p>
                                <p><span class="font-medium">Current Medications:</span>
                                    <span class="{{ empty($visit->patient->current_medications) ? 'text-gray-500 italic' : '' }}">
                                        {{ empty($visit->patient->current_medications) ? 'None documented' : $visit->patient->current_medications }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('doctor.prescriptions.store', $visit) }}">
                        @csrf

                        <div class="mb-4">
                            <x-input-label for="treatment_id" :value="__('Associated Treatment (Optional)')" />
                            <select id="treatment_id" name="treatment_id" 
                                class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">-- None --</option>
                                @foreach ($treatments as $treatment)
                                    <option value="{{ $treatment->id }}" {{ old('treatment_id') == $treatment->id ? 'selected' : '' }}>
                                        {{ Str::limit($treatment->diagnosis, 50) }} ({{ ucfirst($treatment->status) }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('treatment_id')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="medication_id" :value="__('Select from Formulary (Optional)')" />
                            <select id="medication_id" name="medication_id" 
                                class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">-- Custom Medication --</option>
                                @foreach ($medications as $medication)
                                    <option value="{{ $medication->id }}" data-name="{{ $medication->name }}" data-strength="{{ $medication->strength }}" data-unit="{{ $medication->unit }}" data-form="{{ $medication->dosage_form }}" data-controlled="{{ $medication->is_controlled_substance ? '1' : '0' }}" {{ old('medication_id') == $medication->id ? 'selected' : '' }}>
                                        {{ $medication->name }} {{ $medication->strength }} {{ $medication->unit }} {{ $medication->dosage_form }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('medication_id')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="medication_name" :value="__('Medication Name')" />
                            <x-text-input id="medication_name" class="block mt-1 w-full" type="text" name="medication_name" :value="old('medication_name')" required autofocus />
                            <x-input-error :messages="$errors->get('medication_name')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="mb-4">
                                <x-input-label for="dosage" :value="__('Dosage')" />
                                <x-text-input id="dosage" class="block mt-1 w-full" type="text" name="dosage" :value="old('dosage')" required />
                                <x-input-error :messages="$errors->get('dosage')" class="mt-2" />
                            </div>
                            
                            <div class="mb-4">
                                <x-input-label for="route" :value="__('Route')" />
                                <select id="route" name="route" 
                                    class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    <option value="">-- Select Route --</option>
                                    <option value="Oral" {{ old('route') == 'Oral' ? 'selected' : '' }}>Oral</option>
                                    <option value="Intravenous" {{ old('route') == 'Intravenous' ? 'selected' : '' }}>Intravenous (IV)</option>
                                    <option value="Intramuscular" {{ old('route') == 'Intramuscular' ? 'selected' : '' }}>Intramuscular (IM)</option>
                                    <option value="Subcutaneous" {{ old('route') == 'Subcutaneous' ? 'selected' : '' }}>Subcutaneous (SC)</option>
                                    <option value="Topical" {{ old('route') == 'Topical' ? 'selected' : '' }}>Topical</option>
                                    <option value="Inhaled" {{ old('route') == 'Inhaled' ? 'selected' : '' }}>Inhaled</option>
                                    <option value="Rectal" {{ old('route') == 'Rectal' ? 'selected' : '' }}>Rectal</option>
                                    <option value="Sublingual" {{ old('route') == 'Sublingual' ? 'selected' : '' }}>Sublingual</option>
                                    <option value="Transdermal" {{ old('route') == 'Transdermal' ? 'selected' : '' }}>Transdermal</option>
                                    <option value="Other" {{ old('route') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                <x-input-error :messages="$errors->get('route')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="frequency" :value="__('Frequency')" />
                            <select id="frequency" name="frequency" 
                                class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                <option value="">-- Select Frequency --</option>
                                <option value="Once daily" {{ old('frequency') == 'Once daily' ? 'selected' : '' }}>Once daily</option>
                                <option value="Twice daily" {{ old('frequency') == 'Twice daily' ? 'selected' : '' }}>Twice daily (BID)</option>
                                <option value="Three times daily" {{ old('frequency') == 'Three times daily' ? 'selected' : '' }}>Three times daily (TID)</option>
                                <option value="Four times daily" {{ old('frequency') == 'Four times daily' ? 'selected' : '' }}>Four times daily (QID)</option>
                                <option value="Every 4 hours" {{ old('frequency') == 'Every 4 hours' ? 'selected' : '' }}>Every 4 hours (q4h)</option>
                                <option value="Every 6 hours" {{ old('frequency') == 'Every 6 hours' ? 'selected' : '' }}>Every 6 hours (q6h)</option>
                                <option value="Every 8 hours" {{ old('frequency') == 'Every 8 hours' ? 'selected' : '' }}>Every 8 hours (q8h)</option>
                                <option value="Every 12 hours" {{ old('frequency') == 'Every 12 hours' ? 'selected' : '' }}>Every 12 hours (q12h)</option>
                                <option value="As needed" {{ old('frequency') == 'As needed' ? 'selected' : '' }}>As needed (PRN)</option>
                                <option value="Once" {{ old('frequency') == 'Once' ? 'selected' : '' }}>Once</option>
                                <option value="Other" {{ old('frequency') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            <x-input-error :messages="$errors->get('frequency')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="mb-4">
                                <x-input-label for="quantity" :value="__('Quantity')" />
                                <x-text-input id="quantity" class="block mt-1 w-full" type="number" name="quantity" :value="old('quantity', 1)" min="1" required />
                                <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                            </div>
                            
                            <div class="mb-4">
                                <x-input-label for="refills" :value="__('Refills')" />
                                <x-text-input id="refills" class="block mt-1 w-full" type="number" name="refills" :value="old('refills', 0)" min="0" required />
                                <x-input-error :messages="$errors->get('refills')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="instructions" :value="__('Instructions')" />
                            <textarea id="instructions" name="instructions" rows="3" 
                                class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                required>{{ old('instructions') }}</textarea>
                            <x-input-error :messages="$errors->get('instructions')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="notes" :value="__('Additional Notes (not shown to patient)')" />
                            <textarea id="notes" name="notes" rows="2" 
                                class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('notes') }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="start_date" :value="__('Start Date')" />
                            <x-text-input id="start_date" class="block mt-1 w-full" type="date" name="start_date" :value="old('start_date', date('Y-m-d'))" required />
                            <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="end_date" :value="__('End Date (Optional)')" />
                            <x-text-input id="end_date" class="block mt-1 w-full" type="date" name="end_date" :value="old('end_date')" />
                            <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <label class="inline-flex items-center">
                                <input type="checkbox" id="is_controlled_substance" name="is_controlled_substance" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="1" {{ old('is_controlled_substance') ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600">{{ __('This is a controlled substance') }}</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('doctor.visits.show', $visit) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 mr-3">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button>
                                {{ __('Create Prescription') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Populate medication fields when selecting from formulary
        document.getElementById('medication_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value !== '') {
                document.getElementById('medication_name').value = selectedOption.dataset.name + ' ' + 
                    selectedOption.dataset.strength + ' ' + selectedOption.dataset.unit + ' ' + 
                    selectedOption.dataset.form;
                
                document.getElementById('dosage').value = selectedOption.dataset.strength + ' ' + 
                    selectedOption.dataset.unit;
                
                document.getElementById('is_controlled_substance').checked = selectedOption.dataset.controlled === '1';
            }
        });
    </script>
</x-app-layout>
