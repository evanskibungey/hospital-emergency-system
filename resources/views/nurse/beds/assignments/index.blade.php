<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Current Bed Assignments') }}
            </h2>
            <div>
                <a href="{{ route('nurse.beds.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300">
                    {{ __('Bed Management') }}
                </a>
                <a href="{{ route('nurse.dashboard') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300">
                    {{ __('Back to Dashboard') }}
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

            <!-- Occupied Beds Summary -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ __('Occupied Beds') }}
                    </h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Bed
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
                                        Patient
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Assigned At
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Assigned To
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($occupiedBeds as $bed)
                                <tr>
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
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($bed->type == 'regular') bg-gray-100 text-gray-800
                                            @elseif($bed->type == 'icu') bg-red-100 text-red-800
                                            @elseif($bed->type == 'pediatric') bg-blue-100 text-blue-800
                                            @elseif($bed->type == 'maternity') bg-pink-100 text-pink-800
                                            @else bg-purple-100 text-purple-800 @endif">
                                            {{ ucfirst($bed->type) }}
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
                                        <span class="text-sm text-red-500">Data inconsistency</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($bed->currentVisit)
                                        <div class="text-sm text-gray-900">
                                            {{ $bed->currentVisit->bed_assigned_at ? $bed->currentVisit->bed_assigned_at->format('M d, Y H:i') : 'N/A' }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $bed->currentVisit->bed_assigned_at ? $bed->currentVisit->bed_assigned_at->diffForHumans() : '' }}
                                        </div>
                                        @else
                                        <span class="text-sm text-gray-500">N/A</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($bed->currentVisit && $bed->currentVisit->assignedTo)
                                        <div class="text-sm text-gray-900">
                                            {{ $bed->currentVisit->assignedTo->name }}
                                        </div>
                                        @else
                                        <span class="text-sm text-yellow-500">Not assigned</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @if($bed->currentVisit)
                                        <a href="{{ route('nurse.bed-assignments.show', $bed->currentVisit->id) }}"
                                            class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            View Assignment
                                        </a>
                                        <a href="{{ route('nurse.bed-assignments.edit', $bed->currentVisit->id) }}"
                                            class="text-yellow-600 hover:text-yellow-900">
                                            Transfer
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                        No beds are currently occupied
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $occupiedBeds->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>