<div>
    <nav class="border-b border-gray-200">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between gap-4">
            <div class="flex items-center gap-4 sm:gap-6 overflow-x-auto pb-1 flex-1 min-w-0">
                <a href="{{ route('dashboard') }}" wire:navigate class="text-sm text-gray-500 hover:text-gray-900 shrink-0">Dashboard</a>
                <a href="{{ route('members.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-gray-900 shrink-0">Members</a>
                <a href="{{ route('deposits.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-gray-900 shrink-0">Deposits</a>
                <a href="{{ route('expenses.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-gray-900 shrink-0">Expenses</a>
                <a href="{{ route('meals.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-gray-900 shrink-0">Meals</a>
            </div>
            <div class="relative shrink-0" x-data="{ open: false }" @click.outside="open = false">
                <button @click="open = ! open" class="flex items-center gap-1.5 text-sm text-gray-700 hover:text-gray-900">
                    <div class="w-8 h-8 flex items-center justify-center text-xs font-medium text-gray-600">
                        Setting
                    </div>
                    <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>
                <div x-show="open"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     @click="open = false"
                     class="absolute right-0 mt-2 w-48 bg-white rounded-xl border border-gray-200 shadow-lg py-1 z-50">
                    <a href="{{ route('profile') }}" wire:navigate class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">Profile</a>
                    @if (Auth::user()->role_id === App\Enums\Role::Manager)
                        <a href="{{ route('manager.transfer') }}" wire:navigate class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">Transfer Manager</a>
                    @endif
                    <button wire:click="logout"
                            wire:loading.attr="disabled"
                            wire:target="logout"
                            class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 disabled:opacity-50">
                        <span wire:loading.remove wire:target="logout">Logout</span>
                        <span wire:loading wire:target="logout">Logging out...</span>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-4 sm:px-6 py-6 sm:py-12">
        <div class="mb-6 sm:mb-8">
            <h1 class="text-xl sm:text-2xl font-bold">Transfer Manager</h1>
            <p class="text-gray-500 text-sm mt-1">Select a member to transfer the manager role. You will be demoted to a regular member and logged out.</p>
        </div>

        <div class="border border-gray-200 rounded-xl overflow-hidden">
            @forelse ($members as $member)
                <div class="flex items-center justify-between px-4 sm:px-6 py-4 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-sm font-medium text-gray-600 shrink-0">
                            {{ $member->user->initials() }}
                        </div>
                        <div>
                            <div class="font-medium text-sm">{{ $member->user->name }}</div>
                            <p class="text-xs text-gray-400">{{ $member->user->email }}</p>
                        </div>
                    </div>
                    <button wire:click="transfer({{ $member->id }})"
                            wire:loading.attr="disabled"
                            wire:target="transfer({{ $member->id }})"
                            wire:confirm="Transfer manager role to {{ $member->user->name }}? You will be demoted to a regular member and logged out."
                            class="shrink-0 px-4 py-2 bg-gray-900 hover:bg-gray-800 disabled:opacity-50 text-white text-xs font-medium rounded-lg">
                        <span wire:loading.remove wire:target="transfer({{ $member->id }})">Make Manager</span>
                        <span wire:loading wire:target="transfer({{ $member->id }})">Transferring...</span>
                    </button>
                </div>
            @empty
                <div class="p-8 sm:p-12 text-center text-gray-400 text-sm">No other members to transfer to.</div>
            @endforelse
        </div>
    </main>
</div>
