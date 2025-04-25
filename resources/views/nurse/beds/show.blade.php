<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Bed Details') }}: {{ $bed->location }} - {{ $bed->bed_number }}
            </h2>
            <div>
                <a href="{{ route('nurse.beds.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300">
                    {{ __('Back to Beds') }}
                </a>
                <a href="{{ route('nurse.beds.edit', $bed->id) }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300">
                    {{ __('Edit Bed') }}
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

            @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p>{{ session('error') }}</p>
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ __('Bed Information') }}
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Bed Number</h4>
                                <p class="text-base">{{ $bed->bed_number }}</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Location</h4>
                                <p class="text-base">{{ $bed->location }}</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Type</h4>
                                <p class="text-base">{{ ucfirst($bed->type) }}</p>
                            </div>
                        </div>
                        <div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Status</h4>
                                <p class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($bed->status == 'available') bg-green-100 text-green-800 
                                    @elseif($bed->status == 'occupied') bg-red-100 text-red-800 
                                    @elseif($bed->status == 'cleaning') bg-yellow-100 text-yellow-800 
                                    @elseif($bed->status == 'maintenance') bg-gray-100 text-gray-800 
                                    @else bg-blue-100 text-blue-800 @endif">
                                    {{ ucfirst($bed->status) }}
                                </p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Active</h4>
                                <p class="text-base">{{ $bed->is_active ? 'Yes' : 'No' }}</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Added On</h4>
                                <p class="text-base">{{ $bed->created_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    @if($bed->notes)
                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-gray-500">Notes</h4>
                        <div class="mt-1 p-4 bg-gray-50 rounded-md">
                            <p class="text-sm text-gray-700 whitespace-pre-line">{{ $bed->notes }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Quick Actions -->
                    <div class="mt-6 flex flex-wrap gap-2">
                        @if($bed->status !== 'occupied')
                            @if($bed->status === 'cleaning')
                            <form method="POST" action="{{ route('nurse.beds.mark-clean', $bed->id) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    Mark as Clean & Available
                                </button>
                            </form>
                            @elseif($bed->status !== 'maintenance')
                            <form method="POST" action="{{ route('nurse.beds.mark-cleaning', $bed->id) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                    Mark for Cleaning
                                </button>
                            </form>
                            @endif
                        
                            @if($bed->status !== 'maintenance')
                            <button type="button" onclick="showMaintenanceForm()"
                                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                Mark for Maintenance
                            </button>
                            @endif
                        @endif
                    </div>

                    <!-- Maintenance Form (Hidden by Default) -->
                    <div id="maintenance-form" class="hidden mt-4 p-4 bg-gray-50 rounded-md">
                        <h4 class="text-md font-medium text-gray-700 mb-2">Mark Bed for Maintenance</h4>
                        <form method="POST" action="{{ route('nurse.beds.mark-maintenance', $bed->id) }}">
                            @csrf
                            @method('PATCH')
                            <div class="mb-3">
                                <label for="maintenance_notes" class="block text-sm font-medium text-gray-700">Maintenance Notes*</label>
                                <textarea name="maintenance_notes" id="maintenance_notes" rows="2" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                                <p class="text-gray-500 text-xs mt-1">Please describe the maintenance issue</p>
                            </div>
                            <div class="flex justify-end">
                                <button type="button" onclick="hideMaintenanceForm()"
                                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring ring-blue-200 active:text-gray-800 active:bg-gray-50 mr-2">
                                    Cancel
                                </button>
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300">
                                    Submit Maintenance Request
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Current Patient Section (if bed is occupied) -->
            @if($bed->status === 'occupied' && $bed->currentVisit)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ __('Current Patient') }}
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Patient Name</h4>
                                <p class="text-base">{{ $bed->currentVisit->patient->first_name }} {{ $bed->currentVisit->patient->last_name }}</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Medical Record Number</h4>
                                <p class="text-base">{{ $bed->currentVisit->patient->medical_record_number ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Gender / Age</h4>
                                <p class="text-base">
                                    {{ ucfirst($bed->currentVisit->patient->gender ?? 'N/A') }} / 
                                    {{ $bed->currentVisit->patient->date_of_birth ? $bed->currentVisit->patient->date_of_birth->age . ' years' : 'N/A' }}
                                </p>
                            </div>
                        </div>
                        <div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Chief Complaint</h4>
                                <p class="text-base">{{ $bed->currentVisit->chief_complaint ?? 'Not recorded' }}</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Assigned To</h4>
                                <p class="text-base">{{ $bed->currentVisit->assignedTo ? $bed->currentVisit->assignedTo->name : 'Not assigned' }}</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Bed Assigned At</h4>
                                <p class="text-base">{{ $bed->currentVisit->bed_assigned_at ? $bed->currentVisit->bed_assigned_at->format('M d, Y H:i') : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 flex flex-wrap gap-2">
                        <a href="{{ route('nurse.vital-signs.index', $bed->currentVisit->id) }}"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300">
                            View Vital Signs
                        </a>
                        <a href="{{ route('nurse.medication-schedules.index', $bed->currentVisit->id) }}"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300">
                            View Medications
                        </a>
                        <a href="{{ route('nurse.bed-assignments.edit', $bed->currentVisit->id) }}"
                            class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:border-yellow-900 focus:ring ring-yellow-300">
                            Transfer Patient
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Recent Patient History -->
            @if($bed->visits->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ __('Recent Patient History') }}
                    </h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Patient
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Assigned At
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Chief Complaint
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($bed->visits->sortByDesc('bed_assigned_at')->take(10) as $visit)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $visit->patient->first_name }} {{ $visit->patient->last_name }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            MRN: {{ $visit->patient->medical_record_number ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $visit->bed_assigned_at ? $visit->bed_assigned_at->format('M d, Y H:i') : 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $visit->chief_complaint ?? 'Not recorded' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($visit->status == 'waiting') bg-blue-100 text-blue-800 
                                            @elseif($visit->status == 'in_progress') bg-green-100 text-green-800 
                                            @elseif($visit->status == 'discharged') bg-gray-100 text-gray-800
                                            @else bg-purple-100 text-purple-800 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $visit->status)) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <script>
        function showMaintenanceForm() {
            document.getElementById('maintenance-form').classList.remove('hidden');
        }

        function hideMaintenanceForm() {
            document.getElementById('maintenance-form').classList.add('hidden');
        }
    </script>
</x-app-layout>