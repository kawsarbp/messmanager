<div>
    <nav class="border-b border-gray-200">
        <div class="max-w-5xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-6">
                <a href="{{ route('dashboard') }}" wire:navigate class="font-semibold text-sm text-gray-900">Dashboard</a>
                <a href="{{ route('members.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-gray-900">Members</a>
                <a href="{{ route('deposits.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-gray-900">Deposits</a>
                <a href="{{ route('expenses.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-gray-900">Expenses</a>
                <a href="{{ route('meals.index') }}" wire:navigate class="text-sm text-gray-900 font-medium">Meals</a>
            </div>
            <button wire:click="logout" wire:loading.attr="disabled" wire:target="logout" class="text-sm text-gray-500 hover:text-gray-900">
                <span wire:loading.remove wire:target="logout">Logout</span>
                <span wire:loading wire:target="logout">Logging out...</span>
            </button>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-6 py-12">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-bold">Meals</h1>
        </div>

        @if (Auth::user()->role_id === App\Enums\Role::Manager)
        <form wire:submit="save" class="mb-10 p-6 border border-gray-200 rounded-xl">
            <h2 class="font-semibold text-sm mb-4">{{ $editingId ? 'Edit Meal' : 'Add Meal' }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-4">
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
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Type</label>
                    <select wire:model.blur="type" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 outline-none text-sm">
                        <option value="">Select type</option>
                        @foreach ($mealTypes as $mealType)
                            <option value="{{ $mealType->value }}">{{ ucfirst($mealType->name) }} ({{ $mealType->rate() }})</option>
                        @endforeach
                    </select>
                    @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Quantity</label>
                    <input type="number" step="0.01" wire:model.blur="quantity" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 outline-none text-sm">
                    @error('quantity') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
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

        <div class="mb-6 flex items-end gap-3">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1.5">Filter by member</label>
                <select wire:model="filterMemberId" class="w-56 px-3 py-2 rounded-lg border border-gray-300 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 outline-none text-sm">
                    <option value="">All members</option>
                    @foreach ($members as $member)
                        <option value="{{ $member->id }}">{{ $member->user->name }}</option>
                    @endforeach
                </select>
            </div>
            <button wire:click="filter" wire:loading.attr="disabled" wire:target="filter" class="px-4 py-2 bg-gray-900 hover:bg-gray-800 disabled:opacity-50 text-white text-sm font-medium rounded-lg">
                <span wire:loading.remove wire:target="filter">Filter</span>
                <span wire:loading wire:target="filter">Loading...</span>
            </button>
        </div>

        <div class="border border-gray-200 rounded-xl overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left">
                        <th class="px-6 py-3 font-medium text-gray-600">Member</th>
                        <th class="px-6 py-3 font-medium text-gray-600">Date</th>
                        <th class="px-6 py-3 font-medium text-gray-600">Type</th>
                        <th class="px-6 py-3 font-medium text-gray-600">Quantity</th>
                        @if (Auth::user()->role_id === App\Enums\Role::Manager)
                            <th class="px-6 py-3 font-medium text-gray-600"></th>
                            <th class="px-6 py-3 font-medium text-gray-600"></th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($meals as $meal)
                        <tr>
                            <td class="px-6 py-3">{{ $meal->member->user->name }}</td>
                            <td class="px-6 py-3 text-gray-500">{{ $meal->date->format('M d, Y') }}</td>
                            <td class="px-6 py-3">{{ ucfirst($meal->type->name) }}</td>
                            <td class="px-6 py-3">{{ number_format($meal->quantity, 2) }}</td>
                            @if (Auth::user()->role_id === App\Enums\Role::Manager)
                                <td class="px-6 py-3 text-right">
                                    <button wire:click="editMeal({{ $meal->id }})" wire:loading.attr="disabled" wire:target="editMeal({{ $meal->id }})" class="disabled:opacity-50 text-gray-500 hover:text-gray-700 text-xs font-medium">Edit</button>
                                </td>
                                <td class="px-6 py-3 text-right">
                                    <button wire:click="deleteMeal({{ $meal->id }})" wire:loading.attr="disabled" wire:target="deleteMeal({{ $meal->id }})" wire:confirm="Are you sure you want to delete this meal?" class="disabled:opacity-50 text-red-500 hover:text-red-700 text-xs font-medium">Delete</button>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if ($meals->isEmpty())
                <div class="p-12 text-center text-gray-400 text-sm">No meals recorded yet.</div>
            @endif
        </div>

        <div class="mt-6">{{ $meals->links() }}</div>
    </main>
</div>
