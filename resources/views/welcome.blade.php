<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hospital Emergency Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f3f4f6;
        }
    </style>
</head>
<body class="antialiased">
    <div class="relative flex items-center justify-center min-h-screen bg-gray-100 sm:items-center py-4 sm:pt-0">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-center pt-8 sm:pt-0">
                <h1 class="text-3xl font-bold text-gray-800">Hospital Emergency Management System</h1>
            </div>

            <div class="mt-8 text-center">
                <p class="text-lg text-gray-600 mb-8">
                    Welcome to our Hospital Emergency Management System. Please select your portal to log in.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-8">
                <!-- Admin Portal -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg hover:shadow-2xl transition-shadow duration-300">
                    <div class="p-6 text-center">
                        <div class="bg-blue-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800 mb-2">Administrator</h2>
                        <p class="text-gray-600 mb-4">System administration with full access to all features</p>
                        <div class="mt-4">
                            <p class="text-sm text-gray-500 mb-1">Demo Account:</p>
                            <p class="text-sm text-gray-900 font-medium mb-4">admin@hospital.com / password</p>
                        </div>
                        <a href="{{ route('login') }}" onclick="localStorage.setItem('selectedRole', 'admin')" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-300">
                            Login as Admin
                        </a>
                    </div>
                </div>

                <!-- Reception Portal -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg hover:shadow-2xl transition-shadow duration-300">
                    <div class="p-6 text-center">
                        <div class="bg-green-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800 mb-2">Reception</h2>
                        <p class="text-gray-600 mb-4">Patient registration, intake, and visitor management</p>
                        <div class="mt-4">
                            <p class="text-sm text-gray-500 mb-1">Demo Account:</p>
                            <p class="text-sm text-gray-900 font-medium mb-4">reception@hospital.com / password</p>
                        </div>
                        <a href="{{ route('login') }}" onclick="localStorage.setItem('selectedRole', 'reception')" class="inline-block bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-300">
                            Login as Reception
                        </a>
                    </div>
                </div>

                <!-- Nurse Portal -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg hover:shadow-2xl transition-shadow duration-300">
                    <div class="p-6 text-center">
                        <div class="bg-indigo-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800 mb-2">Nurse</h2>
                        <p class="text-gray-600 mb-4">Patient care, vital signs, medication management</p>
                        <div class="mt-4">
                            <p class="text-sm text-gray-500 mb-1">Demo Account:</p>
                            <p class="text-sm text-gray-900 font-medium mb-4">nurse@hospital.com / password</p>
                        </div>
                        <a href="{{ route('login') }}" onclick="localStorage.setItem('selectedRole', 'nurse')" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-300">
                            Login as Nurse
                        </a>
                    </div>
                </div>

                <!-- Doctor Portal -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg hover:shadow-2xl transition-shadow duration-300">
                    <div class="p-6 text-center">
                        <div class="bg-red-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800 mb-2">Doctor</h2>
                        <p class="text-gray-600 mb-4">Patient diagnosis, treatment, and care planning</p>
                        <div class="mt-4">
                            <p class="text-sm text-gray-500 mb-1">Demo Account:</p>
                            <p class="text-sm text-gray-900 font-medium mb-4">doctor@hospital.com / password</p>
                        </div>
                        <a href="{{ route('login') }}" onclick="localStorage.setItem('selectedRole', 'doctor')" class="inline-block bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-300">
                            Login as Doctor
                        </a>
                    </div>
                </div>
            </div>

            <div class="flex justify-center mt-12">
                <p class="text-gray-500 text-sm">
                    Hospital Emergency Management System © {{ date('Y') }}
                </p>
            </div>
        </div>
    </div>

    <script>
        // Auto-fill email field on login page based on selected role
        document.addEventListener('DOMContentLoaded', function() {
            // Add event listeners to store the selected role
            const loginButtons = document.querySelectorAll('a[onclick]');
            loginButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    const role = this.getAttribute('onclick').match(/selectedRole', '(.+?)'/)[1];
                    localStorage.setItem('selectedRole', role);
                });
            });
        });
    </script>
</body>
</html>
