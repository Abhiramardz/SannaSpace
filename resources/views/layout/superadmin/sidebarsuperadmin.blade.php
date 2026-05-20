<!-- Overlay untuk mobile -->
<div id="sidebar-overlay" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-40 hidden md:hidden transition-opacity"></div>

<!-- Sidebar Superadmin -->
<aside id="sidebar" class="w-72 bg-white shadow-2xl transform -translate-x-full md:translate-x-0 transition-all duration-300 ease-in-out fixed inset-y-0 left-0 z-50 md:relative md:block border-r border-gray-100">
    <div class="flex flex-col h-full">
        <!-- Logo Area -->
        <div class="flex justify-between items-center p-8">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-green-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <span class="font-black text-xl tracking-tight text-gray-800">Sanna <span class="text-green-600">Space</span></span>
            </div>
            <button id="close-sidebar-btn" class="md:hidden p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 space-y-2">
            <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Utama</p>
            
            <a href="/dashboardsuperadmin" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-200 {{ request()->is('dashboardsuperadmin') ? 'bg-green-600 text-white shadow-lg shadow-green-100 font-bold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span class="text-sm">Dashboard</span>
            </a>

            <a href="/superadmin/user" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-200 {{ request()->is('superadmin/user') ? 'bg-green-600 text-white shadow-lg shadow-green-100 font-bold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span class="text-sm">Manajemen User</span>
            </a>

            <a href="/superadmin/menu" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-200 {{ request()->is('superadmin/menu') ? 'bg-green-600 text-white shadow-lg shadow-green-100 font-bold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <span class="text-sm">Manajemen Menu</span>
            </a>

            <a href="/superadmin/kategori" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-200 {{ request()->is('superadmin/kategori') ? 'bg-green-600 text-white shadow-lg shadow-green-100 font-bold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 11h.01M7 15h.01M13 7h.01M13 11h.01M13 15h.01M17 7h.01M17 11h.01M17 15h.01"/>
                </svg>
                <span class="text-sm">Manajemen Kategori</span>
            </a>

            <a href="/superadmin/stok" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-200 {{ request()->is('superadmin/stok*') || request()->is('superadmin/tambahstok') ? 'bg-green-600 text-white shadow-lg shadow-green-100 font-bold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <span class="text-sm">Stok Bahan Baku</span>
            </a>
        </nav>

        <!-- Footer -->
        <div class="p-6 border-t border-gray-50">
            <div class="bg-gray-50 p-4 rounded-2xl flex items-center gap-3">
                <div class="w-10 h-10 bg-green-100 text-green-700 rounded-xl flex items-center justify-center font-bold">
                    {{ substr(Auth::user()->name ?? 'S', 0, 1) }}
                </div>
                <div class="flex-1 overflow-hidden">
                    <p class="text-sm font-bold text-gray-800 truncate">{{ Auth::user()->name ?? 'Superadmin' }}</p>
                    <p class="text-[10px] text-gray-400 font-medium uppercase tracking-wider">Superadmin</p>
                </div>
            </div>
        </div>
    </div>
</aside>
