<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Maintenance Request Details') }}
            </h2>
            <div>
                @if(!$equipmentMaintenance->isCompleted())
                    <a href="{{ route('nurse.equipment-maintenance.edit', $equipmentMaintenance->id) }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-2">
                        {{ __('Edit Request') }}
                    </a>
                    <a href="{{ route('nurse.equipment-maintenance.complete', $equipmentMaintenance->id) }}"
                        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 mr-2">
                        {{ __('Complete') }}
                    </a>
                @endif
                <a href="{{ route('nurse.equipment-maintenance.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300">
                    {{ __('Back to List') }}
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Status Badge -->
                    <div class="mb-6 flex items-center justify-between">
                        <div>
                            <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full
                                @if($equipmentMaintenance->status == 'requested') bg-blue-100 text-blue-800
                                @elseif($equipmentMaintenance->status == 'scheduled') bg-green-100 text-green-800
                                @elseif($equipmentMaintenance->status == 'in_progress') bg-yellow-100 text-yellow-800
                                @elseif($equipmentMaintenance->status == 'completed') bg-indigo-100 text-indigo-800
                                @else bg-gray-100 text-gray-800 @endif">
                                Status: {{ ucfirst($equipmentMaintenance->status) }}
                            </span>
                            
                            <span class="ml-2 px-3 py-1 inline-flex text-sm font-semibold rounded-full
                                @if($equipmentMaintenance->priority == 'low') bg-blue-100 text-blue-800
                                @elseif($equipmentMaintenance->priority == 'medium') bg-yellow-100 text-yellow-800
                                @elseif($equipmentMaintenance->priority == 'high') bg-orange-100 text-orange-800
                                @else bg-red-100 text-red-800 @endif">
                                Priority: {{ ucfirst($equipmentMaintenance->priority) }}
                            </span>

                            <span class="ml-2 px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-purple-100 text-purple-800">
                                Type: {{ ucfirst($equipmentMaintenance->type) }}
                            </span>
                        </div>
                        @if($equipmentMaintenance->isOverdue())
                            <span class="px-3 py-1 bg-red-100 text-red-800 font-bold rounded-full">
                                OVERDUE
                            </span>
                        @endif
                    </div>

                    <!-- Equipment Information -->
                    <div class="mb-6 p-4 border rounded-lg bg-gray-50">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Equipment Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Name:</p>
                                <p class="text-md font-medium">{{ $equipmentMaintenance->equipment->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Model:</p>
                                <p class="text-md font-medium">{{ $equipmentMaintenance->equipment->model ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Serial Number:</p>
                                <p class="text-md font-medium">{{ $equipmentMaintenance->equipment->serial_number ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Category:</p>
                                <p class="text-md font-medium">{{ ucfirst(str_replace('_', ' ', $equipmentMaintenance->equipment->category)) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Current Status:</p>
                                <p class="text-md font-medium">{{ ucfirst($equipmentMaintenance->equipment->status) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Last Maintenance:</p>
                                <p class="text-md font-medium">
                                    {{ $equipmentMaintenance->equipment->last_maintenance_date ? $equipmentMaintenance->equipment->last_maintenance_date->format('M d, Y') : 'Never' }}
                                </p>
                            </div>
                        </div>
                        <div class="mt-2">
                            <a href="{{ route('nurse.equipment.show', $equipmentMaintenance->equipment->id) }}" 
                               class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                View Full Equipment Details →
                            </a>
                        </div>
                    </div>

                    <!-- Request Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Request Details -->
                        <div class="border rounded-lg p-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Request Details</h3>
                            
                            <div class="mb-3">
                                <p class="text-sm text-gray-600">Requested By:</p>
                                <p class="text-md font-medium">{{ $equipmentMaintenance->requestedBy->name }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <p class="text-sm text-gray-600">Requested At:</p>
                                <p class="text-md font-medium">{{ $equipmentMaintenance->requested_at->format('M d, Y H:i') }}</p>
                                <p class="text-xs text-gray-500">{{ $equipmentMaintenance->requested_at->diffForHumans() }}</p>
                            </div>
                            
                            @if($equipmentMaintenance->scheduled_for)
                            <div class="mb-3">
                                <p class="text-sm text-gray-600">Scheduled For:</p>
                                <p class="text-md font-medium {{ $equipmentMaintenance->isOverdue() ? 'text-red-600' : '' }}">
                                    {{ $equipmentMaintenance->scheduled_for->format('M d, Y H:i') }}
                                    @if($equipmentMaintenance->isOverdue())
                                    <span class="text-red-600 font-bold">(OVERDUE)</span>
                                    @endif
                                </p>
                            </div>
                            @endif
                            
                            <div class="mb-3">
                                <p class="text-sm text-gray-600">Issue Description:</p>
                                <div class="mt-1 p-2 bg-gray-100 rounded">
                                    <p class="text-md">{{ $equipmentMaintenance->issue_description }}</p>
                                </div>
                            </div>
                            
                            @if($equipmentMaintenance->contact_info)
                            <div class="mb-3">
                                <p class="text-sm text-gray-600">Contact Information:</p>
                                <p class="text-md">{{ $equipmentMaintenance->contact_info }}</p>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Completion Details -->
                        <div class="border rounded-lg p-4 {{ $equipmentMaintenance->isCompleted() ? 'bg-green-50' : '' }}">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                {{ $equipmentMaintenance->isCompleted() ? 'Completion Details' : 'Completion Status' }}
                            </h3>
                            
                            @if($equipmentMaintenance->isCompleted())
                                <div class="mb-3">
                                    <p class="text-sm text-gray-600">Completed By:</p>
                                    <p class="text-md font-medium">{{ $equipmentMaintenance->completedBy->name }}</p>
                                </div>
                                
                                <div class="mb-3">
                                    <p class="text-sm text-gray-600">Completed At:</p>
                                    <p class="text-md font-medium">{{ $equipmentMaintenance->completed_at->format('M d, Y H:i') }}</p>
                                    <p class="text-xs text-gray-500">{{ $equipmentMaintenance->completed_at->diffForHumans() }}</p>
                                </div>
                                
                                <div class="mb-3">
                                    <p class="text-sm text-gray-600">Work Performed:</p>
                                    <div class="mt-1 p-2 bg-white rounded border">
                                        <p class="text-md">{{ $equipmentMaintenance->work_performed }}</p>
                                    </div>
                                </div>
                                
                                @if($equipmentMaintenance->service_provider)
                                <div class="mb-3">
                                    <p class="text-sm text-gray-600">Service Provider:</p>
                                    <p class="text-md">{{ $equipmentMaintenance->service_provider }}</p>
                                </div>
                                @endif
                                
                                @if($equipmentMaintenance->cost)
                                <div class="mb-3">
                                    <p class="text-sm text-gray-600">Cost:</p>
                                    <p class="text-md font-medium">${{ number_format($equipmentMaintenance->cost, 2) }}</p>
                                </div>
                                @endif
                            @else
                                <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-md">
                                    <p class="text-yellow-800">This maintenance request has not been completed yet.</p>
                                    <div class="mt-2">
                                        <a href="{{ route('nurse.equipment-maintenance.complete', $equipmentMaintenance->id) }}"
                                            class="inline-flex items-center px-3 py-1.5 bg-green-600 border border-transparent rounded-md font-medium text-xs text-white hover:bg-green-700">
                                            Complete Now
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Notes -->
                    @if($equipmentMaintenance->notes)
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Additional Notes</h3>
                        <p class="text-md">{{ $equipmentMaintenance->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>