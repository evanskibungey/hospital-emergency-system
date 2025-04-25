<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Register New Patient') }}
            </h2>
            <a href="{{ route('reception.dashboard') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 rounded-md text-white text-sm font-medium transition-colors flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Form progress indicator -->
            <div class="mb-6 bg-white rounded-lg shadow-sm p-4">
                <div class="flex items-center justify-between">
                    <div class="w-full">
                        <div class="flex items-center">
                            <div class="relative z-10 flex items-center justify-center w-8 h-8 bg-blue-600 rounded-full text-white font-semibold text-sm">
                                1
                            </div>
                            <div class="flex-1 ml-4 font-medium text-blue-600">Personal Information</div>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="flex items-center">
                            <div class="relative z-10 flex items-center justify-center w-8 h-8 bg-gray-200 rounded-full text-gray-600 font-semibold text-sm">
                                2
                            </div>
                            <div class="flex-1 ml-4 font-medium text-gray-500">Medical Details</div>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="flex items-center">
                            <div class="relative z-10 flex items-center justify-center w-8 h-8 bg-gray-200 rounded-full text-gray-600 font-semibold text-sm">
                                3
                            </div>
                            <div class="flex-1 ml-4 font-medium text-gray-500">Emergency Contact</div>
                        </div>
                    </div>
                </div>
                <div class="mt-3 h-2 bg-gray-200 rounded-full">
                    <div class="h-2 bg-blue-600 rounded-full" style="width: 33%"></div>
                </div>
            </div>

            <!-- Success message -->
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm flex items-center" role="alert">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('reception.patients.store') }}">
                @csrf

                <!-- Personal Information Section -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                    <div class="border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50 p-4">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            {{ __('Personal Information') }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">Enter the patient's basic personal details</p>
                    </div>
                    <div class="p-6 bg-white">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- First Name -->
                            <div>
                                <x-input-label for="first_name" :value="__('First Name')" class="font-medium text-gray-700" />
                                <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" required autofocus placeholder="John" />
                                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                            </div>

                            <!-- Last Name -->
                            <div>
                                <x-input-label for="last_name" :value="__('Last Name')" class="font-medium text-gray-700" />
                                <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" required placeholder="Doe" />
                                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                            </div>

                            <!-- Date of Birth -->
                            <div>
                                <x-input-label for="date_of_birth" :value="__('Date of Birth')" class="font-medium text-gray-700" />
                                <x-text-input id="date_of_birth" class="block mt-1 w-full" type="date" name="date_of_birth" :value="old('date_of_birth')" required />
                                <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                            <!-- Gender -->
                            <div>
                                <x-input-label for="gender" :value="__('Gender')" class="font-medium text-gray-700" />
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
                                <x-input-label for="phone_number" :value="__('Phone Number')" class="font-medium text-gray-700" />
                                <div class="relative mt-1">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                    </div>
                                    <x-text-input id="phone_number" class="block mt-1 w-full pl-10" type="text" name="phone_number" :value="old('phone_number')" required placeholder="555-123-4567" />
                                </div>
                                <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                            </div>

                            <!-- Email -->
                            <div>
                                <x-input-label for="email" :value="__('Email')" class="font-medium text-gray-700" />
                                <div class="relative mt-1">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <x-text-input id="email" class="block mt-1 w-full pl-10" type="email" name="email" :value="old('email')" placeholder="johndoe@example.com" />
                                </div>
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Address Information Section -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                    <div class="border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-purple-50 p-4">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ __('Address Information') }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">Enter the patient's residential address</p>
                    </div>
                    <div class="p-6 bg-white">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Address -->
                            <div>
                                <x-input-label for="address" :value="__('Address')" class="font-medium text-gray-700" />
                                <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address')" placeholder="123 Main St, Apt 4B" />
                                <x-input-error :messages="$errors->get('address')" class="mt-2" />
                            </div>

                            <!-- City -->
                            <div>
                                <x-input-label for="city" :value="__('City')" class="font-medium text-gray-700" />
                                <x-text-input id="city" class="block mt-1 w-full" type="text" name="city" :value="old('city')" placeholder="Springfield" />
                                <x-input-error :messages="$errors->get('city')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <!-- State -->
                            <div>
                                <x-input-label for="state" :value="__('State')" class="font-medium text-gray-700" />
                                <x-text-input id="state" class="block mt-1 w-full" type="text" name="state" :value="old('state')" placeholder="California" />
                                <x-input-error :messages="$errors->get('state')" class="mt-2" />
                            </div>

                            <!-- Postal Code -->
                            <div>
                                <x-input-label for="postal_code" :value="__('Postal Code')" class="font-medium text-gray-700" />
                                <x-text-input id="postal_code" class="block mt-1 w-full" type="text" name="postal_code" :value="old('postal_code')" placeholder="90210" />
                                <x-input-error :messages="$errors->get('postal_code')" class="mt-2" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Emergency Contact Section -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                    <div class="border-b border-gray-200 bg-gradient-to-r from-red-50 to-pink-50 p-4">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            {{ __('Emergency Contact') }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">Provide details of who to contact in case of emergency</p>
                    </div>
                    <div class="p-6 bg-white">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Emergency Contact Name -->
                            <div>
                                <x-input-label for="emergency_contact_name" :value="__('Contact Name')" class="font-medium text-gray-700" />
                                <x-text-input id="emergency_contact_name" class="block mt-1 w-full" type="text" name="emergency_contact_name" :value="old('emergency_contact_name')" placeholder="Jane Doe" />
                                <x-input-error :messages="$errors->get('emergency_contact_name')" class="mt-2" />
                            </div>

                            <!-- Emergency Contact Phone -->
                            <div>
                                <x-input-label for="emergency_contact_phone" :value="__('Contact Phone')" class="font-medium text-gray-700" />
                                <div class="relative mt-1">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                    </div>
                                    <x-text-input id="emergency_contact_phone" class="block mt-1 w-full pl-10" type="text" name="emergency_contact_phone" :value="old('emergency_contact_phone')" placeholder="555-987-6543" />
                                </div>
                                <x-input-error :messages="$errors->get('emergency_contact_phone')" class="mt-2" />
                            </div>

                            <!-- Emergency Contact Relationship -->
                            <div>
                                <x-input-label for="emergency_contact_relationship" :value="__('Relationship')" class="font-medium text-gray-700" />
                                <x-text-input id="emergency_contact_relationship" class="block mt-1 w-full" type="text" name="emergency_contact_relationship" :value="old('emergency_contact_relationship')" placeholder="Spouse, Parent, etc." />
                                <x-input-error :messages="$errors->get('emergency_contact_relationship')" class="mt-2" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Insurance Information Section -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                    <div class="border-b border-gray-200 bg-gradient-to-r from-green-50 to-teal-50 p-4">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            {{ __('Insurance Information') }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">Enter the patient's health insurance details</p>
                    </div>
                    <div class="p-6 bg-white">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Insurance Provider -->
                            <div>
                                <x-input-label for="insurance_provider" :value="__('Insurance Provider')" class="font-medium text-gray-700" />
                                <x-text-input id="insurance_provider" class="block mt-1 w-full" type="text" name="insurance_provider" :value="old('insurance_provider')" placeholder="Blue Cross, Aetna, etc." />
                                <x-input-error :messages="$errors->get('insurance_provider')" class="mt-2" />
                            </div>

                            <!-- Insurance Policy Number -->
                            <div>
                                <x-input-label for="insurance_policy_number" :value="__('Policy Number')" class="font-medium text-gray-700" />
                                <x-text-input id="insurance_policy_number" class="block mt-1 w-full" type="text" name="insurance_policy_number" :value="old('insurance_policy_number')" placeholder="ABC12345678" />
                                <x-input-error :messages="$errors->get('insurance_policy_number')" class="mt-2" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Medical Information Section -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                    <div class="border-b border-gray-200 bg-gradient-to-r from-blue-50 to-cyan-50 p-4">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            {{ __('Medical Information') }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">Provide relevant medical history and details</p>
                    </div>
                    <div class="p-6 bg-white">
                        <div class="grid grid-cols-1 gap-6">
                            <!-- Medical History -->
                            <div>
                                <x-input-label for="medical_history" :value="__('Medical History')" class="font-medium text-gray-700" />
                                <div class="mt-1">
                                    <textarea id="medical_history" name="medical_history" rows="3" class="block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Include any significant past medical conditions, surgeries, or hospitalizations">{{ old('medical_history') }}</textarea>
                                </div>
                                <x-input-error :messages="$errors->get('medical_history')" class="mt-2" />
                            </div>

                            <!-- Allergies -->
                            <div>
                                <x-input-label for="allergies" :value="__('Allergies')" class="font-medium text-gray-700" />
                                <div class="mt-1">
                                    <textarea id="allergies" name="allergies" rows="2" class="block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="List any known allergies to medications, foods, or other substances">{{ old('allergies') }}</textarea>
                                </div>
                                <x-input-error :messages="$errors->get('allergies')" class="mt-2" />
                            </div>

                            <!-- Current Medications -->
                            <div>
                                <x-input-label for="current_medications" :value="__('Current Medications')" class="font-medium text-gray-700" />
                                <div class="mt-1">
                                    <textarea id="current_medications" name="current_medications" rows="2" class="block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="List all medications, dosages, and frequency the patient is currently taking">{{ old('current_medications') }}</textarea>
                                </div>
                                <x-input-error :messages="$errors->get('current_medications')" class="mt-2" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-between mt-8">
                    <a href="{{ route('reception.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        {{ __('Cancel') }}
                    </a>
                    <div class="flex space-x-2">
                        <button type="reset" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 active:bg-yellow-700 focus:outline-none focus:border-yellow-700 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            {{ __('Reset Form') }}
                        </button>
                        <button type="submit" class="inline-flex items-center px-6 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ __('Register Patient') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>