<!-- Header Superadmin -->
<header class="bg-white/80 backdrop-blur-md sticky top-0 z-40 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
    <div class="flex items-center gap-4">
        <!-- Hamburger Button for Mobile -->
        <button id="mobile-menu-btn" class="md:hidden p-2 text-gray-500 hover:text-green-600 hover:bg-green-50 rounded-xl transition-all">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        <div>
            <h1 class="text-xl font-black text-gray-800 tracking-tight">{{ $title ?? 'Dashboard' }}</h1>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest hidden md:block">Superadmin Panel • Sanna Space</p>
        </div>
    </div>
    
    <div class="flex items-center gap-3">
        <div class="hidden md:flex flex-col items-end mr-2">
            <span class="text-sm font-bold text-gray-800">{{ Auth::user()->name ?? 'Admin' }}</span>
            <span class="text-[10px] text-green-500 font-bold uppercase tracking-widest">Aktif</span>
        </div>
        <form action="{{ route('logout') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="p-2.5 text-red-500 hover:bg-red-50 rounded-xl transition-all group" title="Logout">
                <svg class="w-6 h-6 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
            </button>
        </form>
    </div>
</header>
