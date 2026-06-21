<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Register - DIU Mess Management System</title>
        @fonts
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-white text-gray-900 flex items-center justify-center min-h-screen p-6">
        <div class="w-full max-w-sm mx-auto">
            <div class="text-center mb-10">
                <a href="/" class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-700 text-sm mb-6 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                    </svg>
                    Back to home
                </a>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">Create an account</h1>
                <p class="text-gray-500 text-sm mt-1">Join your mess with a valid code</p>
            </div>

            <form method="POST" action="{{ route('register.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                           class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 outline-none transition-colors text-sm @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 outline-none transition-colors text-sm @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" id="password" required
                           class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 outline-none transition-colors text-sm @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                           class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 outline-none transition-colors text-sm">
                </div>

                <div>
                    <label for="mess_code" class="block text-sm font-medium text-gray-700 mb-1">Mess Code</label>
                    <input type="text" name="mess_code" id="mess_code" value="{{ old('mess_code') }}" required placeholder="Enter your mess code"
                           class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 outline-none transition-colors text-sm @error('mess_code') border-red-500 @enderror">
                    @error('mess_code')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="w-full bg-gray-900 hover:bg-gray-800 text-white font-semibold py-2.5 rounded-lg transition-colors text-sm">
                    Register
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-8">
                Already have an account?
                <a href="{{ route('login') }}" class="font-medium text-gray-900 hover:underline">Sign in</a>
            </p>
        </div>
    </body>
</html>
