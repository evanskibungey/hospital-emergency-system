<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Order Lab Test') }}
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

                    <form method="POST" action="{{ route('doctor.lab-orders.store', $visit) }}">
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
                            <x-input-label for="test_name" :value="__('Test Name')" />
                            <select id="test_name" name="test_name" 
                                class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                <option value="">-- Select Test --</option>
                                <optgroup label="Common Tests">
                                    <option value="Complete Blood Count (CBC)" {{ old('test_name') == 'Complete Blood Count (CBC)' ? 'selected' : '' }}>Complete Blood Count (CBC)</option>
                                    <option value="Basic Metabolic Panel (BMP)" {{ old('test_name') == 'Basic Metabolic Panel (BMP)' ? 'selected' : '' }}>Basic Metabolic Panel (BMP)</option>
                                    <option value="Comprehensive Metabolic Panel (CMP)" {{ old('test_name') == 'Comprehensive Metabolic Panel (CMP)' ? 'selected' : '' }}>Comprehensive Metabolic Panel (CMP)</option>
                                    <option value="Urinalysis" {{ old('test_name') == 'Urinalysis' ? 'selected' : '' }}>Urinalysis</option>
                                    <option value="Liver Function Tests (LFT)" {{ old('test_name') == 'Liver Function Tests (LFT)' ? 'selected' : '' }}>Liver Function Tests (LFT)</option>
                                    <option value="Creatinine" {{ old('test_name') == 'Creatinine' ? 'selected' : '' }}>Creatinine</option>
                                    <option value="Blood Urea Nitrogen (BUN)" {{ old('test_name') == 'Blood Urea Nitrogen (BUN)' ? 'selected' : '' }}>Blood Urea Nitrogen (BUN)</option>
                                </optgroup>
                                <optgroup label="Cardiac Tests">
                                    <option value="Troponin" {{ old('test_name') == 'Troponin' ? 'selected' : '' }}>Troponin</option>
                                    <option value="B-type Natriuretic Peptide (BNP)" {{ old('test_name') == 'B-type Natriuretic Peptide (BNP)' ? 'selected' : '' }}>B-type Natriuretic Peptide (BNP)</option>
                                    <option value="Creatine Kinase (CK)" {{ old('test_name') == 'Creatine Kinase (CK)' ? 'selected' : '' }}>Creatine Kinase (CK)</option>
                                    <option value="Lipid Panel" {{ old('test_name') == 'Lipid Panel' ? 'selected' : '' }}>Lipid Panel</option>
                                </optgroup>
                                <optgroup label="Coagulation Tests">
                                    <option value="Prothrombin Time (PT)" {{ old('test_name') == 'Prothrombin Time (PT)' ? 'selected' : '' }}>Prothrombin Time (PT)</option>
                                    <option value="Partial Thromboplastin Time (PTT)" {{ old('test_name') == 'Partial Thromboplastin Time (PTT)' ? 'selected' : '' }}>Partial Thromboplastin Time (PTT)</option>
                                    <option value="International Normalized Ratio (INR)" {{ old('test_name') == 'International Normalized Ratio (INR)' ? 'selected' : '' }}>International Normalized Ratio (INR)</option>
                                    <option value="D-dimer" {{ old('test_name') == 'D-dimer' ? 'selected' : '' }}>D-dimer</option>
                                </optgroup>
                                <optgroup label="Infectious Disease">
                                    <option value="Blood Culture" {{ old('test_name') == 'Blood Culture' ? 'selected' : '' }}>Blood Culture</option>
                                    <option value="Urine Culture" {{ old('test_name') == 'Urine Culture' ? 'selected' : '' }}>Urine Culture</option>
                                    <option value="Wound Culture" {{ old('test_name') == 'Wound Culture' ? 'selected' : '' }}>Wound Culture</option>
                                    <option value="Rapid Strep Test" {{ old('test_name') == 'Rapid Strep Test' ? 'selected' : '' }}>Rapid Strep Test</option>
                                    <option value="COVID-19 PCR" {{ old('test_name') == 'COVID-19 PCR' ? 'selected' : '' }}>COVID-19 PCR</option>
                                    <option value="Influenza A/B" {{ old('test_name') == 'Influenza A/B' ? 'selected' : '' }}>Influenza A/B</option>
                                </optgroup>
                                <optgroup label="Other Tests">
                                    <option value="Arterial Blood Gas (ABG)" {{ old('test_name') == 'Arterial Blood Gas (ABG)' ? 'selected' : '' }}>Arterial Blood Gas (ABG)</option>
                                    <option value="Thyroid Stimulating Hormone (TSH)" {{ old('test_name') == 'Thyroid Stimulating Hormone (TSH)' ? 'selected' : '' }}>Thyroid Stimulating Hormone (TSH)</option>
                                    <option value="Hemoglobin A1c" {{ old('test_name') == 'Hemoglobin A1c' ? 'selected' : '' }}>Hemoglobin A1c</option>
                                    <option value="Toxicology Screen" {{ old('test_name') == 'Toxicology Screen' ? 'selected' : '' }}>Toxicology Screen</option>
                                    <option value="Lactate" {{ old('test_name') == 'Lactate' ? 'selected' : '' }}>Lactate</option>
                                    <option value="Other (specify in details)" {{ old('test_name') == 'Other (specify in details)' ? 'selected' : '' }}>Other (specify in details)</option>
                                </optgroup>
                            </select>
                            <x-input-error :messages="$errors->get('test_name')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="test_details" :value="__('Test Details (Optional)')" />
                            <textarea id="test_details" name="test_details" rows="2" 
                                class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('test_details') }}</textarea>
                            <x-input-error :messages="$errors->get('test_details')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="reason_for_test" :value="__('Reason for Test')" />
                            <textarea id="reason_for_test" name="reason_for_test" rows="2" 
                                class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>{{ old('reason_for_test') }}</textarea>
                            <x-input-error :messages="$errors->get('reason_for_test')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="notes" :value="__('Additional Notes')" />
                            <textarea id="notes" name="notes" rows="2" 
                                class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('notes') }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="scheduled_for" :value="__('Scheduled For (Optional)')" />
                            <x-text-input id="scheduled_for" class="block mt-1 w-full" type="datetime-local" name="scheduled_for" :value="old('scheduled_for')" />
                            <x-input-error :messages="$errors->get('scheduled_for')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="is_stat" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="1" {{ old('is_stat') ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600">{{ __('STAT (Urgent) Order') }}</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('doctor.visits.show', $visit) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 mr-3">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button>
                                {{ __('Order Lab Test') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
