<div class="flex items-center justify-center min-h-screen p-6">
    <div class="w-full max-w-2xl mx-auto text-center">
        <div class="mb-8 flex justify-center">
            <div class="w-20 h-20 sm:w-24 sm:h-24 bg-gray-100 rounded-2xl flex items-center justify-center">
                <svg class="w-10 h-10 sm:w-12 sm:h-12 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
                </svg>
            </div>
        </div>

        <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold tracking-tight mb-4 text-gray-900">
            DIU Mess Management System
        </h1>

        <p class="text-base sm:text-lg text-gray-500 max-w-md mx-auto mb-10 leading-relaxed">
            Simplify your mess operations — manage members, track expenses, plan meals, and stay organized effortlessly.
        </p>

        <a href="{{ route('login') }}" wire:navigate
           class="inline-flex items-center gap-2 bg-gray-900 hover:bg-gray-800 text-white font-semibold px-8 py-3.5 sm:px-10 sm:py-4 rounded-xl transition-all duration-200 text-base sm:text-lg">
            Get Started
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
            </svg>
        </a>

        <p class="mt-8 text-xs sm:text-sm text-gray-400">
            &copy; {{ date('Y') }} DIU Mess Management System. All rights reserved.
        </p>
    </div>
</div>
