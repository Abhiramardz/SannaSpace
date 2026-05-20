<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Bahan Baku - Sanna Space</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="flex min-h-screen">
    @include('layout.superadmin.sidebarsuperadmin')

    <div class="flex-1 flex flex-col">
        @include('layout.superadmin.headersuperadmin', ['title' => 'Tambah Bahan Baku'])

        <main class="p-4 md:p-8">
            <div class="max-w-2xl mx-auto">
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                    <form action="{{ route('superadmin.stok.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nama Bahan Baku</label>
                            <input type="text" name="name" required value="{{ old('name') }}"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none transition font-medium">
                            @error('name')
                                <p class="text-red-500 text-xs font-bold mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6 grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Satuan (Unit)</label>
                                <input type="text" name="unit" required value="{{ old('unit', 'pcs') }}" placeholder="Contoh: kg, gr, ml, pcs"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none transition font-medium">
                                @error('unit')
                                    <p class="text-red-500 text-xs font-bold mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Stok Awal</label>
                                <input type="number" step="any" name="stock" required value="{{ old('stock', 0) }}"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none transition font-medium text-center">
                                @error('stock')
                                    <p class="text-red-500 text-xs font-bold mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-8">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Batas Minimum Stok</label>
                            <input type="number" step="any" name="min_stock" required value="{{ old('min_stock', 0) }}"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none transition font-medium text-center">
                            <p class="text-xs text-gray-400 mt-2 font-medium">Peringatan stok menipis akan muncul jika stok di bawah batas ini.</p>
                            @error('min_stock')
                                <p class="text-red-500 text-xs font-bold mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex gap-4">
                            <a href="{{ route('superadmin.stok.index') }}" 
                                class="flex-1 bg-gray-100 text-gray-600 text-center px-6 py-3.5 rounded-xl font-bold hover:bg-gray-200 transition">
                                Batal
                            </a>
                            <button type="submit" 
                                class="flex-1 bg-green-600 text-white px-6 py-3.5 rounded-xl font-bold hover:bg-green-700 transition shadow-lg shadow-green-100">
                                Simpan Bahan Baku
                            </button>
                        </div>
                    </form>
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
