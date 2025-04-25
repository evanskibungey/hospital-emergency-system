<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Assign Doctor to Patient') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Patient Information -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Patient Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm text-gray-600">Name:</p>
                                <p class="text-md font-medium">{{ $visit->patient->first_name }} {{ $visit->patient->last_name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">MRN:</p>
                                <p class="text-md font-medium">{{ $visit->patient->medical_record_number ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Date of Birth:</p>
                                <p class="text-md font-medium">{{ $visit->patient->date_of_birth ? $visit->patient->date_of_birth->format('M d, Y') : 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Gender:</p>
                                <p class="text-md font-medium">{{ ucfirst($visit->patient->gender ?? 'N/A') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Visit Information -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Visit Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm text-gray-600">Check-in Time:</p>
                                <p class="text-md font-medium">{{ $visit->check_in_time ? $visit->check_in_time->format('M d, Y H:i') : 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Chief Complaint:</p>
                                <p class="text-md font-medium">{{ $visit->chief_complaint ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Priority:</p>
                                <p class="text-md font-medium">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($visit->priority == 'high' || $visit->priority == 'critical') bg-red-100 text-red-800 
                                                @elseif($visit->priority == 'medium') bg-yellow-100 text-yellow-800 
                                                @else bg-green-100 text-green-800 @endif">
                                        {{ ucfirst($visit->priority) }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Status:</p>
                                <p class="text-md font-medium">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($visit->status == 'waiting') bg-blue-100 text-blue-800 
                                                @elseif($visit->status == 'in_progress') bg-green-100 text-green-800 
                                                @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $visit->status)) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Doctor Assignment Form -->
                    <form method="POST" action="{{ route('nurse.store-doctor-assignment', $visit->id) }}">
                        @csrf
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Assign Doctor</h3>
                            
                            @if ($errors->any())
                            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            
                            <div class="mb-4">
                                <label for="doctor_id" class="block text-sm font-medium text-gray-700 mb-2">Select Doctor</label>
                                <select id="doctor_id" name="doctor_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                    <option value="">-- Select a Doctor --</option>
                                    @foreach ($doctors as $doctor)
                                        <option value="{{ $doctor->id }}">Dr. {{ $doctor->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="flex items-center justify-between mt-6">
                                <a href="{{ route('nurse.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-300 focus:outline-none focus:border-gray-300 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Cancel
                                </a>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-700 focus:outline-none focus:border-green-700 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Assign Doctor
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>