<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Order Imaging') }}
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

                    <form method="POST" action="{{ route('doctor.imaging-orders.store', $visit) }}">
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
                            <x-input-label for="imaging_type" :value="__('Imaging Type')" />
                            <select id="imaging_type" name="imaging_type" 
                                class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                <option value="">-- Select Imaging Type --</option>
                                <option value="X-Ray" {{ old('imaging_type') == 'X-Ray' ? 'selected' : '' }}>X-Ray</option>
                                <option value="CT Scan" {{ old('imaging_type') == 'CT Scan' ? 'selected' : '' }}>CT Scan</option>
                                <option value="MRI" {{ old('imaging_type') == 'MRI' ? 'selected' : '' }}>MRI</option>
                                <option value="Ultrasound" {{ old('imaging_type') == 'Ultrasound' ? 'selected' : '' }}>Ultrasound</option>
                                <option value="Echocardiogram" {{ old('imaging_type') == 'Echocardiogram' ? 'selected' : '' }}>Echocardiogram</option>
                                <option value="Nuclear Medicine" {{ old('imaging_type') == 'Nuclear Medicine' ? 'selected' : '' }}>Nuclear Medicine</option>
                                <option value="PET Scan" {{ old('imaging_type') == 'PET Scan' ? 'selected' : '' }}>PET Scan</option>
                                <option value="Mammogram" {{ old('imaging_type') == 'Mammogram' ? 'selected' : '' }}>Mammogram</option>
                                <option value="Fluoroscopy" {{ old('imaging_type') == 'Fluoroscopy' ? 'selected' : '' }}>Fluoroscopy</option>
                                <option value="Angiography" {{ old('imaging_type') == 'Angiography' ? 'selected' : '' }}>Angiography</option>
                                <option value="Other" {{ old('imaging_type') == 'Other' ? 'selected' : '' }}>Other (specify in details)</option>
                            </select>
                            <x-input-error :messages="$errors->get('imaging_type')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="body_part" :value="__('Body Part')" />
                            <select id="body_part" name="body_part" 
                                class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                <option value="">-- Select Body Part --</option>
                                <optgroup label="Head & Neck">
                                    <option value="Head" {{ old('body_part') == 'Head' ? 'selected' : '' }}>Head</option>
                                    <option value="Brain" {{ old('body_part') == 'Brain' ? 'selected' : '' }}>Brain</option>
                                    <option value="Face" {{ old('body_part') == 'Face' ? 'selected' : '' }}>Face</option>
                                    <option value="Sinuses" {{ old('body_part') == 'Sinuses' ? 'selected' : '' }}>Sinuses</option>
                                    <option value="Neck" {{ old('body_part') == 'Neck' ? 'selected' : '' }}>Neck</option>
                                    <option value="Thyroid" {{ old('body_part') == 'Thyroid' ? 'selected' : '' }}>Thyroid</option>
                                </optgroup>
                                <optgroup label="Chest & Thorax">
                                    <option value="Chest" {{ old('body_part') == 'Chest' ? 'selected' : '' }}>Chest</option>
                                    <option value="Lungs" {{ old('body_part') == 'Lungs' ? 'selected' : '' }}>Lungs</option>
                                    <option value="Heart" {{ old('body_part') == 'Heart' ? 'selected' : '' }}>Heart</option>
                                    <option value="Ribs" {{ old('body_part') == 'Ribs' ? 'selected' : '' }}>Ribs</option>
                                </optgroup>
                                <optgroup label="Abdomen & Pelvis">
                                    <option value="Abdomen" {{ old('body_part') == 'Abdomen' ? 'selected' : '' }}>Abdomen</option>
                                    <option value="Liver" {{ old('body_part') == 'Liver' ? 'selected' : '' }}>Liver</option>
                                    <option value="Gallbladder" {{ old('body_part') == 'Gallbladder' ? 'selected' : '' }}>Gallbladder</option>
                                    <option value="Pancreas" {{ old('body_part') == 'Pancreas' ? 'selected' : '' }}>Pancreas</option>
                                    <option value="Spleen" {{ old('body_part') == 'Spleen' ? 'selected' : '' }}>Spleen</option>
                                    <option value="Kidneys" {{ old('body_part') == 'Kidneys' ? 'selected' : '' }}>Kidneys</option>
                                    <option value="Pelvis" {{ old('body_part') == 'Pelvis' ? 'selected' : '' }}>Pelvis</option>
                                </optgroup>
                                <optgroup label="Spine">
                                    <option value="Cervical Spine" {{ old('body_part') == 'Cervical Spine' ? 'selected' : '' }}>Cervical Spine</option>
                                    <option value="Thoracic Spine" {{ old('body_part') == 'Thoracic Spine' ? 'selected' : '' }}>Thoracic Spine</option>
                                    <option value="Lumbar Spine" {{ old('body_part') == 'Lumbar Spine' ? 'selected' : '' }}>Lumbar Spine</option>
                                    <option value="Sacrum/Coccyx" {{ old('body_part') == 'Sacrum/Coccyx' ? 'selected' : '' }}>Sacrum/Coccyx</option>
                                </optgroup>
                                <optgroup label="Upper Extremities">
                                    <option value="Shoulder" {{ old('body_part') == 'Shoulder' ? 'selected' : '' }}>Shoulder</option>
                                    <option value="Humerus" {{ old('body_part') == 'Humerus' ? 'selected' : '' }}>Humerus</option>
                                    <option value="Elbow" {{ old('body_part') == 'Elbow' ? 'selected' : '' }}>Elbow</option>
                                    <option value="Forearm" {{ old('body_part') == 'Forearm' ? 'selected' : '' }}>Forearm</option>
                                    <option value="Wrist" {{ old('body_part') == 'Wrist' ? 'selected' : '' }}>Wrist</option>
                                    <option value="Hand" {{ old('body_part') == 'Hand' ? 'selected' : '' }}>Hand</option>
                                    <option value="Fingers" {{ old('body_part') == 'Fingers' ? 'selected' : '' }}>Fingers</option>
                                </optgroup>
                                <optgroup label="Lower Extremities">
                                    <option value="Hip" {{ old('body_part') == 'Hip' ? 'selected' : '' }}>Hip</option>
                                    <option value="Femur" {{ old('body_part') == 'Femur' ? 'selected' : '' }}>Femur</option>
                                    <option value="Knee" {{ old('body_part') == 'Knee' ? 'selected' : '' }}>Knee</option>
                                    <option value="Tibia/Fibula" {{ old('body_part') == 'Tibia/Fibula' ? 'selected' : '' }}>Tibia/Fibula</option>
                                    <option value="Ankle" {{ old('body_part') == 'Ankle' ? 'selected' : '' }}>Ankle</option>
                                    <option value="Foot" {{ old('body_part') == 'Foot' ? 'selected' : '' }}>Foot</option>
                                    <option value="Toes" {{ old('body_part') == 'Toes' ? 'selected' : '' }}>Toes</option>
                                </optgroup>
                                <option value="Full Body" {{ old('body_part') == 'Full Body' ? 'selected' : '' }}>Full Body</option>
                                <option value="Other" {{ old('body_part') == 'Other' ? 'selected' : '' }}>Other (specify in details)</option>
                            </select>
                            <x-input-error :messages="$errors->get('body_part')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="clinical_information" :value="__('Clinical Information')" />
                            <textarea id="clinical_information" name="clinical_information" rows="2" 
                                class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('clinical_information') }}</textarea>
                            <x-input-error :messages="$errors->get('clinical_information')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="reason_for_exam" :value="__('Reason for Exam')" />
                            <textarea id="reason_for_exam" name="reason_for_exam" rows="2" 
                                class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>{{ old('reason_for_exam') }}</textarea>
                            <x-input-error :messages="$errors->get('reason_for_exam')" class="mt-2" />
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

                        <div class="mb-4">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="requires_contrast" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="1" {{ old('requires_contrast') ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600">{{ __('Requires Contrast') }}</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('doctor.visits.show', $visit) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 mr-3">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button>
                                {{ __('Order Imaging') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
