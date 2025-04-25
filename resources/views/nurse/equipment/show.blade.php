<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Equipment Details') }}: {{ $equipment->name }}
            </h2>
            <div>
                <a href="{{ route('nurse.equipment.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300">
                    {{ __('Back to Equipment') }}
                </a>
                <a href="{{ route('nurse.equipment.edit', $equipment->id) }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300">
                    {{ __('Edit Equipment') }}
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

            @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p>{{ session('error') }}</p>
            </div>
            @endif

            <!-- Equipment Information Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ __('Equipment Information') }}
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Name</h4>
                                <p class="text-base">{{ $equipment->name }}</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Model</h4>
                                <p class="text-base">{{ $equipment->model ?? 'Not specified' }}</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Serial Number</h4>
                                <p class="text-base">{{ $equipment->serial_number ?? 'Not specified' }}</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Manufacturer</h4>
                                <p class="text-base">{{ $equipment->manufacturer ?? 'Not specified' }}</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Type</h4>
                                <p class="text-base">{{ ucfirst($equipment->type) }}</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Category</h4>
                                <p class="text-base">{{ ucfirst(str_replace('_', ' ', $equipment->category)) }}</p>
                            </div>
                        </div>
                        <div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Status</h4>
                                <p class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($equipment->status == 'available') bg-green-100 text-green-800 
                                    @elseif($equipment->status == 'in_use') bg-blue-100 text-blue-800 
                                    @elseif($equipment->status == 'maintenance') bg-yellow-100 text-yellow-800 
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($equipment->status) }}
                                </p>
                                @if(!$equipment->is_active)
                                <p class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                    Inactive
                                </p>
                                @endif
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Location</h4>
                                <p class="text-base">{{ $equipment->location ?? 'Not specified' }}</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Quantity</h4>
                                <p class="text-base">{{ $equipment->quantity }} total units</p>
                                <p class="text-sm {{ $equipment->available_quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $equipment->available_quantity }} units available
                                </p>
                                <p class="text-sm text-blue-600">
                                    {{ $equipment->checked_out_count }} units currently checked out
                                </p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Maintenance Schedule</h4>
                                <p class="text-base">
                                    Last Maintenance: {{ $equipment->last_maintenance_date ? $equipment->last_maintenance_date->format('M d, Y') : 'Not recorded' }}
                                </p>
                                <p class="text-sm {{ $equipment->isMaintenanceDue() ? 'text-red-600 font-bold' : 'text-blue-600' }}">
                                    Next Maintenance: {{ $equipment->next_maintenance_date ? $equipment->next_maintenance_date->format('M d, Y') : 'Not scheduled' }}
                                    @if($equipment->isMaintenanceDue())
                                    <span class="text-red-600 font-bold">(OVERDUE)</span>
                                    @endif
                                </p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Purchase Information</h4>
                                <p class="text-base">Purchase Date: {{ $equipment->purchase_date ? $equipment->purchase_date->format('M d, Y') : 'Not recorded' }}</p>
                            </div>
                        </div>
                    </div>

                    @if($equipment->notes)
                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-gray-500">Notes</h4>
                        <div class="mt-1 p-4 bg-gray-50 rounded-md">
                            <p class="text-sm text-gray-700 whitespace-pre-line">{{ $equipment->notes }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Quick Actions -->
                    <div class="mt-6 flex flex-wrap gap-2">
                        @if($equipment->isAvailable())
                        <a href="{{ route('nurse.equipment-checkouts.create', ['equipment_id' => $equipment->id]) }}"
                            class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Check Out
                        </a>
                        @endif
                        
                        <a href="{{ route('nurse.equipment-maintenance.create', ['equipment_id' => $equipment->id]) }}"
                            class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                            Request Maintenance
                        </a>
                    </div>
                </div>
            </div>

            <!-- Current Checkouts Section -->
            @if($equipment->activeCheckouts && $equipment->activeCheckouts->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ __('Current Checkouts') }}
                    </h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Patient
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Checked Out By
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Checkout Time
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Expected Return
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Quantity
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($equipment->activeCheckouts as $checkout)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($checkout->visit && $checkout->visit->patient)
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $checkout->visit->patient->first_name }} {{ $checkout->visit->patient->last_name }}
                                        </div>
                                        @else
                                        <span class="text-sm text-gray-500">Not assigned to a patient</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $checkout->checkedOutBy ? $checkout->checkedOutBy->name : 'Unknown' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $checkout->checked_out_at->format('M d, Y H:i') }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $checkout->checked_out_at->diffForHumans() }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($checkout->expected_return_at)
                                        <div class="text-sm text-gray-900">
                                            {{ $checkout->expected_return_at->format('M d, Y H:i') }}
                                        </div>
                                        <div class="text-xs {{ $checkout->isOverdue() ? 'text-red-600 font-bold' : 'text-gray-500' }}">
                                            {{ $checkout->expected_return_at->diffForHumans() }}
                                            @if($checkout->isOverdue())
                                            <span class="text-red-600 font-bold">(OVERDUE)</span>
                                            @endif
                                        </div>
                                        @else
                                        <span class="text-sm text-gray-500">Not specified</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $checkout->quantity }} units
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('nurse.equipment-checkouts.show', $checkout->id) }}"
                                            class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            View
                                        </a>
                                        <a href="{{ route('nurse.equipment-checkouts.checkin', $checkout->id) }}"
                                            class="text-green-600 hover:text-green-900">
                                            Check In
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Active Maintenance Requests Section -->
            @if($equipment->activeMaintenance && $equipment->activeMaintenance->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ __('Active Maintenance Requests') }}
                    </h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Requested By
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
                                        Requested At
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Scheduled For
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($equipment->activeMaintenance as $maintenance)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $maintenance->requestedBy ? $maintenance->requestedBy->name : 'Unknown' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ ucfirst($maintenance->type) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($maintenance->priority == 'low') bg-green-100 text-green-800
                                            @elseif($maintenance->priority == 'medium') bg-yellow-100 text-yellow-800
                                            @elseif($maintenance->priority == 'high') bg-orange-100 text-orange-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($maintenance->priority) }}
                                        </span>
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
                                        @if($maintenance->scheduled_for)
                                        <div class="text-sm text-gray-900">
                                            {{ $maintenance->scheduled_for->format('M d, Y H:i') }}
                                        </div>
                                        <div class="text-xs {{ $maintenance->isOverdue() ? 'text-red-600 font-bold' : 'text-gray-500' }}">
                                            {{ $maintenance->scheduled_for->diffForHumans() }}
                                            @if($maintenance->isOverdue())
                                            <span class="text-red-600 font-bold">(OVERDUE)</span>
                                            @endif
                                        </div>
                                        @else
                                        <span class="text-sm text-gray-500">Not scheduled</span>
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
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Checkout History -->
            @if($recentCheckouts->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ __('Recent Checkout History') }}
                    </h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Patient
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Checked Out By
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Checked In By
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Checkout Time
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Check-in Time
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($recentCheckouts as $checkout)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($checkout->visit && $checkout->visit->patient)
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $checkout->visit->patient->first_name }} {{ $checkout->visit->patient->last_name }}
                                        </div>
                                        @else
                                        <span class="text-sm text-gray-500">Not assigned to a patient</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $checkout->checkedOutBy ? $checkout->checkedOutBy->name : 'Unknown' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $checkout->checkedInBy ? $checkout->checkedInBy->name : ($checkout->checked_in_at ? 'Unknown' : 'Not checked in') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $checkout->checked_out_at->format('M d, Y H:i') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $checkout->checked_in_at ? $checkout->checked_in_at->format('M d, Y H:i') : 'Not checked in' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($checkout->status == 'checked_out') bg-blue-100 text-blue-800
                                            @elseif($checkout->status == 'checked_in') bg-green-100 text-green-800
                                            @elseif($checkout->status == 'overdue') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $checkout->status)) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 text-center">
                        <a href="{{ route('nurse.equipment-checkouts.history') }}" class="text-indigo-600 hover:text-indigo-900">
                            View Complete Checkout History
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Maintenance History -->
            @if($recentMaintenance->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ __('Recent Maintenance History') }}
                    </h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Requested By
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Completed By
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Type
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Requested At
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Completed At
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($recentMaintenance as $maintenance)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $maintenance->requestedBy ? $maintenance->requestedBy->name : 'Unknown' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $maintenance->completedBy ? $maintenance->completedBy->name : ($maintenance->completed_at ? 'Unknown' : 'Not completed') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ ucfirst($maintenance->type) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $maintenance->requested_at->format('M d, Y H:i') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $maintenance->completed_at ? $maintenance->completed_at->format('M d, Y H:i') : 'Not completed' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($maintenance->status == 'requested') bg-blue-100 text-blue-800
                                            @elseif($maintenance->status == 'scheduled') bg-yellow-100 text-yellow-800
                                            @elseif($maintenance->status == 'in_progress') bg-orange-100 text-orange-800
                                            @elseif($maintenance->status == 'completed') bg-green-100 text-green-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($maintenance->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 text-center">
                        <a href="{{ route('nurse.equipment-maintenance.history') }}" class="text-indigo-600 hover:text-indigo-900">
                            View Complete Maintenance History
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>