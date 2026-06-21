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
        <div class="max-w-lg mx-auto">
            <h1 class="text-xl sm:text-2xl font-bold mb-2">Profile</h1>
            <p class="text-gray-500 text-sm mb-6 sm:mb-8">Manage your account information</p>

            <form wire:submit="save" class="p-4 sm:p-6 border border-gray-200 rounded-xl space-y-5">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" wire:model.blur="name" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 outline-none text-sm">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" wire:model.blur="email" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 outline-none text-sm">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="border-t border-gray-100 pt-5">
                    <h2 class="font-semibold text-sm mb-4">Change Password (optional)</h2>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">New Password</label>
                            <input type="password" wire:model.blur="new_password" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 outline-none text-sm">
                            @error('new_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Confirm New Password</label>
                            <input type="password" wire:model.blur="new_password_confirmation" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 outline-none text-sm">
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" wire:loading.attr="disabled" wire:target="save" class="bg-gray-900 hover:bg-gray-800 disabled:opacity-50 text-white text-sm font-semibold px-5 py-2 rounded-lg">
                        <span wire:loading.remove wire:target="save">Save</span>
                        <span wire:loading wire:target="save">Saving...</span>
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>
