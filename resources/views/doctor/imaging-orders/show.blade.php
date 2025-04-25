<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Imaging Order Details') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('doctor.visits.show', $imagingOrder->visit) }}" class="px-4 py-2 bg-gray-200 rounded-md text-gray-700 hover:bg-gray-300">
                    {{ __('Back to Visit') }}
                </a>
                @if ($imagingOrder->status === 'ordered' && $imagingOrder->ordered_by === Auth::id())
                    <a href="{{ route('doctor.imaging-orders.edit', $imagingOrder) }}" class="px-4 py-2 bg-indigo-500 rounded-md text-white hover:bg-indigo-600">
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
                        <h3 class="text-lg font-semibold">Imaging Order Information</h3>
                        <div>
                            <span class="px-3 py-1 rounded-full text-xs 
                                {{ $imagingOrder->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                   ($imagingOrder->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 
                                   ($imagingOrder->status === 'cancelled' ? 'bg-red-100 text-red-800' : 
                                   ($imagingOrder->status === 'scheduled' ? 'bg-purple-100 text-purple-800' : 'bg-yellow-100 text-yellow-800'))) }}">
                                {{ ucfirst($imagingOrder->status) }}
                            </span>

                            @if ($imagingOrder->is_stat)
                                <span class="ml-2 px-3 py-1 rounded-full text-xs bg-red-100 text-red-800">STAT</span>
                            @endif

                            @if ($imagingOrder->requires_contrast)
                                <span class="ml-2 px-3 py-1 rounded-full text-xs bg-yellow-100 text-yellow-800">Contrast</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p><span class="font-medium">Patient:</span> {{ $imagingOrder->visit->patient->full_name }}</p>
                                <p><span class="font-medium">Age:</span> {{ $imagingOrder->visit->patient->date_of_birth->age }} years</p>
                                <p><span class="font-medium">MRN:</span> {{ $imagingOrder->visit->patient->medical_record_number }}</p>
                                <p><span class="font-medium">Visit:</span> #{{ $imagingOrder->visit_id }} - {{ $imagingOrder->visit->chief_complaint }}</p>
                            </div>
                            <div>
                                <p><span class="font-medium">Ordered by:</span> Dr. {{ $imagingOrder->orderedBy->name }}</p>
                                <p><span class="font-medium">Date Ordered:</span> {{ $imagingOrder->ordered_at->format('M d, Y g:i A') }}</p>
                                @if ($imagingOrder->scheduled_for)
                                    <p><span class="font-medium">Scheduled For:</span> {{ $imagingOrder->scheduled_for->format('M d, Y g:i A') }}</p>
                                @endif
                                @if ($imagingOrder->completed_at)
                                    <p><span class="font-medium">Completed:</span> {{ $imagingOrder->completed_at->format('M d, Y g:i A') }}</p>
                                @endif
                                @if ($imagingOrder->radiologist_id)
                                    <p><span class="font-medium">Radiologist:</span> Dr. {{ $imagingOrder->radiologist->name }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mb-6 border border-gray-200 rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                            <h4 class="font-medium text-gray-700">Imaging Details</h4>
                        </div>
                        <div class="p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <p><span class="font-medium">Imaging Type:</span> {{ $imagingOrder->imaging_type }}</p>
                                    <p><span class="font-medium">Body Part:</span> {{ $imagingOrder->body_part }}</p>
                                    <p><span class="font-medium">Requires Contrast:</span> {{ $imagingOrder->requires_contrast ? 'Yes' : 'No' }}</p>
                                </div>
                            </div>
                            
                            @if ($imagingOrder->clinical_information)
                                <div class="mt-3">
                                    <p class="font-medium">Clinical Information:</p>
                                    <div class="mt-1 p-3 bg-gray-50 rounded">{{ $imagingOrder->clinical_information }}</div>
                                </div>
                            @endif
                            
                            <div class="mt-3">
                                <p class="font-medium">Reason for Exam:</p>
                                <div class="mt-1 p-3 bg-gray-50 rounded">{{ $imagingOrder->reason_for_exam }}</div>
                            </div>
                            
                            @if ($imagingOrder->notes)
                                <div class="mt-3">
                                    <p class="font-medium">Additional Notes:</p>
                                    <div class="mt-1 p-3 bg-gray-50 rounded">{{ $imagingOrder->notes }}</div>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if (in_array($imagingOrder->status, ['in_progress', 'completed']))
                        <div class="mb-6 border border-gray-200 rounded-lg overflow-hidden">
                            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                                <h4 class="font-medium text-gray-700">Imaging Results</h4>
                            </div>
                            <div class="p-4">
                                @if ($imagingOrder->findings)
                                    <div class="mb-3">
                                        <p class="font-medium">Findings:</p>
                                        <div class="mt-1 p-3 bg-gray-50 rounded">{{ $imagingOrder->findings }}</div>
                                    </div>
                                @endif
                                
                                @if ($imagingOrder->impression)
                                    <div class="mb-3">
                                        <p class="font-medium">Impression:</p>
                                        <div class="mt-1 p-3 bg-gray-50 rounded">{{ $imagingOrder->impression }}</div>
                                    </div>
                                @endif
                                
                                @if ($imagingOrder->status === 'in_progress' && (Auth::user()->hasRole('admin') || Auth::user()->hasRole('doctor')))
                                    <div class="mt-6 border-t border-gray-200 pt-4">
                                        <h5 class="font-medium text-gray-700 mb-2">Update Imaging Results</h5>
                                        <form method="POST" action="{{ route('doctor.imaging-orders.update-results', $imagingOrder) }}">
                                            @csrf
                                            
                                            <div class="mb-4">
                                                <x-input-label for="findings" :value="__('Findings')" />
                                                <textarea id="findings" name="findings" rows="4" 
                                                    class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>{{ old('findings', $imagingOrder->findings) }}</textarea>
                                                <x-input-error :messages="$errors->get('findings')" class="mt-2" />
                                            </div>
                                            
                                            <div class="mb-4">
                                                <x-input-label for="impression" :value="__('Impression')" />
                                                <textarea id="impression" name="impression" rows="4" 
                                                    class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>{{ old('impression', $imagingOrder->impression) }}</textarea>
                                                <x-input-error :messages="$errors->get('impression')" class="mt-2" />
                                            </div>
                                            
                                            <div class="mb-4">
                                                <label class="inline-flex items-center">
                                                    <input type="checkbox" name="mark_as_completed" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="1" {{ old('mark_as_completed') ? 'checked' : '' }}>
                                                    <span class="ml-2 text-sm text-gray-600">{{ __('Mark this imaging order as completed') }}</span>
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
                    @elseif ($imagingOrder->status === 'ordered' && $imagingOrder->ordered_by === Auth::id())
                        <div class="flex space-x-3 mt-6">
                            <form method="POST" action="{{ route('doctor.imaging-orders.cancel', $imagingOrder) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600" onclick="return confirm('Are you sure you want to cancel this imaging order?')">
                                    Cancel Order
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            @if ($imagingOrder->treatment)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-semibold mb-4">Associated Treatment</h3>
                        <div class="mb-4">
                            <p><span class="font-medium">Diagnosis:</span> {{ $imagingOrder->treatment->diagnosis }}</p>
                            <p><span class="font-medium">Treatment Status:</span> 
                                <span class="px-2 py-1 rounded-full text-xs 
                                    {{ $imagingOrder->treatment->status === 'active' ? 'bg-green-100 text-green-800' : 
                                       ($imagingOrder->treatment->status === 'completed' ? 'bg-blue-100 text-blue-800' : 
                                       ($imagingOrder->treatment->status === 'discontinued' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                    {{ ucfirst($imagingOrder->treatment->status) }}
                                </span>
                            </p>
                            <p class="mt-2"><span class="font-medium">Created By:</span> Dr. {{ $imagingOrder->treatment->creator->name }} on {{ $imagingOrder->treatment->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="flex justify-end">
                            <a href="{{ route('doctor.treatments.show', $imagingOrder->treatment) }}" class="px-4 py-2 bg-indigo-500 text-white rounded-md hover:bg-indigo-600 text-sm">
                                View Treatment Details
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Print button for the imaging order -->
            <div class="flex justify-center mt-6">
                <button onclick="window.print();" class="px-6 py-3 bg-gray-800 text-white rounded-md hover:bg-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print Imaging Order
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
            .imaging-order-print-header {
                display: block !important;
                text-align: center;
                margin-bottom: 20px;
                padding-bottom: 10px;
                border-bottom: 2px solid #000;
            }

            .imaging-order-print-header h1 {
                font-size: 18pt;
                font-weight: bold;
            }

            .imaging-order-print-header p {
                font-size: 10pt;
                margin: 5px 0;
            }
        }
    </style>

    <!-- Print header (only visible when printing) -->
    <div class="imaging-order-print-header" style="display: none;">
        <h1>Hospital Emergency Medical Center</h1>
        <p>123 Healthcare Avenue, Medical City</p>
        <p>Radiology and Imaging Department</p>
        <p>Phone: (555) 123-4567 | Fax: (555) 765-4321</p>
        <p>IMAGING ORDER</p>
    </div>
</x-app-layout>
