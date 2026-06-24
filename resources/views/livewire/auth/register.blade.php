<div class="flex items-center justify-center min-h-screen p-6">
    <div class="w-full max-w-sm mx-auto">
        <div class="text-center mb-10">
            <a href="{{ route('home') }}" wire:navigate class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-700 text-sm mb-6 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
                Back to home
            </a>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Create an account</h1>
            <p class="text-gray-500 text-sm mt-1">Join your mess with a valid code</p>
        </div>

        <form wire:submit="register" class="space-y-5">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input type="text" wire:model.blur="name" id="name" autofocus
                       class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 outline-none transition-colors text-sm">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" wire:model.blur="email" id="email"
                       class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 outline-none transition-colors text-sm">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div x-data="{ show: false }">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <div class="relative">
                    <input :type="show ? 'text' : 'password'" wire:model.blur="password" id="password"
                           class="w-full px-4 py-2.5 pr-10 rounded-lg border border-gray-300 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 outline-none transition-colors text-sm">
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                        <svg x-show="!show" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                        <svg x-show="show" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div x-data="{ show: false }">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                <div class="relative">
                    <input :type="show ? 'text' : 'password'" wire:model.blur="password_confirmation" id="password_confirmation"
                           class="w-full px-4 py-2.5 pr-10 rounded-lg border border-gray-300 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 outline-none transition-colors text-sm">
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                        <svg x-show="!show" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                        <svg x-show="show" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                    </button>
                </div>
            </div>

            <div>
                <label for="mess_code" class="block text-sm font-medium text-gray-700 mb-1">Mess Code</label>
                <input type="text" wire:model.blur="mess_code" id="mess_code" placeholder="Enter your mess code"
                       class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 outline-none transition-colors text-sm">
                @error('mess_code')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    wire:loading.attr="disabled"
                    wire:target="register"
                    class="w-full bg-gray-900 hover:bg-gray-800 disabled:opacity-50 text-white font-semibold py-2.5 rounded-lg transition-colors text-sm">
                <span wire:loading.remove wire:target="register">Register</span>
                <span wire:loading wire:target="register">Registering...</span>
            </button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-8">
            Already have an account?
            <a href="{{ route('login') }}" wire:navigate class="font-medium text-gray-900 hover:underline">Sign in</a>
        </p>
    </div>
</div>
