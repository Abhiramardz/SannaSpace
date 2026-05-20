<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah User - Sanna Space</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="flex min-h-screen">
    @include('layout.superadmin.sidebarsuperadmin')

    <div class="flex-1 flex flex-col">
        @include('layout.superadmin.headersuperadmin', ['title' => 'Tambah User Baru'])

        <main class="p-4 md:p-8">
            <div class="max-w-2xl mx-auto">
                {{-- Tombol Kembali --}}
                <a href="/superadmin/user" class="inline-flex items-center text-sm font-bold text-gray-400 hover:text-green-600 transition mb-6 gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7"/>
                    </svg>
                    Kembali ke Daftar User
                </a>

                <div class="xl:col-span-1">
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sticky top-24">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-green-50 text-green-600 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Tambah User</h3>
                        </div>

                        <form action="/superadmin/user" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Nama Lengkap</label>
                                <input type="text" name="name" class="w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none transition text-sm font-bold" placeholder="Nama Lengkap" required>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Email</label>
                                <input type="email" name="email" class="w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none transition text-sm font-bold" placeholder="email@example.com" required>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Password</label>
                                <input type="password" name="password" class="w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none transition text-sm font-bold" placeholder="Minimal 8 karakter" required>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Role</label>
                                <select name="role" class="w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none transition text-sm font-bold appearance-none cursor-pointer" required>
                                    <option value="superadmin">Super Admin</option>
                                    <option value="kasir">Kasir</option>
                                </select>
                            </div>
                            <button type="submit" class="w-full bg-green-600 text-white py-4 rounded-2xl font-bold hover:bg-green-700 transition shadow-lg shadow-green-100 mt-4 active:scale-95">
                                + Tambah User
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const closeSidebarBtn = document.getElementById('close-sidebar-btn');

        function toggleSidebar() {
            if(sidebar) sidebar.classList.toggle('-translate-x-full');
            if(sidebarOverlay) sidebarOverlay.classList.toggle('hidden');
        }

        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', toggleSidebar);
        }
        
        if (closeSidebarBtn) {
            closeSidebarBtn.addEventListener('click', toggleSidebar);
        }
        
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', toggleSidebar);
        }
    });
</script>

</body>
</html>