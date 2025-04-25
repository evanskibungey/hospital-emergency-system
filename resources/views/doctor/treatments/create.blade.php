<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create Treatment Plan') }}
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

                    <form method="POST" action="{{ route('doctor.treatments.store', $visit) }}">
                        @csrf

                        <div class="mb-4">
                            <x-input-label for="diagnosis" :value="__('Diagnosis')" />
                            <textarea id="diagnosis" name="diagnosis" rows="3" 
                                class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                required>{{ old('diagnosis') }}</textarea>
                            <x-input-error :messages="$errors->get('diagnosis')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="treatment_plan" :value="__('Treatment Plan')" />
                            <textarea id="treatment_plan" name="treatment_plan" rows="5" 
                                class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                required>{{ old('treatment_plan') }}</textarea>
                            <x-input-error :messages="$errors->get('treatment_plan')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="notes" :value="__('Additional Notes')" />
                            <textarea id="notes" name="notes" rows="3" 
                                class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('notes') }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="status" :value="__('Treatment Status')" />
                            <select id="status" name="status" 
                                class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            <p class="text-xs text-gray-500 mt-1">* Active treatments will start immediately</p>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('doctor.visits.show', $visit) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 mr-3">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button>
                                {{ __('Create Treatment Plan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
