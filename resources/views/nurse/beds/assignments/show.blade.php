<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Bed Assignment Details') }}
            </h2>
            <div>
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

            @if (session('warning'))
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
                <p>{{ session('warning') }}</p>
            </div>
            @endif

            @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p>{{ session('error') }}</p>
            </div>
            @endif

            <!-- Bed Assignment Information -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ __('Current Bed Assignment') }}
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Patient Name</h4>
                                <p class="text-base">{{ $visit->patient->first_name }} {{ $visit->patient->last_name }}</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Medical Record Number</h4>
                                <p class="text-base">{{ $visit->patient->medical_record_number ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Gender / Age</h4>
                                <p class="text-base">
                                    {{ ucfirst($visit->patient->gender ?? 'N/A') }} / 
                                    {{ $visit->patient->date_of_birth ? $visit->patient->date_of_birth->age . ' years' : 'N/A' }}
                                </p>
                            </div>
                        </div>
                        <div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Assigned Bed</h4>
                                <p class="text-base font-medium">{{ $visit->bed->location }} - {{ $visit->bed->bed_number }}</p>
                                <p class="text-sm text-gray-500">{{ ucfirst($visit->bed->type) }} bed</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Assigned On</h4>
                                <p class="text-base">{{ $visit->bed_assigned_at ? $visit->bed_assigned_at->format('M d, Y H:i') : 'N/A' }}</p>
                                <p class="text-sm text-gray-500">{{ $visit->bed_assigned_at ? $visit->bed_assigned_at->diffForHumans() : '' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center mt-6 space-x-3">
                        <a href="{{ route('nurse.bed-assignments.edit', $visit->id) }}"
                            class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:border-yellow-900 focus:ring ring-yellow-300">
                            Transfer Patient
                        </a>
                        
                        <button type="button" onclick="showUnassignForm()"
                            class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Unassign Bed
                        </button>
                        
                        <a href="{{ route('nurse.beds.show', $visit->bed_id) }}"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300">
                            View Bed Details
                        </a>
                    </div>

                    <!-- Unassign Bed Form (Hidden by Default) -->
                    <div id="unassign-form" class="hidden mt-4 p-4 bg-gray-50 rounded-md">
                        <h4 class="text-md font-medium text-gray-700 mb-2">Unassign Bed</h4>
                        <p class="text-sm text-gray-500 mb-3">This will make the bed available for cleaning and assignment to another patient.</p>
                        <form method="POST" action="{{ route('nurse.bed-assignments.destroy', $visit->id) }}">
                            @csrf
                            @method('DELETE')
                            <div class="mb-3">
                                <label for="notes" class="block text-sm font-medium text-gray-700">Unassignment Notes</label>
                                <textarea name="notes" id="notes" rows="2"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                                <p class="text-gray-500 text-xs mt-1">Reason for unassigning the bed</p>
                            </div>
                            <div class="flex justify-end">
                                <button type="button" onclick="hideUnassignForm()"
                                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring ring-blue-200 active:text-gray-800 active:bg-gray-50 mr-2">
                                    Cancel
                                </button>
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300">
                                    Confirm Unassignment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Visit Information -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ __('Visit Information') }}
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Chief Complaint</h4>
                                <p class="text-base">{{ $visit->chief_complaint ?? 'Not recorded' }}</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Priority</h4>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($visit->priority == 'high' || $visit->priority == 'critical') bg-red-100 text-red-800 
                                            @elseif($visit->priority == 'medium') bg-yellow-100 text-yellow-800 
                                            @else bg-green-100 text-green-800 @endif">
                                    {{ ucfirst($visit->priority) }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Check-in Time</h4>
                                <p class="text-base">{{ $visit->check_in_time ? $visit->check_in_time->format('M d, Y H:i') : 'N/A' }}</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Status</h4>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($visit->status == 'waiting') bg-blue-100 text-blue-800 
                                            @elseif($visit->status == 'in_progress') bg-green-100 text-green-800 
                                            @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $visit->status)) }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Assigned To</h4>
                                <p class="text-base">{{ $visit->assignedTo ? $visit->assignedTo->name : 'Not assigned' }}</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Department</h4>
                                <p class="text-base">{{ $visit->department ?? 'Not specified' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 flex flex-wrap gap-2">
                        <a href="{{ route('nurse.vital-signs.index', $visit->id) }}"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300">
                            View Vital Signs
                        </a>
                        <a href="{{ route('nurse.medication-schedules.index', $visit->id) }}"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300">
                            View Medications
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showUnassignForm() {
            document.getElementById('unassign-form').classList.remove('hidden');
        }

        function hideUnassignForm() {
            document.getElementById('unassign-form').classList.add('hidden');
        }
    </script>
</x-app-layout>