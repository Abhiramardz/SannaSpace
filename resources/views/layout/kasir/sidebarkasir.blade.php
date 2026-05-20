<!-- Overlay untuk mobile -->
<div id="sidebar-overlay" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-40 hidden md:hidden transition-opacity"></div>

<!-- Sidebar Kasir -->
<aside id="sidebar" class="w-72 bg-white shadow-2xl transform -translate-x-full md:translate-x-0 transition-all duration-300 ease-in-out fixed inset-y-0 left-0 z-50 md:relative md:block border-r border-gray-100">
    <div class="flex flex-col h-full">
        <!-- Logo Area -->
        <div class="flex justify-between items-center p-8">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <span class="font-black text-xl tracking-tight text-gray-800">Sanna <span class="text-blue-600">Space</span></span>
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
            
            <a href="/kasir/pesanan" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-200 {{ request()->is('kasir/pesanan') ? 'bg-blue-600 text-white shadow-lg shadow-blue-100 font-bold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span class="text-sm">Pesanan Aktif</span>
            </a>

            <a href="/kasir/stok" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-200 {{ request()->is('kasir/stok') ? 'bg-blue-600 text-white shadow-lg shadow-blue-100 font-bold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                </svg>
                <span class="text-sm">Stok Bahan Baku</span>
            </a>
        </nav>

        <!-- Footer -->
        <div class="p-6 border-t border-gray-50">
            <div class="bg-gray-50 p-4 rounded-2xl flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 text-blue-700 rounded-xl flex items-center justify-center font-bold">
                    {{ substr(Auth::user()->name ?? 'K', 0, 1) }}
                </div>
                <div class="flex-1 overflow-hidden">
                    <p class="text-sm font-bold text-gray-800 truncate">{{ Auth::user()->name ?? 'Kasir' }}</p>
                    <p class="text-[10px] text-gray-400 font-medium uppercase tracking-wider">Kasir</p>
                </div>
            </div>
        </div>
    </div>
</aside>
