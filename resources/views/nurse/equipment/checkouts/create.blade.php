<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Check Out Equipment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('nurse.equipment-checkouts.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <!-- Equipment Selection -->
                                <div class="mb-4">
                                    <label for="equipment_id" class="block text-sm font-medium text-gray-700">Equipment*</label>
                                    <select name="equipment_id" id="equipment_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('equipment_id') border-red-500 @enderror"
                                        required>
                                        <option value="">-- Select Equipment --</option>
                                        @if(isset($equipment) && $equipment)
                                            <option value="{{ $equipment->id }}" selected>
                                                {{ $equipment->name }} - {{ $equipment->available_quantity }} available
                                            </option>
                                        @else
                                            @foreach($availableEquipment as $category => $items)
                                                <optgroup label="{{ ucfirst(str_replace('_', ' ', $category)) }}">
                                                    @foreach($items as $item)
                                                        <option value="{{ $item->id }}" {{ old('equipment_id') == $item->id ? 'selected' : '' }}>
                                                            {{ $item->name }} - {{ $item->available_quantity }} available
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('equipment_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Patient Visit Selection -->
                                <div class="mb-4">
                                    <label for="visit_id" class="block text-sm font-medium text-gray-700">Patient Visit (Optional)</label>
                                    <div class="mt-1 flex rounded-md shadow-sm">
                                        <select name="visit_id" id="visit_id"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('visit_id') border-red-500 @enderror">
                                            <option value="">-- No Patient Visit --</option>
                                            @if(isset($visit) && $visit)
                                                <option value="{{ $visit->id }}" selected>
                                                    {{ $visit->patient->first_name }} {{ $visit->patient->last_name }} ({{ $visit->check_in_time ? $visit->check_in_time->format('M d, Y H:i') : 'No check-in time' }})
                                                </option>
                                            @else
                                                @foreach($activeVisits as $activeVisit)
                                                    <option value="{{ $activeVisit->id }}" {{ old('visit_id') == $activeVisit->id ? 'selected' : '' }}>
                                                        {{ $activeVisit->patient->first_name }} {{ $activeVisit->patient->last_name }} ({{ $activeVisit->check_in_time ? $activeVisit->check_in_time->format('M d, Y H:i') : 'No check-in time' }})
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <button type="button" id="searchPatientsBtn"
                                            class="ml-2 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            Search
                                        </button>
                                    </div>
                                    @error('visit_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                    
                                    <!-- Patient Search Results (Hidden by Default) -->
                                    <div id="patient-search-container" class="hidden mt-2">
                                        <div class="flex rounded-md shadow-sm">
                                            <input type="text" id="patient-search" placeholder="Search by name or MRN"
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        </div>
                                        <div id="patient-search-results" class="mt-2 max-h-40 overflow-y-auto">
                                            <!-- Results will be loaded here -->
                                        </div>
                                    </div>
                                </div>

                                <!-- Quantity -->
                                <div class="mb-4">
                                    <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity*</label>
                                    <input type="number" name="quantity" id="quantity" value="{{ old('quantity', 1) }}" min="1"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('quantity') border-red-500 @enderror"
                                        required>
                                    @error('quantity')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div>
                                <!-- Purpose -->
                                <div class="mb-4">
                                    <label for="purpose" class="block text-sm font-medium text-gray-700">Purpose*</label>
                                    <input type="text" name="purpose" id="purpose" value="{{ old('purpose') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('purpose') border-red-500 @enderror"
                                        required placeholder="e.g., Patient monitoring, Procedure use, Transport">
                                    @error('purpose')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Expected Return Date -->
                                <div class="mb-4">
                                    <label for="expected_return_at" class="block text-sm font-medium text-gray-700">Expected Return Date/Time</label>
                                    <input type="datetime-local" name="expected_return_at" id="expected_return_at" value="{{ old('expected_return_at') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('expected_return_at') border-red-500 @enderror">
                                    @error('expected_return_at')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                    <p class="text-gray-500 text-xs mt-1">When the equipment is expected to be returned. Leave blank if unknown.</p>
                                </div>

                                <!-- Condition at Checkout -->
                                <div class="mb-4">
                                    <label for="condition_at_checkout" class="block text-sm font-medium text-gray-700">Condition at Checkout</label>
                                    <select name="condition_at_checkout" id="condition_at_checkout"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('condition_at_checkout') border-red-500 @enderror">
                                        <option value="Excellent condition">Excellent condition</option>
                                        <option value="Good condition">Good condition</option>
                                        <option value="Fair condition">Fair condition</option>
                                        <option value="Needs attention">Needs attention</option>
                                    </select>
                                    @error('condition_at_checkout')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Notes -->
                                <div class="mb-4">
                                    <label for="checkout_notes" class="block text-sm font-medium text-gray-700">Checkout Notes</label>
                                    <textarea name="checkout_notes" id="checkout_notes" rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('checkout_notes') border-red-500 @enderror">{{ old('checkout_notes') }}</textarea>
                                    @error('checkout_notes')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                    <p class="text-gray-500 text-xs mt-1">Any special instructions or notes about this checkout</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('nurse.equipment-checkouts.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring ring-blue-200 active:text-gray-800 active:bg-gray-50 mr-2">
                                Cancel
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300">
                                Check Out Equipment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchBtn = document.getElementById('searchPatientsBtn');
            const searchContainer = document.getElementById('patient-search-container');
            const searchInput = document.getElementById('patient-search');
            const resultsContainer = document.getElementById('patient-search-results');
            const visitSelect = document.getElementById('visit_id');
            
            searchBtn.addEventListener('click', function() {
                searchContainer.classList.toggle('hidden');
                if (!searchContainer.classList.contains('hidden')) {
                    searchInput.focus();
                }
            });
            
            // Patient search functionality
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const query = this.value.trim();
                
                if (query.length < 2) {
                    resultsContainer.innerHTML = '';
                    return;
                }
                
                searchTimeout = setTimeout(function() {
                    // Make AJAX request to search visits endpoint
                    fetch(`{{ route('nurse.equipment-checkouts.search-visits') }}?search=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(data => {
                            resultsContainer.innerHTML = '';
                            
                            if (data.length === 0) {
                                resultsContainer.innerHTML = '<p class="text-sm text-gray-500 p-2">No patients found</p>';
                                return;
                            }
                            
                            data.forEach(visit => {
                                const option = document.createElement('div');
                                option.className = 'p-2 hover:bg-gray-100 cursor-pointer';
                                option.innerHTML = `
                                    <div class="text-sm font-medium">${visit.patient.first_name} ${visit.patient.last_name}</div>
                                    <div class="text-xs text-gray-500">MRN: ${visit.patient.medical_record_number || 'N/A'} | Visit: ${new Date(visit.check_in_time).toLocaleString()}</div>
                                `;
                                
                                option.addEventListener('click', function() {
                                    // Add option to select if it doesn't exist
                                    if (!Array.from(visitSelect.options).some(opt => opt.value == visit.id)) {
                                        const newOption = new Option(
                                            `${visit.patient.first_name} ${visit.patient.last_name} (${new Date(visit.check_in_time).toLocaleString()})`, 
                                            visit.id
                                        );
                                        visitSelect.add(newOption);
                                    }
                                    
                                    // Select the option
                                    visitSelect.value = visit.id;
                                    
                                    // Hide search container
                                    searchContainer.classList.add('hidden');
                                    searchInput.value = '';
                                    resultsContainer.innerHTML = '';
                                });
                                
                                resultsContainer.appendChild(option);
                            });
                        })
                        .catch(error => {
                            console.error('Error searching patients:', error);
                            resultsContainer.innerHTML = '<p class="text-sm text-red-500 p-2">Error searching patients</p>';
                        });
                }, 300);
            });
        });
    </script>
</x-app-layout>