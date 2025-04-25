<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Equipment Needing Maintenance') }}
            </h2>
            <div>
                <a href="{{ route('nurse.equipment-maintenance.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300">
                    {{ __('Back to Maintenance') }}
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

            <!-- Alert Box -->
            <div class="bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-4 mb-6" role="alert">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <!-- Warning icon -->
                        <svg class="h-5 w-5 text-orange-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">
                            The following equipment items need maintenance or have maintenance due soon. Equipment with past due dates should be scheduled immediately.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ __('Equipment Due for Maintenance') }}
                        </h3>
                        <a href="{{ route('nurse.equipment-maintenance.create') }}"
                            class="inline-flex items-center px-3 py-1.5 bg-yellow-600 border border-transparent rounded-md font-medium text-xs text-white hover:bg-yellow-700">
                            New Maintenance Request
                        </a>
                    </div>

                    <!-- Equipment Needing Maintenance Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Equipment Name
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Category
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Last Maintenance
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Next Due Date
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Current Requests
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($equipmentNeedingMaintenance as $equipment)
                                <tr class="{{ 
                                    $equipment->next_maintenance_date && $equipment->next_maintenance_date->isPast() ? 'bg-red-50' : 
                                    ($equipment->next_maintenance_date && $equipment->next_maintenance_date->diffInDays(now()) <= 7 ? 'bg-yellow-50' : '') 
                                }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $equipment->name }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $equipment->model ?? 'No model' }} {{ $equipment->serial_number ? "($equipment->serial_number)" : '' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ ucfirst(str_replace('_', ' ', $equipment->category)) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $equipment->last_maintenance_date ? $equipment->last_maintenance_date->format('M d, Y') : 'Never' }}
                                        </div>
                                        @if($equipment->last_maintenance_date)
                                        <div class="text-xs text-gray-500">
                                            {{ $equipment->last_maintenance_date->diffForHumans() }}
                                        </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($equipment->next_maintenance_date)
                                            <div class="text-sm {{ $equipment->next_maintenance_date->isPast() ? 'font-bold text-red-600' : 'text-gray-900' }}">
                                                {{ $equipment->next_maintenance_date->format('M d, Y') }}
                                            </div>
                                            <div class="text-xs {{ $equipment->next_maintenance_date->isPast() ? 'text-red-600' : 'text-gray-500' }}">
                                                {{ $equipment->next_maintenance_date->diffForHumans() }}
                                                @if($equipment->next_maintenance_date->isPast())
                                                <span class="font-bold">(OVERDUE)</span>
                                                @endif
                                            </div>
                                        @else
                                            <div class="text-sm text-gray-500">Not scheduled</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($equipment->status == 'available') bg-green-100 text-green-800
                                            @elseif($equipment->status == 'in_use') bg-blue-100 text-blue-800
                                            @elseif($equipment->status == 'maintenance') bg-yellow-100 text-yellow-800
                                            @elseif($equipment->status == 'out_of_order') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $equipment->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($equipment->activeMaintenance && $equipment->activeMaintenance->count() > 0)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ $equipment->activeMaintenance->count() }} active
                                            </span>
                                        @else
                                            <span class="text-sm text-gray-500">None</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('nurse.equipment-maintenance.create', ['equipment_id' => $equipment->id]) }}"
                                            class="text-yellow-600 hover:text-yellow-900 mr-3">
                                            Schedule
                                        </a>
                                        <a href="{{ route('nurse.equipment.show', $equipment->id) }}"
                                            class="text-indigo-600 hover:text-indigo-900">
                                            View
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                        No equipment needs maintenance at this time
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $equipmentNeedingMaintenance->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>