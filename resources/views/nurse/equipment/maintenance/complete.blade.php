<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Complete Maintenance Request') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Equipment and Maintenance Info Summary -->
                    <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Maintenance Request Summary</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Equipment:</p>
                                <p class="text-md font-medium">{{ $equipmentMaintenance->equipment->name }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ $equipmentMaintenance->equipment->model ?? 'No model' }} 
                                    {{ $equipmentMaintenance->equipment->serial_number ? "($equipmentMaintenance->equipment->serial_number)" : '' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Type/Priority:</p>
                                <p class="text-md font-medium">
                                    {{ ucfirst($equipmentMaintenance->type) }} / 
                                    <span class="
                                        @if($equipmentMaintenance->priority == 'low') text-blue-600
                                        @elseif($equipmentMaintenance->priority == 'medium') text-yellow-600
                                        @elseif($equipmentMaintenance->priority == 'high') text-orange-600
                                        @else text-red-600 @endif
                                    ">{{ ucfirst($equipmentMaintenance->priority) }}</span>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Requested:</p>
                                <p class="text-md font-medium">{{ $equipmentMaintenance->requested_at->format('M d, Y') }}</p>
                                <p class="text-xs text-gray-500">By: {{ $equipmentMaintenance->requestedBy->name }}</p>
                            </div>
                        </div>
                        <div class="mt-2">
                            <p class="text-sm text-gray-600">Issue Description:</p>
                            <p class="text-md bg-white p-2 rounded mt-1 border">{{ $equipmentMaintenance->issue_description }}</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('nurse.equipment-maintenance.process-complete', $equipmentMaintenance->id) }}">
                        @csrf

                        <!-- Work Performed -->
                        <div class="mb-4">
                            <label for="work_performed" class="block text-sm font-medium text-gray-700">Work Performed*</label>
                            <textarea name="work_performed" id="work_performed" rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('work_performed') border-red-500 @enderror"
                                required>{{ old('work_performed') }}</textarea>
                            @error('work_performed')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-xs mt-1">Describe the maintenance work performed in detail</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <!-- Service Provider -->
                                <div class="mb-4">
                                    <label for="service_provider" class="block text-sm font-medium text-gray-700">Service Provider (Optional)</label>
                                    <input type="text" name="service_provider" id="service_provider" value="{{ old('service_provider') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('service_provider') border-red-500 @enderror"
                                        placeholder="e.g., Company name or technician">
                                    @error('service_provider')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Contact Info -->
                                <div class="mb-4">
                                    <label for="contact_info" class="block text-sm font-medium text-gray-700">Contact Information (Optional)</label>
                                    <input type="text" name="contact_info" id="contact_info" 
                                        value="{{ old('contact_info', $equipmentMaintenance->contact_info) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('contact_info') border-red-500 @enderror"
                                        placeholder="e.g., Phone number or email">
                                    @error('contact_info')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div>
                                <!-- Cost -->
                                <div class="mb-4">
                                    <label for="cost" class="block text-sm font-medium text-gray-700">Cost (Optional)</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">$</span>
                                        </div>
                                        <input type="number" name="cost" id="cost" step="0.01" min="0" 
                                            value="{{ old('cost') }}"
                                            class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('cost') border-red-500 @enderror"
                                            placeholder="0.00">
                                    </div>
                                    @error('cost')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Calculate Next Maintenance -->
                                <div class="mb-4">
                                    <div class="flex items-center">
                                        <input type="checkbox" name="calculate_next_maintenance" id="calculate_next_maintenance" value="1"
                                            {{ old('calculate_next_maintenance') ? 'checked' : '' }}
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="calculate_next_maintenance" class="ml-2 block text-sm text-gray-700">
                                            Automatically schedule next maintenance
                                        </label>
                                    </div>
                                    <p class="text-gray-500 text-xs mt-1 ml-6">
                                        For preventive maintenance: 6 months.<br>
                                        For inspection/calibration: 12 months.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mb-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Additional Notes</label>
                            <textarea name="notes" id="notes" rows="2"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('notes') border-red-500 @enderror">{{ old('notes', $equipmentMaintenance->notes) }}</textarea>
                            @error('notes')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('nurse.equipment-maintenance.show', $equipmentMaintenance->id) }}"
                                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring ring-blue-200 active:text-gray-800 active:bg-gray-50 mr-2">
                                Cancel
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300">
                                Complete Maintenance
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>