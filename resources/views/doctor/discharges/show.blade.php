<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Discharge Details') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('doctor.visits.show', $discharge->visit) }}" class="px-4 py-2 bg-gray-200 rounded-md text-gray-700 hover:bg-gray-300">
                    {{ __('Back to Visit') }}
                </a>
                <a href="{{ route('doctor.follow-up-appointments.create') }}?discharge_id={{ $discharge->id }}" class="px-4 py-2 bg-green-500 rounded-md text-white hover:bg-green-600">
                    {{ __('Schedule Follow-up') }}
                </a>
                <a href="{{ route('doctor.discharges.print', $discharge) }}" class="px-4 py-2 bg-blue-500 rounded-md text-white hover:bg-blue-600">
                    {{ __('Print Instructions') }}
                </a>
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-lg font-semibold">Discharge Information</h3>
                        <div>
                            <span class="px-3 py-1 rounded-full text-xs bg-green-100 text-green-800">Discharged</span>
                            @if ($discharge->instructions_provided)
                                <span class="ml-2 px-3 py-1 rounded-full text-xs bg-blue-100 text-blue-800">Instructions Provided</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p><span class="font-medium">Patient:</span> {{ $discharge->patient->full_name }}</p>
                                <p><span class="font-medium">Age:</span> {{ $discharge->patient->date_of_birth->age }} years</p>
                                <p><span class="font-medium">MRN:</span> {{ $discharge->patient->medical_record_number }}</p>
                                <p><span class="font-medium">Visit:</span> #{{ $discharge->visit_id }} - {{ $discharge->visit->chief_complaint }}</p>
                            </div>
                            <div>
                                <p><span class="font-medium">Discharged by:</span> Dr. {{ $discharge->dischargedBy->name }}</p>
                                <p><span class="font-medium">Date Discharged:</span> {{ $discharge->discharged_at->format('M d, Y g:i A') }}</p>
                                <p><span class="font-medium">Disposition:</span> {{ ucwords(str_replace('_', ' ', $discharge->discharge_disposition)) }}</p>
                                @if ($discharge->discharge_disposition === 'transfer_to_facility')
                                    <p><span class="font-medium">Destination Facility:</span> {{ $discharge->destination_facility }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mb-6 border border-gray-200 rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                            <h4 class="font-medium text-gray-700">Discharge Diagnosis and Summary</h4>
                        </div>
                        <div class="p-4">
                            <div class="mb-4">
                                <p class="font-medium">Discharge Diagnosis:</p>
                                <div class="mt-1 p-3 bg-gray-50 rounded">{{ $discharge->discharge_diagnosis }}</div>
                            </div>
                            
                            <div class="mb-4">
                                <p class="font-medium">Discharge Summary:</p>
                                <div class="mt-1 p-3 bg-gray-50 rounded">{{ $discharge->discharge_summary }}</div>
                            </div>

                            @if ($discharge->notes)
                                <div>
                                    <p class="font-medium">Additional Notes (not shown to patient):</p>
                                    <div class="mt-1 p-3 bg-gray-50 rounded">{{ $discharge->notes }}</div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mb-6 border border-gray-200 rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                            <h4 class="font-medium text-gray-700">Discharge Instructions</h4>
                        </div>
                        <div class="p-4">
                            <div class="mb-4">
                                <p class="font-medium">General Instructions:</p>
                                <div class="mt-1 p-3 bg-gray-50 rounded">{{ $discharge->discharge_instructions }}</div>
                            </div>
                            
                            @if ($discharge->medications_at_discharge)
                                <div class="mb-4">
                                    <p class="font-medium">Medications:</p>
                                    <div class="mt-1 p-3 bg-gray-50 rounded">{{ $discharge->medications_at_discharge }}</div>
                                </div>
                            @endif
                            
                            @if ($discharge->activity_restrictions)
                                <div class="mb-4">
                                    <p class="font-medium">Activity Restrictions:</p>
                                    <div class="mt-1 p-3 bg-gray-50 rounded">{{ $discharge->activity_restrictions }}</div>
                                </div>
                            @endif
                            
                            @if ($discharge->diet_instructions)
                                <div class="mb-4">
                                    <p class="font-medium">Diet Instructions:</p>
                                    <div class="mt-1 p-3 bg-gray-50 rounded">{{ $discharge->diet_instructions }}</div>
                                </div>
                            @endif
                            
                            @if ($discharge->follow_up_instructions)
                                <div>
                                    <p class="font-medium">Follow-up Instructions:</p>
                                    <div class="mt-1 p-3 bg-gray-50 rounded">{{ $discharge->follow_up_instructions }}</div>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if ($prescriptions && $prescriptions->count() > 0)
                        <div class="mb-6 border border-gray-200 rounded-lg overflow-hidden">
                            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                                <h4 class="font-medium text-gray-700">Prescriptions at Discharge</h4>
                            </div>
                            <div class="p-4">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Medication</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dosage</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Frequency</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Instructions</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($prescriptions as $prescription)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ $prescription->medication_name }}
                                                        @if ($prescription->is_controlled_substance)
                                                            <span class="ml-1 px-2 py-0.5 bg-yellow-100 text-yellow-800 rounded-full text-xs">Controlled</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ $prescription->dosage }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ $prescription->frequency }}
                                                    </td>
                                                    <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                                        {{ $prescription->instructions }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        <a href="{{ route('doctor.prescriptions.show', $prescription) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($discharge->followUpAppointments && $discharge->followUpAppointments->count() > 0)
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                                <h4 class="font-medium text-gray-700">Follow-up Appointments</h4>
                            </div>
                            <div class="p-4">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Provider/Department</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($discharge->followUpAppointments as $appointment)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ $appointment->appointment_time->format('M d, Y g:i A') }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        @if ($appointment->doctor)
                                                            Dr. {{ $appointment->doctor->name }}
                                                        @else
                                                            {{ $appointment->department ?: $appointment->specialty ?: 'Not specified' }}
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                                        {{ $appointment->reason_for_visit }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                        <span class="px-2 py-1 rounded-full text-xs {{ $appointment->status === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                            {{ ucfirst($appointment->status) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        <a href="{{ route('doctor.follow-up-appointments.show', $appointment) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (!$discharge->instructions_provided)
                        <div class="flex justify-end mt-6">
                            <form method="POST" action="{{ route('doctor.discharges.update', $discharge) }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="discharge_diagnosis" value="{{ $discharge->discharge_diagnosis }}">
                                <input type="hidden" name="discharge_summary" value="{{ $discharge->discharge_summary }}">
                                <input type="hidden" name="discharge_instructions" value="{{ $discharge->discharge_instructions }}">
                                <input type="hidden" name="medications_at_discharge" value="{{ $discharge->medications_at_discharge }}">
                                <input type="hidden" name="activity_restrictions" value="{{ $discharge->activity_restrictions }}">
                                <input type="hidden" name="diet_instructions" value="{{ $discharge->diet_instructions }}">
                                <input type="hidden" name="follow_up_instructions" value="{{ $discharge->follow_up_instructions }}">
                                <input type="hidden" name="discharge_disposition" value="{{ $discharge->discharge_disposition }}">
                                <input type="hidden" name="destination_facility" value="{{ $discharge->destination_facility }}">
                                <input type="hidden" name="notes" value="{{ $discharge->notes }}">
                                <input type="hidden" name="instructions_provided" value="1">
                                
                                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                                    Mark Instructions as Provided to Patient
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
