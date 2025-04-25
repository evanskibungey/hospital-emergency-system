<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Equipment Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p>{{ session('success') }}</p>
            </div>
            @endif

            <!-- Equipment Status Overview -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ __('Equipment Status Overview') }}
                        </h3>
                        <div>
                            <a href="{{ route('nurse.equipment-checkouts.create') }}"
                                class="inline-flex items-center mr-2 px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                {{ __('Check Out Equipment') }}
                            </a>
                            <a href="{{ route('nurse.equipment.create') }}"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('Add New Equipment') }}
                            </a>
                        </div>
                    </div>

                    <!-- Equipment Status Summary -->
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
                        <a href="{{ route('nurse.equipment.filter', ['status' => 'available']) }}" class="block">
                            <div class="bg-green-100 p-4 rounded-lg text-center hover:bg-green-200 transition-colors">
                                <div class="text-xl font-bold text-green-800">{{ $availableCount }}</div>
                                <div class="text-sm text-green-700">Available</div>
                            </div>
                        </a>
                        <a href="{{ route('nurse.equipment.filter', ['status' => 'in_use']) }}" class="block">
                            <div class="bg-blue-100 p-4 rounded-lg text-center hover:bg-blue-200 transition-colors">
                                <div class="text-xl font-bold text-blue-800">{{ $inUseCount }}</div>
                                <div class="text-sm text-blue-700">In Use</div>
                            </div>
                        </a>
                        <a href="{{ route('nurse.equipment.filter', ['status' => 'maintenance']) }}" class="block">
                            <div class="bg-yellow-100 p-4 rounded-lg text-center hover:bg-yellow-200 transition-colors">
                                <div class="text-xl font-bold text-yellow-800">{{ $maintenanceCount }}</div>
                                <div class="text-sm text-yellow-700">Maintenance</div>
                            </div>
                        </a>
                        <a href="{{ route('nurse.equipment.filter', ['status' => 'retired']) }}" class="block">
                            <div class="bg-gray-100 p-4 rounded-lg text-center hover:bg-gray-200 transition-colors">
                                <div class="text-xl font-bold text-gray-800">{{ $retiredCount }}</div>
                                <div class="text-sm text-gray-700">Retired</div>
                            </div>
                        </a>
                        <a href="{{ route('nurse.equipment-maintenance.equipment-needing-maintenance') }}" class="block">
                            <div class="bg-red-100 p-4 rounded-lg text-center hover:bg-red-200 transition-colors">
                                <div class="text-xl font-bold text-red-800">{{ $needsMaintenanceCount }}</div>
                                <div class="text-sm text-red-700">Needs Maintenance</div>
                            </div>
                        </a>
                    </div>

                    <!-- Equipment Category Summary -->
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
                        <a href="{{ route('nurse.equipment.filter', ['category' => 'diagnostic']) }}" class="block">
                            <div class="bg-indigo-50 p-4 rounded-lg text-center hover:bg-indigo-100 transition-colors">
                                <div class="text-xl font-bold text-indigo-800">{{ $diagnosticCount }}</div>
                                <div class="text-sm text-indigo-700">Diagnostic</div>
                            </div>
                        </a>
                        <a href="{{ route('nurse.equipment.filter', ['category' => 'therapeutic']) }}" class="block">
                            <div class="bg-indigo-50 p-4 rounded-lg text-center hover:bg-indigo-100 transition-colors">
                                <div class="text-xl font-bold text-indigo-800">{{ $therapeuticCount }}</div>
                                <div class="text-sm text-indigo-700">Therapeutic</div>
                            </div>
                        </a>
                        <a href="{{ route('nurse.equipment.filter', ['category' => 'monitoring']) }}" class="block">
                            <div class="bg-indigo-50 p-4 rounded-lg text-center hover:bg-indigo-100 transition-colors">
                                <div class="text-xl font-bold text-indigo-800">{{ $monitoringCount }}</div>
                                <div class="text-sm text-indigo-700">Monitoring</div>
                            </div>
                        </a>
                        <a href="{{ route('nurse.equipment.filter', ['category' => 'emergency']) }}" class="block">
                            <div class="bg-indigo-50 p-4 rounded-lg text-center hover:bg-indigo-100 transition-colors">
                                <div class="text-xl font-bold text-indigo-800">{{ $emergencyCount }}</div>
                                <div class="text-sm text-indigo-700">Emergency</div>
                            </div>
                        </a>
                        <a href="{{ route('nurse.equipment.filter', ['category' => 'patient_care']) }}" class="block">
                            <div class="bg-indigo-50 p-4 rounded-lg text-center hover:bg-indigo-100 transition-colors">
                                <div class="text-xl font-bold text-indigo-800">{{ $patientCareCount }}</div>
                                <div class="text-sm text-indigo-700">Patient Care</div>
                            </div>
                        </a>
                    </div>

                    <!-- Equipment Quick Links -->
                    <div class="flex flex-wrap gap-2 mb-6">
                        <a href="{{ route('nurse.equipment-checkouts.index') }}"
                            class="inline-flex items-center px-3 py-1.5 bg-blue-600 border border-transparent rounded-md font-medium text-xs text-white hover:bg-blue-700">
                            Current Checkouts
                        </a>
                        <a href="{{ route('nurse.equipment-checkouts.overdue') }}"
                            class="inline-flex items-center px-3 py-1.5 bg-red-600 border border-transparent rounded-md font-medium text-xs text-white hover:bg-red-700">
                            Overdue Checkouts
                        </a>
                        <a href="{{ route('nurse.equipment-maintenance.index') }}"
                            class="inline-flex items-center px-3 py-1.5 bg-yellow-600 border border-transparent rounded-md font-medium text-xs text-white hover:bg-yellow-700">
                            Maintenance Requests
                        </a>
                        <a href="{{ route('nurse.equipment-maintenance.create') }}"
                            class="inline-flex items-center px-3 py-1.5 bg-green-600 border border-transparent rounded-md font-medium text-xs text-white hover:bg-green-700">
                            Request Maintenance
                        </a>
                    </div>

                    <!-- Equipment Filter Form -->
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <h4 class="text-md font-medium text-gray-700 mb-2">Filter Equipment</h4>
                        <form action="{{ route('nurse.equipment.filter') }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                <div>
                                    <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                                    <select name="type" id="type"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="all">All Types</option>
                                        <option value="portable">Portable</option>
                                        <option value="fixed">Fixed</option>
                                        <option value="disposable">Disposable</option>
                                        <option value="reusable">Reusable</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                                    <select name="category" id="category"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="all">All Categories</option>
                                        <option value="diagnostic">Diagnostic</option>
                                        <option value="therapeutic">Therapeutic</option>
                                        <option value="monitoring">Monitoring</option>
                                        <option value="laboratory">Laboratory</option>
                                        <option value="surgical">Surgical</option>
                                        <option value="emergency">Emergency</option>
                                        <option value="life_support">Life Support</option>
                                        <option value="patient_care">Patient Care</option>
                                        <option value="administrative">Administrative</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                    <select name="status" id="status"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="all">All Statuses</option>
                                        <option value="available">Available</option>
                                        <option value="in_use">In Use</option>
                                        <option value="maintenance">Maintenance</option>
                                        <option value="retired">Retired</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                                    <input type="text" name="location" id="location"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        placeholder="Enter location">
                                </div>
                            </div>
                            <div class="mt-4 flex items-center">
                                <input type="checkbox" name="show_inactive" id="show_inactive"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 mr-2">
                                <label for="show_inactive" class="text-sm text-gray-700">
                                    Show Inactive Equipment
                                </label>
                                <div class="ml-auto">
                                    <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300">
                                        Apply Filters
                                    </button>
                                    <a href="{{ route('nurse.equipment.index') }}"
                                        class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring ring-blue-200 active:text-gray-800 active:bg-gray-50 ml-2">
                                        Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Equipment Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Name
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Type/Category
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Quantity
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Location
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($equipment as $item)
                                <tr class="{{ !$item->is_active ? 'bg-gray-100' : '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $item->name }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $item->model ?? 'No model' }} {{ $item->serial_number ? "($item->serial_number)" : '' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ ucfirst($item->type) }}
                                        </span>
                                        <span class="px-2 ml-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                            {{ ucfirst(str_replace('_', ' ', $item->category)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div>Available: <span class="font-medium {{ $item->available_quantity > 0 ? 'text-green-600' : 'text-red-600' }}">{{ $item->available_quantity }}</span> / {{ $item->quantity }}</div>
                                        @if($item->activeCheckouts && $item->activeCheckouts->count() > 0)
                                        <div class="text-xs text-blue-600">{{ $item->activeCheckouts->count() }} currently checked out</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($item->status == 'available') bg-green-100 text-green-800 
                                            @elseif($item->status == 'in_use') bg-blue-100 text-blue-800 
                                            @elseif($item->status == 'maintenance') bg-yellow-100 text-yellow-800 
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                        @if(!$item->is_active)
                                        <span class="px-2 mt-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Inactive
                                        </span>
                                        @endif
                                        @if($item->isMaintenanceDue())
                                        <span class="px-2 mt-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Maintenance Due
                                        </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item->location ?? 'Not specified' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('nurse.equipment.show', $item->id) }}"
                                            class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            View
                                        </a>
                                        @if($item->isAvailable())
                                        <a href="{{ route('nurse.equipment-checkouts.create', ['equipment_id' => $item->id]) }}"
                                            class="text-green-600 hover:text-green-900 mr-3">
                                            Check Out
                                        </a>
                                        @endif
                                        <a href="{{ route('nurse.equipment-maintenance.create', ['equipment_id' => $item->id]) }}"
                                            class="text-yellow-600 hover:text-yellow-900">
                                            Maintenance
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        No equipment found
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $equipment->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>