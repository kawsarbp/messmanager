<div>
    <nav class="border-b border-gray-200">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between gap-4">
            <div class="flex items-center gap-4 sm:gap-6 overflow-x-auto pb-1 flex-1 min-w-0">
                <a href="{{ route('dashboard') }}" wire:navigate class="font-semibold text-sm text-gray-900 shrink-0">Dashboard</a>
                <a href="{{ route('members.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-gray-900 shrink-0">Members</a>
                <a href="{{ route('deposits.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-gray-900 shrink-0">Deposits</a>
                <a href="{{ route('expenses.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-gray-900 shrink-0">Expenses</a>
                <a href="{{ route('meals.index') }}" wire:navigate class="text-sm text-gray-900 font-medium shrink-0">Meals</a>
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
        <h1 class="text-xl sm:text-2xl font-bold mb-6 sm:mb-8">Meals</h1>

        @if (Auth::user()->role_id === App\Enums\Role::Manager)
        <form wire:submit="save" class="mb-10 p-6 border border-gray-200 rounded-xl">
            <h2 class="font-semibold text-sm mb-4">{{ $editingId ? 'Edit Meal' : 'Add Meal' }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Member</label>
                    <select wire:model.blur="member_id" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 outline-none text-sm">
                        <option value="">Select member</option>
                        @foreach ($members as $member)
                            <option value="{{ $member->id }}">{{ $member->user->name }}</option>
                        @endforeach
                    </select>
                    @error('member_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Date</label>
                    <input type="date" wire:model.blur="date" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 outline-none text-sm">
                    @error('date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-xs font-medium text-gray-700 mb-2">Types</label>
                <div class="flex flex-wrap gap-4">
                    @foreach ($mealTypes as $mealType)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model="types" value="{{ $mealType->value }}" class="rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                            <span class="text-sm">{{ ucfirst($mealType->name) }} ({{ $mealType->rate() }})</span>
                        </label>
                    @endforeach
                </div>
                @error('types') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                @error('types.*') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="flex items-center gap-3">
                <button type="submit" wire:loading.attr="disabled" wire:target="save" class="bg-gray-900 hover:bg-gray-800 disabled:opacity-50 text-white text-sm font-semibold px-5 py-2 rounded-lg">
                    <span wire:loading.remove wire:target="save">{{ $editingId ? 'Update' : 'Add Meal' }}</span>
                    <span wire:loading wire:target="save">Submitting...</span>
                </button>
                @if ($editingId)
                    <button type="button" wire:click="cancelEdit" class="text-sm text-gray-500 hover:text-gray-700 font-medium">Cancel</button>
                @endif
            </div>
        </form>
        @endif

        <div class="mb-6 flex flex-col sm:flex-row items-start sm:items-end gap-3">
            <div class="w-full sm:w-auto">
                <label class="block text-xs font-medium text-gray-700 mb-1.5">Filter by member</label>
                <select wire:model="filterMemberId" class="w-full sm:w-56 px-3 py-2 rounded-lg border border-gray-300 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 outline-none text-sm">
                    <option value="">All members</option>
                    @foreach ($members as $member)
                        <option value="{{ $member->id }}">{{ $member->user->name }}</option>
                    @endforeach
                </select>
            </div>
            <button wire:click="filter" wire:loading.attr="disabled" wire:target="filter" class="w-full sm:w-auto px-4 py-2 bg-gray-900 hover:bg-gray-800 disabled:opacity-50 text-white text-sm font-medium rounded-lg">
                <span wire:loading.remove wire:target="filter">Filter</span>
                <span wire:loading wire:target="filter">Loading...</span>
            </button>
        </div>

        <div class="border border-gray-200 rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left">
                        <th class="px-6 py-3 font-medium text-gray-600">Member</th>
                        <th class="px-6 py-3 font-medium text-gray-600">Date</th>
                        <th class="px-6 py-3 font-medium text-gray-600">Breakfast</th>
                        <th class="px-6 py-3 font-medium text-gray-600">Lunch</th>
                        <th class="px-6 py-3 font-medium text-gray-600">Dinner</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($meals as $meal)
                        <tr>
                            <td class="px-6 py-3">{{ $meal->member_name }}</td>
                            <td class="px-6 py-3 text-gray-500">{{ \Carbon\Carbon::parse($meal->date)->format('M d, Y') }}</td>
                            <td class="px-6 py-3">
                                @if ($meal->breakfast_quantity !== null)
                                    <div class="flex items-center gap-2">
                                        <span>{{ number_format($meal->breakfast_quantity, 2) }}</span>
                                        @if (Auth::user()->role_id === App\Enums\Role::Manager)
                                            <button wire:click="editMeal({{ $meal->breakfast_id }})" wire:loading.attr="disabled" class="disabled:opacity-50 text-gray-500 hover:text-gray-700 text-xs font-medium">Edit</button>
                                            <button wire:click="deleteMeal({{ $meal->breakfast_id }})" wire:loading.attr="disabled" wire:confirm="Are you sure you want to delete this meal?" class="disabled:opacity-50 text-red-500 hover:text-red-700 text-xs font-medium">Delete</button>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-3">
                                @if ($meal->lunch_quantity !== null)
                                    <div class="flex items-center gap-2">
                                        <span>{{ number_format($meal->lunch_quantity, 2) }}</span>
                                        @if (Auth::user()->role_id === App\Enums\Role::Manager)
                                            <button wire:click="editMeal({{ $meal->lunch_id }})" wire:loading.attr="disabled" class="disabled:opacity-50 text-gray-500 hover:text-gray-700 text-xs font-medium">Edit</button>
                                            <button wire:click="deleteMeal({{ $meal->lunch_id }})" wire:loading.attr="disabled" wire:confirm="Are you sure you want to delete this meal?" class="disabled:opacity-50 text-red-500 hover:text-red-700 text-xs font-medium">Delete</button>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-3">
                                @if ($meal->dinner_quantity !== null)
                                    <div class="flex items-center gap-2">
                                        <span>{{ number_format($meal->dinner_quantity, 2) }}</span>
                                        @if (Auth::user()->role_id === App\Enums\Role::Manager)
                                            <button wire:click="editMeal({{ $meal->dinner_id }})" wire:loading.attr="disabled" class="disabled:opacity-50 text-gray-500 hover:text-gray-700 text-xs font-medium">Edit</button>
                                            <button wire:click="deleteMeal({{ $meal->dinner_id }})" wire:loading.attr="disabled" wire:confirm="Are you sure you want to delete this meal?" class="disabled:opacity-50 text-red-500 hover:text-red-700 text-xs font-medium">Delete</button>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            @if ($meals->isEmpty())
                <div class="p-8 sm:p-12 text-center text-gray-400 text-sm">No meals yet.</div>
            @endif
        </div>

        <div class="mt-6">{{ $meals->links() }}</div>
    </main>
</div>
