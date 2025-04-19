<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Patient Details') }}
            </h2>
            <div>
                <a href="{{ route('reception.visits.create', $patient) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
                    {{ __('Register Visit') }}
                </a>
                <a href="{{ route('reception.visitors.create', $patient) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                    {{ __('Register Visitor') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Patient Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="bg-gray-50 p-4 rounded-md">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-600">Name</p>
                                        <p class="font-medium">{{ $patient->full_name }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Date of Birth</p>
                                        <p class="font-medium">{{ \Carbon\Carbon::parse($patient->date_of_birth)->format('M d, Y') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Gender</p>
                                        <p class="font-medium">{{ ucfirst($patient->gender) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Phone Number</p>
                                        <p class="font-medium">{{ $patient->phone_number }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Email</p>
                                        <p class="font-medium">{{ $patient->email ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 bg-gray-50 p-4 rounded-md">
                                <h4 class="font-medium mb-2">Address</h4>
                                <p>{{ $patient->address ?? 'N/A' }}</p>
                                <p>{{ $patient->city ?? '' }}, {{ $patient->state ?? '' }} {{ $patient->postal_code ?? '' }}</p>
                            </div>
                        </div>

                        <div>
                            <div class="bg-gray-50 p-4 rounded-md">
                                <h4 class="font-medium mb-2">Emergency Contact</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-600">Name</p>
                                        <p class="font-medium">{{ $patient->emergency_contact_name ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Phone</p>
                                        <p class="font-medium">{{ $patient->emergency_contact_phone ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Relationship</p>
                                        <p class="font-medium">{{ $patient->emergency_contact_relationship ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 bg-gray-50 p-4 rounded-md">
                                <h4 class="font-medium mb-2">Insurance Information</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-600">Provider</p>
                                        <p class="font-medium">{{ $patient->insurance_provider ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Policy Number</p>
                                        <p class="font-medium">{{ $patient->insurance_policy_number ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 bg-gray-50 p-4 rounded-md">
                        <h4 class="font-medium mb-2">Medical Information</h4>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Medical History</p>
                                <p>{{ $patient->medical_history ?? 'None recorded' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Allergies</p>
                                <p>{{ $patient->allergies ?? 'None recorded' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Current Medications</p>
                                <p>{{ $patient->current_medications ?? 'None recorded' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Visit History</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Chief Complaint
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Department
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Priority
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($patient->visits as $visit)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $visit->check_in_time->format('M d, Y') }}</div>
                                            <div class="text-sm text-gray-500">{{ $visit->check_in_time->format('H:i') }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ $visit->chief_complaint }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($visit->status === 'waiting')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Waiting
                                                </span>
                                            @elseif ($visit->status === 'in_progress')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    In Progress
                                                </span>
                                            @elseif ($visit->status === 'treated')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Treated
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    Discharged
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $visit->department ?? 'Unassigned' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($visit->priority === 'critical')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Critical
                                                </span>
                                            @elseif ($visit->priority === 'high')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                                    High
                                                </span>
                                            @elseif ($visit->priority === 'medium')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Medium
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Low
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                            No visit history found for this patient.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>