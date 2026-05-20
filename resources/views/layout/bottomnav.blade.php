<div class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-5xl bg-white border-t border-gray-100 px-2 py-2 z-50 shadow-[0_-4px_10px_rgba(0,0,0,0.05)]">
    <div class="flex items-center justify-around">
        
        <!-- Home -->
        <a href="/" class="flex flex-col items-center gap-1 group">
            <div class="p-1 rounded-lg transition-colors group-hover:bg-gray-50">
                <svg class="w-6 h-6 {{ request()->is('home') ? 'text-red-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
            </div>
            <span class="text-[10px] font-medium {{ request()->is('home') ? 'text-red-500' : 'text-gray-400' }}">Home</span>
        </a>

        <!-- Menu -->
        <a href="/" class="flex flex-col items-center gap-1 group">
            <div class="p-1 rounded-lg transition-colors group-hover:bg-red-50">
                <svg class="w-6 h-6 {{ request()->is('/') ? 'text-red-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <span class="text-[10px] font-medium {{ request()->is('/') ? 'text-red-500' : 'text-gray-400' }}">Menu</span>
        </a>

        <!-- Center Logo (SANNA) -->
        <div class="relative -mt-8">
            <div class="w-16 h-16 bg-[#0a1d37] rounded-full border-4 border-white flex items-center justify-center shadow-lg transform active:scale-95 transition-transform cursor-pointer">
                <div class="text-center">
                    <p class="text-[10px] text-white font-bold leading-none">sanna</p>
                    <p class="text-[10px] text-white font-bold leading-none mt-0.5">space</p>
                </div>
            </div>
        </div>

        <!-- Pesanan -->
        <a href="/pesanan" class="flex flex-col items-center gap-1 group">
            <div class="p-1 rounded-lg transition-colors group-hover:bg-gray-50">
                <svg class="w-6 h-6 {{ request()->is('pesanan') ? 'text-red-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <span class="text-[10px] font-medium {{ request()->is('pesanan') ? 'text-red-500' : 'text-gray-400' }}">Pesanan</span>
        </a>

        <!-- Profile -->
        <a href="/profile" class="flex flex-col items-center gap-1 group">
            <div class="p-1 rounded-lg transition-colors group-hover:bg-gray-50">
                <svg class="w-6 h-6 {{ request()->is('profile') ? 'text-red-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <span class="text-[10px] font-medium {{ request()->is('profile') ? 'text-red-500' : 'text-gray-400' }}">Profile</span>
        </a>

    </div>
</div>
