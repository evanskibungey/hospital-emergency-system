<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Medication') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('nurse.medications.store') }}">
                        @csrf

                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Basic Information</h3>
                            <div class="grid grid-cols-1 gap-6">
                                <!-- Medication Name -->
                                <div>
                                    <x-input-label for="name" :value="__('Medication Name')" />
                                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>
                                
                                <!-- Description -->
                                <div>
                                    <x-input-label for="description" :value="__('Description')" />
                                    <textarea id="description" name="description" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('description') }}</textarea>
                                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Dosage Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Dosage Form -->
                                <div>
                                    <x-input-label for="dosage_form" :value="__('Dosage Form')" />
                                    <select id="dosage_form" name="dosage_form" required class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="">Select a dosage form</option>
                                        <option value="tablet" {{ old('dosage_form') == 'tablet' ? 'selected' : '' }}>Tablet</option>
                                        <option value="capsule" {{ old('dosage_form') == 'capsule' ? 'selected' : '' }}>Capsule</option>
                                        <option value="liquid" {{ old('dosage_form') == 'liquid' ? 'selected' : '' }}>Liquid</option>
                                        <option value="injection" {{ old('dosage_form') == 'injection' ? 'selected' : '' }}>Injection</option>
                                        <option value="topical" {{ old('dosage_form') == 'topical' ? 'selected' : '' }}>Topical</option>
                                        <option value="inhaler" {{ old('dosage_form') == 'inhaler' ? 'selected' : '' }}>Inhaler</option>
                                        <option value="patch" {{ old('dosage_form') == 'patch' ? 'selected' : '' }}>Patch</option>
                                        <option value="suppository" {{ old('dosage_form') == 'suppository' ? 'selected' : '' }}>Suppository</option>
                                        <option value="drops" {{ old('dosage_form') == 'drops' ? 'selected' : '' }}>Drops</option>
                                        <option value="powder" {{ old('dosage_form') == 'powder' ? 'selected' : '' }}>Powder</option>
                                        <option value="other" {{ old('dosage_form') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('dosage_form')" class="mt-2" />
                                </div>

                                <!-- Strength -->
                                <div>
                                    <x-input-label for="strength" :value="__('Strength')" />
                                    <x-text-input id="strength" class="block mt-1 w-full" type="text" name="strength" :value="old('strength')" required placeholder="e.g., 500, 10, 25" />
                                    <x-input-error :messages="$errors->get('strength')" class="mt-2" />
                                </div>

                                <!-- Unit -->
                                <div>
                                    <x-input-label for="unit" :value="__('Unit (Optional)')" />
                                    <x-text-input id="unit" class="block mt-1 w-full" type="text" name="unit" :value="old('unit')" placeholder="e.g., mg, ml, g, mcg" />
                                    <x-input-error :messages="$errors->get('unit')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Administration Details</h3>
                            <div class="grid grid-cols-1 gap-6">
                                <!-- Instructions -->
                                <div>
                                    <x-input-label for="instructions" :value="__('Administration Instructions')" />
                                    <textarea id="instructions" name="instructions" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('instructions') }}</textarea>
                                    <p class="text-sm text-gray-500 mt-1">Include any specific instructions for administering this medication.</p>
                                    <x-input-error :messages="$errors->get('instructions')" class="mt-2" />
                                </div>

                                <!-- Controlled Substance -->
                                <div>
                                    <div class="flex items-center mt-2">
                                        <input id="is_controlled_substance" type="checkbox" name="is_controlled_substance" value="1" {{ old('is_controlled_substance') ? 'checked' : '' }}
                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <x-input-label for="is_controlled_substance" :value="__('This is a controlled substance')" class="ml-2" />
                                    </div>
                                    <p class="text-sm text-gray-500 mt-1">Check this box if this medication is a controlled substance requiring additional documentation.</p>
                                    <x-input-error :messages="$errors->get('is_controlled_substance')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('nurse.medications.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-3">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button>
                                {{ __('Create Medication') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>