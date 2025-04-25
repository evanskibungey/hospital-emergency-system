<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bed Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p>{{ session('success') }}</p>
            </div>
            @endif

            @if (session('warning'))
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
                <p>{{ session('warning') }}</p>
            </div>
            @endif

            @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p>{{ session('error') }}</p>
            </div>
            @endif

            <!-- Bed Status Overview -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ __('Bed Status Overview') }}
                        </h3>
                        <a href="{{ route('nurse.beds.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Add New Bed') }}
                        </a>
                    </div>

                    <!-- Summary Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
                        <div class="bg-green-100 p-4 rounded-lg text-center">
                            <div class="text-xl font-bold text-green-800">{{ $availableBeds }}</div>
                            <div class="text-sm text-green-700">Available</div>
                        </div>
                        <div class="bg-red-100 p-4 rounded-lg text-center">
                            <div class="text-xl font-bold text-red-800">{{ $occupiedBeds }}</div>
                            <div class="text-sm text-red-700">Occupied</div>
                        </div>
                        <div class="bg-yellow-100 p-4 rounded-lg text-center">
                            <div class="text-xl font-bold text-yellow-800">{{ $cleaningBeds }}</div>
                            <div class="text-sm text-yellow-700">Cleaning</div>
                        </div>
                        <div class="bg-gray-100 p-4 rounded-lg text-center">
                            <div class="text-xl font-bold text-gray-800">{{ $maintenanceBeds }}</div>
                            <div class="text-sm text-gray-700">Maintenance</div>
                        </div>
                        <div class="bg-blue-100 p-4 rounded-lg text-center">
                            <div class="text-xl font-bold text-blue-800">{{ $reservedBeds }}</div>
                            <div class="text-sm text-blue-700">Reserved</div>
                        </div>
                    </div>

                    <!-- Bed Types -->
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
                        <div class="bg-indigo-50 p-4 rounded-lg text-center">
                            <div class="text-xl font-bold text-indigo-800">{{ $regularBeds }}</div>
                            <div class="text-sm text-indigo-700">Regular</div>
                        </div>
                        <div class="bg-indigo-50 p-4 rounded-lg text-center">
                            <div class="text-xl font-bold text-indigo-800">{{ $icuBeds }}</div>
                            <div class="text-sm text-indigo-700">ICU</div>
                        </div>
                        <div class="bg-indigo-50 p-4 rounded-lg text-center">
                            <div class="text-xl font-bold text-indigo-800">{{ $pediatricBeds }}</div>
                            <div class="text-sm text-indigo-700">Pediatric</div>
                        </div>
                        <div class="bg-indigo-50 p-4 rounded-lg text-center">
                            <div class="text-xl font-bold text-indigo-800">{{ $maternityBeds }}</div>
                            <div class="text-sm text-indigo-700">Maternity</div>
                        </div>
                        <div class="bg-indigo-50 p-4 rounded-lg text-center">
                            <div class="text-xl font-bold text-indigo-800">{{ $isolationBeds }}</div>
                            <div class="text-sm text-indigo-700">Isolation</div>
                        </div>
                    </div>

                    <!-- Bed Filter Form -->
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <h4 class="text-md font-medium text-gray-700 mb-2">Filter Beds</h4>
                        <form action="{{ route('nurse.beds.filter') }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                <div>
                                    <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                                    <select name="type" id="type"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="all">All Types</option>
                                        <option value="regular">Regular</option>
                                        <option value="icu">ICU</option>
                                        <option value="pediatric">Pediatric</option>
                                        <option value="maternity">Maternity</option>
                                        <option value="isolation">Isolation</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                    <select name="status" id="status"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="all">All Statuses</option>
                                        <option value="available">Available</option>
                                        <option value="occupied">Occupied</option>
                                        <option value="cleaning">Cleaning</option>
                                        <option value="maintenance">Maintenance</option>
                                        <option value="reserved">Reserved</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                                    <input type="text" name="location" id="location"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        placeholder="Enter location">
                                </div>
                                <div class="flex items-end">
                                    <div class="flex items-center h-5 mt-5">
                                        <input type="checkbox" name="show_inactive" id="show_inactive"
                                            class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                        <label for="show_inactive" class="ml-2 block text-sm text-gray-700">
                                            Show Inactive Beds
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300">
                                    Apply Filters
                                </button>
                                <a href="{{ route('nurse.beds.index') }}"
                                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring ring-blue-200 active:text-gray-800 active:bg-gray-50">
                                    Reset
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Beds Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Bed Number
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Location
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Type
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Current Patient
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($beds as $bed)
                                <tr class="{{ !$bed->is_active ? 'bg-gray-100' : '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $bed->bed_number }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $bed->location }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ ucfirst($bed->type) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($bed->status == 'available') bg-green-100 text-green-800 
                                            @elseif($bed->status == 'occupied') bg-red-100 text-red-800 
                                            @elseif($bed->status == 'cleaning') bg-yellow-100 text-yellow-800 
                                            @elseif($bed->status == 'maintenance') bg-gray-100 text-gray-800 
                                            @else bg-blue-100 text-blue-800 @endif">
                                            {{ ucfirst($bed->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($bed->currentVisit && $bed->currentVisit->patient)
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $bed->currentVisit->patient->first_name }}
                                            {{ $bed->currentVisit->patient->last_name }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            MRN: {{ $bed->currentVisit->patient->medical_record_number ?? 'N/A' }}
                                        </div>
                                        @else
                                        <span class="text-sm text-gray-500">No patient assigned</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('nurse.beds.show', $bed->id) }}"
                                            class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            View
                                        </a>
                                        <a href="{{ route('nurse.beds.edit', $bed->id) }}"
                                            class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            Edit
                                        </a>
                                        
                                        @if($bed->status !== 'occupied')
                                            @if($bed->status === 'cleaning')
                                            <form method="POST" action="{{ route('nurse.beds.mark-clean', $bed->id) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-green-600 hover:text-green-900">
                                                    Mark Clean
                                                </button>
                                            </form>
                                            @elseif($bed->status !== 'maintenance')
                                            <form method="POST" action="{{ route('nurse.beds.mark-cleaning', $bed->id) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-yellow-600 hover:text-yellow-900 mr-3">
                                                    Mark for Cleaning
                                                </button>
                                            </form>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        No beds found
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $beds->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>