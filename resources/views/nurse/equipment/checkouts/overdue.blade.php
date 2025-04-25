<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Overdue Equipment Checkouts') }}
            </h2>
            <div>
                <a href="{{ route('nurse.equipment-checkouts.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300">
                    {{ __('Back to Checkouts') }}
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

            <!-- Overdue Checkouts Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ __('Overdue Equipment') }}
                        </h3>
                    </div>

                    @if($overdueCheckouts->count() > 0)
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                        <p class="font-bold">Attention Required</p>
                        <p>These items are overdue for return. Please locate and check in as soon as possible.</p>
                    </div>

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
                                        Expected Return
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Overdue By
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($overdueCheckouts as $checkout)
                                <tr class="bg-red-50">
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
                                        <div class="text-xs text-gray-500">
                                            {{ $checkout->checked_out_at->format('M d, Y H:i') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($checkout->expected_return_at)
                                        <div class="text-sm text-red-600 font-medium">
                                            {{ $checkout->expected_return_at->format('M d, Y H:i') }}
                                        </div>
                                        @else
                                        <span class="text-sm text-gray-500">Not specified</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($checkout->expected_return_at)
                                        <div class="text-sm text-red-600 font-bold">
                                            {{ now()->diffForHumans($checkout->expected_return_at, ['parts' => 2, 'short' => true]) }}
                                        </div>
                                        @else
                                        <span class="text-sm text-gray-500">N/A</span>
                                        @endif
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
                    
                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $overdueCheckouts->links() }}
                    </div>
                    @else
                    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4">
                        <p class="font-bold">All Clear!</p>
                        <p>There are no overdue equipment checkouts at this time.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>