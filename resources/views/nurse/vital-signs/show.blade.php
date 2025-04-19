<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Vital Signs Details') }}
            </h2>
            <div>
                <a href="{{ route('nurse.vital-signs.edit', [$visit->id, $vitalSign->id]) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-2">
                    Edit
                </a>
                <a href="{{ route('nurse.vital-signs.index', $visit->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">
                            Patient: {{ $visit->patient->first_name }} {{ $visit->patient->last_name }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            MRN: {{ $visit->patient->medical_record_number }} | Visit ID: {{ $visit->id }}
                        </p>
                    </div>

                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="bg-gray-50 p-6 rounded-lg shadow-inner">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Recorded At</h4>
                                <p class="text-gray-900">{{ $vitalSign->created_at->format('M d, Y H:i') }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Recorded By</h4>
                                <p class="text-gray-900">{{ $vitalSign->user->name }}</p>
                            </div>
                        </div>

                        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Temperature</h4>
                                <p class="text-gray-900 text-2xl">{{ $vitalSign->temperature ? $vitalSign->temperature . ' °C' : 'Not recorded' }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Heart Rate</h4>
                                <p class="text-gray-900 text-2xl">{{ $vitalSign->heart_rate ? $vitalSign->heart_rate . ' bpm' : 'Not recorded' }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Respiratory Rate</h4>
                                <p class="text-gray-900 text-2xl">{{ $vitalSign->respiratory_rate ? $vitalSign->respiratory_rate . '/min' : 'Not recorded' }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Blood Pressure</h4>
                                <p class="text-gray-900 text-2xl">{{ $vitalSign->blood_pressure != 'N/A' ? $vitalSign->blood_pressure . ' mmHg' : 'Not recorded' }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Oxygen Saturation</h4>
                                <p class="text-gray-900 text-2xl">{{ $vitalSign->oxygen_saturation ? $vitalSign->oxygen_saturation . '%' : 'Not recorded' }}</p>
                            </div>
                        </div>

                        @if($vitalSign->notes)
                            <div class="mt-8">
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Notes</h4>
                                <div class="bg-white p-4 rounded border border-gray-200">
                                    <p class="text-gray-700 whitespace-pre-line">{{ $vitalSign->notes }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>