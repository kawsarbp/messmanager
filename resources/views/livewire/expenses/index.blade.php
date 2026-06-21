<div>
    <nav class="border-b border-gray-200">
        <div class="max-w-5xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-6">
                <a href="{{ route('dashboard') }}" wire:navigate class="font-semibold text-sm text-gray-900">Dashboard</a>
                <a href="{{ route('members.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-gray-900">Members</a>
                <a href="{{ route('deposits.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-gray-900">Deposits</a>
                <a href="{{ route('expenses.index') }}" wire:navigate class="text-sm text-gray-900 font-medium">Expenses</a>
                <a href="{{ route('meals.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-gray-900">Meals</a>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('profile') }}" wire:navigate class="text-sm text-gray-500 hover:text-gray-900">Profile</a>
                <button wire:click="logout" wire:loading.attr="disabled" wire:target="logout" class="text-sm text-gray-500 hover:text-gray-900">
                    <span wire:loading.remove wire:target="logout">Logout</span>
                    <span wire:loading wire:target="logout">Logging out...</span>
                </button>
            </div>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-6 py-12">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-bold">Expenses</h1>
        </div>

        @if (Auth::user()->role_id === App\Enums\Role::Manager)
        <form wire:submit="save" class="mb-10 p-6 border border-gray-200 rounded-xl">
            <h2 class="font-semibold text-sm mb-4">{{ $editingId ? 'Edit Expense' : 'Add Expense' }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Amount</label>
                    <input type="number" step="0.01" wire:model.blur="amount" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 outline-none text-sm">
                    @error('amount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Category</label>
                    <input type="text" wire:model.blur="category" placeholder="e.g. Groceries, Utilities" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 outline-none text-sm">
                    @error('category') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Date</label>
                    <input type="date" wire:model.blur="date" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 outline-none text-sm">
                    @error('date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Description (optional)</label>
                    <input type="text" wire:model.blur="description" placeholder="Optional details" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 outline-none text-sm">
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button type="submit" wire:loading.attr="disabled" wire:target="save" class="bg-gray-900 hover:bg-gray-800 disabled:opacity-50 text-white text-sm font-semibold px-5 py-2 rounded-lg">
                    <span wire:loading.remove wire:target="save">{{ $editingId ? 'Update' : 'Add Expense' }}</span>
                    <span wire:loading wire:target="save">Submitting...</span>
                </button>
                @if ($editingId)
                    <button type="button" wire:click="cancelEdit" class="text-sm text-gray-500 hover:text-gray-700 font-medium">Cancel</button>
                @endif
            </div>
        </form>
        @endif

        <div class="border border-gray-200 rounded-xl overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left">
                        <th class="px-6 py-3 font-medium text-gray-600">Category</th>
                        <th class="px-6 py-3 font-medium text-gray-600">Amount</th>
                        <th class="px-6 py-3 font-medium text-gray-600">Date</th>
                        <th class="px-6 py-3 font-medium text-gray-600">Description</th>
                        @if (Auth::user()->role_id === App\Enums\Role::Manager)
                            <th class="px-6 py-3 font-medium text-gray-600"></th>
                            <th class="px-6 py-3 font-medium text-gray-600"></th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($expenses as $expense)
                        <tr>
                            <td class="px-6 py-3">{{ $expense->category }}</td>
                            <td class="px-6 py-3 font-medium"><span class="font-bold text-lg mr-0.5">&#2547;</span>{{ number_format($expense->amount, 2) }}</td>
                            <td class="px-6 py-3 text-gray-500">{{ $expense->date->format('M d, Y') }}</td>
                            <td class="px-6 py-3 text-gray-500">{{ $expense->description ?: '-' }}</td>
                            @if (Auth::user()->role_id === App\Enums\Role::Manager)
                                <td class="px-6 py-3 text-right">
                                    <button wire:click="editExpense({{ $expense->id }})" wire:loading.attr="disabled" wire:target="editExpense({{ $expense->id }})" class="disabled:opacity-50 text-gray-500 hover:text-gray-700 text-xs font-medium">Edit</button>
                                </td>
                                <td class="px-6 py-3 text-right">
                                    <button wire:click="deleteExpense({{ $expense->id }})" wire:loading.attr="disabled" wire:target="deleteExpense({{ $expense->id }})" wire:confirm="Are you sure you want to delete this expense?" class="disabled:opacity-50 text-red-500 hover:text-red-700 text-xs font-medium">Delete</button>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if ($expenses->isEmpty())
                <div class="p-12 text-center text-gray-400 text-sm">No expenses yet.</div>
            @endif
        </div>

        <div class="mt-6">{{ $expenses->links() }}</div>
    </main>
</div>
