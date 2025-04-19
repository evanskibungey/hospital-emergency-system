<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Vital Signs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">
                            Patient: {{ $visit->patient->first_name }} {{ $visit->patient->last_name }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            MRN: {{ $visit->patient->medical_record_number }} | Visit ID: {{ $visit->id }}
                        </p>
                        <p class="mt-1 text-sm text-gray-500">
                            Recorded: {{ $vitalSign->created_at->format('M d, Y H:i') }} by {{ $vitalSign->user->name }}
                        </p>
                    </div>

                    <form method="POST" action="{{ route('nurse.vital-signs.update', [$visit->id, $vitalSign->id]) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Temperature -->
                            <div>
                                <x-input-label for="temperature" :value="__('Temperature (°C)')" />
                                <x-text-input id="temperature" class="block mt-1 w-full" type="number" name="temperature" step="0.1" :value="old('temperature', $vitalSign->temperature)" />
                                <x-input-error :messages="$errors->get('temperature')" class="mt-2" />
                            </div>

                            <!-- Heart Rate -->
                            <div>
                                <x-input-label for="heart_rate" :value="__('Heart Rate (BPM)')" />
                                <x-text-input id="heart_rate" class="block mt-1 w-full" type="number" name="heart_rate" :value="old('heart_rate', $vitalSign->heart_rate)" />
                                <x-input-error :messages="$errors->get('heart_rate')" class="mt-2" />
                            </div>

                            <!-- Respiratory Rate -->
                            <div>
                                <x-input-label for="respiratory_rate" :value="__('Respiratory Rate (breaths/min)')" />
                                <x-text-input id="respiratory_rate" class="block mt-1 w-full" type="number" name="respiratory_rate" :value="old('respiratory_rate', $vitalSign->respiratory_rate)" />
                                <x-input-error :messages="$errors->get('respiratory_rate')" class="mt-2" />
                            </div>

                            <!-- Blood Pressure -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="systolic_bp" :value="__('Systolic BP (mmHg)')" />
                                    <x-text-input id="systolic_bp" class="block mt-1 w-full" type="number" name="systolic_bp" :value="old('systolic_bp', $vitalSign->systolic_bp)" />
                                    <x-input-error :messages="$errors->get('systolic_bp')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="diastolic_bp" :value="__('Diastolic BP (mmHg)')" />
                                    <x-text-input id="diastolic_bp" class="block mt-1 w-full" type="number" name="diastolic_bp" :value="old('diastolic_bp', $vitalSign->diastolic_bp)" />
                                    <x-input-error :messages="$errors->get('diastolic_bp')" class="mt-2" />
                                </div>
                            </div>

                            <!-- Oxygen Saturation -->
                            <div>
                                <x-input-label for="oxygen_saturation" :value="__('Oxygen Saturation (%)')" />
                                <x-text-input id="oxygen_saturation" class="block mt-1 w-full" type="number" name="oxygen_saturation" :value="old('oxygen_saturation', $vitalSign->oxygen_saturation)" />
                                <x-input-error :messages="$errors->get('oxygen_saturation')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mt-6">
                            <x-input-label for="notes" :value="__('Notes')" />
                            <textarea id="notes" name="notes" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('notes', $vitalSign->notes) }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('nurse.vital-signs.show', [$visit->id, $vitalSign->id]) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-3">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button>
                                {{ __('Update Vital Signs') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>