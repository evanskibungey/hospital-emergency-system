<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Follow-up Appointment Details') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('doctor.follow-up-appointments.index') }}" class="px-4 py-2 bg-gray-200 rounded-md text-gray-700 hover:bg-gray-300">
                    {{ __('Back to Appointments') }}
                </a>
                @if (in_array($followUpAppointment->status, ['scheduled', 'confirmed']))
                    <a href="{{ route('doctor.follow-up-appointments.edit', $followUpAppointment) }}" class="px-4 py-2 bg-indigo-500 rounded-md text-white hover:bg-indigo-600">
                        {{ __('Edit Appointment') }}
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
                        <h3 class="text-lg font-semibold">Appointment Information</h3>
                        <div>
                            <span class="px-3 py-1 rounded-full text-xs 
                                {{ $followUpAppointment->status === 'scheduled' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($followUpAppointment->status === 'confirmed' ? 'bg-green-100 text-green-800' : 
                                   ($followUpAppointment->status === 'completed' ? 'bg-blue-100 text-blue-800' : 
                                   ($followUpAppointment->status === 'no_show' ? 'bg-purple-100 text-purple-800' : 'bg-red-100 text-red-800'))) }}">
                                {{ ucfirst($followUpAppointment->status) }}
                            </span>

                            @if ($followUpAppointment->is_urgent)
                                <span class="ml-2 px-3 py-1 rounded-full text-xs bg-red-100 text-red-800">Urgent</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p><span class="font-medium">Patient:</span> {{ $followUpAppointment->patient->full_name }}</p>
                                <p><span class="font-medium">Age:</span> {{ $followUpAppointment->patient->date_of_birth->age }} years</p>
                                <p><span class="font-medium">MRN:</span> {{ $followUpAppointment->patient->medical_record_number }}</p>
                                @if ($followUpAppointment->visit)
                                    <p><span class="font-medium">Related Visit:</span> #{{ $followUpAppointment->visit_id }} - {{ $followUpAppointment->visit->chief_complaint }}</p>
                                @endif
                            </div>
                            <div>
                                <p><span class="font-medium">Date & Time:</span> {{ $followUpAppointment->appointment_time->format('l, M d, Y g:i A') }}</p>
                                <p><span class="font-medium">Duration:</span> {{ $followUpAppointment->estimated_duration_minutes }} minutes</p>
                                <p><span class="font-medium">Scheduled by:</span> {{ $followUpAppointment->scheduledBy->name }}</p>
                                <p><span class="font-medium">Scheduled on:</span> {{ $followUpAppointment->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6 border border-gray-200 rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                            <h4 class="font-medium text-gray-700">Appointment Details</h4>
                        </div>
                        <div class="p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <p><span class="font-medium">Provider:</span> 
                                        @if ($followUpAppointment->doctor)
                                            Dr. {{ $followUpAppointment->doctor->name }}
                                        @else
                                            Not specified
                                        @endif
                                    </p>
                                    @if ($followUpAppointment->department)
                                        <p><span class="font-medium">Department:</span> {{ $followUpAppointment->department }}</p>
                                    @endif
                                    @if ($followUpAppointment->specialty)
                                        <p><span class="font-medium">Specialty:</span> {{ $followUpAppointment->specialty }}</p>
                                    @endif
                                </div>
                                <div>
                                    <p><span class="font-medium">Status:</span> {{ ucfirst($followUpAppointment->status) }}</p>
                                    <p><span class="font-medium">Urgent Follow-up:</span> {{ $followUpAppointment->is_urgent ? 'Yes' : 'No' }}</p>
                                </div>
                            </div>
                            
                            <div class="mt-3">
                                <p class="font-medium">Reason for Visit:</p>
                                <div class="mt-1 p-3 bg-gray-50 rounded">{{ $followUpAppointment->reason_for_visit }}</div>
                            </div>
                            
                            @if ($followUpAppointment->special_instructions)
                                <div class="mt-3">
                                    <p class="font-medium">Special Instructions:</p>
                                    <div class="mt-1 p-3 bg-gray-50 rounded">{{ $followUpAppointment->special_instructions }}</div>
                                </div>
                            @endif
                            
                            @if ($followUpAppointment->notes)
                                <div class="mt-3">
                                    <p class="font-medium">Internal Notes:</p>
                                    <div class="mt-1 p-3 bg-gray-50 rounded">{{ $followUpAppointment->notes }}</div>
                                </div>
                            @endif

                            @if ($followUpAppointment->discharge)
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <p class="font-medium">Related to Discharge:</p>
                                    <p class="mt-1">This appointment was scheduled as part of the discharge process on {{ $followUpAppointment->discharge->discharged_at->format('M d, Y') }}.</p>
                                    <div class="mt-2">
                                        <a href="{{ route('doctor.discharges.show', $followUpAppointment->discharge) }}" class="text-indigo-600 hover:text-indigo-900">
                                            View Discharge Details
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if (in_array($followUpAppointment->status, ['scheduled', 'confirmed']))
                        <div class="flex flex-wrap gap-3 mt-6">
                            @if ($followUpAppointment->status === 'scheduled')
                                <form method="POST" action="{{ route('doctor.follow-up-appointments.confirm', $followUpAppointment) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                                        Confirm Appointment
                                    </button>
                                </form>
                            @endif
                            
                            <form method="POST" action="{{ route('doctor.follow-up-appointments.complete', $followUpAppointment) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600" onclick="return confirm('Are you sure you want to mark this appointment as completed?')">
                                    Mark as Completed
                                </button>
                            </form>
                            
                            <form method="POST" action="{{ route('doctor.follow-up-appointments.no-show', $followUpAppointment) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-4 py-2 bg-purple-500 text-white rounded-md hover:bg-purple-600" onclick="return confirm('Are you sure you want to mark this appointment as no-show?')">
                                    Mark as No-Show
                                </button>
                            </form>
                            
                            <form method="POST" action="{{ route('doctor.follow-up-appointments.cancel', $followUpAppointment) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600" onclick="return confirm('Are you sure you want to cancel this appointment? This action cannot be undone.')">
                                    Cancel Appointment
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Print button for the appointment -->
            <div class="flex justify-center mt-6">
                <a href="{{ route('doctor.follow-up-appointments.print', $followUpAppointment) }}" class="px-6 py-3 bg-gray-800 text-white rounded-md hover:bg-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print Appointment Card
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
