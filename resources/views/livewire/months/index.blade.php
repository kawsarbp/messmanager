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
        @if ($viewingMonth)
            {{-- Viewing a specific month --}}
            <div class="mb-6">
                <button wire:click="backToMonths" class="text-sm text-gray-500 hover:text-gray-700 font-medium">&larr; Back to Months</button>
            </div>

            <h1 class="text-xl sm:text-2xl font-bold mb-6 sm:mb-8">{{ $viewingMonth->label }}</h1>

            @php
                $members = $viewingMonth->mess->members()->with('user')->where('status', App\Enums\VisibilityStatus::Active)->get();
                $memberIds = $members->pluck('id');

                $totalExpenses = $viewingMonth->expenses()->sum('amount');

                $deposits = $viewingMonth->deposits()
                    ->whereIn('member_id', $memberIds)
                    ->selectRaw('member_id, sum(amount) as total')
                    ->groupBy('member_id')
                    ->pluck('total', 'member_id');

                $meals = $viewingMonth->meals()
                    ->whereIn('member_id', $memberIds)
                    ->selectRaw('member_id, sum(quantity) as total')
                    ->groupBy('member_id')
                    ->pluck('total', 'member_id');

                $totalMealQty = $meals->sum();
                $totalDeposits = $deposits->sum();
                $currentMemberId = Auth::user()->member->id;
            @endphp

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
                @foreach ($members as $member)
                    @php
                        $memberDeposits = (float) ($deposits[$member->id] ?? 0);
                        $memberMeals = (float) ($meals[$member->id] ?? 0);
                        $expenseShare = $totalMealQty > 0
                            ? ($memberMeals / $totalMealQty) * $totalExpenses
                            : $totalExpenses / max($members->count(), 1);
                        $balance = $memberDeposits - $expenseShare;
                    @endphp
                    <div class="border border-gray-200 rounded-xl p-5 {{ $member->id === $currentMemberId ? 'ring-2 ring-gray-900 bg-gray-50' : '' }}">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-semibold text-sm">{{ $member->user->name }}</h3>
                            @if ($member->id === $currentMemberId)
                                <span class="text-xs font-medium text-gray-500 bg-white px-2 py-0.5 rounded-full border border-gray-200">You</span>
                            @endif
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500">Deposits</span>
                                <span class="font-medium"><span class="font-bold text-lg mr-0.5">&#2547;</span>{{ number_format($memberDeposits, 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500">Meals</span>
                                <span class="font-medium">{{ number_format($memberMeals, 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm pt-2 border-t border-gray-100">
                                <span class="text-gray-500 font-medium">Balance</span>
                                <span class="font-semibold {{ $balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    <span class="font-bold text-lg mr-0.5">&#2547;</span>{{ number_format(abs($balance), 2) }}
                                    <span class="text-xs font-normal">{{ $balance >= 0 ? ' (Receivable)' : ' (Payable)' }}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        @else
            {{-- Month listing --}}
            <div class="mb-6 sm:mb-8">
                <h1 class="text-xl sm:text-2xl font-bold">Months</h1>
                <p class="text-gray-500 text-sm mt-1">Manage monthly periods for {{ Auth::user()->member->mess->name }}</p>
            </div>

            @if ($activeMonth)
                <div class="border border-green-200 bg-green-50 rounded-xl p-5 mb-8">
                    <div class="flex items-center justify-between flex-wrap gap-3">
                        <div>
                            <h2 class="font-semibold text-sm text-green-800">Active Month: {{ $activeMonth->label }}</h2>
                            <p class="text-xs text-green-600 mt-0.5">Started {{ $activeMonth->start_date->format('M d, Y') }}</p>
                        </div>
                        @if (Auth::user()->role_id === App\Enums\Role::Manager)
                            <div>
                                @if ($confirmingClose)
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm text-red-600 font-medium">Are you sure?</span>
                                        <button wire:click="closeMonth" wire:loading.attr="disabled" wire:target="closeMonth" class="px-4 py-2 bg-red-600 hover:bg-red-700 disabled:opacity-50 text-white text-xs font-medium rounded-lg">
                                            <span wire:loading.remove wire:target="closeMonth">Yes, Close Month</span>
                                            <span wire:loading wire:target="closeMonth">Closing...</span>
                                        </button>
                                        <button wire:click="cancelClose" class="px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 text-xs font-medium rounded-lg">Cancel</button>
                                    </div>
                                @else
                                    <button wire:click="confirmClose" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-lg">Close Month</button>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <h2 class="font-semibold text-sm text-gray-700 mb-4">All Months</h2>

            <div class="border border-gray-200 rounded-xl overflow-hidden">
                @forelse ($months as $month)
                    <div class="flex items-center justify-between px-4 sm:px-6 py-4 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-medium text-sm">{{ $month->label }}</span>
                                @if ($month->is_active)
                                    <span class="text-xs font-medium text-green-600 bg-green-50 px-2 py-0.5 rounded-full">Active</span>
                                @else
                                    <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full">Closed</span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-400 mt-0.5">
                                {{ $month->start_date->format('M d, Y') }}
                                @if ($month->end_date)
                                    &ndash; {{ $month->end_date->format('M d, Y') }}
                                @else
                                    &ndash; Present
                                @endif
                            </p>
                        </div>
                        <button wire:click="viewMonth({{ $month->id }})" class="text-xs font-medium text-gray-500 hover:text-gray-900 underline shrink-0">View Report</button>
                    </div>
                @empty
                    <div class="p-8 sm:p-12 text-center text-gray-400 text-sm">No months yet.</div>
                @endforelse
            </div>
        @endif
    </main>
</div>
