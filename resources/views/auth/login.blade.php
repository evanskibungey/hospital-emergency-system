<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-6 text-center">
        <h2 class="text-xl font-semibold text-gray-800">Hospital Emergency Management System</h2>
        <p class="text-sm text-gray-600 mt-1">Enter your credentials to access the system</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ml-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    <div class="mt-6 text-center">
        <a href="/" class="text-sm text-indigo-600 hover:text-indigo-900">← Return to role selection</a>
    </div>

    <script>
        // Auto-fill email field based on the selected role from the welcome page
        document.addEventListener('DOMContentLoaded', function() {
            const selectedRole = localStorage.getItem('selectedRole');
            if (selectedRole) {
                const emailField = document.getElementById('email');
                
                switch(selectedRole) {
                    case 'admin':
                        emailField.value = 'admin@hospital.com';
                        break;
                    case 'reception':
                        emailField.value = 'reception@hospital.com';
                        break;
                    case 'nurse':
                        emailField.value = 'nurse@hospital.com';
                        break;
                    case 'doctor':
                        emailField.value = 'doctor@hospital.com';
                        break;
                }
                
                // Focus on password field since email is prefilled
                if (emailField.value) {
                    document.getElementById('password').focus();
                }
            }
        });
    </script>
</x-guest-layout>
