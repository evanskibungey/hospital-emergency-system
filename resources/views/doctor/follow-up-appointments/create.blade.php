<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Schedule Follow-up Appointment') }}
            </h2>
            <div>
                @if (isset($patient))
                    <a href="{{ route('doctor.patients.summary', $patient->id) }}" class="px-4 py-2 bg-gray-200 rounded-md text-gray-700 hover:bg-gray-300">
                        {{ __('Back to Patient Summary') }}
                    </a>
                @elseif (isset($visit))
                    <a href="{{ route('doctor.visits.show', $visit) }}" class="px-4 py-2 bg-gray-200 rounded-md text-gray-700 hover:bg-gray-300">
                        {{ __('Back to Visit') }}
                    </a>
                @elseif (isset($discharge))
                    <a href="{{ route('doctor.discharges.show', $discharge) }}" class="px-4 py-2 bg-gray-200 rounded-md text-gray-700 hover:bg-gray-300">
                        {{ __('Back to Discharge') }}
                    </a>
                @else
                    <a href="{{ route('doctor.follow-up-appointments.index') }}" class="px-4 py-2 bg-gray-200 rounded-md text-gray-700 hover:bg-gray-300">
                        {{ __('Back to Appointments') }}
                    </a>
                @endif
            </div>
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
                                <p><span class="font-medium">Name:</span> {{ $patient->full_name }}</p>
                                <p><span class="font-medium">Age:</span> {{ $patient->date_of_birth->age }} years</p>
                                <p><span class="font-medium">MRN:</span> {{ $patient->medical_record_number }}</p>
                            </div>
                            <div>
                                <p><span class="font-medium">Gender:</span> {{ $patient->gender }}</p>
                                <p><span class="font-medium">Phone:</span> {{ $patient->phone_number }}</p>
                                @if ($visit)
                                    <p><span class="font-medium">Visit:</span> #{{ $visit->id }} - {{ $visit->chief_complaint }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('doctor.follow-up-appointments.store') }}">
                        @csrf

                        <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                        
                        @if (isset($visit))
                            <input type="hidden" name="visit_id" value="{{ $visit->id }}">
                        @endif
                        
                        @if (isset($discharge))
                            <input type="hidden" name="discharge_id" value="{{ $discharge->id }}">
                        @endif

                        <div class="mb-4">
                            <x-input-label for="doctor_id" :value="__('Select Provider (Optional)')" />
                            <select id="doctor_id" name="doctor_id" 
                                class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">-- Select a specific doctor --</option>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                        Dr. {{ $doctor->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('doctor_id')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="mb-4">
                                <x-input-label for="department" :value="__('Department (Optional)')" />
                                <select id="department" name="department" 
                                    class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">-- Select Department --</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department }}" {{ old('department') == $department ? 'selected' : '' }}>
                                            {{ $department }}
                                        </option>
                                    @endforeach
                                    <option value="Other">Other</option>
                                </select>
                                <x-input-error :messages="$errors->get('department')" class="mt-2" />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="specialty" :value="__('Specialty (Optional)')" />
                                <x-text-input id="specialty" class="block mt-1 w-full" type="text" name="specialty" :value="old('specialty')" />
                                <x-input-error :messages="$errors->get('specialty')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="reason_for_visit" :value="__('Reason for Visit')" />
                            <textarea id="reason_for_visit" name="reason_for_visit" rows="3" 
                                class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>{{ old('reason_for_visit') }}</textarea>
                            <x-input-error :messages="$errors->get('reason_for_visit')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="mb-4">
                                <x-input-label for="appointment_time" :value="__('Appointment Date & Time')" />
                                <x-text-input id="appointment_time" class="block mt-1 w-full" type="datetime-local" name="appointment_time" :value="old('appointment_time')" required />
                                <x-input-error :messages="$errors->get('appointment_time')" class="mt-2" />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="estimated_duration_minutes" :value="__('Estimated Duration (minutes)')" />
                                <select id="estimated_duration_minutes" name="estimated_duration_minutes" 
                                    class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    <option value="15" {{ old('estimated_duration_minutes') == '15' ? 'selected' : '' }}>15 minutes</option>
                                    <option value="30" {{ old('estimated_duration_minutes', '30') == '30' ? 'selected' : '' }}>30 minutes</option>
                                    <option value="45" {{ old('estimated_duration_minutes') == '45' ? 'selected' : '' }}>45 minutes</option>
                                    <option value="60" {{ old('estimated_duration_minutes') == '60' ? 'selected' : '' }}>60 minutes</option>
                                    <option value="90" {{ old('estimated_duration_minutes') == '90' ? 'selected' : '' }}>90 minutes</option>
                                    <option value="120" {{ old('estimated_duration_minutes') == '120' ? 'selected' : '' }}>120 minutes</option>
                                </select>
                                <x-input-error :messages="$errors->get('estimated_duration_minutes')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="special_instructions" :value="__('Special Instructions (Optional)')" />
                            <textarea id="special_instructions" name="special_instructions" rows="2" 
                                class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('special_instructions') }}</textarea>
                            <x-input-error :messages="$errors->get('special_instructions')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="notes" :value="__('Internal Notes (Optional)')" />
                            <textarea id="notes" name="notes" rows="2" 
                                class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('notes') }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="is_urgent" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="1" {{ old('is_urgent') ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600">{{ __('Mark as urgent follow-up') }}</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('doctor.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 mr-3">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button>
                                {{ __('Schedule Appointment') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
