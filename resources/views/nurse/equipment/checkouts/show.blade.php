<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Equipment Checkout Details') }}
            </h2>
            <div>
                <a href="{{ route('nurse.equipment-checkouts.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300">
                    {{ __('Back to Checkouts') }}
                </a>
                @if(!$equipmentCheckout->checked_in_at)
                <a href="{{ route('nurse.equipment-checkouts.checkin', $equipmentCheckout->id) }}"
                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300">
                    {{ __('Check In Equipment') }}
                </a>
                @endif
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

            <!-- Checkout Information Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ __('Checkout Information') }}
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Equipment</h4>
                                <p class="text-base">{{ $equipmentCheckout->equipment->name }}</p>
                                <p class="text-sm text-gray-500">{{ $equipmentCheckout->equipment->model ?? 'No model' }} {{ $equipmentCheckout->equipment->serial_number ? "($equipmentCheckout->equipment->serial_number)" : '' }}</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Quantity</h4>
                                <p class="text-base">{{ $equipmentCheckout->quantity }} units</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Purpose</h4>
                                <p class="text-base">{{ $equipmentCheckout->purpose }}</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Status</h4>
                                <p class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($equipmentCheckout->status == 'checked_out') bg-blue-100 text-blue-800
                                    @elseif($equipmentCheckout->status == 'checked_in') bg-green-100 text-green-800
                                    @elseif($equipmentCheckout->status == 'overdue') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $equipmentCheckout->status)) }}
                                </p>
                                @if($equipmentCheckout->isOverdue())
                                <p class="text-red-600 font-medium text-sm mt-1">
                                    This equipment is overdue for return.
                                </p>
                                @endif
                            </div>
                        </div>
                        <div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Checked Out By</h4>
                                <p class="text-base">{{ $equipmentCheckout->checkedOutBy ? $equipmentCheckout->checkedOutBy->name : 'Unknown' }}</p>
                                <p class="text-sm text-gray-500">{{ $equipmentCheckout->checked_out_at->format('M d, Y H:i') }}</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Checked In By</h4>
                                @if($equipmentCheckout->checked_in_at)
                                <p class="text-base">{{ $equipmentCheckout->checkedInBy ? $equipmentCheckout->checkedInBy->name : 'Unknown' }}</p>
                                <p class="text-sm text-gray-500">{{ $equipmentCheckout->checked_in_at->format('M d, Y H:i') }}</p>
                                @else
                                <p class="text-base text-yellow-600">Not checked in yet</p>
                                @endif
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Expected Return</h4>
                                @if($equipmentCheckout->expected_return_at)
                                <p class="text-base {{ $equipmentCheckout->isOverdue() ? 'text-red-600 font-medium' : '' }}">
                                    {{ $equipmentCheckout->expected_return_at->format('M d, Y H:i') }}
                                </p>
                                @else
                                <p class="text-base">Not specified</p>
                                @endif
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Condition at Checkout</h4>
                                <p class="text-base">{{ $equipmentCheckout->condition_at_checkout ?? 'Not recorded' }}</p>
                            </div>
                        </div>
                    </div>

                    @if($equipmentCheckout->checkout_notes)
                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-gray-500">Checkout Notes</h4>
                        <div class="mt-1 p-4 bg-gray-50 rounded-md">
                            <p class="text-sm text-gray-700 whitespace-pre-line">{{ $equipmentCheckout->checkout_notes }}</p>
                        </div>
                    </div>
                    @endif

                    @if($equipmentCheckout->checkin_notes)
                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-gray-500">Check-in Notes</h4>
                        <div class="mt-1 p-4 bg-gray-50 rounded-md">
                            <p class="text-sm text-gray-700 whitespace-pre-line">{{ $equipmentCheckout->checkin_notes }}</p>
                        </div>
                    </div>
                    @endif

                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-gray-500">Condition at Check-in</h4>
                        <p class="text-base">{{ $equipmentCheckout->condition_at_checkin ?? 'Not checked in yet' }}</p>
                    </div>

                    <!-- Actions -->
                    <div class="mt-6 flex flex-wrap gap-2">
                        @if(!$equipmentCheckout->checked_in_at)
                        <a href="{{ route('nurse.equipment-checkouts.checkin', $equipmentCheckout->id) }}"
                            class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Check In Equipment
                        </a>
                        
                        <form method="POST" action="{{ route('nurse.equipment-checkouts.mark-lost', $equipmentCheckout->id) }}" 
                              onsubmit="return confirm('Are you sure you want to mark this equipment as lost? This will reduce the inventory count.')">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Mark as Lost
                            </button>
                        </form>
                        @endif
                        
                        <a href="{{ route('nurse.equipment.show', $equipmentCheckout->equipment_id) }}"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            View Equipment Details
                        </a>
                    </div>
                </div>
            </div>

            <!-- Patient Information (if applicable) -->
            @if($equipmentCheckout->visit && $equipmentCheckout->visit->patient)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ __('Patient Information') }}
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Patient Name</h4>
                                <p class="text-base">{{ $equipmentCheckout->visit->patient->first_name }} {{ $equipmentCheckout->visit->patient->last_name }}</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Medical Record Number</h4>
                                <p class="text-base">{{ $equipmentCheckout->visit->patient->medical_record_number ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Gender / Age</h4>
                                <p class="text-base">
                                    {{ ucfirst($equipmentCheckout->visit->patient->gender ?? 'N/A') }} / 
                                    {{ $equipmentCheckout->visit->patient->date_of_birth ? $equipmentCheckout->visit->patient->date_of_birth->age . ' years' : 'N/A' }}
                                </p>
                            </div>
                        </div>
                        <div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Visit Information</h4>
                                <p class="text-base">Check-in: {{ $equipmentCheckout->visit->check_in_time ? $equipmentCheckout->visit->check_in_time->format('M d, Y H:i') : 'N/A' }}</p>
                                <p class="text-sm text-gray-500">Status: {{ ucfirst(str_replace('_', ' ', $equipmentCheckout->visit->status)) }}</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Chief Complaint</h4>
                                <p class="text-base">{{ $equipmentCheckout->visit->chief_complaint ?? 'Not recorded' }}</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Department</h4>
                                <p class="text-base">{{ $equipmentCheckout->visit->department ?? 'Not specified' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 flex flex-wrap gap-2">
                        <a href="{{ route('nurse.equipment-checkouts.visit', $equipmentCheckout->visit_id) }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            View All Patient Checkouts
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>