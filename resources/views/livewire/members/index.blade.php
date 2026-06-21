<div>
    <nav class="border-b border-gray-200">
        <div class="max-w-5xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-6">
                <a href="{{ route('dashboard') }}" wire:navigate class="font-semibold text-sm text-gray-900">Dashboard</a>
                <a href="{{ route('members.index') }}" wire:navigate class="text-sm text-gray-900 font-medium">Members</a>
                <a href="{{ route('deposits.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-gray-900">Deposits</a>
                <a href="{{ route('expenses.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-gray-900">Expenses</a>
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
            <div>
                <h1 class="text-2xl font-bold">Members</h1>
                <p class="text-gray-500 text-sm mt-1">{{ Auth::user()->member->mess->name }} &middot; {{ $this->members->count() }} {{ Str::plural('member', $this->members->count()) }}</p>
            </div>
        </div>

        <div class="border border-gray-200 rounded-xl overflow-hidden">
            @foreach ($this->members as $member)
                <div class="flex items-center justify-between px-6 py-4 {{ !$loop->last ? 'border-b border-gray-100' : '' }} {{ $member->user_id === Auth::id() ? 'bg-gray-50' : '' }}">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-sm font-medium text-gray-600 flex-shrink-0">
                            {{ $member->user->initials() }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-medium text-sm">{{ $member->user->name }}</span>
                                @if ($member->user->role_id === App\Enums\Role::Manager)
                                    <span class="text-xs font-medium bg-gray-900 text-white px-2 py-0.5 rounded-full">Manager</span>
                                @endif
                                @if ($member->user_id === Auth::id())
                                    <span class="text-xs text-gray-400">(You)</span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-400">{{ $member->user->email }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        @if ($member->status === App\Enums\VisibilityStatus::Active)
                            <span class="text-xs font-medium text-green-600 bg-green-50 px-2 py-0.5 rounded-full">Active</span>
                        @else
                            <span class="text-xs font-medium text-red-600 bg-red-50 px-2 py-0.5 rounded-full">Inactive</span>
                        @endif
                        @if (Auth::user()->role_id === App\Enums\Role::Manager && $member->user_id !== Auth::id())
                            <button wire:click="toggleStatus({{ $member->id }})"
                                    wire:loading.attr="disabled"
                                    wire:target="toggleStatus({{ $member->id }})"
                                    wire:confirm="Are you sure you want to {{ $member->status === App\Enums\VisibilityStatus::Active ? 'deactivate' : 'activate' }} this member?"
                                    class="disabled:opacity-50 text-xs font-medium text-gray-500 hover:text-gray-900 underline transition-colors">
                                <span wire:loading.remove wire:target="toggleStatus({{ $member->id }})">{{ $member->status === App\Enums\VisibilityStatus::Active ? 'Deactivate' : 'Activate' }}</span>
                                <span wire:loading wire:target="toggleStatus({{ $member->id }})">Processing...</span>
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @if ($this->members->isEmpty())
            <div class="border-2 border-dashed border-gray-200 rounded-xl p-12 text-center">
                <p class="text-gray-400 text-sm">No members found.</p>
            </div>
        @endif
    </main>
</div>
