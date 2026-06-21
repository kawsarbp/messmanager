<div>
    <nav class="border-b border-gray-200">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between gap-4 flex-wrap">
            <div class="flex items-center gap-4 sm:gap-6 overflow-x-auto pb-1">
                <a href="{{ route('dashboard') }}" wire:navigate class="font-semibold text-sm text-gray-900 shrink-0">Dashboard</a>
                <a href="{{ route('members.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-gray-900 shrink-0">Members</a>
                <a href="{{ route('deposits.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-gray-900 shrink-0">Deposits</a>
                <a href="{{ route('expenses.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-gray-900 shrink-0">Expenses</a>
                <a href="{{ route('meals.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-gray-900 shrink-0">Meals</a>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ route('profile') }}" wire:navigate class="text-sm text-gray-500 hover:text-gray-900 hidden sm:inline">Profile</a>
                <button wire:click="logout" wire:loading.attr="disabled" wire:target="logout" class="text-sm text-gray-500 hover:text-gray-900">
                    <span wire:loading.remove wire:target="logout">Logout</span>
                    <span wire:loading wire:target="logout">Logging out...</span>
                </button>
            </div>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-4 sm:px-6 py-6 sm:py-12">
        <div class="mb-6 sm:mb-8">
            <h1 class="text-xl sm:text-2xl font-bold">{{ Auth::user()->member->mess->name }}</h1>
            <p class="text-gray-500 text-sm mt-1">Member summary &mdash; {{ $summary->count() }} members</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
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
