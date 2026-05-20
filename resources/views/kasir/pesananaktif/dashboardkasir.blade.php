<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir Dashboard - SannaSpace</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <style>
        * { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 antialiased text-gray-900">
    
<div class="flex min-h-screen">
    @include('layout.kasir.sidebarkasir')

    <div class="flex-1 flex flex-col">
        @include('layout.kasir.headerkasir', ['title' => 'Pesanan Aktif'])

        <!-- Main Content -->
        <main class="p-4 md:p-8">
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">Kelola Pesanan</h2>
                    <p class="mt-1 text-sm text-gray-500">Pantau dan perbarui status pesanan pelanggan secara realtime.</p>
                </div>
                <button onclick="window.location.reload()" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i data-feather="refresh-cw" class="w-4 h-4 mr-2"></i> Segarkan
                </button>
            </div>

            @if(session('success'))
            <div class="rounded-xl bg-green-50 p-4 mb-8 border border-green-200">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i data-feather="check-circle" class="h-5 w-5 text-green-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-bold text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Orders Grid -->
            @if($orders->isEmpty())
                <div class="text-center bg-white rounded-3xl shadow-sm p-16 border border-gray-100 flex flex-col items-center justify-center min-h-[400px]">
                    <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                        <i data-feather="inbox" class="h-10 w-10 text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Belum ada pesanan masuk</h3>
                    <p class="text-gray-500">Menunggu pesanan baru dari pelanggan.</p>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($orders as $order)
                    <div class="bg-white rounded-[32px] shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300 flex flex-col h-full">
                        <div class="p-6 border-b border-gray-100">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                                        {{ $order->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $order->status == 'diproses' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $order->status == 'siap_saji' ? 'bg-green-100 text-green-800' : '' }}">
                                        {{ str_replace('_', ' ', $order->status) }}
                                    </span>
                                    <p class="text-xs text-gray-500 mt-2 font-medium">{{ $order->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Order ID</p>
                                    <p class="text-lg font-black text-gray-900">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
                                </div>
                            </div>
                            <div class="mb-2 bg-gray-50 p-4 rounded-2xl">
                                <p class="text-sm font-bold text-gray-900 mb-1">{{ $order->user->name ?? 'Pelanggan' }}</p>
                                <div class="flex items-center text-xs text-gray-500 font-medium">
                                    <i data-feather="credit-card" class="w-3 h-3 mr-1"></i>
                                    {{ $order->payment_method ?? 'Tidak Diketahui' }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-6 bg-white flex-1 overflow-y-auto max-h-[250px]">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Detail Pesanan</p>
                            <ul class="space-y-4">
                                @foreach($order->items as $item)
                                <li class="flex justify-between items-center group">
                                    <div class="flex-1 pr-4">
                                        <p class="text-sm font-bold text-gray-900 line-clamp-1">{{ $item->product->name ?? 'Produk Dihapus' }}</p>
                                        <p class="text-xs text-gray-500 font-medium mt-0.5">{{ $item->quantity }}x @ Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                    </div>
                                    <span class="text-sm font-black text-gray-900 bg-gray-50 px-3 py-1.5 rounded-xl">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        
                        <div class="p-6 border-t border-gray-100 bg-gray-50 mt-auto">
                            <div class="flex justify-between items-center mb-6">
                                <span class="text-sm font-bold text-gray-500">Total Harga</span>
                                <span class="text-xl font-black text-blue-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </div>
                            
                            <!-- Detail Action -->
                            <div class="flex gap-3">
                                <a href="{{ route('kasir.order.show', $order->id) }}" class="flex-1 inline-flex justify-center items-center px-4 py-3.5 border border-transparent text-sm font-bold rounded-2xl shadow-sm text-white bg-gray-900 hover:bg-gray-800 hover:shadow-lg transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900">
                                    Lihat Detail Pesanan
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </main>
    </div>
</div>

<script>
    feather.replace();
    
    // Auto-refresh every 30 seconds to get new orders
    setTimeout(function() {
        window.location.reload();
    }, 30000);

    // Mobile Sidebar Logic
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
</script>
</body>
</html>
