<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Prescription Details') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('doctor.visits.show', $prescription->visit) }}" class="px-4 py-2 bg-gray-200 rounded-md text-gray-700 hover:bg-gray-300">
                    {{ __('Back to Visit') }}
                </a>
                @if ($prescription->status === 'active' && $prescription->prescribed_by === Auth::id())
                    <a href="{{ route('doctor.prescriptions.edit', $prescription) }}" class="px-4 py-2 bg-indigo-500 rounded-md text-white hover:bg-indigo-600">
                        {{ __('Edit Prescription') }}
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
                        <h3 class="text-lg font-semibold">Prescription Information</h3>
                        <div>
                            @if ($prescription->status === 'active')
                                <span class="px-3 py-1 rounded-full text-xs bg-green-100 text-green-800">Active</span>
                            @elseif ($prescription->status === 'completed')
                                <span class="px-3 py-1 rounded-full text-xs bg-blue-100 text-blue-800">Completed</span>
                            @elseif ($prescription->status === 'cancelled')
                                <span class="px-3 py-1 rounded-full text-xs bg-red-100 text-red-800">Cancelled</span>
                            @elseif ($prescription->status === 'on_hold')
                                <span class="px-3 py-1 rounded-full text-xs bg-yellow-100 text-yellow-800">On Hold</span>
                            @endif

                            @if ($prescription->is_controlled_substance)
                                <span class="ml-2 px-3 py-1 rounded-full text-xs bg-yellow-100 text-yellow-800">Controlled Substance</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p><span class="font-medium">Patient:</span> {{ $prescription->patient->full_name }}</p>
                                <p><span class="font-medium">Age:</span> {{ $prescription->patient->date_of_birth->age }} years</p>
                                <p><span class="font-medium">MRN:</span> {{ $prescription->patient->medical_record_number }}</p>
                                <p><span class="font-medium">Allergies:</span> 
                                    <span class="{{ empty($prescription->patient->allergies) ? 'text-green-600' : 'text-red-600 font-medium' }}">
                                        {{ empty($prescription->patient->allergies) ? 'No Known Allergies' : $prescription->patient->allergies }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <p><span class="font-medium">Prescribed by:</span> Dr. {{ $prescription->prescribedBy->name }}</p>
                                <p><span class="font-medium">Date Prescribed:</span> {{ $prescription->created_at->format('M d, Y') }}</p>
                                <p><span class="font-medium">Start Date:</span> {{ $prescription->start_date->format('M d, Y') }}</p>
                                @if ($prescription->end_date)
                                    <p><span class="font-medium">End Date:</span> {{ $prescription->end_date->format('M d, Y') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mb-6 border border-gray-200 rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                            <h4 class="font-medium text-gray-700">Medication Details</h4>
                        </div>
                        <div class="p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <p><span class="font-medium">Medication:</span> {{ $prescription->medication_name }}</p>
                                    <p><span class="font-medium">Dosage:</span> {{ $prescription->dosage }}</p>
                                    <p><span class="font-medium">Route:</span> {{ $prescription->route }}</p>
                                    <p><span class="font-medium">Frequency:</span> {{ $prescription->frequency }}</p>
                                </div>
                                <div>
                                    <p><span class="font-medium">Quantity:</span> {{ $prescription->quantity }}</p>
                                    <p><span class="font-medium">Refills:</span> {{ $prescription->refills }}</p>
                                    <p><span class="font-medium">Status:</span> {{ ucfirst($prescription->status) }}</p>
                                    @if ($prescription->medication)
                                        <p><span class="font-medium">From Formulary:</span> Yes ({{ $prescription->medication->name }})</p>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <p class="font-medium">Instructions:</p>
                                <div class="mt-1 p-3 bg-gray-50 rounded">{{ $prescription->instructions }}</div>
                            </div>
                            
                            @if ($prescription->notes)
                                <div class="mt-4">
                                    <p class="font-medium">Additional Notes (not shown to patient):</p>
                                    <div class="mt-1 p-3 bg-gray-50 rounded">{{ $prescription->notes }}</div>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if ($prescription->status === 'active' && $prescription->prescribed_by === Auth::id())
                        <div class="flex flex-wrap gap-2 mt-6">
                            <form method="POST" action="{{ route('doctor.prescriptions.complete', $prescription) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600" onclick="return confirm('Are you sure you want to mark this prescription as completed?')">
                                    Mark as Completed
                                </button>
                            </form>
                            
                            <form method="POST" action="{{ route('doctor.prescriptions.hold', $prescription) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600" onclick="return confirm('Are you sure you want to put this prescription on hold?')">
                                    Put on Hold
                                </button>
                            </form>
                            
                            <form method="POST" action="{{ route('doctor.prescriptions.cancel', $prescription) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600" onclick="return confirm('Are you sure you want to cancel this prescription? This action cannot be undone.')">
                                    Cancel Prescription
                                </button>
                            </form>
                        </div>
                    @elseif ($prescription->status === 'on_hold' && $prescription->prescribed_by === Auth::id())
                        <div class="mt-6">
                            <form method="POST" action="{{ route('doctor.prescriptions.reactivate', $prescription) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600" onclick="return confirm('Are you sure you want to reactivate this prescription?')">
                                    Reactivate Prescription
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            @if ($prescription->treatment)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-semibold mb-4">Associated Treatment</h3>
                        <div class="mb-4">
                            <p><span class="font-medium">Diagnosis:</span> {{ $prescription->treatment->diagnosis }}</p>
                            <p><span class="font-medium">Treatment Status:</span> 
                                <span class="px-2 py-1 rounded-full text-xs 
                                    {{ $prescription->treatment->status === 'active' ? 'bg-green-100 text-green-800' : 
                                    ($prescription->treatment->status === 'completed' ? 'bg-blue-100 text-blue-800' : 
                                    ($prescription->treatment->status === 'discontinued' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                    {{ ucfirst($prescription->treatment->status) }}
                                </span>
                            </p>
                            <p class="mt-2"><span class="font-medium">Created By:</span> Dr. {{ $prescription->treatment->creator->name }} on {{ $prescription->treatment->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="flex justify-end">
                            <a href="{{ route('doctor.treatments.show', $prescription->treatment) }}" class="px-4 py-2 bg-indigo-500 text-white rounded-md hover:bg-indigo-600 text-sm">
                                View Treatment Details
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Print button for the prescription -->
            <div class="flex justify-center mt-6">
                <button onclick="window.print();" class="px-6 py-3 bg-gray-800 text-white rounded-md hover:bg-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print Prescription
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
            .prescription-print-header {
                display: block !important;
                text-align: center;
                margin-bottom: 20px;
                padding-bottom: 10px;
                border-bottom: 2px solid #000;
            }

            .prescription-print-header h1 {
                font-size: 18pt;
                font-weight: bold;
            }

            .prescription-print-header p {
                font-size: 10pt;
                margin: 5px 0;
            }
        }
    </style>

    <!-- Print header (only visible when printing) -->
    <div class="prescription-print-header" style="display: none;">
        <h1>Hospital Emergency Medical Center</h1>
        <p>123 Healthcare Avenue, Medical City</p>
        <p>Phone: (555) 123-4567 | Fax: (555) 765-4321</p>
        <p>PRESCRIPTION</p>
    </div>
</x-app-layout>
