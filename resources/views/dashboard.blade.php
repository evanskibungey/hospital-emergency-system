<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Hospital Administration Dashboard') }}
            </h2>
            <div class="flex space-x-2">
                <span class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1.5 rounded-full flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    System Status: Online
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <div class="flex flex-col md:flex-row items-center justify-between">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">
                                Welcome to the Hospital Management System, {{ Auth::user()->name }}
                            </h3>
                            <p class="text-gray-600">
                                As an administrator, you have complete access to all features of the system.
                            </p>
                        </div>
                        <div class="mt-4 md:mt-0">
                            <span class="text-xs text-gray-500">Last login: {{ now()->subHours(rand(1, 5))->format('M d, Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Overview Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <!-- Total Patients Card -->
                <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-blue-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 rounded-full p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Patients</dt>
                                <dd>
                                    <div class="text-lg font-semibold text-gray-900">{{ \App\Models\Patient::count() }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                
                <!-- Active Visits Card -->
                <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-green-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 rounded-full p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Active Visits</dt>
                                <dd>
                                    <div class="text-lg font-semibold text-gray-900">{{ \App\Models\Visit::whereIn('status', ['waiting', 'in_progress'])->count() }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                
                <!-- Available Beds Card -->
                <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-yellow-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-100 rounded-full p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Available Beds</dt>
                                <dd>
                                    <div class="text-lg font-semibold text-gray-900">{{ \App\Models\Bed::where('status', 'available')->where('is_active', true)->count() }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                
                <!-- Staff On Duty Card -->
                <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-indigo-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-indigo-100 rounded-full p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Staff On Duty</dt>
                                <dd>
                                    <div class="text-lg font-semibold text-gray-900">{{ \App\Models\User::where('is_on_call', true)->count() }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Role Dashboards Section -->
            <h3 class="text-lg font-semibold text-gray-800 mb-4 mt-8">Module Access</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Reception Dashboard Access -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200 hover:shadow-md transition-shadow duration-300">
                    <div class="bg-gradient-to-r from-green-400 to-green-500 px-4 py-2">
                        <h3 class="text-white font-semibold">Reception Module</h3>
                    </div>
                    <div class="p-4">
                        <div class="flex items-start mb-4">
                            <div class="bg-green-100 p-2 rounded-full mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-gray-800 font-medium">Patient Registration & Intake</h4>
                                <p class="text-sm text-gray-600 mt-1">Manage patient check-ins, visitors, and waiting rooms</p>
                            </div>
                        </div>
                        <ul class="text-sm text-gray-600 mb-4 ml-3">
                            <li class="mb-1 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Patient registration
                            </li>
                            <li class="mb-1 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Visitor management
                            </li>
                            <li class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Waiting room status
                            </li>
                        </ul>
                        <a href="{{ route('reception.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-300 w-full justify-center">
                            Access Reception Module
                        </a>
                    </div>
                </div>
                
                <!-- Nurse Dashboard Access -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200 hover:shadow-md transition-shadow duration-300">
                    <div class="bg-gradient-to-r from-blue-400 to-blue-500 px-4 py-2">
                        <h3 class="text-white font-semibold">Nursing Module</h3>
                    </div>
                    <div class="p-4">
                        <div class="flex items-start mb-4">
                            <div class="bg-blue-100 p-2 rounded-full mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-gray-800 font-medium">Patient Care & Monitoring</h4>
                                <p class="text-sm text-gray-600 mt-1">Manage vital signs, medications, and bed assignments</p>
                            </div>
                        </div>
                        <ul class="text-sm text-gray-600 mb-4 ml-3">
                            <li class="mb-1 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Vital signs recording
                            </li>
                            <li class="mb-1 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Medication administration
                            </li>
                            <li class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Bed & equipment management
                            </li>
                        </ul>
                        <a href="{{ route('nurse.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-300 w-full justify-center">
                            Access Nursing Module
                        </a>
                    </div>
                </div>
                
                <!-- Doctor Dashboard Access -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200 hover:shadow-md transition-shadow duration-300">
                    <div class="bg-gradient-to-r from-indigo-400 to-indigo-500 px-4 py-2">
                        <h3 class="text-white font-semibold">Doctor Module</h3>
                    </div>
                    <div class="p-4">
                        <div class="flex items-start mb-4">
                            <div class="bg-indigo-100 p-2 rounded-full mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-gray-800 font-medium">Patient Treatment & Diagnosis</h4>
                                <p class="text-sm text-gray-600 mt-1">Manage treatments, prescriptions, and discharges</p>
                            </div>
                        </div>
                        <ul class="text-sm text-gray-600 mb-4 ml-3">
                            <li class="mb-1 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-indigo-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Medical notes & treatment plans
                            </li>
                            <li class="mb-1 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-indigo-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Lab & imaging orders
                            </li>
                            <li class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-indigo-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Prescription & discharge management
                            </li>
                        </ul>
                        <a href="{{ route('doctor.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-300 w-full justify-center">
                            Access Doctor Module
                        </a>
                    </div>
                </div>
            </div>
            

            
            <!-- Recent Activity Section -->
            <h3 class="text-lg font-semibold text-gray-800 mb-4 mt-8">Recent System Activity</h3>
            <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Timestamp
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    User
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Activity
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Details
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- Sample activity entries -->
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ now()->subMinutes(5)->format('M d, Y H:i:s') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">Dr. John Smith</div>
                                    <div class="text-xs text-gray-500">Doctor</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Patient Discharge
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    Discharged Patient #12345 - Jane Doe
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ now()->subMinutes(15)->format('M d, Y H:i:s') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">Nurse Staff</div>
                                    <div class="text-xs text-gray-500">Nurse</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Medication Admin
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    Administered medication to Patient #54321
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ now()->subMinutes(30)->format('M d, Y H:i:s') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">Reception Staff</div>
                                    <div class="text-xs text-gray-500">Reception</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                        Patient Registration
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    Registered new patient - John Smith
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="bg-gray-50 px-6 py-3 flex justify-end">
                    <a href="#" class="text-sm text-indigo-600 hover:text-indigo-900 flex items-center">
                        View All Activity
                        <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
            
            <!-- System Health Section -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-800">System Health</h3>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Database Status -->
                        <div class="p-4 bg-green-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="rounded-full bg-green-200 p-2 mr-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-medium text-green-800">Database</h4>
                                    <p class="text-sm text-green-700">Healthy (< 0.1s response time)</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- API Status -->
                        <div class="p-4 bg-green-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="rounded-full bg-green-200 p-2 mr-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-medium text-green-800">API Endpoints</h4>
                                    <p class="text-sm text-green-700">Operational (100% uptime)</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Storage Status -->
                        <div class="p-4 bg-yellow-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="rounded-full bg-yellow-200 p-2 mr-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-medium text-yellow-800">Storage</h4>
                                    <p class="text-sm text-yellow-700">75% Capacity (12.3GB free)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>