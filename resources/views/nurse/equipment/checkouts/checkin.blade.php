<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Check In Equipment') }}
            </h2>
            <a href="{{ route('nurse.equipment-checkouts.show', $equipmentCheckout->id) }}"
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300">
                {{ __('Back to Checkout Details') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p>{{ session('error') }}</p>
            </div>
            @endif

            <!-- Equipment Information -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ __('Equipment Information') }}
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Equipment</h4>
                                <p class="text-base">{{ $equipmentCheckout->equipment->name }}</p>
                                <p class="text-sm text-gray-500">{{ $equipmentCheckout->equipment->model ?? 'No model' }} {{ $equipmentCheckout->equipment->serial_number ? "($equipmentCheckout->equipment->serial_number)" : '' }}</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Quantity</h4>
                                <p class="text-base">{{ $equipmentCheckout->quantity }} units</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Purpose</h4>
                                <p class="text-base">{{ $equipmentCheckout->purpose }}</p>
                            </div>
                        </div>
                        <div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Checked Out By</h4>
                                <p class="text-base">{{ $equipmentCheckout->checkedOutBy ? $equipmentCheckout->checkedOutBy->name : 'Unknown' }}</p>
                                <p class="text-sm text-gray-500">{{ $equipmentCheckout->checked_out_at->format('M d, Y H:i') }}</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Expected Return</h4>
                                @if($equipmentCheckout->expected_return_at)
                                <p class="text-base {{ $equipmentCheckout->isOverdue() ? 'text-red-600 font-medium' : '' }}">
                                    {{ $equipmentCheckout->expected_return_at->format('M d, Y H:i') }}
                                </p>
                                @else
                                <p class="text-base">Not specified</p>
                                @endif
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Condition at Checkout</h4>
                                <p class="text-base">{{ $equipmentCheckout->condition_at_checkout ?? 'Not recorded' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Check-in Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ __('Check-in Details') }}
                    </h3>

                    <form method="POST" action="{{ route('nurse.equipment-checkouts.process-checkin', $equipmentCheckout->id) }}">
                        @csrf

                        <!-- Condition at Check-in -->
                        <div class="mb-4">
                            <label for="condition_at_checkin" class="block text-sm font-medium text-gray-700">Condition at Check-in*</label>
                            <select name="condition_at_checkin" id="condition_at_checkin"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('condition_at_checkin') border-red-500 @enderror"
                                required>
                                @foreach($conditions as $value => $label)
                                <option value="{{ $label }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('condition_at_checkin')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Check-in Notes -->
                        <div class="mb-4">
                            <label for="checkin_notes" class="block text-sm font-medium text-gray-700">Check-in Notes</label>
                            <textarea name="checkin_notes" id="checkin_notes" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('checkin_notes') border-red-500 @enderror">{{ old('checkin_notes') }}</textarea>
                            @error('checkin_notes')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-xs mt-1">Any notes about the equipment condition or usage</p>
                        </div>

                        <!-- Maintenance Request Checkbox -->
                        <div class="mb-4">
                            <div class="flex items-center">
                                <input type="checkbox" name="create_maintenance_request" id="create_maintenance_request" value="1"
                                    {{ old('create_maintenance_request') ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <label for="create_maintenance_request" class="ml-2 block text-sm text-gray-700">
                                    Equipment needs maintenance or repair
                                </label>
                            </div>
                        </div>

                        <!-- Maintenance Request Fields (Hidden Initially) -->
                        <div id="maintenance-fields" class="hidden mb-4 p-4 bg-yellow-50 rounded-md">
                            <h4 class="text-md font-medium text-yellow-700 mb-2">Maintenance Request Details</h4>
                            
                            <!-- Maintenance Issue -->
                            <div class="mb-4">
                                <label for="maintenance_issue" class="block text-sm font-medium text-gray-700">Maintenance Issue*</label>
                                <textarea name="maintenance_issue" id="maintenance_issue" rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('maintenance_issue') border-red-500 @enderror">{{ old('maintenance_issue') }}</textarea>
                                @error('maintenance_issue')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-gray-500 text-xs mt-1">Describe the issue requiring maintenance</p>
                            </div>

                            <!-- Maintenance Priority -->
                            <div class="mb-4">
                                <label for="maintenance_priority" class="block text-sm font-medium text-gray-700">Priority*</label>
                                <select name="maintenance_priority" id="maintenance_priority"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('maintenance_priority') border-red-500 @enderror">
                                    <option value="low">Low - Can wait, not urgent</option>
                                    <option value="medium" selected>Medium - Should be addressed soon</option>
                                    <option value="high">High - Urgent attention needed</option>
                                    <option value="critical">Critical - Immediate attention required</option>
                                </select>
                                @error('maintenance_priority')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <p class="text-yellow-700 text-sm">
                                Note: If you create a maintenance request, the equipment will be marked as 'Maintenance Required' 
                                and will not be available for checkout until maintenance is completed.
                            </p>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('nurse.equipment-checkouts.show', $equipmentCheckout->id) }}"
                                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring ring-blue-200 active:text-gray-800 active:bg-gray-50 mr-2">
                                Cancel
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300">
                                Complete Check-in
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const maintenanceCheckbox = document.getElementById('create_maintenance_request');
            const maintenanceFields = document.getElementById('maintenance-fields');
            const maintenanceIssue = document.getElementById('maintenance_issue');
            const maintenancePriority = document.getElementById('maintenance_priority');
            
            // Show/hide maintenance fields based on checkbox
            function toggleMaintenanceFields() {
                if (maintenanceCheckbox.checked) {
                    maintenanceFields.classList.remove('hidden');
                    maintenanceIssue.setAttribute('required', 'required');
                    maintenancePriority.setAttribute('required', 'required');
                } else {
                    maintenanceFields.classList.add('hidden');
                    maintenanceIssue.removeAttribute('required');
                    maintenancePriority.removeAttribute('required');
                }
            }
            
            // Initial setup
            toggleMaintenanceFields();
            
            // Add event listener
            maintenanceCheckbox.addEventListener('change', toggleMaintenanceFields);
        });
    </script>
</x-app-layout>