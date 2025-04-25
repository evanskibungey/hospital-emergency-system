<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Equipment') }}: {{ $equipment->name }}
            </h2>
            <a href="{{ route('nurse.equipment.show', $equipment->id) }}"
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300">
                {{ __('Back to Details') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('nurse.equipment.update', $equipment->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <!-- Name -->
                                <div class="mb-4">
                                    <label for="name" class="block text-sm font-medium text-gray-700">Name*</label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $equipment->name) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('name') border-red-500 @enderror"
                                        required>
                                    @error('name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Serial Number -->
                                <div class="mb-4">
                                    <label for="serial_number" class="block text-sm font-medium text-gray-700">Serial Number</label>
                                    <input type="text" name="serial_number" id="serial_number" value="{{ old('serial_number', $equipment->serial_number) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('serial_number') border-red-500 @enderror">
                                    @error('serial_number')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Model -->
                                <div class="mb-4">
                                    <label for="model" class="block text-sm font-medium text-gray-700">Model</label>
                                    <input type="text" name="model" id="model" value="{{ old('model', $equipment->model) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('model') border-red-500 @enderror">
                                    @error('model')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Manufacturer -->
                                <div class="mb-4">
                                    <label for="manufacturer" class="block text-sm font-medium text-gray-700">Manufacturer</label>
                                    <input type="text" name="manufacturer" id="manufacturer" value="{{ old('manufacturer', $equipment->manufacturer) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('manufacturer') border-red-500 @enderror">
                                    @error('manufacturer')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Type -->
                                <div class="mb-4">
                                    <label for="type" class="block text-sm font-medium text-gray-700">Type*</label>
                                    <select name="type" id="type"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('type') border-red-500 @enderror"
                                        required>
                                        @foreach($types as $value => $label)
                                        <option value="{{ $value }}" {{ old('type', $equipment->type) == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Category -->
                                <div class="mb-4">
                                    <label for="category" class="block text-sm font-medium text-gray-700">Category*</label>
                                    <select name="category" id="category"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('category') border-red-500 @enderror"
                                        required>
                                        @foreach($categories as $value => $label)
                                        <option value="{{ $value }}" {{ old('category', $equipment->category) == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div>
                                <!-- Quantity -->
                                <div class="mb-4">
                                    <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity*</label>
                                    <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $equipment->quantity) }}" min="1"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('quantity') border-red-500 @enderror"
                                        required>
                                    @error('quantity')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                    <p class="text-gray-500 text-xs mt-1">Total number of units (currently {{ $equipment->quantity }})</p>
                                    
                                    <p class="text-gray-500 text-xs mt-1">
                                        <span class="font-medium">Available: {{ $equipment->available_quantity }}</span> | 
                                        <span class="font-medium">In Use: {{ $equipment->checked_out_count }}</span>
                                    </p>
                                    
                                    @if($equipment->activeCheckouts && $equipment->activeCheckouts->count() > 0)
                                    <p class="text-yellow-600 text-xs mt-1">
                                        Note: There are currently {{ $equipment->activeCheckouts->count() }} active checkouts. 
                                        Changing the total quantity may affect availability.
                                    </p>
                                    @endif
                                </div>

                                <!-- Status -->
                                <div class="mb-4">
                                    <label for="status" class="block text-sm font-medium text-gray-700">Status*</label>
                                    <select name="status" id="status"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('status') border-red-500 @enderror"
                                        required>
                                        @foreach($statuses as $value => $label)
                                        <option value="{{ $value }}" {{ old('status', $equipment->status) == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                    
                                    @if($equipment->activeCheckouts && $equipment->activeCheckouts->count() > 0)
                                    <p class="text-yellow-600 text-xs mt-1">
                                        Note: There are active checkouts. Changing status to something other than "In Use" 
                                        may cause inconsistencies.
                                    </p>
                                    @endif
                                </div>

                                <!-- Location -->
                                <div class="mb-4">
                                    <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                                    <input type="text" name="location" id="location" value="{{ old('location', $equipment->location) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('location') border-red-500 @enderror">
                                    @error('location')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                    <p class="text-gray-500 text-xs mt-1">Department or area where the equipment is located</p>
                                </div>

                                <!-- Purchase Date -->
                                <div class="mb-4">
                                    <label for="purchase_date" class="block text-sm font-medium text-gray-700">Purchase Date</label>
                                    <input type="date" name="purchase_date" id="purchase_date" 
                                        value="{{ old('purchase_date', $equipment->purchase_date ? $equipment->purchase_date->format('Y-m-d') : '') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('purchase_date') border-red-500 @enderror">
                                    @error('purchase_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Last Maintenance Date -->
                                <div class="mb-4">
                                    <label for="last_maintenance_date" class="block text-sm font-medium text-gray-700">Last Maintenance Date</label>
                                    <input type="date" name="last_maintenance_date" id="last_maintenance_date" 
                                        value="{{ old('last_maintenance_date', $equipment->last_maintenance_date ? $equipment->last_maintenance_date->format('Y-m-d') : '') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('last_maintenance_date') border-red-500 @enderror">
                                    @error('last_maintenance_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Next Maintenance Date -->
                                <div class="mb-4">
                                    <label for="next_maintenance_date" class="block text-sm font-medium text-gray-700">Next Maintenance Date</label>
                                    <input type="date" name="next_maintenance_date" id="next_maintenance_date" 
                                        value="{{ old('next_maintenance_date', $equipment->next_maintenance_date ? $equipment->next_maintenance_date->format('Y-m-d') : '') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('next_maintenance_date') border-red-500 @enderror">
                                    @error('next_maintenance_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Notes -->
                        <div class="mb-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea name="notes" id="notes" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('notes') border-red-500 @enderror">{{ old('notes', $equipment->notes) }}</textarea>
                            @error('notes')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-xs mt-1">Additional details about the equipment</p>
                        </div>

                        <!-- Is Active -->
                        <div class="mb-4">
                            <div class="flex items-center">
                                <input type="checkbox" name="is_active" id="is_active" value="1"
                                    {{ old('is_active', $equipment->is_active) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <label for="is_active" class="ml-2 block text-sm text-gray-700">
                                    Equipment is active and available for use
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('nurse.equipment.show', $equipment->id) }}"
                                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring ring-blue-200 active:text-gray-800 active:bg-gray-50 mr-2">
                                Cancel
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300">
                                Update Equipment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>