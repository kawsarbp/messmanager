<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Members - DIU Mess Management System</title>
        @fonts
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-white text-gray-900 min-h-screen">
        <nav class="border-b border-gray-200">
            <div class="max-w-5xl mx-auto px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-6">
                    <a href="{{ route('dashboard') }}" class="font-semibold text-sm text-gray-900">DIU Mess Management</a>
                    <a href="{{ route('members.index') }}" class="text-sm text-gray-500 hover:text-gray-900 transition-colors">Members</a>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-gray-500 hover:text-gray-900 transition-colors">Logout</button>
                </form>
            </div>
        </nav>

        <main class="max-w-5xl mx-auto px-6 py-12">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-2xl font-bold">Members</h1>
                    <p class="text-gray-500 text-sm mt-1">{{ $mess->name }} &middot; {{ $members->count() }} {{ Str::plural('member', $members->count()) }}</p>
                </div>
            </div>

            <div class="border border-gray-200 rounded-xl overflow-hidden">
                @foreach ($members as $member)
                    <div class="flex items-center justify-between px-6 py-4 {{ !$loop->last ? 'border-b border-gray-100' : '' }} {{ $member->user_id === Auth::id() ? 'bg-gray-50' : '' }}">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-sm font-medium text-gray-600 flex-shrink-0">
                                {{ $member->user->initials() }}
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-medium text-sm">{{ $member->user->name }}</span>
                                    @if ($member->user->role_id === App\Enums\Role::Manager)
                                        <span class="text-xs font-medium bg-gray-900 text-white px-2 py-0.5 rounded-full">Manager</span>
                                    @endif
                                    @if ($member->user_id === Auth::id())
                                        <span class="text-xs text-gray-400">(You)</span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-400">{{ $member->user->email }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($members->isEmpty())
                <div class="border-2 border-dashed border-gray-200 rounded-xl p-12 text-center">
                    <p class="text-gray-400 text-sm">No members found.</p>
                </div>
            @endif
        </main>
    </body>
</html>
