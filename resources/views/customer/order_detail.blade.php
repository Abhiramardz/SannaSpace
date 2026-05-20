<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan - Sanna Space</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <style>
        * { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 antialiased text-gray-900">

<div class="max-w-md mx-auto min-h-screen bg-white shadow-xl flex flex-col">
    <!-- Header -->
    <header class="bg-white sticky top-0 z-10 px-4 py-4 border-b border-gray-100 flex items-center gap-4">
        <a href="{{ route('profile') }}" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-full transition">
            <i data-feather="arrow-left" class="w-6 h-6"></i>
        </a>
        <div>
            <h1 class="text-lg font-bold text-gray-900">Detail Pesanan</h1>
            <p class="text-xs text-gray-500 uppercase tracking-widest font-bold">{{ $order->order_number }}</p>
        </div>
    </header>

    <main class="flex-1 p-4 pb-24 overflow-y-auto">
        <!-- Status Card -->
        <div class="bg-gray-50 rounded-[24px] p-6 mb-6 flex flex-col items-center text-center border border-gray-100">
            @php
                $statusConfig = [
                    'pending' => ['label' => 'Menunggu Diproses', 'desc' => 'Pesanan Anda sudah masuk dan sedang menunggu konfirmasi kasir.', 'icon' => 'clock', 'color' => 'text-yellow-500', 'bg' => 'bg-yellow-100'],
                    'diproses' => ['label' => 'Sedang Diproses', 'desc' => 'Kasir sedang memproses dan menyiapkan pesanan Anda.', 'icon' => 'loader', 'color' => 'text-blue-500', 'bg' => 'bg-blue-100'],
                    'siap_saji' => ['label' => 'Siap Diambil', 'desc' => 'Pesanan Anda sudah siap! Silakan ambil di konter pengambilan.', 'icon' => 'coffee', 'color' => 'text-purple-500', 'bg' => 'bg-purple-100'],
                    'completed' => ['label' => 'Selesai', 'desc' => 'Terima kasih telah memesan di Sanna Space!', 'icon' => 'check-circle', 'color' => 'text-green-500', 'bg' => 'bg-green-100'],
                    'cancelled' => ['label' => 'Dibatalkan', 'desc' => 'Pesanan ini telah dibatalkan.', 'icon' => 'x-circle', 'color' => 'text-red-500', 'bg' => 'bg-red-100']
                ];
                $status = $statusConfig[$order->status] ?? ['label' => $order->status, 'desc' => '', 'icon' => 'info', 'color' => 'text-gray-500', 'bg' => 'bg-gray-100'];
            @endphp
            
            <div class="w-16 h-16 {{ $status['bg'] }} {{ $status['color'] }} rounded-full flex items-center justify-center mb-4 shadow-sm">
                <i data-feather="{{ $status['icon'] }}" class="w-8 h-8"></i>
            </div>
            <h2 class="text-xl font-black text-gray-900 mb-2">{{ $status['label'] }}</h2>
            <p class="text-sm text-gray-500 leading-relaxed">{{ $status['desc'] }}</p>
        </div>

        <!-- Info Order -->
        <div class="mb-8 px-2">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Informasi Pesanan</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                    <span class="text-sm font-medium text-gray-500">Waktu Pemesanan</span>
                    <span class="text-sm font-bold text-gray-900">{{ $order->created_at->format('d M Y, H:i') }}</span>
                </div>
                <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                    <span class="text-sm font-medium text-gray-500">Metode Pembayaran</span>
                    <span class="text-sm font-bold text-gray-900">{{ $order->payment_method ?? 'Cash' }}</span>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="mb-8">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 px-2">Daftar Menu</h3>
            <div class="space-y-4">
                @foreach($order->items as $item)
                <div class="bg-white border border-gray-100 rounded-3xl p-4 flex items-center gap-4 shadow-sm">
                    <div class="w-16 h-16 bg-gray-100 rounded-2xl overflow-hidden flex-shrink-0">
                        @if($item->product && $item->product->image)
                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <i data-feather="image" class="w-6 h-6"></i>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-bold text-gray-900 line-clamp-1">{{ $item->product->name ?? 'Produk Dihapus' }}</h4>
                        <p class="text-xs font-medium text-gray-500 mt-1">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-bold text-gray-400 mb-1">{{ $item->quantity }}x</p>
                        <p class="text-sm font-black text-gray-900">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Total -->
        <div class="bg-gray-900 text-white rounded-[32px] p-6 shadow-xl relative overflow-hidden">
            <div class="relative z-10">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-300 font-medium">Total Pembayaran</span>
                    <span class="text-xs text-gray-400 font-bold uppercase">{{ $order->items->count() }} Item</span>
                </div>
                <div class="text-3xl font-black">
                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                </div>
            </div>
            <!-- Decorative circle -->
            <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-white opacity-5 rounded-full"></div>
        </div>
    </main>

</div>

<script>
    feather.replace();
    // Auto-refresh periodically if the order is not yet completed/cancelled
    @if(!in_array($order->status, ['completed', 'cancelled']))
    setTimeout(function() {
        window.location.reload();
    }, 15000); // 15 seconds
    @endif
</script>
</body>
</html>
