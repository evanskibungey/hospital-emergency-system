<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nurse Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p>{{ session('success') }}</p>
            </div>
            @endif

            <!-- Active and Waiting Patients Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ __('Patients') }}
                        </h3>
                        <a href="{{ route('nurse.vital-signs.search-patient') }}"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Find Patient') }}
                        </a>
                    </div>

                    @if(count($activeVisits) > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Patient
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Chief Complaint
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Check-in Time
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Priority
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Last Vitals
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($activeVisits as $visit)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $visit->patient->first_name }} {{ $visit->patient->last_name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        MRN: {{ $visit->patient->medical_record_number ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $visit->chief_complaint ?? 'Not recorded' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $visit->check_in_time ? $visit->check_in_time->format('M d, Y H:i') : 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($visit->priority == 'high' || $visit->priority == 'critical') bg-red-100 text-red-800 
                                                @elseif($visit->priority == 'medium') bg-yellow-100 text-yellow-800 
                                                @else bg-green-100 text-green-800 @endif">
                                        {{ ucfirst($visit->priority) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
    @if($visit->status == 'waiting') bg-blue-100 text-blue-800 
    @elseif($visit->status == 'in_progress') bg-green-100 text-green-800 
    @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($visit->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        @if($visit->vitalSigns->count() > 0)
                                        {{ $visit->vitalSigns->first()->created_at->diffForHumans() }}
                                        @else
                                        <span class="text-red-500">Not recorded</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if($visit->status == 'waiting')
                                    <form method="POST" action="{{ route('nurse.assign-visit', $visit->id) }}"
                                        class="inline">
                                        @csrf
                                        <button type="submit" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            Assign to Me
                                        </button>
                                    </form>
                                    @endif

                                    <a href="{{ route('nurse.vital-signs.create', $visit->id) }}"
                                        class="text-indigo-600 hover:text-indigo-900 mr-3">
                                        Record Vitals
                                    </a>

                                    @if($visit->vitalSigns->count() > 0)
                                    <a href="{{ route('nurse.vital-signs.index', $visit->id) }}"
                                        class="text-indigo-600 hover:text-indigo-900">
                                        View History
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                        <div class="flex">
                            <div>
                                <p class="text-yellow-700">
                                    No patients currently in the system. Patients will appear here after reception
                                    registers them.
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Patients Needing Vitals Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ __('Patients Needing Vital Signs') }}
                    </h3>

                    @if(isset($needVitals) && count($needVitals) > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Patient
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Check-in Time
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Priority
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($needVitals as $visit)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $visit->patient->first_name }} {{ $visit->patient->last_name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $visit->check_in_time ? $visit->check_in_time->format('M d, Y H:i') : 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($visit->priority == 'high' || $visit->priority == 'critical') bg-red-100 text-red-800 
                                                @elseif($visit->priority == 'medium') bg-yellow-100 text-yellow-800 
                                                @else bg-green-100 text-green-800 @endif">
                                        {{ ucfirst($visit->priority) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($visit->status == 'waiting') bg-blue-100 text-blue-800 
                                                @elseif($visit->status == 'active') bg-green-100 text-green-800 
                                                @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($visit->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if($visit->status == 'waiting')
                                    <form method="POST" action="{{ route('nurse.assign-visit', $visit->id) }}"
                                        class="inline">
                                        @csrf
                                        <button type="submit" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            Assign to Me
                                        </button>
                                    </form>
                                    @endif
                                    <a href="{{ route('nurse.vital-signs.create', $visit->id) }}"
                                        class="text-indigo-600 hover:text-indigo-900">
                                        Record Vitals
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p class="text-gray-500">All patients have their vital signs recorded.</p>
                    @endif
                </div>
            </div>

            <!-- Recently Registered Patients -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ __('Recently Registered Patients') }}
                    </h3>

                    @if(isset($recentPatients) && count($recentPatients) > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Patient Name
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    MRN
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date of Birth
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Gender
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Registration Date
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentPatients as $patient)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $patient->first_name }} {{ $patient->last_name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $patient->medical_record_number ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $patient->date_of_birth ? $patient->date_of_birth->format('M d, Y') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ ucfirst($patient->gender ?? 'N/A') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $patient->created_at->format('M d, Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('nurse.vital-signs.patient-visits', $patient->id) }}"
                                        class="text-indigo-600 hover:text-indigo-900">
                                        View Visits
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p class="text-gray-500">No recently registered patients.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>