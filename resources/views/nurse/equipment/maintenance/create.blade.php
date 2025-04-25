<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Request Equipment Maintenance') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('nurse.equipment-maintenance.store') }}">
                        @csrf

                        <!-- Equipment Selection -->
                        <div class="mb-4">
                            <label for="equipment_id" class="block text-sm font-medium text-gray-700">Equipment*</label>
                            <select name="equipment_id" id="equipment_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('equipment_id') border-red-500 @enderror"
                                required>
                                <option value="">-- Select Equipment --</option>
                                @if(isset($equipment) && $equipment)
                                    <option value="{{ $equipment->id }}" selected>
                                        {{ $equipment->name }} - {{ $equipment->serial_number ?? 'No S/N' }}
                                    </option>
                                @else
                                    @foreach($allEquipment as $category => $items)
                                        <optgroup label="{{ ucfirst(str_replace('_', ' ', $category)) }}">
                                            @foreach($items as $item)
                                                <option value="{{ $item->id }}" {{ old('equipment_id') == $item->id ? 'selected' : '' }}>
                                                    {{ $item->name }} - {{ $item->model ?? 'No model' }} {{ $item->serial_number ? "($item->serial_number)" : '' }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                @endif
                            </select>
                            @error('equipment_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <!-- Maintenance Type -->
                                <div class="mb-4">
                                    <label for="type" class="block text-sm font-medium text-gray-700">Maintenance Type*</label>
                                    <select name="type" id="type"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('type') border-red-500 @enderror"
                                        required>
                                        @foreach($maintenanceTypes as $value => $label)
                                        <option value="{{ $value }}" {{ old('type') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Priority -->
                                <div class="mb-4">
                                    <label for="priority" class="block text-sm font-medium text-gray-700">Priority*</label>
                                    <select name="priority" id="priority"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('priority') border-red-500 @enderror"
                                        required>
                                        @foreach($priorities as $value => $label)
                                        <option value="{{ $value }}" {{ old('priority', 'medium') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('priority')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                    <p class="text-gray-500 text-xs mt-1">High/Critical priority will mark equipment as unavailable</p>
                                </div>
                            </div>
                            
                            <div>
                                <!-- Scheduled For -->
                                <div class="mb-4">
                                    <label for="scheduled_for" class="block text-sm font-medium text-gray-700">Schedule Date (Optional)</label>
                                    <input type="datetime-local" name="scheduled_for" id="scheduled_for" value="{{ old('scheduled_for') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('scheduled_for') border-red-500 @enderror">
                                    @error('scheduled_for')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                    <p class="text-gray-500 text-xs mt-1">When maintenance should be performed. Leave blank if not scheduled yet.</p>
                                </div>

                                <!-- Contact Info -->
                                <div class="mb-4">
                                    <label for="contact_info" class="block text-sm font-medium text-gray-700">Contact Information (Optional)</label>
                                    <input type="text" name="contact_info" id="contact_info" value="{{ old('contact_info') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('contact_info') border-red-500 @enderror"
                                        placeholder="e.g., Service company phone number or contact person">
                                    @error('contact_info')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Issue Description -->
                        <div class="mb-4">
                            <label for="issue_description" class="block text-sm font-medium text-gray-700">Issue Description*</label>
                            <textarea name="issue_description" id="issue_description" rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('issue_description') border-red-500 @enderror"
                                required>{{ old('issue_description') }}</textarea>
                            @error('issue_description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-xs mt-1">Describe the issue requiring maintenance in detail</p>
                        </div>

                        <!-- Additional Notes -->
                        <div class="mb-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Additional Notes</label>
                            <textarea name="notes" id="notes" rows="2"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                            @error('notes')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('nurse.equipment-maintenance.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring ring-blue-200 active:text-gray-800 active:bg-gray-50 mr-2">
                                Cancel
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:border-yellow-900 focus:ring ring-yellow-300">
                                Submit Maintenance Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>