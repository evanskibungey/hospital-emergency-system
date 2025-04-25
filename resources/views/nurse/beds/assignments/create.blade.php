<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Assign Bed') }}: {{ $visit->patient->first_name }} {{ $visit->patient->last_name }}
            </h2>
            <a href="{{ route('nurse.dashboard') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300">
                {{ __('Back to Dashboard') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Patient Info Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ __('Patient Information') }}
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
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
                                <h4 class="text-sm font-medium text-gray-500">Gender / Age</h4>
                                <p class="text-base">
                                    {{ ucfirst($visit->patient->gender ?? 'N/A') }} / 
                                    {{ $visit->patient->date_of_birth ? $visit->patient->date_of_birth->age . ' years' : 'N/A' }}
                                </p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Visit Status</h4>
                                <p class="text-base">{{ ucfirst(str_replace('_', ' ', $visit->status)) }}</p>
                            </div>
                        </div>
                        <div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Chief Complaint</h4>
                                <p class="text-base">{{ $visit->chief_complaint ?? 'Not recorded' }}</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Check-in Time</h4>
                                <p class="text-base">{{ $visit->check_in_time ? $visit->check_in_time->format('M d, Y H:i') : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bed Assignment Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ __('Assign Bed') }}
                    </h3>

                    @if($bedsByLocation->isEmpty())
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                        <div class="flex">
                            <div>
                                <p class="text-yellow-700">
                                    No beds are currently available. Please try again later or check with bed management.
                                </p>
                            </div>
                        </div>
                    </div>
                    @else
                    <form method="POST" action="{{ route('nurse.bed-assignments.store', $visit->id) }}">
                        @csrf

                        <!-- Bed Selection -->
                        <div class="mb-4">
                            <label for="bed_id" class="block text-sm font-medium text-gray-700">Select Available Bed*</label>
                            <select name="bed_id" id="bed_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('bed_id') border-red-500 @enderror"
                                required>
                                <option value="">-- Select a Bed --</option>
                                @foreach($bedsByLocation as $location => $beds)
                                <optgroup label="{{ $location }}">
                                    @foreach($beds as $bed)
                                    <option value="{{ $bed->id }}" {{ old('bed_id') == $bed->id ? 'selected' : '' }}>
                                        {{ $bed->bed_number }} - {{ ucfirst($bed->type) }}
                                    </option>
                                    @endforeach
                                </optgroup>
                                @endforeach
                            </select>
                            @error('bed_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Assignment Notes -->
                        <div class="mb-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Assignment Notes</label>
                            <textarea name="notes" id="notes" rows="2"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                            @error('notes')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-xs mt-1">Any special considerations for this bed assignment</p>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('nurse.dashboard') }}"
                                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring ring-blue-200 active:text-gray-800 active:bg-gray-50 mr-2">
                                Cancel
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300">
                                Assign Bed
                            </button>
                        </div>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>