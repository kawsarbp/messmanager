<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Dashboard - DIU Mess Management System</title>
        @fonts
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-white text-gray-900 min-h-screen">
        <nav class="border-b border-gray-200">
            <div class="max-w-5xl mx-auto px-6 py-4 flex items-center justify-between">
                <span class="font-semibold text-sm">DIU Mess Management</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-gray-500 hover:text-gray-900 transition-colors">Logout</button>
                </form>
            </div>
        </nav>

        <main class="max-w-5xl mx-auto px-6 py-12">
            <h1 class="text-2xl font-bold mb-2">Welcome, {{ Auth::user()->name }}</h1>
            <p class="text-gray-500 text-sm mb-8">
                @if (Auth::user()->member)
                    You are a member of <strong>{{ Auth::user()->member->mess->name }}</strong>
                @else
                    You are not assigned to any mess yet.
                @endif
            </p>

            <div class="border-2 border-dashed border-gray-200 rounded-xl p-12 text-center">
                <p class="text-gray-400 text-sm">Dashboard content coming soon...</p>
            </div>
        </main>
    </body>
</html>
