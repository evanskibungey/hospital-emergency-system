<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Search Medications') }}
            </h2>
            <div>
                <a href="{{ route('nurse.medications.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-2">
                    {{ __('Add New Medication') }}
                </a>
                <a href="{{ route('nurse.medications.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    {{ __('All Medications') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search Bar -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('nurse.medications.search') }}" method="GET" class="mb-0">
                        <div class="flex items-center">
                            <div class="flex-grow">
                                <x-label for="search" :value="__('Find Medication')" class="mb-1" />
                                <x-input id="search" type="text" name="search" :value="$search" 
                                    class="block w-full" placeholder="Enter medication name..." autofocus />
                            </div>
                            <div class="ml-4 mt-5">
                                <x-button class="bg-indigo-600 hover:bg-indigo-700">
                                    {{ __('Search') }}
                                </x-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Search Results -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        @if($search)
                            {{ __('Search Results for') }}: "{{ $search }}"
                        @else
                            {{ __('Search for a Medication') }}
                        @endif
                    </h3>

                    @if($search && count($medications) > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Name
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Dosage Form
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Strength
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Controlled
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($medications as $medication)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $medication->name }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ ucfirst($medication->dosage_form) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $medication->strength }}
                                            @if($medication->unit)
                                                {{ $medication->unit }}
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($medication->is_controlled_substance)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Yes
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                No
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @if(isset($visit) && isset($selectMode) && $selectMode)
                                            <a href="#" onclick="selectMedication('{{ $medication->id }}', '{{ $medication->name }}', '{{ $medication->dosage_form }}', '{{ $medication->strength }} {{ $medication->unit }}')"
                                                class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                Select
                                            </a>
                                        @else
                                            <a href="{{ route('nurse.medications.show', $medication->id) }}"
                                                class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                View
                                            </a>
                                            <a href="{{ route('nurse.medications.edit', $medication->id) }}"
                                                class="text-indigo-600 hover:text-indigo-900">
                                                Edit
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @elseif($search)
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                            <div class="flex">
                                <div>
                                    <p class="text-yellow-700">
                                        No medications found matching "{{ $search }}". Try a different search term or
                                        <a href="{{ route('nurse.medications.create') }}" class="font-bold underline">add a new medication</a>.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                            <div class="flex">
                                <div>
                                    <p class="text-blue-700">
                                        Enter a medication name, description, or dosage form in the search box above.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if(isset($visit) && isset($selectMode) && $selectMode)
    <script>
        function selectMedication(id, name, dosageForm, strength) {
            window.opener.document.getElementById('medication_id').value = id;
            window.opener.document.getElementById('selected_medication_info').innerHTML = 
                '<div class="p-3 bg-indigo-50 rounded-md">' +
                '<p class="font-medium text-indigo-900">' + name + '</p>' +
                '<p class="text-sm text-indigo-700">' + dosageForm + ' - ' + strength + '</p>' +
                '</div>';
            window.opener.document.getElementById('selected_medication_info').classList.remove('hidden');
            window.close();
        }
    </script>
    @endif
</x-app-layout>