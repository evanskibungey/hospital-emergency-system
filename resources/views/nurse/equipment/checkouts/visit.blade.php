<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Patient Equipment Checkouts') }}
            </h2>
            <div>
                <a href="{{ route('nurse.equipment-checkouts.create', ['visit_id' => $visit->id]) }}"
                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 mr-2">
                    {{ __('Check Out More Equipment') }}
                </a>
                <a href="{{ route('nurse.equipment-checkouts.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300">
                    {{ __('All Checkouts') }}
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

            <!-- Patient Information -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ __('Patient Information') }}
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Patient Name</h4>
                                <p class="text-base">{{ $visit->patient->first_name }} {{ $visit->patient->last_name }}</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Medical Record Number</h4>
                                <p class="text-base">{{ $visit->patient->medical_record_number ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Gender / Age</h4>
                                <p class="text-base">
                                    {{ ucfirst($visit->patient->gender ?? 'N/A') }} / 
                                    {{ $visit->patient->date_of_birth ? $visit->patient->date_of_birth->age . ' years' : 'N/A' }}
                                </p>
                            </div>
                        </div>
                        <div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Visit Information</h4>
                                <p class="text-base">Check-in: {{ $visit->check_in_time ? $visit->check_in_time->format('M d, Y H:i') : 'N/A' }}</p>
                                <p class="text-sm text-gray-500">Status: {{ ucfirst(str_replace('_', ' ', $visit->status)) }}</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Chief Complaint</h4>
                                <p class="text-base">{{ $visit->chief_complaint ?? 'Not recorded' }}</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Department</h4>
                                <p class="text-base">{{ $visit->department ?? 'Not specified' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Equipment Checkouts -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ __('Equipment Checkouts') }}
                    </h3>

                    @if($checkouts->count() > 0)
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
                                        Checked Out By
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Checked Out At
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Purpose
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($checkouts as $checkout)
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
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($checkout->status == 'checked_out') bg-blue-100 text-blue-800
                                            @elseif($checkout->status == 'checked_in') bg-green-100 text-green-800
                                            @elseif($checkout->status == 'overdue') bg-red-100 text-red-800
                                            @elseif($checkout->status == 'lost') bg-gray-100 text-gray-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $checkout->status)) }}
                                        </span>
                                        
                                        @if($checkout->checked_in_at)
                                        <div class="text-xs text-gray-500 mt-1">
                                            Returned: {{ $checkout->checked_in_at->format('M d, Y H:i') }}
                                        </div>
                                        @elseif($checkout->expected_return_at)
                                        <div class="text-xs {{ $checkout->isOverdue() ? 'text-red-600 font-medium' : 'text-gray-500' }} mt-1">
                                            Expected: {{ $checkout->expected_return_at->format('M d, Y H:i') }}
                                        </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $checkout->purpose }}
                                        </div>
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
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $checkouts->links() }}
                    </div>
                    
                    @else
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                        <div class="flex">
                            <div>
                                <p class="text-yellow-700">
                                    No equipment has been checked out for this patient visit yet.
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="mt-4">
                        <a href="{{ route('nurse.equipment-checkouts.create', ['visit_id' => $visit->id]) }}"
                            class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Check Out Equipment
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>