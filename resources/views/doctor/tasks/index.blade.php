<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tasks') }}
            </h2>
            <a href="{{ route('doctor.tasks.create') }}" class="bg-indigo-500 hover:bg-indigo-600 text-white py-1 px-3 rounded text-sm">
                New Task
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

            <!-- Overdue Tasks -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-red-600 mb-4">Overdue Tasks</h3>
                @if($overdueTasks->count() > 0)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr>
                                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Task
                                            </th>
                                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Patient
                                            </th>
                                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Due Date
                                            </th>
                                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Priority
                                            </th>
                                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status
                                            </th>
                                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($overdueTasks as $task)
                                            <tr class="bg-red-50">
                                                <td class="px-6 py-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $task->title }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        {{ \Illuminate\Support\Str::limit($task->description, 60) }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($task->visit)
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $task->visit->patient->first_name }} {{ $task->visit->patient->last_name }}
                                                        </div>
                                                    @else
                                                        <div class="text-sm text-gray-500">
                                                            No patient assigned
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-red-600">
                                                        {{ $task->due_at ? $task->due_at->format('M d, Y h:i A') : 'No due date' }}
                                                    </div>
                                                    <div class="text-xs text-red-500">
                                                        {{ $task->due_at ? $task->due_at->diffForHumans() : '' }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        {{ $task->priority === 'urgent' ? 'bg-red-100 text-red-800' : 
                                                        ($task->priority === 'high' ? 'bg-orange-100 text-orange-800' : 
                                                        ($task->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800')) }}">
                                                        {{ ucfirst($task->priority) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        {{ $task->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                        ($task->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 
                                                        ($task->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800')) }}">
                                                        {{ str_replace('_', ' ', ucfirst($task->status)) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <div class="flex space-x-2">
                                                        <a href="{{ route('doctor.tasks.show', $task) }}" class="text-blue-600 hover:text-blue-900">
                                                            View
                                                        </a>
                                                        @if($task->status === 'pending')
                                                            <form method="POST" action="{{ route('doctor.tasks.start', $task) }}" class="inline">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="text-indigo-600 hover:text-indigo-900">
                                                                    Start
                                                                </button>
                                                            </form>
                                                        @endif
                                                        @if(in_array($task->status, ['pending', 'in_progress']))
                                                            <form method="POST" action="{{ route('doctor.tasks.complete', $task) }}" class="inline">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="text-green-600 hover:text-green-900">
                                                                    Complete
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <p class="text-gray-500">No overdue tasks.</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Pending Tasks -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Pending Tasks</h3>
                @if($pendingTasks->count() > 0)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr>
                                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Task
                                            </th>
                                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Patient
                                            </th>
                                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Due Date
                                            </th>
                                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Priority
                                            </th>
                                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($pendingTasks as $task)
                                            <tr>
                                                <td class="px-6 py-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $task->title }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        {{ \Illuminate\Support\Str::limit($task->description, 60) }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($task->visit)
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $task->visit->patient->first_name }} {{ $task->visit->patient->last_name }}
                                                        </div>
                                                    @else
                                                        <div class="text-sm text-gray-500">
                                                            No patient assigned
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">
                                                        {{ $task->due_at ? $task->due_at->format('M d, Y h:i A') : 'No due date' }}
                                                    </div>
                                                    @if($task->due_at)
                                                        <div class="text-xs text-gray-500">
                                                            {{ $task->due_at->diffForHumans() }}
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        {{ $task->priority === 'urgent' ? 'bg-red-100 text-red-800' : 
                                                        ($task->priority === 'high' ? 'bg-orange-100 text-orange-800' : 
                                                        ($task->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800')) }}">
                                                        {{ ucfirst($task->priority) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <div class="flex space-x-2">
                                                        <a href="{{ route('doctor.tasks.show', $task) }}" class="text-blue-600 hover:text-blue-900">
                                                            View
                                                        </a>
                                                        <a href="{{ route('doctor.tasks.edit', $task) }}" class="text-indigo-600 hover:text-indigo-900">
                                                            Edit
                                                        </a>
                                                        <form method="POST" action="{{ route('doctor.tasks.start', $task) }}" class="inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="text-green-600 hover:text-green-900">
                                                                Start
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <p class="text-gray-500">No pending tasks.</p>
                        </div>
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- In Progress Tasks -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">In Progress Tasks</h3>
                    @if($inProgressTasks->count() > 0)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 bg-white border-b border-gray-200">
                                <div class="space-y-4">
                                    @foreach($inProgressTasks as $task)
                                        <div class="border border-blue-200 bg-blue-50 rounded-lg p-4">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <h4 class="font-semibold">{{ $task->title }}</h4>
                                                    <p class="text-sm text-gray-600 mt-1">
                                                        {{ $task->visit ? 'Patient: ' . $task->visit->patient->first_name . ' ' . $task->visit->patient->last_name : 'No patient assigned' }}
                                                    </p>
                                                    <p class="text-sm text-gray-600">
                                                        Due: {{ $task->due_at ? $task->due_at->format('M d, Y h:i A') : 'No due date' }}
                                                    </p>
                                                    <div class="mt-2">
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                            {{ $task->priority === 'urgent' ? 'bg-red-100 text-red-800' : 
                                                            ($task->priority === 'high' ? 'bg-orange-100 text-orange-800' : 
                                                            ($task->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800')) }}">
                                                            {{ ucfirst($task->priority) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('doctor.tasks.show', $task) }}" class="text-blue-600 hover:text-blue-900">
                                                        View
                                                    </a>
                                                    <form method="POST" action="{{ route('doctor.tasks.complete', $task) }}" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="text-green-600 hover:text-green-900">
                                                            Complete
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 bg-white border-b border-gray-200">
                                <p class="text-gray-500">No tasks in progress.</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Completed Tasks -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Completed Tasks</h3>
                    @if($completedTasks->count() > 0)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 bg-white border-b border-gray-200">
                                <div class="space-y-4">
                                    @foreach($completedTasks as $task)
                                        <div class="border border-green-200 bg-green-50 rounded-lg p-4">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <h4 class="font-semibold">{{ $task->title }}</h4>
                                                    <p class="text-sm text-gray-600 mt-1">
                                                        {{ $task->visit ? 'Patient: ' . $task->visit->patient->first_name . ' ' . $task->visit->patient->last_name : 'No patient assigned' }}
                                                    </p>
                                                    <p class="text-sm text-gray-600">
                                                        Completed: {{ $task->completed_at->format('M d, Y h:i A') }}
                                                    </p>
                                                    <div class="mt-2">
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                            {{ $task->priority === 'urgent' ? 'bg-red-100 text-red-800' : 
                                                            ($task->priority === 'high' ? 'bg-orange-100 text-orange-800' : 
                                                            ($task->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800')) }}">
                                                            {{ ucfirst($task->priority) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div>
                                                    <a href="{{ route('doctor.tasks.show', $task) }}" class="text-blue-600 hover:text-blue-900">
                                                        View
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 bg-white border-b border-gray-200">
                                <p class="text-gray-500">No completed tasks.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
