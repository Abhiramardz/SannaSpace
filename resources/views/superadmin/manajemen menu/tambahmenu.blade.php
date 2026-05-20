<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Menu - Sanna Space</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="flex min-h-screen">
    @include('layout.superadmin.sidebarsuperadmin')

    <div class="flex-1 flex flex-col">
        @include('layout.superadmin.headersuperadmin', ['title' => 'Tambah Menu'])

        <main class="p-4 md:p-8">
            <div class="max-w-2xl mx-auto">
                {{-- Tombol Kembali --}}
                <a href="/superadmin/menu" class="inline-flex items-center text-sm font-bold text-gray-400 hover:text-green-600 transition mb-6 gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7"/>
                    </svg>
                    Kembali ke Daftar Menu
                </a>

                <div class="xl:col-span-1">
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sticky top-24">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-green-50 text-green-600 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0h-3m-9-4h.01M9 16h.01M7 10h.01M9 4h.01M12 12h.01"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Tambah Menu</h3>
                        </div>

                        <form action="/superadmin/menu" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Nama Menu</label>
                                <input type="text" name="name" class="w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none transition text-sm font-bold" placeholder="Nama Menu" required>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Modal</label>
                                    <input type="number" name="cost_price" class="w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none transition text-sm font-bold" placeholder="0" required>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Jual</label>
                                    <input type="number" name="price" class="w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none transition text-sm font-bold" placeholder="0" required>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Kategori</label>
                                <select name="category" class="w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none transition text-sm font-bold appearance-none" required>
                                    <option value="" disabled selected>Pilih Kategori</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Foto Menu</label>
                                <div class="mt-2 flex items-center gap-6">
                                    <div id="preview-box" class="w-24 h-24 rounded-2xl bg-gray-50 border-2 border-dashed border-gray-200 flex items-center justify-center overflow-hidden">
                                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2h12"/></svg>
                                    </div>
                                    <input type="file" name="image" id="image-input" class="text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 transition">
                                </div>

                        </div>
                            <button type="submit" class="w-full bg-green-600 text-white py-4 rounded-2xl font-bold hover:bg-green-700 transition shadow-lg shadow-green-100 mt-4 active:scale-95">
                                + Simpan Menu
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
        // --- Logika Sidebar ---
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


        // --- Logika Preview Gambar ---
        const imageInput = document.getElementById('image-input');
        const previewBox = document.getElementById('preview-box');

        if (imageInput) {
            imageInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewBox.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                        previewBox.classList.remove('border-dashed');
                        previewBox.classList.add('border-solid');
                    }
                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>

</body>
</html>