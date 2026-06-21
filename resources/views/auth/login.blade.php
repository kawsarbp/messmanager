<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Login - DIU Mess Management System</title>
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
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">Welcome back</h1>
                <p class="text-gray-500 text-sm mt-1">Sign in to your account</p>
            </div>

            <form method="POST" action="{{ route('authenticate') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
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

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-gray-600">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                        Remember me
                    </label>
                </div>

                <button type="submit"
                        class="w-full bg-gray-900 hover:bg-gray-800 text-white font-semibold py-2.5 rounded-lg transition-colors text-sm">
                    Sign in
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-8">
                Don't have an account?
                <a href="{{ route('register') }}" class="font-medium text-gray-900 hover:underline">Register</a>
            </p>
        </div>
    </body>
</html>
