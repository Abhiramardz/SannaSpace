<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - Sanna Space</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * { font-family: 'Inter', sans-serif; }
        .glass-effect { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px); }
    </style>
</head>
<body class="bg-gray-50">

<div class="max-w-5xl mx-auto min-h-screen bg-white flex flex-col shadow-xl pb-32">
    
    <!-- Top Header Image -->
    <div class="relative w-full h-[400px] bg-gray-100 overflow-hidden">
        @if($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full flex items-center justify-center">
                <svg class="w-24 h-24 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        @endif

        <!-- Floating Back Button -->
        <a href="/" class="absolute top-6 left-6 w-10 h-10 glass-effect rounded-full flex items-center justify-center shadow-lg hover:scale-110 transition active:scale-95">
            <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
    </div>

    <!-- Content -->
    <div class="px-6 py-8 -mt-10 relative bg-white rounded-t-[40px] shadow-2xl flex-1">
        <div class="flex justify-between items-start mb-4">
            <div class="flex-1">
                <span class="inline-block px-3 py-1 bg-red-100 text-red-600 text-[10px] font-bold rounded-full uppercase tracking-wider mb-2">
                    {{ $product->category }}
                </span>
                <h1 class="text-3xl font-black text-gray-800 leading-tight">{{ $product->name }}</h1>
            </div>
            <div class="text-right flex flex-col items-end">
                <p class="text-2xl font-black text-red-500">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                <div class="mt-1">
                    @if($product->stock <= 0)
                        <span class="px-2 py-0.5 bg-gray-100 text-gray-500 text-[10px] font-black uppercase rounded tracking-widest border border-gray-200">Stok Habis</span>
                    @else
                        <span class="px-2 py-0.5 bg-green-50 text-green-600 text-[10px] font-black uppercase rounded tracking-widest border border-green-100">Stok: {{ $product->stock }}</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="h-px bg-gray-100 w-full my-6"></div>

        <h2 class="text-lg font-bold text-gray-800 mb-3">Deskripsi Menu</h2>
        <p class="text-gray-500 leading-relaxed">
            {{ $product->description ?: 'Belum ada deskripsi untuk menu ini. Silakan hubungi staf kami untuk informasi lebih lanjut mengenai bahan-bahan dan rasa.' }}
        </p>

        <div class="mt-12">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Kenapa memilih ini?</h2>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gray-50 p-4 rounded-2xl flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center text-green-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <p class="text-xs font-bold text-gray-700">Fresh Ingredients</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-2xl flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-xs font-bold text-gray-700">Fast Serving</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Action Bar -->
    <div class="fixed bottom-6 left-1/2 -translate-x-1/2 w-full max-w-5xl px-6 z-50">
        <div class="bg-white border border-gray-100 rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.15)] p-5 flex items-center justify-between gap-6">
            @if($product->stock > 0)
                <div class="flex items-center bg-gray-100 rounded-full p-1 border border-gray-200">
                    <button onclick="updateQty(-1)" class="w-10 h-10 flex items-center justify-center text-gray-500 hover:text-red-500 transition font-black text-lg">-</button>
                    <span id="qty-count" class="w-8 text-center font-black text-base text-gray-800">0</span>
                    <button onclick="updateQty(1)" class="w-10 h-10 flex items-center justify-center text-gray-500 hover:text-red-500 transition font-black text-lg">+</button>
                </div>
                
                <button onclick="addToCartAndGo()" class="flex-1 bg-red-500 text-white font-bold py-3 rounded-2xl shadow-lg shadow-red-200 hover:bg-red-600 transition active:scale-[0.98] flex items-center justify-center gap-2 text-sm">
                    Tambahkan Ke Keranjang
                </button>
            @else
                <div class="w-full bg-gray-100 text-gray-400 font-black py-4 rounded-2xl flex items-center justify-center gap-3 border border-gray-200 cursor-not-allowed">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    MAAF, STOK SEDANG HABIS
                </div>
            @endif
        </div>
    </div>

</div>

<script>
    let currentQty = 0;
    const productId = {{ $product->id }};

    // Initialize quantity from session if exists
    document.addEventListener('DOMContentLoaded', function() {
        @php
            $cart = session()->get('cart', []);
            $qty = isset($cart[$product->id]) ? $cart[$product->id]['quantity'] : 0;
        @endphp
        currentQty = {{ $qty }};
        updateUI();
    });

    function updateQty(change) {
        let maxStock = {{ $product->stock }};
        currentQty += change;
        if (currentQty < 0) currentQty = 0;
        if (currentQty > maxStock) {
            currentQty = maxStock;
            alert('Stok terbatas! Maksimal pembelian: ' + maxStock);
        }
        updateUI();
    }

    function updateUI() {
        document.getElementById('qty-count').textContent = currentQty;
    }

    function addToCartAndGo() {
        if (currentQty === 0) {
            alert('Silakan pilih jumlah pesanan terlebih dahulu');
            return;
        }

        fetch('{{ route("cart.update") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ id: productId, quantity: currentQty })
        })
        .then(response => response.json())
        .then(data => {
            window.location.href = '/';
        });
    }
</script>

</body>
</html>
