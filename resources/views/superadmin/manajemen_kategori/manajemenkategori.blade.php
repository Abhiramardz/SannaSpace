<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kategori - Sanna Space</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="flex min-h-screen">
    @include('layout.superadmin.sidebarsuperadmin')

    <div class="flex-1 flex flex-col">
        @include('layout.superadmin.headersuperadmin', ['title' => 'Manajemen Kategori'])

        <main class="p-4 md:p-8">
            {{-- Pesan Sukses --}}
            @if(session('success'))
            <div class="bg-green-50 border border-green-100 text-green-700 px-6 py-4 rounded-2xl mb-8 flex items-center gap-3">
                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span class="font-bold text-sm">{{ session('success') }}</span>
            </div>
            @endif

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                {{-- Form Tambah Kategori --}}
                <div class="xl:col-span-1">
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sticky top-24">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-green-50 text-green-600 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 11h.01M7 15h.01M13 7h.01M13 11h.01M13 15h.01M17 7h.01M17 11h.01M17 15h.01"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Tambah Kategori</h3>
                        </div>

                        <form action="/superadmin/kategori" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Nama Kategori</label>
                                <input type="text" name="name" value="{{ old('name') }}" class="w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none transition text-sm font-bold" placeholder="Cth: Minuman, Makanan..." required>
                            </div>
                            <button type="submit" class="w-full bg-green-600 text-white py-4 rounded-2xl font-bold hover:bg-green-700 transition shadow-lg shadow-green-100 mt-4 active:scale-95">
                                + Tambah Kategori
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Daftar Kategori --}}
                <div class="xl:col-span-2">
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between">
                            <h3 class="text-lg font-bold text-gray-800">Daftar Kategori</h3>
                            <span class="px-3 py-1 bg-green-50 text-green-600 text-[10px] font-bold uppercase rounded-full tracking-wider">{{ $categories->count() }} Kategori</span>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-left whitespace-nowrap min-w-[500px]">
                                <thead>
                                    <tr class="text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50">
                                        <th class="px-8 py-5">#</th>
                                        <th class="px-8 py-5">Nama Kategori</th>
                                        <th class="px-8 py-5 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @forelse ($categories as $i => $category)
                                    <tr class="hover:bg-gray-50/50 transition group">
                                        <td class="px-8 py-5 text-gray-400 font-medium text-xs">{{ $i + 1 }}</td>
                                        <td class="px-8 py-5">
                                            <span class="px-4 py-1.5 bg-green-50 text-green-700 rounded-full text-xs font-bold tracking-tight border border-green-100">
                                                {{ $category->name }}
                                            </span>
                                        </td>
                                        <td class="px-8 py-5 text-right">
                                            <form action="/superadmin/kategori/{{ $category->id }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus kategori {{ $category->name }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="px-8 py-20 text-center">
                                            <p class="text-sm font-bold text-gray-400">Belum ada kategori</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
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

        if (mobileMenuBtn) mobileMenuBtn.addEventListener('click', toggleSidebar);
        if (closeSidebarBtn) closeSidebarBtn.addEventListener('click', toggleSidebar);
        if (sidebarOverlay) sidebarOverlay.addEventListener('click', toggleSidebar);
    });
</script>

</body>
</html>
