<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Treatment Plan Details') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('doctor.visits.show', $treatment->visit) }}" class="px-4 py-2 bg-gray-200 rounded-md text-gray-700 hover:bg-gray-300">
                    {{ __('Back to Visit') }}
                </a>
                @if ($treatment->status === 'draft' || $treatment->status === 'active')
                    <a href="{{ route('doctor.treatments.edit', $treatment) }}" class="px-4 py-2 bg-indigo-500 rounded-md text-white hover:bg-indigo-600">
                        {{ __('Edit Treatment') }}
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                        <h3 class="font-semibold text-lg mb-2">Patient Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p><span class="font-medium">Name:</span> {{ $treatment->visit->patient->full_name }}</p>
                                <p><span class="font-medium">Age:</span> {{ $treatment->visit->patient->date_of_birth->age }} years</p>
                                <p><span class="font-medium">MRN:</span> {{ $treatment->visit->patient->medical_record_number }}</p>
                            </div>
                            <div>
                                <p><span class="font-medium">Chief Complaint:</span> {{ $treatment->visit->chief_complaint }}</p>
                                <p><span class="font-medium">Visit Status:</span> 
                                    <span class="px-2 py-1 rounded-full text-xs {{ $treatment->visit->is_critical ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $treatment->visit->is_critical ? 'CRITICAL' : 'STANDARD' }}
                                    </span>
                                </p>
                                <p><span class="font-medium">Check-in Time:</span> {{ $treatment->visit->check_in_time->format('M d, Y g:i A') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-3">Treatment Plan</h3>
                        <div class="flex justify-between items-center mb-3">
                            <div>
                                <span class="px-3 py-1 rounded-full text-xs 
                                    {{ $treatment->status === 'active' ? 'bg-green-100 text-green-800' : 
                                       ($treatment->status === 'completed' ? 'bg-blue-100 text-blue-800' : 
                                       ($treatment->status === 'discontinued' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                    {{ ucfirst($treatment->status) }}
                                </span>
                                <span class="text-sm text-gray-500 ml-2">
                                    Created by: Dr. {{ $treatment->creator->name }} on {{ $treatment->created_at->format('M d, Y g:i A') }}
                                </span>
                            </div>
                            <div>
                                @if ($treatment->status === 'active')
                                    <form method="POST" action="{{ route('doctor.treatments.complete', $treatment) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 text-sm" onclick="return confirm('Are you sure you want to mark this treatment as completed?')">
                                            Mark Complete
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('doctor.treatments.discontinue', $treatment) }}" class="inline ml-2">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 text-sm" onclick="return confirm('Are you sure you want to discontinue this treatment?')">
                                            Discontinue
                                        </button>
                                    </form>
                                @elseif ($treatment->status === 'draft')
                                    <a href="{{ route('doctor.treatments.edit', $treatment) }}" class="px-3 py-1 bg-indigo-500 text-white rounded-md hover:bg-indigo-600 text-sm">
                                        Edit Draft
                                    </a>
                                @endif
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <div>
                                <h4 class="font-medium text-gray-700 mb-1">Diagnosis:</h4>
                                <div class="p-3 bg-gray-50 rounded min-h-[100px]">
                                    {{ $treatment->diagnosis }}
                                </div>
                            </div>
                            
                            <div>
                                <h4 class="font-medium text-gray-700 mb-1">Treatment Status Timeline:</h4>
                                <div class="p-3 bg-gray-50 rounded">
                                    <ul class="list-disc list-inside space-y-1">
                                        <li>Created: {{ $treatment->created_at->format('M d, Y g:i A') }}</li>
                                        @if ($treatment->started_at)
                                            <li>Started: {{ $treatment->started_at->format('M d, Y g:i A') }}</li>
                                        @endif
                                        @if ($treatment->completed_at)
                                            <li>Completed: {{ $treatment->completed_at->format('M d, Y g:i A') }}</li>
                                        @endif
                                        @if ($treatment->updated_at->gt($treatment->created_at))
                                            <li>Last Updated: {{ $treatment->updated_at->format('M d, Y g:i A') }}</li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h4 class="font-medium text-gray-700 mb-1">Treatment Plan:</h4>
                            <div class="p-3 bg-gray-50 rounded min-h-[150px]">
                                {{ $treatment->treatment_plan }}
                            </div>
                        </div>
                        
                        @if ($treatment->notes)
                            <div class="mb-4">
                                <h4 class="font-medium text-gray-700 mb-1">Additional Notes:</h4>
                                <div class="p-3 bg-gray-50 rounded">
                                    {{ $treatment->notes }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Medical Notes Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Medical Notes</h3>
                        <a href="{{ route('doctor.medical-notes.create', ['visit' => $treatment->visit_id]) }}" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 text-sm">
                            Add Note
                        </a>
                    </div>

                    @if ($treatment->medicalNotes->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date/Time</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Content</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($treatment->medicalNotes as $note)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $note->created_at->format('M d, Y g:i A') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ ucfirst($note->note_type) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                Dr. {{ $note->creator->name }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500 max-w-md truncate">
                                                {{ Str::limit($note->content, 100) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <a href="{{ route('doctor.medical-notes.show', ['visit' => $treatment->visit_id, 'medicalNote' => $note->id]) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 italic">No medical notes have been added to this treatment yet.</p>
                    @endif
                </div>
            </div>

            <!-- Related Orders Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Lab Orders</h3>
                            <a href="{{ route('doctor.lab-orders.create', ['visit' => $treatment->visit_id]) }}" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 text-sm">
                                Order Lab Test
                            </a>
                        </div>

                        @if ($treatment->labOrders->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ordered Date</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Test Name</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Result</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($treatment->labOrders as $order)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $order->ordered_at->format('M d, Y g:i A') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $order->test_name }}
                                                    @if ($order->is_stat)
                                                        <span class="ml-2 px-2 py-0.5 bg-red-100 text-red-800 rounded-full text-xs">STAT</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    <span class="px-2 py-1 rounded-full text-xs 
                                                        {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                                           ($order->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 
                                                           ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                                        {{ ucfirst($order->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                                    {{ $order->result_summary ?? 'Pending' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <a href="{{ route('doctor.lab-orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500 italic">No lab orders have been created for this treatment yet.</p>
                        @endif
                    </div>

                    <div class="mt-8">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Imaging Orders</h3>
                            <a href="{{ route('doctor.imaging-orders.create', ['visit' => $treatment->visit_id]) }}" class="px-4 py-2 bg-purple-500 text-white rounded-md hover:bg-purple-600 text-sm">
                                Order Imaging
                            </a>
                        </div>

                        @if ($treatment->imagingOrders->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ordered Date</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Imaging Type</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Body Part</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($treatment->imagingOrders as $order)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $order->ordered_at->format('M d, Y g:i A') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $order->imaging_type }}
                                                    @if ($order->is_stat)
                                                        <span class="ml-2 px-2 py-0.5 bg-red-100 text-red-800 rounded-full text-xs">STAT</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $order->body_part }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    <span class="px-2 py-1 rounded-full text-xs 
                                                        {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                                           ($order->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 
                                                           ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                                        {{ ucfirst($order->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <a href="{{ route('doctor.imaging-orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500 italic">No imaging orders have been created for this treatment yet.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Prescriptions Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Prescriptions</h3>
                        <a href="{{ route('doctor.prescriptions.create', ['visit' => $treatment->visit_id]) }}" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 text-sm">
                            Write Prescription
                        </a>
                    </div>

                    @if ($treatment->prescriptions->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prescribed</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Medication</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dosage & Route</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($treatment->prescriptions as $prescription)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $prescription->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $prescription->medication_name }}
                                                @if ($prescription->is_controlled_substance)
                                                    <span class="ml-2 px-2 py-0.5 bg-yellow-100 text-yellow-800 rounded-full text-xs">Controlled</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $prescription->dosage }}, {{ $prescription->route }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <span class="px-2 py-1 rounded-full text-xs 
                                                    {{ $prescription->status === 'active' ? 'bg-green-100 text-green-800' : 
                                                       ($prescription->status === 'completed' ? 'bg-blue-100 text-blue-800' : 
                                                       ($prescription->status === 'cancelled' ? 'bg-red-100 text-red-800' : 
                                                       ($prescription->status === 'on_hold' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800'))) }}">
                                                    {{ ucfirst($prescription->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <a href="{{ route('doctor.prescriptions.show', $prescription) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 italic">No prescriptions have been created for this treatment yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
