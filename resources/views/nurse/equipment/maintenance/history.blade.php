<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Maintenance History') }}
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ __('Equipment Maintenance History') }}
                        </h3>
                        <div class="flex space-x-2">
                            <a href="{{ route('nurse.equipment-maintenance.overdue') }}"
                                class="inline-flex items-center px-3 py-1.5 bg-red-600 border border-transparent rounded-md font-medium text-xs text-white hover:bg-red-700">
                                Overdue Maintenance
                            </a>
                            <a href="{{ route('nurse.equipment-maintenance.scheduled-today') }}"
                                class="inline-flex items-center px-3 py-1.5 bg-green-600 border border-transparent rounded-md font-medium text-xs text-white hover:bg-green-700">
                                Today's Schedule
                            </a>
                            <a href="{{ route('nurse.equipment-maintenance.create') }}"
                                class="inline-flex items-center px-3 py-1.5 bg-yellow-600 border border-transparent rounded-md font-medium text-xs text-white hover:bg-yellow-700">
                                New Request
                            </a>
                        </div>
                    </div>

                    <!-- Maintenance History Filter (Future enhancement) -->
                    <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600">
                            Showing all maintenance records ordered by most recent request date.
                        </p>
                        <!-- Future enhancement: Add filter options here -->
                    </div>

                    <!-- Maintenance History Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Equipment
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Type
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Requested
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Completed
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($maintenanceHistory as $maintenance)
                                <tr class="{{ $maintenance->isCompleted() ? 'bg-green-50' : ($maintenance->status == 'cancelled' ? 'bg-gray-50' : '') }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $maintenance->equipment->name }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $maintenance->equipment->model ?? 'No model' }} {{ $maintenance->equipment->serial_number ? "($maintenance->equipment->serial_number)" : '' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ ucfirst($maintenance->type) }}
                                        </div>
                                        <span class="inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($maintenance->priority == 'low') bg-blue-100 text-blue-800
                                            @elseif($maintenance->priority == 'medium') bg-yellow-100 text-yellow-800
                                            @elseif($maintenance->priority == 'high') bg-orange-100 text-orange-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($maintenance->priority) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $maintenance->requested_at->format('M d, Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            By: {{ $maintenance->requestedBy ? $maintenance->requestedBy->name : 'Unknown' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($maintenance->completed_at)
                                        <div class="text-sm text-gray-900">
                                            {{ $maintenance->completed_at->format('M d, Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            By: {{ $maintenance->completedBy ? $maintenance->completedBy->name : 'Unknown' }}
                                        </div>
                                        @elseif($maintenance->status == 'cancelled')
                                        <div class="text-sm text-gray-500">
                                            Cancelled
                                        </div>
                                        @else
                                        <div class="text-sm text-yellow-600">
                                            Pending
                                        </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($maintenance->status == 'requested') bg-blue-100 text-blue-800
                                            @elseif($maintenance->status == 'scheduled') bg-green-100 text-green-800
                                            @elseif($maintenance->status == 'in_progress') bg-yellow-100 text-yellow-800
                                            @elseif($maintenance->status == 'completed') bg-indigo-100 text-indigo-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($maintenance->status) }}
                                        </span>
                                        
                                        @if($maintenance->scheduled_for && !$maintenance->completed_at && $maintenance->status != 'cancelled')
                                        <div class="text-xs {{ $maintenance->isOverdue() ? 'text-red-600 font-medium' : 'text-gray-500' }} mt-1">
                                            Scheduled: {{ $maintenance->scheduled_for->format('M d, Y') }}
                                            @if($maintenance->isOverdue())
                                            <span class="text-red-600 font-bold">(OVERDUE)</span>
                                            @endif
                                        </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('nurse.equipment-maintenance.show', $maintenance->id) }}"
                                            class="text-indigo-600 hover:text-indigo-900">
                                            View
                                        </a>
                                        @if(!$maintenance->isCompleted() && $maintenance->status != 'cancelled')
                                        <a href="{{ route('nurse.equipment-maintenance.complete', $maintenance->id) }}"
                                            class="text-green-600 hover:text-green-900 ml-3">
                                            Complete
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        No maintenance history found
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $maintenanceHistory->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>