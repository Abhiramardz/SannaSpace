<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Menu - Sanna Space</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="flex min-h-screen">
    @include('layout.superadmin.sidebarsuperadmin')

    <div class="flex-1 flex flex-col">
        @include('layout.superadmin.headersuperadmin', ['title' => 'Manajemen Menu'])

        <main class="p-4 md:p-8">
            @if(session('success'))
            <div class="bg-green-50 border border-green-100 text-green-700 px-6 py-4 rounded-2xl mb-8 flex items-center gap-3">
                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span class="font-bold text-sm">{{ session('success') }}</span>
            </div>
            @endif

            {{-- Filter & Search Section --}}
            <div class="bg-white rounded-3xl p-6 mb-8 shadow-sm border border-gray-100">
                <form action="{{ route('superadmin.menu.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                    <!-- Search Input -->
                    <div class="flex-1 relative">
                        <span class="absolute inset-y-0 left-4 flex items-center text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama menu..." 
                            class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-transparent focus:bg-white focus:ring-2 focus:ring-green-500/20 focus:border-green-500 rounded-2xl text-sm transition outline-none">
                    </div>

                    <!-- Category Filter -->
                    <div class="w-full md:w-56">
                        <select name="category" onchange="this.form.submit()" 
                            class="w-full px-4 py-3 bg-gray-50 border border-transparent focus:bg-white focus:ring-2 focus:ring-green-500/20 focus:border-green-500 rounded-2xl text-sm font-bold text-gray-600 transition outline-none appearance-none">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->name }}" {{ request('category') == $cat->name ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="bg-green-600 text-white px-8 py-3 rounded-2xl text-sm font-bold hover:bg-green-700 transition shadow-lg shadow-green-100">
                            Filter
                        </button>
                        @if(request('search') || request('category'))
                            <a href="{{ route('superadmin.menu.index') }}" class="bg-gray-100 text-gray-500 px-4 py-3 rounded-2xl text-sm font-bold hover:bg-gray-200 transition">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-1 gap-8">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between bg-white">
                        <h3 class="text-lg font-bold text-gray-800">Daftar Menu</h3>
                        <div class="flex items-center gap-4">
                            <span class="px-3 py-1 bg-green-50 text-green-600 text-[10px] font-bold uppercase rounded-full tracking-wider">
                                {{ $menus->count() }} Items
                            </span>
                            <a href="/superadmin/tambahmenu" class="inline-flex items-center justify-center bg-green-600 text-white px-4 py-2.5 rounded-xl text-xs font-bold hover:bg-green-700 transition shadow-md shadow-green-100 active:scale-95 gap-2 flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Tambah Menu
                            </a>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left whitespace-nowrap min-w-[800px]">
                            <thead>
                                <tr class="text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50 bg-gray-50/30">
                                    {{-- Kolom Nama --}}
                                    <th class="px-8 py-5">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center gap-1 hover:text-green-600 transition">
                                            Info Menu
                                            @if(request('sort') == 'name' || !request('sort'))
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ request('direction') == 'desc' ? 'M19 9l-7 7-7-7' : 'M5 15l7-7 7 7' }}"/></svg>
                                            @endif
                                        </a>
                                    </th>
                                    {{-- Kolom Kategori --}}
                                    <th class="px-8 py-5">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'category', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center gap-1 hover:text-green-600 transition">
                                            Kategori
                                            @if(request('sort') == 'category')
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ request('direction') == 'desc' ? 'M19 9l-7 7-7-7' : 'M5 15l7-7 7 7' }}"/></svg>
                                            @endif
                                        </a>
                                    </th>
                                    {{-- Kolom Stok --}}
                                    <th class="px-8 py-5 text-center">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'stock', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center justify-center gap-1 hover:text-green-600 transition">
                                            Stok
                                            @if(request('sort') == 'stock')
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ request('direction') == 'desc' ? 'M19 9l-7 7-7-7' : 'M5 15l7-7 7 7' }}"/></svg>
                                            @endif
                                        </a>
                                    </th>
                                    {{-- Kolom Harga --}}
                                    <th class="px-8 py-5">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'price', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center gap-1 hover:text-green-600 transition">
                                            Harga
                                            @if(request('sort') == 'price')
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ request('direction') == 'desc' ? 'M19 9l-7 7-7-7' : 'M5 15l7-7 7 7' }}"/></svg>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="px-8 py-5 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse ($menus as $menu)
                                <tr class="hover:bg-gray-50/50 transition group">
                                    <td class="px-8 py-5">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 rounded-xl overflow-hidden bg-gray-50 border border-gray-100 shadow-sm">
                                                @if($menu->image)
                                                    <img src="{{ asset('storage/' . $menu->image) }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="flex items-center justify-center h-full text-gray-300 font-bold text-[10px]">NO IMG</div>
                                                @endif
                                            </div>
                                            <div class="font-bold text-gray-800">{{ $menu->name }}</div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5">
                                        <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-lg text-[10px] font-bold uppercase tracking-wider">{{ $menu->category }}</span>
                                    </td>
                                    <td class="px-8 py-5">
                                        <div class="flex flex-col items-center">
                                            <form action="/superadmin/menu/{{ $menu->id }}/stock" method="POST" class="flex items-center gap-2">
                                                @csrf
                                                @method('PATCH')
                                                <input type="number" name="stock" value="{{ $menu->stock }}" 
                                                    class="w-16 px-2 py-1 text-center bg-gray-50 border border-gray-100 rounded-lg text-xs font-bold focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none transition"
                                                    onchange="this.form.submit()">
                                            </form>
                                            @if($menu->stock <= 0)
                                                <span class="text-[9px] font-bold text-red-500 uppercase mt-1">Habis</span>
                                            @else
                                                <span class="text-[9px] font-bold text-green-500 uppercase mt-1">Tersedia</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-8 py-5">
                                        <div class="flex flex-col">
                                            <span class="font-black text-green-600">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                                            <span class="text-[10px] font-bold text-gray-400">Modal: Rp {{ number_format($menu->cost_price ?? 0, 0, ',', '.') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="/superadmin/menu/{{ $menu->id }}/edit" class="p-2 text-green-600 hover:bg-green-50 rounded-xl transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </a>
                                            <form action="/superadmin/menu/{{ $menu->id }}" method="POST" class="inline" onsubmit="return confirm('Hapus menu ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-2 text-red-400 hover:bg-red-50 rounded-xl transition">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-20 text-center">
                                        <div class="flex flex-col items-center gap-2">
                                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center text-gray-300">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0l-8 4-8-4"/></svg>
                                            </div>
                                            <p class="text-gray-400 font-bold">Menu tidak ditemukan</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
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

        // Preview gambar sebelum upload
        const imageInput = document.getElementById('image');
        if (imageInput) {
            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(ev) {
                        let preview = document.getElementById('image-preview');
                        if (!preview) {
                            preview = document.createElement('img');
                            preview.id = 'image-preview';
                            preview.className = 'mt-2 w-24 h-24 object-cover rounded-lg shadow';
                            imageInput.parentNode.appendChild(preview);
                        }
                        preview.src = ev.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>

</body>
</html>