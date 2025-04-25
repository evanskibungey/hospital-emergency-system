<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Consultation Details') }}
            </h2>
            <a href="{{ route('doctor.consultations.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 py-1 px-3 rounded text-sm">
                Back to Consultations
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alerts -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Consultation Status -->
            <div class="mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                    {{ $consultationRequest->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                    ($consultationRequest->status === 'accepted' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                    Status: {{ ucfirst($consultationRequest->status) }}
                                </span>
                                <div class="mt-2 text-sm text-gray-600">
                                    Requested at: {{ $consultationRequest->requested_at->format('M d, Y h:i A') }}
                                    @if($consultationRequest->accepted_at)
                                        <br>Accepted at: {{ $consultationRequest->accepted_at->format('M d, Y h:i A') }}
                                    @endif
                                    @if($consultationRequest->completed_at)
                                        <br>Completed at: {{ $consultationRequest->completed_at->format('M d, Y h:i A') }}
                                    @endif
                                </div>
                            </div>
                            @if($consultationRequest->isPending())
                                <form method="POST" action="{{ route('doctor.consultations.accept', $consultationRequest) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded">
                                        Accept Consultation
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Patient Information -->
                <div class="md:col-span-1">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Patient Information</h3>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <div class="mb-4">
                                <h4 class="font-medium text-gray-700">Name</h4>
                                <p>{{ $consultationRequest->visit->patient->first_name }} {{ $consultationRequest->visit->patient->last_name }}</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="font-medium text-gray-700">Age/DOB</h4>
                                <p>{{ $consultationRequest->visit->patient->date_of_birth->age }} years ({{ $consultationRequest->visit->patient->date_of_birth->format('M d, Y') }})</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="font-medium text-gray-700">Medical Record Number</h4>
                                <p>{{ $consultationRequest->visit->patient->medical_record_number ?? 'Not available' }}</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="font-medium text-gray-700">Gender</h4>
                                <p>{{ ucfirst($consultationRequest->visit->patient->gender ?? 'Not specified') }}</p>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-700">Location</h4>
                                <p>{{ $consultationRequest->visit->bed ? $consultationRequest->visit->bed->location . ' - Bed ' . $consultationRequest->visit->bed->bed_number : 'No bed assigned' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Visit Information -->
                <div class="md:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Visit Information</h3>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="mb-4">
                                    <h4 class="font-medium text-gray-700">Chief Complaint</h4>
                                    <p>{{ $consultationRequest->visit->chief_complaint }}</p>
                                </div>
                                <div class="mb-4">
                                    <h4 class="font-medium text-gray-700">Status</h4>
                                    <p>{{ ucfirst($consultationRequest->visit->status) }}</p>
                                </div>
                                <div class="mb-4">
                                    <h4 class="font-medium text-gray-700">Priority</h4>
                                    <p>{{ ucfirst($consultationRequest->visit->priority) }}</p>
                                </div>
                                <div class="mb-4">
                                    <h4 class="font-medium text-gray-700">Department</h4>
                                    <p>{{ $consultationRequest->visit->department ?? 'Not assigned' }}</p>
                                </div>
                            </div>
                            <div class="mb-4">
                                <h4 class="font-medium text-gray-700">Visit Notes</h4>
                                <p>{{ $consultationRequest->visit->notes ?? 'No notes available' }}</p>
                            </div>
                            
                            <!-- Latest Vital Signs -->
                            <div class="mt-6">
                                <h4 class="font-medium text-gray-700 mb-2">Latest Vital Signs</h4>
                                @if($consultationRequest->visit->latestVitalSigns)
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        <div class="bg-gray-50 p-3 rounded">
                                            <span class="text-sm text-gray-500">Temperature</span>
                                            <p class="text-lg font-medium">{{ $consultationRequest->visit->latestVitalSigns->temperature }}°C</p>
                                        </div>
                                        <div class="bg-gray-50 p-3 rounded">
                                            <span class="text-sm text-gray-500">Blood Pressure</span>
                                            <p class="text-lg font-medium">{{ $consultationRequest->visit->latestVitalSigns->blood_pressure }}</p>
                                        </div>
                                        <div class="bg-gray-50 p-3 rounded">
                                            <span class="text-sm text-gray-500">Heart Rate</span>
                                            <p class="text-lg font-medium">{{ $consultationRequest->visit->latestVitalSigns->heart_rate }} bpm</p>
                                        </div>
                                        <div class="bg-gray-50 p-3 rounded">
                                            <span class="text-sm text-gray-500">Respiratory Rate</span>
                                            <p class="text-lg font-medium">{{ $consultationRequest->visit->latestVitalSigns->respiratory_rate ?? 'N/A' }}</p>
                                        </div>
                                        <div class="bg-gray-50 p-3 rounded">
                                            <span class="text-sm text-gray-500">Oxygen Saturation</span>
                                            <p class="text-lg font-medium">{{ $consultationRequest->visit->latestVitalSigns->oxygen_saturation ?? 'N/A' }}%</p>
                                        </div>
                                        <div class="bg-gray-50 p-3 rounded">
                                            <span class="text-sm text-gray-500">Pain Score</span>
                                            <p class="text-lg font-medium">{{ $consultationRequest->visit->latestVitalSigns->pain_score ?? 'N/A' }}/10</p>
                                        </div>
                                        <div class="bg-gray-50 p-3 rounded">
                                            <span class="text-sm text-gray-500">Blood Glucose</span>
                                            <p class="text-lg font-medium">{{ $consultationRequest->visit->latestVitalSigns->blood_glucose ?? 'N/A' }}</p>
                                        </div>
                                        <div class="bg-gray-50 p-3 rounded">
                                            <span class="text-sm text-gray-500">Recorded</span>
                                            <p class="text-sm font-medium">{{ $consultationRequest->visit->latestVitalSigns->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-gray-500">No vital signs recorded</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Consultation Details -->
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Consultation Details</h3>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="mb-6">
                            <h4 class="font-medium text-gray-700 mb-2">Requested by</h4>
                            <p>{{ $consultationRequest->requester->name }}</p>
                        </div>
                        <div class="mb-6">
                            <h4 class="font-medium text-gray-700 mb-2">Reason for consultation</h4>
                            <div class="bg-gray-50 p-4 rounded">
                                <p>{{ $consultationRequest->reason }}</p>
                            </div>
                        </div>
                        @if($consultationRequest->notes)
                            <div class="mb-6">
                                <h4 class="font-medium text-gray-700 mb-2">Additional Notes</h4>
                                <div class="bg-gray-50 p-4 rounded">
                                    <p>{{ $consultationRequest->notes }}</p>
                                </div>
                            </div>
                        @endif
                        
                        @if($consultationRequest->isAccepted() && !$consultationRequest->isCompleted())
                            <div class="mt-8">
                                <h4 class="font-medium text-gray-700 mb-2">Complete Consultation</h4>
                                <form method="POST" action="{{ route('doctor.consultations.complete', $consultationRequest) }}">
                                    @csrf
                                    @method('PATCH')
                                    <div class="mb-4">
                                        <label for="response" class="block text-sm font-medium text-gray-700 mb-1">Consultation Response</label>
                                        <textarea
                                            id="response"
                                            name="response"
                                            rows="6"
                                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                            placeholder="Enter your consultation findings, recommendations, and plan..."
                                            required
                                        >{{ old('response') }}</textarea>
                                    </div>
                                    <div class="flex justify-end">
                                        <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 text-white py-2 px-4 rounded">
                                            Complete Consultation
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endif
                        
                        @if($consultationRequest->isCompleted())
                            <div class="mt-8">
                                <h4 class="font-medium text-gray-700 mb-2">Consultation Response</h4>
                                <div class="bg-gray-50 p-4 rounded">
                                    <p>{{ $consultationRequest->response }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
