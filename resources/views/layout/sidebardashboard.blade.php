<!-- Overlay untuk mobile -->
<div id="sidebar-overlay" class="fixed inset-0 bg-black opacity-50 z-40 hidden md:hidden"></div>

<!-- Sidebar -->
<aside id="sidebar" class="w-64 bg-white shadow-md transform -translate-x-full md:translate-x-0 transition-transform duration-200 ease-in-out fixed inset-y-0 left-0 z-50 md:relative md:block">
    <div class="flex justify-between items-center p-6">
        <div class="font-bold text-xl text-green-800">
            Sanna Space
        </div>
        <button id="close-sidebar-btn" class="md:hidden text-gray-500 hover:text-gray-700 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
    <nav class="px-4 space-y-3">
        <a href="#" class="block p-2 rounded hover:bg-green-100">Dashboard</a>
        <a href="#" class="block p-2 rounded hover:bg-green-100">Menu</a>
        <a href="#" class="block p-2 rounded hover:bg-green-100">Orders</a>
    </nav>
</aside>
