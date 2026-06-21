<div>
    <nav class="border-b border-gray-200">
        <div class="max-w-5xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-6">
                <a href="{{ route('dashboard') }}" wire:navigate class="font-semibold text-sm text-gray-900">DIU Mess Management</a>
                <a href="{{ route('members.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-gray-900">Members</a>
                <a href="{{ route('deposits.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-gray-900">Deposits</a>
                <a href="{{ route('expenses.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-gray-900">Expenses</a>
                <a href="{{ route('meals.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-gray-900">Meals</a>
            </div>
            <button wire:click="logout" class="text-sm text-gray-500 hover:text-gray-900 transition-colors">Logout</button>
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
</div>
