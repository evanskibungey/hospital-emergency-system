<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Equipment Maintenance') }}
            </h2>
            <div>
                <a href="{{ route('nurse.equipment-maintenance.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 mr-2">
                    {{ __('Request Maintenance') }}
                </a>
                <a href="{{ route('nurse.equipment.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300">
                    {{ __('Equipment List') }}
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

            <!-- Maintenance Status Overview -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ __('Maintenance Status Overview') }}
                        </h3>
                    </div>

                    <!-- Maintenance Summary Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
                        <div class="bg-blue-100 p-4 rounded-lg text-center">
                            <div class="text-xl font-bold text-blue-800">{{ $totalActiveMaintenance }}</div>
                            <div class="text-sm text-blue-700">Active Requests</div>
                        </div>
                        <div class="bg-red-100 p-4 rounded-lg text-center">
                            <div class="text-xl font-bold text-red-800">{{ $highPriorityMaintenance }}</div>
                            <div class="text-sm text-red-700">High Priority</div>
                        </div>
                        <a href="{{ route('nurse.equipment-maintenance.scheduled-today') }}" class="block">
                            <div class="bg-green-100 p-4 rounded-lg text-center hover:bg-green-200 transition-colors">
                                <div class="text-xl font-bold text-green-800">{{ $scheduledToday }}</div>
                                <div class="text-sm text-green-700">Scheduled Today</div>
                            </div>
                        </a>
                        <a href="{{ route('nurse.equipment-maintenance.overdue') }}" class="block">
                            <div class="bg-yellow-100 p-4 rounded-lg text-center hover:bg-yellow-200 transition-colors">
                                <div class="text-xl font-bold text-yellow-800">{{ $overdueMaintenance }}</div>
                                <div class="text-sm text-yellow-700">Overdue</div>
                            </div>
                        </a>
                        <div class="bg-indigo-100 p-4 rounded-lg text-center">
                            <div class="text-xl font-bold text-indigo-800">{{ $completedThisMonth }}</div>
                            <div class="text-sm text-indigo-700">Completed This Month</div>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="flex flex-wrap gap-2 mb-6">
                        <a href="{{ route('nurse.equipment-maintenance.create') }}"
                            class="inline-flex items-center px-3 py-1.5 bg-yellow-600 border border-transparent rounded-md font-medium text-xs text-white hover:bg-yellow-700">
                            New Maintenance Request
                        </a>
                        <a href="{{ route('nurse.equipment-maintenance.equipment-needing-maintenance') }}"
                            class="inline-flex items-center px-3 py-1.5 bg-red-600 border border-transparent rounded-md font-medium text-xs text-white hover:bg-red-700">
                            Equipment Needing Maintenance
                        </a>
                        <a href="{{ route('nurse.equipment-maintenance.scheduled-today') }}"
                            class="inline-flex items-center px-3 py-1.5 bg-green-600 border border-transparent rounded-md font-medium text-xs text-white hover:bg-green-700">
                            Today's Schedule
                        </a>
                        <a href="{{ route('nurse.equipment-maintenance.overdue') }}"
                            class="inline-flex items-center px-3 py-1.5 bg-orange-600 border border-transparent rounded-md font-medium text-xs text-white hover:bg-orange-700">
                            Overdue Maintenance
                        </a>
                        <a href="{{ route('nurse.equipment-maintenance.history') }}"
                            class="inline-flex items-center px-3 py-1.5 bg-gray-600 border border-transparent rounded-md font-medium text-xs text-white hover:bg-gray-700">
                            Maintenance History
                        </a>
                    </div>

                    <!-- Active Maintenance Requests Table -->
                    <h4 class="text-md font-medium text-gray-700 mb-2">Current Active Maintenance Requests</h4>
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
                                        Priority
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Requested By
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Requested At
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
                                @forelse($activeMaintenance as $maintenance)
                                <tr class="{{ 
                                    $maintenance->priority == 'critical' ? 'bg-red-50' : 
                                    ($maintenance->priority == 'high' ? 'bg-orange-50' : '') 
                                }}">
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
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($maintenance->priority == 'low') bg-blue-100 text-blue-800
                                            @elseif($maintenance->priority == 'medium') bg-yellow-100 text-yellow-800
                                            @elseif($maintenance->priority == 'high') bg-orange-100 text-orange-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($maintenance->priority) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $maintenance->requestedBy ? $maintenance->requestedBy->name : 'Unknown' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $maintenance->requested_at->format('M d, Y H:i') }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $maintenance->requested_at->diffForHumans() }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($maintenance->status == 'requested') bg-blue-100 text-blue-800
                                            @elseif($maintenance->status == 'scheduled') bg-green-100 text-green-800
                                            @elseif($maintenance->status == 'in_progress') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($maintenance->status) }}
                                        </span>
                                        
                                        @if($maintenance->scheduled_for)
                                        <div class="text-xs {{ $maintenance->isOverdue() ? 'text-red-600 font-medium' : 'text-gray-500' }} mt-1">
                                            Scheduled: {{ $maintenance->scheduled_for->format('M d, Y H:i') }}
                                            @if($maintenance->isOverdue())
                                            <span class="text-red-600 font-bold">(OVERDUE)</span>
                                            @endif
                                        </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('nurse.equipment-maintenance.show', $maintenance->id) }}"
                                            class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            View
                                        </a>
                                        <a href="{{ route('nurse.equipment-maintenance.complete', $maintenance->id) }}"
                                            class="text-green-600 hover:text-green-900">
                                            Complete
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                        No active maintenance requests found
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $activeMaintenance->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>