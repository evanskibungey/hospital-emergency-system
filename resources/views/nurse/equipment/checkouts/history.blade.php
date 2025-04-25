<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Equipment Checkout History') }}
            </h2>
            <div>
                <a href="{{ route('nurse.equipment-checkouts.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300">
                    {{ __('Back to Active Checkouts') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Checkout History Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ __('Complete Checkout History') }}
                        </h3>
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
                                        Checked Out At
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Checked In At
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
                                @forelse($checkouts as $checkout)
                                <tr class="{{ !$checkout->checked_in_at ? ($checkout->isOverdue() ? 'bg-red-50' : 'bg-blue-50') : '' }}">
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
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($checkout->checked_in_at)
                                        <div class="text-sm text-gray-900">
                                            {{ $checkout->checked_in_at->format('M d, Y H:i') }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            By: {{ $checkout->checkedInBy ? $checkout->checkedInBy->name : 'Unknown' }}
                                        </div>
                                        @else
                                        <span class="text-sm {{ $checkout->isOverdue() ? 'text-red-600 font-medium' : 'text-blue-600' }}">
                                            Not checked in yet
                                        </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($checkout->status == 'checked_out') bg-blue-100 text-blue-800
                                            @elseif($checkout->status == 'checked_in') bg-green-100 text-green-800
                                            @elseif($checkout->status == 'overdue') bg-red-100 text-red-800
                                            @elseif($checkout->status == 'lost') bg-gray-100 text-gray-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $checkout->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('nurse.equipment-checkouts.show', $checkout->id) }}"
                                            class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            View
                                        </a>
                                        @if(!$checkout->checked_in_at)
                                        <a href="{{ route('nurse.equipment-checkouts.checkin', $checkout->id) }}"
                                            class="text-green-600 hover:text-green-900">
                                            Check In
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                        No checkout history found
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $checkouts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>