<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu - Sanna Space</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">

<div class="flex min-h-screen">
    @include('layout.superadmin.sidebarsuperadmin')

    <div class="flex-1 flex flex-col">
        @include('layout.superadmin.headersuperadmin', ['title' => 'Edit Menu'])

        <main class="p-4 md:p-8">
            <div class="max-w-3xl mx-auto">
                <a href="/superadmin/menu" class="inline-flex items-center text-xs font-bold uppercase tracking-widest text-gray-400 hover:text-green-600 mb-6 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7"/>
                    </svg>
                    Kembali ke Manajemen Menu
                </a>

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                    <div class="flex items-center gap-5 mb-10">
                        <div class="w-20 h-20 rounded-2xl overflow-hidden shadow-md flex-shrink-0 border border-gray-50 bg-gray-50">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h2 class="text-3xl font-black text-gray-800 tracking-tight">Edit Menu</h2>
                            <p class="text-sm text-gray-400 mt-1 font-medium">Memperbarui informasi untuk <span class="text-green-600 font-bold">{{ $product->name }}</span></p>
                        </div>
                    </div>

                    <form action="/superadmin/menu/{{ $product->id }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Nama Menu</label>
                                <input type="text" name="name" value="{{ old('name', $product->name) }}" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none transition text-sm font-bold" required>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Modal (Rp)</label>
                                    <input type="number" name="cost_price" value="{{ old('cost_price', $product->cost_price) }}" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none transition text-sm font-bold" required>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Jual (Rp)</label>
                                    <input type="number" name="price" value="{{ old('price', $product->price) }}" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none transition text-sm font-bold" required>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Kategori</label>
                            <select name="category" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none transition text-sm font-bold appearance-none cursor-pointer" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->name }}" {{ $product->category === $cat->name ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Foto Menu (Biarkan kosong jika tidak ganti)</label>
                            <div class="relative group">
                                <input type="file" id="image-upload" name="image" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                <div class="w-full px-5 py-8 bg-gray-50 border-2 border-dashed border-gray-200 rounded-3xl flex flex-col items-center justify-center text-gray-400 group-hover:border-green-500 group-hover:bg-green-50 transition-all">
                                    <svg class="w-8 h-8 mb-2 group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="text-xs font-bold uppercase tracking-widest group-hover:text-green-600">Pilih Gambar Baru</span>
                                </div>
                            </div>
                            <div id="preview-container" class="mt-4 hidden">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Preview Gambar Baru:</p>
                                <img id="preview-img" class="w-full h-48 object-cover rounded-3xl shadow-sm border border-gray-100">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Deskripsi</label>
                            <textarea name="description" rows="4" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none transition text-sm font-medium">{{ old('description', $product->description) }}</textarea>
                        </div>

                        <button type="submit" class="w-full bg-green-600 text-white py-5 rounded-2xl font-black hover:bg-green-700 transition shadow-xl shadow-green-100 active:scale-[0.98]">
                            Simpan Perubahan Menu
                        </button>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
    document.getElementById('image-upload').onchange = function (evt) {
        const [file] = this.files;
        if (file) {
            document.getElementById('preview-container').classList.remove('hidden');
            document.getElementById('preview-img').src = URL.createObjectURL(file);
        }
    }
</script>

</body>
</html>