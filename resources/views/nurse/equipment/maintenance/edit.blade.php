<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Maintenance Request') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('nurse.equipment-maintenance.update', $equipmentMaintenance->id) }}">
                        @csrf
                        @method('PUT')

                        <!-- Equipment Information (Read-only) -->
                        <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Equipment Details</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">Name:</p>
                                    <p class="text-md font-medium">{{ $equipmentMaintenance->equipment->name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Model:</p>
                                    <p class="text-md font-medium">{{ $equipmentMaintenance->equipment->model ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Serial Number:</p>
                                    <p class="text-md font-medium">{{ $equipmentMaintenance->equipment->serial_number ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Category:</p>
                                    <p class="text-md font-medium">{{ ucfirst(str_replace('_', ' ', $equipmentMaintenance->equipment->category)) }}</p>
                                </div>
                            </div>
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
                                        <option value="{{ $value }}" {{ old('type', $equipmentMaintenance->type) == $value ? 'selected' : '' }}>
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
                                        <option value="{{ $value }}" {{ old('priority', $equipmentMaintenance->priority) == $value ? 'selected' : '' }}>
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
                                <!-- Status -->
                                <div class="mb-4">
                                    <label for="status" class="block text-sm font-medium text-gray-700">Status*</label>
                                    <select name="status" id="status"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('status') border-red-500 @enderror"
                                        required>
                                        @foreach($statuses as $value => $label)
                                        <option value="{{ $value }}" {{ old('status', $equipmentMaintenance->status) == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                    <p class="text-gray-500 text-xs mt-1">
                                        Note: Marking as 'Completed' will set completion date to now
                                    </p>
                                </div>

                                <!-- Scheduled For -->
                                <div class="mb-4">
                                    <label for="scheduled_for" class="block text-sm font-medium text-gray-700">Schedule Date (Optional)</label>
                                    <input type="datetime-local" name="scheduled_for" id="scheduled_for" 
                                        value="{{ old('scheduled_for', $equipmentMaintenance->scheduled_for ? $equipmentMaintenance->scheduled_for->format('Y-m-d\TH:i') : '') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('scheduled_for') border-red-500 @enderror">
                                    @error('scheduled_for')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                    <p class="text-gray-500 text-xs mt-1">When maintenance should be performed</p>
                                </div>
                            </div>
                        </div>

                        <!-- Issue Description -->
                        <div class="mb-4">
                            <label for="issue_description" class="block text-sm font-medium text-gray-700">Issue Description*</label>
                            <textarea name="issue_description" id="issue_description" rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('issue_description') border-red-500 @enderror"
                                required>{{ old('issue_description', $equipmentMaintenance->issue_description) }}</textarea>
                            @error('issue_description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Contact Info -->
                        <div class="mb-4">
                            <label for="contact_info" class="block text-sm font-medium text-gray-700">Contact Information (Optional)</label>
                            <input type="text" name="contact_info" id="contact_info" 
                                value="{{ old('contact_info', $equipmentMaintenance->contact_info) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('contact_info') border-red-500 @enderror"
                                placeholder="e.g., Service company phone number or contact person">
                            @error('contact_info')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Additional Notes -->
                        <div class="mb-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Additional Notes</label>
                            <textarea name="notes" id="notes" rows="2"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('notes') border-red-500 @enderror">{{ old('notes', $equipmentMaintenance->notes) }}</textarea>
                            @error('notes')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('nurse.equipment-maintenance.show', $equipmentMaintenance->id) }}"
                                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring ring-blue-200 active:text-gray-800 active:bg-gray-50 mr-2">
                                Cancel
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300">
                                Update Maintenance Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>