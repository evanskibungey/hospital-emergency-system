<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Register New Visit') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6 p-4 bg-gray-50 rounded-md">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Patient Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Name</p>
                                <p class="font-medium">{{ $patient->full_name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Date of Birth</p>
                                <p class="font-medium">{{ \Carbon\Carbon::parse($patient->date_of_birth)->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Phone</p>
                                <p class="font-medium">{{ $patient->phone_number }}</p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('reception.visits.store', $patient) }}">
                        @csrf

                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Visit Information</h3>
                            
                            <!-- Chief Complaint -->
                            <div class="mb-4">
                                <x-input-label for="chief_complaint" :value="__('Chief Complaint')" />
                                <x-text-input id="chief_complaint" class="block mt-1 w-full" type="text" name="chief_complaint" :value="old('chief_complaint')" required autofocus />
                                <x-input-error :messages="$errors->get('chief_complaint')" class="mt-2" />
                            </div>

                            <!-- Priority -->
                            <div class="mb-4">
                                <x-input-label for="priority" :value="__('Priority')" />
                                <select id="priority" name="priority" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }} selected>Medium</option>
                                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                    <option value="critical" {{ old('priority') == 'critical' ? 'selected' : '' }}>Critical</option>
                                </select>
                                <x-input-error :messages="$errors->get('priority')" class="mt-2" />
                                
                                <div class="mt-2 text-sm text-gray-600">
                                    <p><strong>Low:</strong> Routine care, non-urgent (est. wait time: 60 min)</p>
                                    <p><strong>Medium:</strong> Standard care, minor urgency (est. wait time: 30 min)</p>
                                    <p><strong>High:</strong> Urgent care, potentially serious (est. wait time: 15 min)</p>
                                    <p><strong>Critical:</strong> Immediate care needed, life-threatening (est. wait time: immediate)</p>
                                </div>
                            </div>

                            <!-- Department -->
                            <div class="mb-4">
                                <x-input-label for="department" :value="__('Department (if known)')" />
                                <select id="department" name="department" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Select Department</option>
                                    <option value="General Medicine" {{ old('department') == 'General Medicine' ? 'selected' : '' }}>General Medicine</option>
                                    <option value="Cardiology" {{ old('department') == 'Cardiology' ? 'selected' : '' }}>Cardiology</option>
                                    <option value="Pediatrics" {{ old('department') == 'Pediatrics' ? 'selected' : '' }}>Pediatrics</option>
                                    <option value="Orthopedics" {{ old('department') == 'Orthopedics' ? 'selected' : '' }}>Orthopedics</option>
                                    <option value="Neurology" {{ old('department') == 'Neurology' ? 'selected' : '' }}>Neurology</option>
                                    <option value="Obstetrics" {{ old('department') == 'Obstetrics' ? 'selected' : '' }}>Obstetrics</option>
                                    <option value="Psychiatry" {{ old('department') == 'Psychiatry' ? 'selected' : '' }}>Psychiatry</option>
                                </select>
                                <x-input-error :messages="$errors->get('department')" class="mt-2" />
                            </div>

                            <!-- Initial Assessment -->
                            <div class="mb-4">
                                <x-input-label for="initial_assessment" :value="__('Initial Assessment')" />
                                <textarea id="initial_assessment" name="initial_assessment" rows="4" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('initial_assessment') }}</textarea>
                                <x-input-error :messages="$errors->get('initial_assessment')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('reception.patients.show', $patient) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-3">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button>
                                {{ __('Register Visit') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>