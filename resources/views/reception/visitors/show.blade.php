<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Visitor Details') }}
            </h2>
            <form method="POST" action="{{ route('reception.visitors.checkout', $visitor) }}" class="inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150" onclick="return confirm('Are you sure you want to check out this visitor?')">
                    {{ __('Check Out Visitor') }}
                </button>
            </form>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Visitor Information -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-semibold mb-4">Visitor Information</h3>
                        
                        <div class="grid grid-cols-1 gap-4">
                            <div class="bg-gray-50 p-4 rounded-md">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-600">Name</p>
                                        <p class="font-medium">{{ $visitor->full_name }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Phone Number</p>
                                        <p class="font-medium">{{ $visitor->phone_number }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-md">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-600">Relationship to Patient</p>
                                        <p class="font-medium">{{ $visitor->relationship_to_patient }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Visiting</p>
                                        <p class="font-medium">{{ $visitor->patient->full_name }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-md">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-600">ID Type</p>
                                        <p class="font-medium">{{ $visitor->id_type ?? 'Not provided' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">ID Number</p>
                                        <p class="font-medium">{{ $visitor->id_number ?? 'Not provided' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Visitor Pass Information -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-semibold mb-4">Visitor Pass</h3>
                        
                        <div class="border-2 border-gray-200 p-6 rounded-md">
                            <div class="text-center mb-6">
                                <h2 class="text-2xl font-bold text-gray-900">VISITOR PASS</h2>
                                <p class="text-gray-600">Hospital Emergency Management System</p>
                            </div>
                            
                            <div class="flex flex-col items-center mb-6">
                                <div class="h-32 w-32 bg-gray-300 rounded-full flex items-center justify-center text-gray-600 text-3xl">
                                    {{ strtoupper(substr($visitor->first_name, 0, 1) . substr($visitor->last_name, 0, 1)) }}
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 gap-2 mb-4">
                                <div class="text-center">
                                    <p class="text-sm text-gray-600">Visitor Name</p>
                                    <p class="font-bold text-xl">{{ $visitor->full_name }}</p>
                                </div>
                                
                                <div class="text-center">
                                    <p class="text-sm text-gray-600">Visiting</p>
                                    <p class="font-medium">{{ $visitor->patient->full_name }}</p>
                                </div>
                                
                                <div class="text-center">
                                    <p class="text-sm text-gray-600">Pass Number</p>
                                    <p class="font-bold font-mono text-xl">{{ $visitor->pass_number }}</p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div class="text-center">
                                    <p class="text-sm text-gray-600">Check-in Time</p>
                                    <p class="font-medium">{{ $visitor->check_in_time->format('M d, Y H:i') }}</p>
                                </div>
                                
                                <div class="text-center">
                                    <p class="text-sm text-gray-600">Status</p>
                                    @if($visitor->is_active)
                                        <p class="font-medium text-green-600">Active</p>
                                    @else
                                        <p class="font-medium text-red-600">Checked Out</p>
                                        <p class="text-sm">{{ $visitor->check_out_time->format('M d, Y H:i') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6 text-center">
                            <button type="button" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150" onclick="window.print()">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                Print Visitor Pass
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Visitor Guidelines -->
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Visitor Guidelines</h3>
                    
                    <div class="bg-yellow-50 p-4 rounded-md">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Important Information for Visitors</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        <li>Visitors must wear visitor badges at all times</li>
                                        <li>Maximum 2 visitors per patient at a time</li>
                                        <li>Please respect visiting hours (8:00 AM - 8:00 PM)</li>
                                        <li>Visitors must check out at the reception desk when leaving</li>
                                        <li>Please maintain quiet in all patient areas</li>
                                        <li>Wash or sanitize hands before entering and after leaving patient rooms</li>
                                        <li>Do not visit if you have cold, flu, or other contagious illness symptoms</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>