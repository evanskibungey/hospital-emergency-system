<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Medical Note Details') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('doctor.visits.show', $visit) }}" class="px-4 py-2 bg-gray-200 rounded-md text-gray-700 hover:bg-gray-300">
                    {{ __('Back to Visit') }}
                </a>
                @if ($medicalNote->created_by === Auth::id())
                    <a href="{{ route('doctor.medical-notes.edit', ['visit' => $visit->id, 'medicalNote' => $medicalNote->id]) }}" class="px-4 py-2 bg-indigo-500 rounded-md text-white hover:bg-indigo-600">
                        {{ __('Edit Note') }}
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
                                <p><span class="font-medium">Name:</span> {{ $visit->patient->full_name }}</p>
                                <p><span class="font-medium">Age:</span> {{ $visit->patient->date_of_birth->age }} years</p>
                                <p><span class="font-medium">MRN:</span> {{ $visit->patient->medical_record_number }}</p>
                            </div>
                            <div>
                                <p><span class="font-medium">Chief Complaint:</span> {{ $visit->chief_complaint }}</p>
                                <p><span class="font-medium">Visit Status:</span> 
                                    <span class="px-2 py-1 rounded-full text-xs {{ $visit->is_critical ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $visit->is_critical ? 'CRITICAL' : 'STANDARD' }}
                                    </span>
                                </p>
                                <p><span class="font-medium">Check-in Time:</span> {{ $visit->check_in_time->format('M d, Y g:i A') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-3">Medical Note</h3>
                        <div class="flex justify-between items-center mb-3">
                            <div>
                                <span class="px-3 py-1 rounded-full text-xs bg-blue-100 text-blue-800 mr-2">
                                    {{ ucfirst($medicalNote->note_type) }}
                                </span>
                                @if ($medicalNote->is_private)
                                    <span class="px-3 py-1 rounded-full text-xs bg-red-100 text-red-800">
                                        Private
                                    </span>
                                @endif
                                <span class="text-sm text-gray-500 ml-2">
                                    Created by: Dr. {{ $medicalNote->creator->name }} on {{ $medicalNote->created_at->format('M d, Y g:i A') }}
                                </span>
                            </div>

                            @if ($medicalNote->treatment)
                                <div>
                                    <a href="{{ route('doctor.treatments.show', $medicalNote->treatment) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                                        View Associated Treatment
                                    </a>
                                </div>
                            @endif
                        </div>
                        
                        <div class="mb-4 mt-4">
                            <h4 class="font-medium text-gray-700 mb-1">Note Content:</h4>
                            <div class="p-4 bg-gray-50 rounded min-h-[300px] whitespace-pre-line">
                                {{ $medicalNote->content }}
                            </div>
                        </div>
                        
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <div class="flex justify-between">
                                <div>
                                    <span class="text-sm text-gray-500">
                                        Created: {{ $medicalNote->created_at->format('M d, Y g:i A') }}
                                    </span>
                                    @if ($medicalNote->updated_at->gt($medicalNote->created_at))
                                        <span class="text-sm text-gray-500 ml-4">
                                            Last Updated: {{ $medicalNote->updated_at->format('M d, Y g:i A') }}
                                        </span>
                                    @endif
                                </div>

                                @if ($medicalNote->created_by === Auth::id())
                                    <form method="POST" action="{{ route('doctor.medical-notes.destroy', ['visit' => $visit->id, 'medicalNote' => $medicalNote->id]) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 text-sm" onclick="return confirm('Are you sure you want to delete this medical note? This cannot be undone.')">
                                            Delete Note
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Other Notes for This Visit</h3>
                    
                    @if ($visit->medicalNotes->count() > 1)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date/Time</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Preview</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($visit->medicalNotes as $note)
                                        @if ($note->id !== $medicalNote->id)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $note->created_at->format('M d, Y g:i A') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ ucfirst($note->note_type) }}
                                                    @if ($note->is_private)
                                                        <span class="ml-1 px-2 py-0.5 bg-red-100 text-red-800 rounded-full text-xs">Private</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    Dr. {{ $note->creator->name }}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-500 max-w-md truncate">
                                                    {{ Str::limit($note->content, 100) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <a href="{{ route('doctor.medical-notes.show', ['visit' => $visit->id, 'medicalNote' => $note->id]) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 italic">No other medical notes have been added for this visit.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
