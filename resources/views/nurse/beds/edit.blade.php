<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Bed') }}: {{ $bed->location }} - {{ $bed->bed_number }}
            </h2>
            <a href="{{ route('nurse.beds.show', $bed->id) }}"
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300">
                {{ __('Back to Bed Details') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('nurse.beds.update', $bed->id) }}">
                        @csrf
                        @method('PUT')

                        <!-- Bed Number -->
                        <div class="mb-4">
                            <label for="bed_number" class="block text-sm font-medium text-gray-700">Bed Number*</label>
                            <input type="text" name="bed_number" id="bed_number" value="{{ old('bed_number', $bed->bed_number) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('bed_number') border-red-500 @enderror"
                                required>
                            @error('bed_number')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-xs mt-1">Example: "101", "A-23", "ICU-5"</p>
                        </div>

                        <!-- Location -->
                        <div class="mb-4">
                            <label for="location" class="block text-sm font-medium text-gray-700">Location*</label>
                            <input type="text" name="location" id="location" value="{{ old('location', $bed->location) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('location') border-red-500 @enderror"
                                required>
                            @error('location')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-xs mt-1">Example: "Ward A", "Emergency Department", "2nd Floor East"</p>
                        </div>

                        <!-- Bed Type -->
                        <div class="mb-4">
                            <label for="type" class="block text-sm font-medium text-gray-700">Bed Type*</label>
                            <select name="type" id="type"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('type') border-red-500 @enderror"
                                required>
                                @foreach($bedTypes as $value => $label)
                                <option value="{{ $value }}" {{ old('type', $bed->type) == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                            @error('type')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Bed Status -->
                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Bed Status*</label>
                            <select name="status" id="status"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('status') border-red-500 @enderror"
                                required {{ $bed->status === 'occupied' && $bed->currentVisit ? 'disabled' : '' }}>
                                @foreach($bedStatuses as $value => $label)
                                <option value="{{ $value }}" {{ old('status', $bed->status) == $value ? 'selected' : '' }}
                                    {{ $value === 'occupied' && $bed->status !== 'occupied' ? 'disabled' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                            @if($bed->status === 'occupied' && $bed->currentVisit)
                            <input type="hidden" name="status" value="occupied">
                            <p class="text-red-500 text-xs mt-1">This bed is currently occupied. Status cannot be changed until the patient is discharged or transferred.</p>
                            @endif
                            @error('status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div class="mb-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea name="notes" id="notes" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('notes') border-red-500 @enderror">{{ old('notes', $bed->notes) }}</textarea>
                            @error('notes')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Is Active -->
                        <div class="mb-4">
                            <div class="flex items-center">
                                <input type="checkbox" name="is_active" id="is_active" value="1"
                                    {{ old('is_active', $bed->is_active) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    {{ $bed->status === 'occupied' && $bed->currentVisit ? 'disabled' : '' }}>
                                <label for="is_active" class="ml-2 block text-sm text-gray-700">
                                    Bed is active and can be assigned to patients
                                </label>
                            </div>
                            @if($bed->status === 'occupied' && $bed->currentVisit)
                            <input type="hidden" name="is_active" value="1">
                            <p class="text-red-500 text-xs mt-1">This bed is currently occupied. Active status cannot be changed until the patient is discharged or transferred.</p>
                            @endif
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('nurse.beds.show', $bed->id) }}"
                                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring ring-blue-200 active:text-gray-800 active:bg-gray-50 mr-2">
                                Cancel
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300">
                                Update Bed
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>