<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Lab Order Details') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('doctor.visits.show', $labOrder->visit) }}" class="px-4 py-2 bg-gray-200 rounded-md text-gray-700 hover:bg-gray-300">
                    {{ __('Back to Visit') }}
                </a>
                @if ($labOrder->status === 'ordered' && $labOrder->ordered_by === Auth::id())
                    <a href="{{ route('doctor.lab-orders.edit', $labOrder) }}" class="px-4 py-2 bg-indigo-500 rounded-md text-white hover:bg-indigo-600">
                        {{ __('Edit Order') }}
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-lg font-semibold">Lab Order Information</h3>
                        <div>
                            <span class="px-3 py-1 rounded-full text-xs 
                                {{ $labOrder->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                   ($labOrder->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 
                                   ($labOrder->status === 'cancelled' ? 'bg-red-100 text-red-800' : 
                                   ($labOrder->status === 'collected' ? 'bg-purple-100 text-purple-800' : 'bg-yellow-100 text-yellow-800'))) }}">
                                {{ ucfirst($labOrder->status) }}
                            </span>

                            @if ($labOrder->is_stat)
                                <span class="ml-2 px-3 py-1 rounded-full text-xs bg-red-100 text-red-800">STAT</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p><span class="font-medium">Patient:</span> {{ $labOrder->visit->patient->full_name }}</p>
                                <p><span class="font-medium">Age:</span> {{ $labOrder->visit->patient->date_of_birth->age }} years</p>
                                <p><span class="font-medium">MRN:</span> {{ $labOrder->visit->patient->medical_record_number }}</p>
                                <p><span class="font-medium">Visit:</span> #{{ $labOrder->visit_id }} - {{ $labOrder->visit->chief_complaint }}</p>
                            </div>
                            <div>
                                <p><span class="font-medium">Ordered by:</span> Dr. {{ $labOrder->orderedBy->name }}</p>
                                <p><span class="font-medium">Date Ordered:</span> {{ $labOrder->ordered_at->format('M d, Y g:i A') }}</p>
                                @if ($labOrder->scheduled_for)
                                    <p><span class="font-medium">Scheduled For:</span> {{ $labOrder->scheduled_for->format('M d, Y g:i A') }}</p>
                                @endif
                                @if ($labOrder->collected_at)
                                    <p><span class="font-medium">Sample Collected:</span> {{ $labOrder->collected_at->format('M d, Y g:i A') }}</p>
                                @endif
                                @if ($labOrder->completed_at)
                                    <p><span class="font-medium">Completed:</span> {{ $labOrder->completed_at->format('M d, Y g:i A') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mb-6 border border-gray-200 rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                            <h4 class="font-medium text-gray-700">Test Details</h4>
                        </div>
                        <div class="p-4">
                            <p><span class="font-medium">Test Name:</span> {{ $labOrder->test_name }}</p>
                            
                            @if ($labOrder->test_details)
                                <div class="mt-3">
                                    <p class="font-medium">Test Details:</p>
                                    <div class="mt-1 p-3 bg-gray-50 rounded">{{ $labOrder->test_details }}</div>
                                </div>
                            @endif
                            
                            <div class="mt-3">
                                <p class="font-medium">Reason for Test:</p>
                                <div class="mt-1 p-3 bg-gray-50 rounded">{{ $labOrder->reason_for_test }}</div>
                            </div>
                            
                            @if ($labOrder->notes)
                                <div class="mt-3">
                                    <p class="font-medium">Additional Notes:</p>
                                    <div class="mt-1 p-3 bg-gray-50 rounded">{{ $labOrder->notes }}</div>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if (in_array($labOrder->status, ['in_progress', 'completed']))
                        <div class="mb-6 border border-gray-200 rounded-lg overflow-hidden">
                            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                                <h4 class="font-medium text-gray-700">Test Results</h4>
                            </div>
                            <div class="p-4">
                                @if ($labOrder->result_summary)
                                    <div class="mb-3">
                                        <p class="font-medium">Result Summary:</p>
                                        <div class="mt-1 p-3 bg-gray-50 rounded">{{ $labOrder->result_summary }}</div>
                                    </div>
                                @endif
                                
                                @if ($labOrder->result_details)
                                    <div class="mb-3">
                                        <p class="font-medium">Result Details:</p>
                                        <div class="mt-1 p-3 bg-gray-50 rounded whitespace-pre-line">{{ $labOrder->result_details }}</div>
                                    </div>
                                @endif
                                
                                @if ($labOrder->status === 'in_progress' && (Auth::user()->hasRole('admin') || Auth::user()->hasRole('doctor')))
                                    <div class="mt-6 border-t border-gray-200 pt-4">
                                        <h5 class="font-medium text-gray-700 mb-2">Update Lab Results</h5>
                                        <form method="POST" action="{{ route('doctor.lab-orders.update-results', $labOrder) }}">
                                            @csrf
                                            
                                            <div class="mb-4">
                                                <x-input-label for="result_summary" :value="__('Result Summary')" />
                                                <textarea id="result_summary" name="result_summary" rows="2" 
                                                    class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>{{ old('result_summary', $labOrder->result_summary) }}</textarea>
                                                <x-input-error :messages="$errors->get('result_summary')" class="mt-2" />
                                            </div>
                                            
                                            <div class="mb-4">
                                                <x-input-label for="result_details" :value="__('Result Details')" />
                                                <textarea id="result_details" name="result_details" rows="4" 
                                                    class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('result_details', $labOrder->result_details) }}</textarea>
                                                <x-input-error :messages="$errors->get('result_details')" class="mt-2" />
                                            </div>
                                            
                                            <div class="mb-4">
                                                <label class="inline-flex items-center">
                                                    <input type="checkbox" name="mark_as_completed" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="1" {{ old('mark_as_completed') ? 'checked' : '' }}>
                                                    <span class="ml-2 text-sm text-gray-600">{{ __('Mark this lab order as completed') }}</span>
                                                </label>
                                            </div>
                                            
                                            <div class="flex justify-end">
                                                <x-primary-button>
                                                    {{ __('Update Results') }}
                                                </x-primary-button>
                                            </div>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @elseif ($labOrder->status === 'ordered' && $labOrder->ordered_by === Auth::id())
                        <div class="flex space-x-3 mt-6">
                            <form method="POST" action="{{ route('doctor.lab-orders.cancel', $labOrder) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600" onclick="return confirm('Are you sure you want to cancel this lab order?')">
                                    Cancel Order
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            @if ($labOrder->treatment)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-semibold mb-4">Associated Treatment</h3>
                        <div class="mb-4">
                            <p><span class="font-medium">Diagnosis:</span> {{ $labOrder->treatment->diagnosis }}</p>
                            <p><span class="font-medium">Treatment Status:</span> 
                                <span class="px-2 py-1 rounded-full text-xs 
                                    {{ $labOrder->treatment->status === 'active' ? 'bg-green-100 text-green-800' : 
                                       ($labOrder->treatment->status === 'completed' ? 'bg-blue-100 text-blue-800' : 
                                       ($labOrder->treatment->status === 'discontinued' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                    {{ ucfirst($labOrder->treatment->status) }}
                                </span>
                            </p>
                            <p class="mt-2"><span class="font-medium">Created By:</span> Dr. {{ $labOrder->treatment->creator->name }} on {{ $labOrder->treatment->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="flex justify-end">
                            <a href="{{ route('doctor.treatments.show', $labOrder->treatment) }}" class="px-4 py-2 bg-indigo-500 text-white rounded-md hover:bg-indigo-600 text-sm">
                                View Treatment Details
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Print button for the lab order -->
            <div class="flex justify-center mt-6">
                <button onclick="window.print();" class="px-6 py-3 bg-gray-800 text-white rounded-md hover:bg-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print Lab Order
                </button>
            </div>
        </div>
    </div>

    <style>
        @media print {
            header, nav, footer, button, a, form {
                display: none;
            }
            
            body {
                font-size: 12pt;
            }

            .bg-blue-50, .bg-gray-50 {
                background-color: white !important;
            }

            .max-w-7xl {
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .shadow-sm {
                box-shadow: none !important;
            }

            .rounded-lg, .rounded {
                border-radius: 0 !important;
            }

            .border {
                border: 1px solid #000 !important;
            }

            /* Custom print header */
            .lab-order-print-header {
                display: block !important;
                text-align: center;
                margin-bottom: 20px;
                padding-bottom: 10px;
                border-bottom: 2px solid #000;
            }

            .lab-order-print-header h1 {
                font-size: 18pt;
                font-weight: bold;
            }

            .lab-order-print-header p {
                font-size: 10pt;
                margin: 5px 0;
            }
        }
    </style>

    <!-- Print header (only visible when printing) -->
    <div class="lab-order-print-header" style="display: none;">
        <h1>Hospital Emergency Medical Center</h1>
        <p>123 Healthcare Avenue, Medical City</p>
        <p>Laboratory Department</p>
        <p>Phone: (555) 123-4567 | Fax: (555) 765-4321</p>
        <p>LAB ORDER</p>
    </div>
</x-app-layout>
