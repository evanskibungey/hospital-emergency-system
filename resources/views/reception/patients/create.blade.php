<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Register New Patient') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('reception.patients.store') }}">
                        @csrf

                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Personal Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
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

                                <!-- Date of Birth -->
                                <div>
                                    <x-input-label for="date_of_birth" :value="__('Date of Birth')" />
                                    <x-text-input id="date_of_birth" class="block mt-1 w-full" type="date" name="date_of_birth" :value="old('date_of_birth')" required />
                                    <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4">
                                <!-- Gender -->
                                <div>
                                    <x-input-label for="gender" :value="__('Gender')" />
                                    <select id="gender" name="gender" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                                </div>

                                <!-- Phone Number -->
                                <div>
                                    <x-input-label for="phone_number" :value="__('Phone Number')" />
                                    <x-text-input id="phone_number" class="block mt-1 w-full" type="text" name="phone_number" :value="old('phone_number')" required />
                                    <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                                </div>

                                <!-- Email -->
                                <div>
                                    <x-input-label for="email" :value="__('Email')" />
                                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Address Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Address -->
                                <div>
                                    <x-input-label for="address" :value="__('Address')" />
                                    <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address')" />
                                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                                </div>

                                <!-- City -->
                                <div>
                                    <x-input-label for="city" :value="__('City')" />
                                    <x-text-input id="city" class="block mt-1 w-full" type="text" name="city" :value="old('city')" />
                                    <x-input-error :messages="$errors->get('city')" class="mt-2" />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                                <!-- State -->
                                <div>
                                    <x-input-label for="state" :value="__('State')" />
                                    <x-text-input id="state" class="block mt-1 w-full" type="text" name="state" :value="old('state')" />
                                    <x-input-error :messages="$errors->get('state')" class="mt-2" />
                                </div>

                                <!-- Postal Code -->
                                <div>
                                    <x-input-label for="postal_code" :value="__('Postal Code')" />
                                    <x-text-input id="postal_code" class="block mt-1 w-full" type="text" name="postal_code" :value="old('postal_code')" />
                                    <x-input-error :messages="$errors->get('postal_code')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Emergency Contact</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Emergency Contact Name -->
                                <div>
                                    <x-input-label for="emergency_contact_name" :value="__('Contact Name')" />
                                    <x-text-input id="emergency_contact_name" class="block mt-1 w-full" type="text" name="emergency_contact_name" :value="old('emergency_contact_name')" />
                                    <x-input-error :messages="$errors->get('emergency_contact_name')" class="mt-2" />
                                </div>

                                <!-- Emergency Contact Phone -->
                                <div>
                                    <x-input-label for="emergency_contact_phone" :value="__('Contact Phone')" />
                                    <x-text-input id="emergency_contact_phone" class="block mt-1 w-full" type="text" name="emergency_contact_phone" :value="old('emergency_contact_phone')" />
                                    <x-input-error :messages="$errors->get('emergency_contact_phone')" class="mt-2" />
                                </div>

                                <!-- Emergency Contact Relationship -->
                                <div>
                                    <x-input-label for="emergency_contact_relationship" :value="__('Relationship')" />
                                    <x-text-input id="emergency_contact_relationship" class="block mt-1 w-full" type="text" name="emergency_contact_relationship" :value="old('emergency_contact_relationship')" />
                                    <x-input-error :messages="$errors->get('emergency_contact_relationship')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Insurance Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Insurance Provider -->
                                <div>
                                    <x-input-label for="insurance_provider" :value="__('Insurance Provider')" />
                                    <x-text-input id="insurance_provider" class="block mt-1 w-full" type="text" name="insurance_provider" :value="old('insurance_provider')" />
                                    <x-input-error :messages="$errors->get('insurance_provider')" class="mt-2" />
                                </div>

                                <!-- Insurance Policy Number -->
                                <div>
                                    <x-input-label for="insurance_policy_number" :value="__('Policy Number')" />
                                    <x-text-input id="insurance_policy_number" class="block mt-1 w-full" type="text" name="insurance_policy_number" :value="old('insurance_policy_number')" />
                                    <x-input-error :messages="$errors->get('insurance_policy_number')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Medical Information</h3>
                            <div class="grid grid-cols-1 gap-6">
                                <!-- Medical History -->
                                <div>
                                    <x-input-label for="medical_history" :value="__('Medical History')" />
                                    <textarea id="medical_history" name="medical_history" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('medical_history') }}</textarea>
                                    <x-input-error :messages="$errors->get('medical_history')" class="mt-2" />
                                </div>

                                <!-- Allergies -->
                                <div>
                                    <x-input-label for="allergies" :value="__('Allergies')" />
                                    <textarea id="allergies" name="allergies" rows="2" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('allergies') }}</textarea>
                                    <x-input-error :messages="$errors->get('allergies')" class="mt-2" />
                                </div>

                                <!-- Current Medications -->
                                <div>
                                    <x-input-label for="current_medications" :value="__('Current Medications')" />
                                    <textarea id="current_medications" name="current_medications" rows="2" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('current_medications') }}</textarea>
                                    <x-input-error :messages="$errors->get('current_medications')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('reception.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-3">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button>
                                {{ __('Register Patient') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>