<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Task Details') }}
            </h2>
            <a href="{{ route('doctor.tasks.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 py-1 px-3 rounded text-sm">
                Back to Tasks
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">{{ $task->title }}</h1>
                            <div class="flex items-center space-x-3 mt-2">
                                <span class="px-2 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                    {{ $task->priority === 'urgent' ? 'bg-red-100 text-red-800' : 
                                    ($task->priority === 'high' ? 'bg-orange-100 text-orange-800' : 
                                    ($task->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800')) }}">
                                    Priority: {{ ucfirst($task->priority) }}
                                </span>
                                <span class="px-2 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                    {{ $task->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                    ($task->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 
                                    ($task->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800')) }}">
                                    Status: {{ str_replace('_', ' ', ucfirst($task->status)) }}
                                </span>
                                @if($task->due_at)
                                    <span class="px-2 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                        {{ $task->isOverdue() ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' }}">
                                        Due: {{ $task->due_at->format('M d, Y h:i A') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="flex space-x-3">
                            @if($task->status === 'pending')
                                <form method="POST" action="{{ route('doctor.tasks.start', $task) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded text-sm">
                                        Start Task
                                    </button>
                                </form>
                            @endif
                            
                            @if(in_array($task->status, ['pending', 'in_progress']))
                                <form method="POST" action="{{ route('doctor.tasks.complete', $task) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded text-sm">
                                        Complete Task
                                    </button>
                                </form>
                            @endif
                            
                            @if($task->status !== 'completed')
                                <a href="{{ route('doctor.tasks.edit', $task) }}" class="bg-indigo-500 hover:bg-indigo-600 text-white py-2 px-4 rounded text-sm">
                                    Edit Task
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="mt-6">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Description</h3>
                        <div class="bg-gray-50 p-4 rounded">
                            <p>{{ $task->description ?: 'No description provided.' }}</p>
                        </div>
                    </div>
                    
                    @if($task->visit)
                        <div class="mt-6">
                            <h3 class="text-lg font-semibold text-gray-700 mb-2">Associated Patient</h3>
                            <div class="bg-gray-50 p-4 rounded">
                                <div class="flex flex-col md:flex-row md:justify-between">
                                    <div>
                                        <p class="font-medium">{{ $task->visit->patient->first_name }} {{ $task->visit->patient->last_name }}</p>
                                        <p class="text-sm text-gray-600">
                                            {{ $task->visit->patient->date_of_birth ? $task->visit->patient->date_of_birth->age . ' years old' : '' }}
                                            {{ $task->visit->patient->gender ? ' - ' . ucfirst($task->visit->patient->gender) : '' }}
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            MRN: {{ $task->visit->patient->medical_record_number ?? 'Not available' }}
                                        </p>
                                    </div>
                                    <div class="mt-3 md:mt-0">
                                        <p class="font-medium">Chief Complaint</p>
                                        <p class="text-sm text-gray-600">{{ $task->visit->chief_complaint }}</p>
                                        <p class="text-sm text-gray-600 mt-1">
                                            Location: {{ $task->visit->bed ? $task->visit->bed->location . ' - Bed ' . $task->visit->bed->bed_number : 'No bed assigned' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @if($task->status === 'completed' && $task->completed_at)
                        <div class="mt-6">
                            <h3 class="text-lg font-semibold text-gray-700 mb-2">Completion Details</h3>
                            <div class="bg-green-50 p-4 rounded">
                                <p class="text-sm text-gray-600">
                                    Completed on: {{ $task->completed_at->format('M d, Y h:i A') }} ({{ $task->completed_at->diffForHumans() }})
                                </p>
                                @if($task->due_at)
                                    <p class="text-sm text-gray-600 mt-1">
                                        Status: 
                                        @if($task->completed_at->lt($task->due_at))
                                            <span class="text-green-600">Completed on time</span>
                                        @else
                                            <span class="text-red-600">Completed late ({{ $task->completed_at->diffForHumans($task->due_at) }} after due date)</span>
                                        @endif
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endif
                    
                    <div class="mt-8 flex justify-between">
                        <div>
                            <p class="text-sm text-gray-500">
                                Created: {{ $task->created_at->format('M d, Y h:i A') }}
                                @if($task->created_at->ne($task->updated_at))
                                    <br>Updated: {{ $task->updated_at->format('M d, Y h:i A') }}
                                @endif
                            </p>
                        </div>
                        @if($task->status !== 'completed')
                            <form method="POST" action="{{ route('doctor.tasks.destroy', $task) }}" onsubmit="return confirm('Are you sure you want to delete this task?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 text-sm">
                                    Delete Task
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
