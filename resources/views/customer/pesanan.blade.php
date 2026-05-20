<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya - Sanna Space</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">

<div class="max-w-5xl mx-auto min-h-screen bg-white flex flex-col shadow-xl pb-24">
    
    <!-- Header -->
    <div class="sticky top-0 z-30 bg-white px-4 py-4 border-b border-gray-100 flex items-center gap-4">
        <a href="/" class="p-2 hover:bg-gray-100 rounded-full transition">
            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h1 class="text-xl font-bold text-gray-800">Pesanan Saya</h1>
    </div>

    <!-- Alert Messages -->
    <div class="px-4 mt-4">
        @if(session('error'))
            <div class="bg-red-50 border border-red-100 text-red-600 px-4 py-3 rounded-xl text-xs font-bold flex items-center gap-2">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif
    </div>

    <!-- Cart Content -->
    <div class="flex-1 px-4 py-6">
        @if(count($cart) > 0)
            <div class="space-y-4">
                @foreach($cart as $id => $details)
                    <div class="flex gap-4 bg-white border border-gray-100 rounded-2xl p-4 shadow-sm cursor-pointer hover:bg-gray-50 transition-colors" 
                         onclick="window.location.href='/menu/{{ $id }}'" data-id="{{ $id }}">
                        <!-- Image -->
                        <div class="w-20 h-20 flex-shrink-0">
                            @if(isset($details['image']) && $details['image'])
                                <img src="{{ asset('storage/' . $details['image']) }}" alt="{{ $details['name'] ?? 'Menu' }}" class="w-20 h-20 object-cover rounded-xl">
                            @else
                                <div class="w-20 h-20 bg-gray-100 rounded-xl flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Details -->
                        <div class="flex-1 flex flex-col justify-between">
                            <div>
                                <h3 class="font-bold text-gray-800">{{ $details['name'] ?? 'Menu Tidak Dikenal' }}</h3>
                                <p class="text-sm text-red-500 font-semibold mt-1">Rp {{ number_format($details['price'] ?? 0, 0, ',', '.') }}</p>
                            </div>
                            
                            <!-- Quantity Controls -->
                            <div class="flex items-center justify-between mt-2">
                                <div class="flex items-center bg-gray-100 rounded-full px-2 py-1">
                                    <button onclick="event.stopPropagation(); updateQuantity('{{ $id }}', -1)" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-red-500 transition font-bold">-</button>
                                    <span class="w-8 text-center text-sm font-bold text-gray-800 quantity-label">{{ $details['quantity'] ?? 0 }}</span>
                                    <button onclick="event.stopPropagation(); updateQuantity('{{ $id }}', 1)" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-red-500 transition font-bold">+</button>
                                </div>
                                <button onclick="event.stopPropagation(); removeItem('{{ $id }}')" class="text-gray-400 hover:text-red-500 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Summary -->
            <div class="mt-10 border-t border-gray-100 pt-6">
                    <div class="flex justify-between items-center mb-6">
                        <span class="text-gray-500 font-medium">Total Pembayaran</span>
                        <span id="grand-total" class="text-2xl font-black text-gray-800">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    
                    {{-- Mengarah langsung ke halaman pilihan pembayaran --}}
                    <a href="{{ route('checkout.page') }}" class="w-full bg-red-500 text-white font-bold py-4 rounded-2xl shadow-lg shadow-red-200 hover:bg-red-600 transition active:scale-[0.98] block text-center">
                        Lanjut ke Pembayaran
                    </a>
                </div>
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <div class="w-32 h-32 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-16 h-16 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Keranjang Kosong</h3>
                <p class="text-gray-400 mb-8 max-w-[250px]">Sepertinya Anda belum menambahkan menu apapun.</p>
                <a href="/" class="bg-red-500 text-white font-bold px-8 py-3 rounded-full shadow-lg shadow-red-100 hover:bg-red-600 transition">
                    Lihat Menu
                </a>
            </div>
        @endif
    </div>

    <!-- Bottom Nav -->
    @include('layout.bottomnav')

</div>

<script>
    function updateQuantity(id, change) {
        const card = document.querySelector(`[data-id="${id}"]`);
        const label = card.querySelector('.quantity-label');
        let currentQty = parseInt(label.textContent);
        let newQty = currentQty + change;

        if (newQty < 1) return;

        label.textContent = newQty;

        fetch('{{ route("cart.update") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ id: id, quantity: newQty })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(err => {
            alert(err.message || 'Terjadi kesalahan');
            label.textContent = currentQty; // Revert UI
        });
    }

    function removeItem(id) {
        if (confirm('Hapus item ini dari keranjang?')) {
            fetch('{{ route("cart.remove") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }
    }
</script>

</body>
</html>
