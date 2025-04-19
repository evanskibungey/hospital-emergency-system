<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Register New Visitor') }}
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

                    <form method="POST" action="{{ route('reception.visitors.store', $patient) }}">
                        @csrf

                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Visitor Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- First Name -->
                                <div>
                                    <x-input-label for="first_name" :value="__('First Name')" />
                                    <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" required autofocus />
                                    <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                                </div>

                                <!-- Last Name -->
                                <div>
                                    <x-input-label for="last_name" :value="__('Last Name')" />
                                    <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" required />
                                    <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                                <!-- Phone Number -->
                                <div>
                                    <x-input-label for="phone_number" :value="__('Phone Number')" />
                                    <x-text-input id="phone_number" class="block mt-1 w-full" type="text" name="phone_number" :value="old('phone_number')" required />
                                    <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                                </div>

                                <!-- Relationship to Patient -->
                                <div>
                                    <x-input-label for="relationship_to_patient" :value="__('Relationship to Patient')" />
                                    <select id="relationship_to_patient" name="relationship_to_patient" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                        <option value="">Select Relationship</option>
                                        <option value="Spouse" {{ old('relationship_to_patient') == 'Spouse' ? 'selected' : '' }}>Spouse</option>
                                        <option value="Parent" {{ old('relationship_to_patient') == 'Parent' ? 'selected' : '' }}>Parent</option>
                                        <option value="Child" {{ old('relationship_to_patient') == 'Child' ? 'selected' : '' }}>Child</option>
                                        <option value="Sibling" {{ old('relationship_to_patient') == 'Sibling' ? 'selected' : '' }}>Sibling</option>
                                        <option value="Friend" {{ old('relationship_to_patient') == 'Friend' ? 'selected' : '' }}>Friend</option>
                                        <option value="Other" {{ old('relationship_to_patient') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('relationship_to_patient')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Identification</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- ID Type -->
                                <div>
                                    <x-input-label for="id_type" :value="__('ID Type')" />
                                    <select id="id_type" name="id_type" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="">Select ID Type</option>
                                        <option value="Driver's License" {{ old('id_type') == "Driver's License" ? 'selected' : '' }}>Driver's License</option>
                                        <option value="Passport" {{ old('id_type') == 'Passport' ? 'selected' : '' }}>Passport</option>
                                        <option value="State ID" {{ old('id_type') == 'State ID' ? 'selected' : '' }}>State ID</option>
                                        <option value="Other" {{ old('id_type') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('id_type')" class="mt-2" />
                                </div>

                                <!-- ID Number -->
                                <div>
                                    <x-input-label for="id_number" :value="__('ID Number')" />
                                    <x-text-input id="id_number" class="block mt-1 w-full" type="text" name="id_number" :value="old('id_number')" />
                                    <x-input-error :messages="$errors->get('id_number')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="bg-yellow-50 p-4 rounded-md mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">Visitor Guidelines</h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <ul class="list-disc pl-5 space-y-1">
                                            <li>Visitors must wear visitor badges at all times</li>
                                            <li>Maximum 2 visitors per patient at a time</li>
                                            <li>Please respect visiting hours (8:00 AM - 8:00 PM)</li>
                                            <li>Visitors must check out when leaving</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('reception.patients.show', $patient) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-3">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button>
                                {{ __('Register Visitor') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>