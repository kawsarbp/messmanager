<div>
    <nav class="border-b border-gray-200">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between gap-4">
            <div class="flex items-center gap-4 sm:gap-6 overflow-x-auto pb-1 flex-1 min-w-0">
                <a href="{{ route('dashboard') }}" wire:navigate class="font-semibold text-sm text-gray-900 shrink-0">Dashboard</a>
                <a href="{{ route('members.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-gray-900 shrink-0">Members</a>
                <a href="{{ route('deposits.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-gray-900 shrink-0">Deposits</a>
                <a href="{{ route('expenses.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-gray-900 shrink-0">Expenses</a>
                <a href="{{ route('meals.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-gray-900 shrink-0">Meals</a>
            </div>
            <div class="relative shrink-0" x-data="{ open: false }" @click.outside="open = false">
                <button @click="open = ! open" class="flex items-center gap-1.5 text-sm text-gray-700 hover:text-gray-900">
                    <div class="w-8 h-8  flex items-center justify-center text-xs font-medium text-gray-600">
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
                    <a href="{{ route('months.index') }}" wire:navigate class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 font-medium">Month</a>
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
            <h1 class="text-xl sm:text-2xl font-bold">{{ Auth::user()->member->mess->name }}</h1>
            <p class="text-gray-500 text-sm mt-1">Member summary &mdash; {{ $summary->count() }} members</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="border border-gray-200 rounded-xl p-5 bg-blue-50">
                <p class="text-xs font-medium text-blue-600 uppercase tracking-wide">Total Deposits</p>
                <p class="text-2xl font-bold mt-1"><span class="font-bold text-lg mr-0.5">&#2547;</span>{{ number_format($totalDeposits, 2) }}</p>
            </div>
            <div class="border border-gray-200 rounded-xl p-5 bg-amber-50">
                <p class="text-xs font-medium text-amber-600 uppercase tracking-wide">Total Meals</p>
                <p class="text-2xl font-bold mt-1">{{ number_format($totalMealQty, 2) }}</p>
            </div>
            <div class="border border-gray-200 rounded-xl p-5 bg-rose-50">
                <p class="text-xs font-medium text-rose-600 uppercase tracking-wide">Total Expenses</p>
                <p class="text-2xl font-bold mt-1"><span class="font-bold text-lg mr-0.5">&#2547;</span>{{ number_format($totalExpenses, 2) }}</p>
            </div>
            <div class="border border-gray-200 rounded-xl p-5 bg-emerald-50">
                <p class="text-xs font-medium text-emerald-600 uppercase tracking-wide">Meal Rate</p>
                <p class="text-2xl font-bold mt-1"><span class="font-bold text-lg mr-0.5">&#2547;</span>{{ number_format($mealRate, 2) }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($summary as $item)
                <div class="border border-gray-200 rounded-xl p-5 {{ $item['is_me'] ? 'ring-2 ring-gray-900 bg-gray-50' : '' }}">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold text-sm">{{ $item['name'] }}</h3>
                        @if ($item['is_me'])
                            <span class="text-xs font-medium text-gray-500 bg-white px-2 py-0.5 rounded-full border border-gray-200">You</span>
                        @endif
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Deposits</span>
                            <span class="font-medium"><span class="font-bold text-lg mr-0.5">&#2547;</span>{{ number_format($item['total_deposits'], 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Meals</span>
                            <span class="font-medium">{{ number_format($item['total_meals'], 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm pt-2 border-t border-gray-100">
                            <span class="text-gray-500 font-medium">Balance</span>
                            <span class="font-semibold {{ $item['balance'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                <span class="font-bold text-lg mr-0.5">&#2547;</span>{{ number_format(abs($item['balance']), 2) }}
                                <span class="text-xs font-normal">{{ $item['balance'] >= 0 ? ' (Receivable)' : ' (Payable)' }}</span>
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($summary->isEmpty())
            <div class="border-2 border-dashed border-gray-200 rounded-xl p-8 sm:p-12 text-center">
                <p class="text-gray-400 text-sm">No members found.</p>
            </div>
        @endif
    </main>
</div>
