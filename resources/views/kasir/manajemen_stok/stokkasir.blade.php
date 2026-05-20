<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stok Bahan Baku - Sanna Space Kasir</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100">

<div class="flex min-h-screen">
    {{-- Menggunakan Sidebar Kasir Anda --}}
    @include('layout.kasir.sidebarkasir')

    <div class="flex-1 flex flex-col">
        {{-- Menggunakan Header Kasir Anda --}}
        @include('layout.kasir.headerkasir', ['title' => 'Stok Bahan Baku'])

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
                <form action="{{ route('kasir.stok.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1 relative">
                        <span class="absolute inset-y-0 left-4 flex items-center text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari bahan baku..." 
                            class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-transparent focus:bg-white focus:ring-2 focus:ring-green-500/20 focus:border-green-500 rounded-2xl text-sm transition outline-none">
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="bg-green-600 text-white px-8 py-3 rounded-2xl text-sm font-bold hover:bg-green-700 transition shadow-lg shadow-green-100">
                            Cari
                        </button>
                        @if(request('search'))
                            <a href="{{ route('kasir.stok.index') }}" class="bg-gray-100 text-gray-500 px-4 py-3 rounded-2xl text-sm font-bold hover:bg-gray-200 transition">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-1 gap-8">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between bg-white">
                        <h3 class="text-lg font-bold text-gray-800">Stok Bahan Baku</h3>
                        <span class="px-3 py-1 bg-green-50 text-green-600 text-[10px] font-bold uppercase rounded-full tracking-wider">
                            {{ $bahanBakuList->count() }} Items
                        </span>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left whitespace-nowrap min-w-[600px]">
                            <thead>
                                <tr class="text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50 bg-gray-50/30">
                                    <th class="px-8 py-5">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center gap-1 hover:text-green-600 transition">
                                            Nama Bahan Baku
                                            @if(request('sort') == 'name' || !request('sort'))
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ request('direction') == 'desc' ? 'M19 9l-7 7-7-7' : 'M5 15l7-7 7 7' }}"/></svg>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="px-8 py-5 text-center">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'stock', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center justify-center gap-1 hover:text-green-600 transition">
                                            Ubah Kuantitas Stok
                                            @if(request('sort') == 'stock')
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ request('direction') == 'desc' ? 'M19 9l-7 7-7-7' : 'M5 15l7-7 7 7' }}"/></svg>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="px-8 py-5 text-right">Satuan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse ($bahanBakuList as $bahanBaku)
                                <tr class="hover:bg-gray-50/50 transition group">
                                    <td class="px-8 py-5">
                                        <div class="font-bold text-gray-800">{{ $bahanBaku->name }}</div>
                                    </td>
                                    <td class="px-8 py-5">
                                        <div class="flex flex-col items-center">
                                            {{-- Form Update Otomatis saat input berganti nilai --}}
                                            <form action="{{ route('kasir.stok.update', $bahanBaku->id) }}" method="POST" class="flex items-center gap-2">
                                                @csrf
                                                @method('PATCH')
                                                <input type="number" step="any" name="stock" value="{{ floatval($bahanBaku->stock) }}" 
                                                    class="w-24 px-3 py-1.5 text-center bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none transition"
                                                    onchange="this.form.submit()">
                                            </form>
                                            @if($bahanBaku->stock <= $bahanBaku->min_stock)
                                                <span class="text-[9px] font-bold text-red-500 uppercase mt-1">Stok Menipis</span>
                                            @else
                                                <span class="text-[9px] font-bold text-green-500 uppercase mt-1">Aman</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 text-right">
                                        <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-lg text-[10px] font-bold uppercase tracking-wider">{{ $bahanBaku->unit }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-8 py-20 text-center">
                                        <div class="flex flex-col items-center gap-2">
                                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center text-gray-300">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0l-8 4-8-4"/></svg>
                                            </div>
                                            <p class="text-gray-400 font-bold">Bahan baku tidak ditemukan</p>
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
    });
</script>

</body>
</html>