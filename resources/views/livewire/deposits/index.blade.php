<div>
    <nav class="border-b border-gray-200">
        <div class="max-w-5xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-6">
                <a href="{{ route('dashboard') }}" wire:navigate class="font-semibold text-sm text-gray-900">DIU Mess Management</a>
                <a href="{{ route('members.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-gray-900">Members</a>
                <a href="{{ route('deposits.index') }}" wire:navigate class="text-sm text-gray-900 font-medium">Deposits</a>
                <a href="{{ route('expenses.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-gray-900">Expenses</a>
                <a href="{{ route('meals.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-gray-900">Meals</a>
            </div>
            <button wire:click="logout" class="text-sm text-gray-500 hover:text-gray-900">Logout</button>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-6 py-12">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-bold">Deposits</h1>
        </div>

        @if (session('message'))
            <div class="mb-6 text-sm text-green-600 bg-green-50 rounded-lg px-4 py-3">{{ session('message') }}</div>
        @endif

        <form wire:submit="save" class="mb-10 p-6 border border-gray-200 rounded-xl">
            <h2 class="font-semibold text-sm mb-4">Add Deposit</h2>
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
                    <label class="block text-xs font-medium text-gray-700 mb-1">Amount</label>
                    <input type="number" step="0.01" wire:model.blur="amount" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 outline-none text-sm">
                    @error('amount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Date</label>
                    <input type="date" wire:model.blur="date" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 outline-none text-sm">
                    @error('date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Note (optional)</label>
                    <input type="text" wire:model.blur="note" placeholder="e.g. Monthly deposit" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 outline-none text-sm">
                </div>
            </div>
            <button type="submit" class="bg-gray-900 hover:bg-gray-800 text-white text-sm font-semibold px-5 py-2 rounded-lg">Add Deposit</button>
        </form>

        <div class="border border-gray-200 rounded-xl overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left">
                        <th class="px-6 py-3 font-medium text-gray-600">Member</th>
                        <th class="px-6 py-3 font-medium text-gray-600">Amount</th>
                        <th class="px-6 py-3 font-medium text-gray-600">Date</th>
                        <th class="px-6 py-3 font-medium text-gray-600">Note</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($deposits as $deposit)
                        <tr>
                            <td class="px-6 py-3">{{ $deposit->member->user->name }}</td>
                            <td class="px-6 py-3 font-medium">${{ number_format($deposit->amount, 2) }}</td>
                            <td class="px-6 py-3 text-gray-500">{{ $deposit->date->format('M d, Y') }}</td>
                            <td class="px-6 py-3 text-gray-500">{{ $deposit->note ?: '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if ($deposits->isEmpty())
                <div class="p-12 text-center text-gray-400 text-sm">No deposits yet.</div>
            @endif
        </div>

        <div class="mt-6">{{ $deposits->links() }}</div>
    </main>
</div>
