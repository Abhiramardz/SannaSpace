<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }} - Kasir SannaSpace</title>
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
        @include('layout.kasir.headerkasir', ['title' => 'Detail Pesanan'])

        <!-- Main Content -->
        <main class="p-4 md:p-8">
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('kasir.dashboard') }}" class="p-2 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition text-gray-500">
                            <i data-feather="arrow-left" class="w-5 h-5"></i>
                        </a>
                        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">Pesanan #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h2>
                    </div>
                    <p class="mt-2 text-sm text-gray-500 ml-12">Dibuat pada {{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
                
                <!-- Status Badge -->
                <div>
                    <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-bold uppercase tracking-wider shadow-sm
                        {{ $order->status == 'pending' ? 'bg-yellow-100 text-yellow-800 border border-yellow-200' : '' }}
                        {{ $order->status == 'diproses' ? 'bg-blue-100 text-blue-800 border border-blue-200' : '' }}
                        {{ $order->status == 'siap_saji' ? 'bg-purple-100 text-purple-800 border border-purple-200' : '' }}
                        {{ $order->status == 'completed' ? 'bg-green-100 text-green-800 border border-green-200' : '' }}">
                        <i data-feather="info" class="w-4 h-4 mr-2"></i>
                        {{ str_replace('_', ' ', $order->status) }}
                    </span>
                </div>
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column (Order Items) -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-[32px] shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                            <h3 class="text-lg font-bold text-gray-900">Daftar Menu</h3>
                            <span class="text-sm font-bold text-gray-500 bg-gray-50 px-3 py-1 rounded-lg">{{ $order->items->sum('quantity') }} Item</span>
                        </div>
                        <div class="p-6">
                            <ul class="space-y-6">
                                @foreach($order->items as $item)
                                <li class="flex justify-between items-center group">
                                    <div class="flex items-center gap-4">
                                        <div class="w-16 h-16 bg-gray-100 rounded-2xl overflow-hidden flex-shrink-0">
                                            @if($item->product && $item->product->image)
                                                <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                    <i data-feather="image" class="w-6 h-6"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-lg font-bold text-gray-900">{{ $item->product->name ?? 'Produk Dihapus' }}</p>
                                            <p class="text-sm text-gray-500 font-medium mt-1">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-500 font-bold mb-1">{{ $item->quantity }}x</p>
                                        <span class="text-base font-black text-gray-900">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Right Column (Customer Info & Action) -->
                <div class="space-y-6">
                    <!-- Customer Card -->
                    <div class="bg-white rounded-[32px] shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-100">
                            <h3 class="text-lg font-bold text-gray-900">Info Pelanggan</h3>
                        </div>
                        <div class="p-6 bg-gray-50">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold text-xl">
                                    {{ substr($order->user->name ?? 'P', 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-base font-bold text-gray-900">{{ $order->user->name ?? 'Pelanggan Anonim' }}</p>
                                    <p class="text-xs text-gray-500 font-medium">{{ $order->user->email ?? '-' }}</p>
                                </div>
                            </div>
                            
                            <div class="space-y-3">
                                <div class="flex justify-between items-center py-2 border-b border-gray-200 border-dashed">
                                    <span class="text-sm text-gray-500">Metode Bayar</span>
                                    <span class="text-sm font-bold text-gray-900">{{ $order->payment_method ?? 'Cash' }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-200 border-dashed">
                                    <span class="text-sm text-gray-500">Order ID Referensi</span>
                                    <span class="text-sm font-bold text-gray-900">{{ $order->order_number }}</span>
                                </div>
                                <div class="flex justify-between items-center py-4 mt-2">
                                    <span class="text-base font-bold text-gray-900">Total Pembayaran</span>
                                    <span class="text-2xl font-black text-blue-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Card -->
                    <div class="bg-white rounded-[32px] shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Tindakan Kasir</h3>
                            <p class="text-sm text-gray-500 mb-6">Ubah status pesanan ke tahap selanjutnya. Pelanggan akan melihat perubahan status ini secara otomatis.</p>
                            
                            <form action="{{ route('kasir.order.status', $order->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                
                                @if($order->status == 'pending')
                                    <button type="submit" name="status" value="diproses" class="w-full flex justify-center items-center px-6 py-4 border border-transparent text-base font-bold rounded-2xl shadow-sm text-white bg-blue-600 hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-200 transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <i data-feather="loader" class="w-5 h-5 mr-2"></i> Mulai Proses Pesanan
                                    </button>
                                @elseif($order->status == 'diproses')
                                    <button type="submit" name="status" value="siap_saji" class="w-full flex justify-center items-center px-6 py-4 border border-transparent text-base font-bold rounded-2xl shadow-sm text-white bg-purple-600 hover:bg-purple-700 hover:shadow-lg hover:shadow-purple-200 transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                        <i data-feather="coffee" class="w-5 h-5 mr-2"></i> Tandai Siap Saji
                                    </button>
                                @elseif($order->status == 'siap_saji')
                                    <button type="submit" name="status" value="completed" class="w-full flex justify-center items-center px-6 py-4 border border-transparent text-base font-bold rounded-2xl shadow-sm text-white bg-green-600 hover:bg-green-700 hover:shadow-lg hover:shadow-green-200 transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        <i data-feather="check-circle" class="w-5 h-5 mr-2"></i> Selesaikan Pesanan
                                    </button>
                                @else
                                    <div class="p-4 bg-gray-50 rounded-2xl text-center border border-gray-200">
                                        <p class="text-sm font-bold text-gray-500">Pesanan ini sudah selesai atau dibatalkan.</p>
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
    feather.replace();

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
