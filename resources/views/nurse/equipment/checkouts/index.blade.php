<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Equipment Checkouts') }}
            </h2>
            <div>
                <a href="{{ route('nurse.equipment-checkouts.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 mr-2">
                    {{ __('Check Out Equipment') }}
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

            <!-- Checkout Status Overview -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ __('Checkout Status Overview') }}
                        </h3>
                    </div>

                    <!-- Checkout Summary Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-blue-100 p-4 rounded-lg text-center">
                            <div class="text-xl font-bold text-blue-800">{{ $totalActiveCheckouts }}</div>
                            <div class="text-sm text-blue-700">Active Checkouts</div>
                        </div>
                        <a href="{{ route('nurse.equipment-checkouts.overdue') }}" class="block">
                            <div class="bg-red-100 p-4 rounded-lg text-center hover:bg-red-200 transition-colors">
                                <div class="text-xl font-bold text-red-800">{{ $overdueCheckouts }}</div>
                                <div class="text-sm text-red-700">Overdue</div>
                            </div>
                        </a>
                        <div class="bg-green-100 p-4 rounded-lg text-center">
                            <div class="text-xl font-bold text-green-800">{{ $checkedOutToday }}</div>
                            <div class="text-sm text-green-700">Checked Out Today</div>
                        </div>
                        <div class="bg-indigo-100 p-4 rounded-lg text-center">
                            <div class="text-xl font-bold text-indigo-800">{{ $checkedInToday }}</div>
                            <div class="text-sm text-indigo-700">Checked In Today</div>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="flex flex-wrap gap-2 mb-6">
                        <a href="{{ route('nurse.equipment-checkouts.create') }}"
                            class="inline-flex items-center px-3 py-1.5 bg-green-600 border border-transparent rounded-md font-medium text-xs text-white hover:bg-green-700">
                            New Checkout
                        </a>
                        <a href="{{ route('nurse.equipment-checkouts.overdue') }}"
                            class="inline-flex items-center px-3 py-1.5 bg-red-600 border border-transparent rounded-md font-medium text-xs text-white hover:bg-red-700">
                            View Overdue
                        </a>
                        <a href="{{ route('nurse.equipment-checkouts.history') }}"
                            class="inline-flex items-center px-3 py-1.5 bg-gray-600 border border-transparent rounded-md font-medium text-xs text-white hover:bg-gray-700">
                            Checkout History
                        </a>
                    </div>

                    <!-- Active Checkouts Table -->
                    <h4 class="text-md font-medium text-gray-700 mb-2">Current Active Checkouts</h4>
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
                                        Status
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($activeCheckouts as $checkout)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $checkout->equipment->name }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $checkout->quantity }} units
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($checkout->visit && $checkout->visit->patient)
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $checkout->visit->patient->first_name }} {{ $checkout->visit->patient->last_name }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            MRN: {{ $checkout->visit->patient->medical_record_number ?? 'N/A' }}
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
                                        <div class="text-sm {{ $checkout->isOverdue() ? 'text-red-600 font-bold' : 'text-gray-900' }}">
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
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($checkout->status == 'checked_out') bg-blue-100 text-blue-800
                                            @elseif($checkout->status == 'overdue') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $checkout->status)) }}
                                        </span>
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
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                        No active checkouts found
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $activeCheckouts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>