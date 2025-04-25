<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Transfer Patient to Another Bed') }}
            </h2>
            <a href="{{ route('nurse.bed-assignments.show', $visit->id) }}"
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300">
                {{ __('Back to Bed Assignment') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Current Assignment Info -->
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
                        </div>
                        <div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Current Bed</h4>
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
                </div>
            </div>

            <!-- Transfer Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ __('Transfer to New Bed') }}
                    </h3>

                    @if($bedsByLocation->count() <= 1 && $bedsByLocation->first()->count() <= 1)
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                        <div class="flex">
                            <div>
                                <p class="text-yellow-700">
                                    No other beds are currently available for transfer. Please try again later or check with bed management.
                                </p>
                            </div>
                        </div>
                    </div>
                    @else
                    <form method="POST" action="{{ route('nurse.bed-assignments.update', $visit->id) }}">
                        @csrf
                        @method('PUT')

                        <!-- Bed Selection -->
                        <div class="mb-4">
                            <label for="bed_id" class="block text-sm font-medium text-gray-700">Select New Bed*</label>
                            <select name="bed_id" id="bed_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('bed_id') border-red-500 @enderror"
                                required>
                                @foreach($bedsByLocation as $location => $beds)
                                <optgroup label="{{ $location }}">
                                    @foreach($beds as $bed)
                                    <option value="{{ $bed->id }}" {{ $bed->id === $visit->bed_id ? 'selected' : '' }}>
                                        {{ $bed->bed_number }} - {{ ucfirst($bed->type) }}
                                        {{ $bed->id === $visit->bed_id ? '(Current)' : '' }}
                                    </option>
                                    @endforeach
                                </optgroup>
                                @endforeach
                            </select>
                            @error('bed_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-xs mt-1">Selecting the current bed will result in no change</p>
                        </div>

                        <!-- Transfer Notes -->
                        <div class="mb-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Transfer Notes</label>
                            <textarea name="notes" id="notes" rows="2"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                            @error('notes')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-xs mt-1">Reason for bed transfer</p>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('nurse.bed-assignments.show', $visit->id) }}"
                                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring ring-blue-200 active:text-gray-800 active:bg-gray-50 mr-2">
                                Cancel
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:border-yellow-900 focus:ring ring-yellow-300">
                                Transfer Patient
                            </button>
                        </div>
                    </form>
                    @endif

                    <div class="mt-6">
                        <div class="text-sm text-gray-500 mb-2">Note: Transferring a patient will:</div>
                        <ul class="list-disc pl-5 text-sm text-gray-500 space-y-1">
                            <li>Mark the current bed for cleaning</li>
                            <li>Assign the patient to the new bed</li>
                            <li>Update the patient's location in the system</li>
                            <li>Record the transfer in the patient's visit notes</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>