<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Medication') }}: {{ $medication->name }}
            </h2>
            <div>
                <a href="{{ route('nurse.medications.show', $medication->id) }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 mr-2">
                    {{ __('View Details') }}
                </a>
                <a href="{{ route('nurse.medications.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    {{ __('Back to Medications') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p>{{ session('success') }}</p>
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('nurse.medications.update', $medication->id) }}">
                        @csrf
                        @method('PUT')

                        <!-- Medication Name -->
                        <div class="mb-4">
                            <x-label for="name" :value="__('Medication Name')" />
                            <x-input id="name" type="text" name="name" :value="old('name', $medication->name)" required autofocus class="block mt-1 w-full" />
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <x-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="3"
                                class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 w-full">{{ old('description', $medication->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Dosage Form -->
                        <div class="mb-4">
                            <x-label for="dosage_form" :value="__('Dosage Form')" />
                            <select id="dosage_form" name="dosage_form" required
                                class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 w-full">
                                <option value="">Select a dosage form</option>
                                <option value="tablet" {{ old('dosage_form', $medication->dosage_form) == 'tablet' ? 'selected' : '' }}>Tablet</option>
                                <option value="capsule" {{ old('dosage_form', $medication->dosage_form) == 'capsule' ? 'selected' : '' }}>Capsule</option>
                                <option value="liquid" {{ old('dosage_form', $medication->dosage_form) == 'liquid' ? 'selected' : '' }}>Liquid</option>
                                <option value="injection" {{ old('dosage_form', $medication->dosage_form) == 'injection' ? 'selected' : '' }}>Injection</option>
                                <option value="topical" {{ old('dosage_form', $medication->dosage_form) == 'topical' ? 'selected' : '' }}>Topical</option>
                                <option value="inhaler" {{ old('dosage_form', $medication->dosage_form) == 'inhaler' ? 'selected' : '' }}>Inhaler</option>
                                <option value="patch" {{ old('dosage_form', $medication->dosage_form) == 'patch' ? 'selected' : '' }}>Patch</option>
                                <option value="suppository" {{ old('dosage_form', $medication->dosage_form) == 'suppository' ? 'selected' : '' }}>Suppository</option>
                                <option value="drops" {{ old('dosage_form', $medication->dosage_form) == 'drops' ? 'selected' : '' }}>Drops</option>
                                <option value="powder" {{ old('dosage_form', $medication->dosage_form) == 'powder' ? 'selected' : '' }}>Powder</option>
                                <option value="other" {{ old('dosage_form', $medication->dosage_form) == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('dosage_form')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Strength and Unit -->
                        <div class="mb-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-label for="strength" :value="__('Strength')" />
                                <x-input id="strength" type="text" name="strength" :value="old('strength', $medication->strength)" required class="block mt-1 w-full" />
                                @error('strength')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <x-label for="unit" :value="__('Unit (Optional)')" />
                                <x-input id="unit" type="text" name="unit" :value="old('unit', $medication->unit)" class="block mt-1 w-full" />
                                @error('unit')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Instructions -->
                        <div class="mb-4">
                            <x-label for="instructions" :value="__('Administration Instructions')" />
                            <textarea id="instructions" name="instructions" rows="3"
                                class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 w-full">{{ old('instructions', $medication->instructions) }}</textarea>
                            @error('instructions')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Controlled Substance -->
                        <div class="mb-6">
                            <div class="flex items-center">
                                <input id="is_controlled_substance" type="checkbox" name="is_controlled_substance" value="1" 
                                    {{ old('is_controlled_substance', $medication->is_controlled_substance) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <x-label for="is_controlled_substance" :value="__('This is a controlled substance')" class="ml-2" />
                            </div>
                            @error('is_controlled_substance')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-button class="ml-3 bg-indigo-600 hover:bg-indigo-700">
                                {{ __('Update Medication') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>