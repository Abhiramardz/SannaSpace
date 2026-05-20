<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan #{{ $order->id }} - Sanna Space</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100">

<div class="flex min-h-screen">
    @include('layout.superadmin.sidebarsuperadmin')

    <div class="flex-1 flex flex-col">
        @include('layout.superadmin.headersuperadmin', ['title' => 'Rincian Item Pesanan'])

        <main class="p-4 md:p-8">
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                <div class="xl:col-span-2">
                    
                    {{-- Tombol Kembali --}}
                    <a href="{{ route('superadmin.dashboard.orders') }}" class="inline-flex items-center text-xs font-bold uppercase tracking-widest text-gray-400 hover:text-green-600 mb-6 transition gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7"/>
                        </svg>
                        Kembali ke Daftar Pesanan
                    </a>

                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                        {{-- Info Utama Nota --}}
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 pb-6 border-b border-gray-100 mb-6">
                            <div>
                                <h3 class="text-xl font-black text-gray-800">Nota Pesanan #{{ $order->id }}</h3>
                                <p class="text-xs text-gray-400 font-medium mt-1">Dipesan pada {{ $order->created_at->translatedFormat('d F Y, H:i') }}</p>
                            </div>
                            <div class="flex gap-8 text-left md:text-right">
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Pelanggan</p>
                                    <p class="text-sm font-bold text-gray-800 mt-0.5">{{ $order->user ? $order->user->name : 'Guest User' }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Item List --}}
                        <div class="space-y-4">
                            @foreach($order->items as $item)
                                <div class="flex gap-4 bg-white border border-gray-100 rounded-2xl p-4 shadow-sm">
                                    <div class="w-16 h-16 flex-shrink-0">
                                        @if($item->product && $item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="Menu" class="w-16 h-16 object-cover rounded-xl">
                                        @else
                                            <div class="w-16 h-16 bg-green-50 text-green-600 rounded-xl flex items-center justify-center font-bold text-lg">
                                                {{ substr($item->product ? $item->product->name : 'M', 0, 1) }}
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex-1 flex justify-between items-center">
                                        <div>
                                            <h4 class="font-bold text-gray-800 text-sm">{{ $item->product ? $item->product->name : 'Menu Telah Dihapus' }}</h4>
                                            <p class="text-xs text-gray-400 mt-0.5">Kuantitas: <span class="font-bold text-gray-700">{{ $item->quantity }}x</span></p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xs text-gray-400">Harga Satuan: Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                            <p class="text-sm font-black text-green-600 mt-0.5">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Summary Ringkasan Akhir Pembayaran --}}
                        <div class="mt-8 pt-6 border-t border-gray-100 flex justify-between items-end">
                            <div class="flex flex-col gap-2">
                                {{-- TAMBAHAN: Detail Informasi Metode Pembayaran di Bawah --}}
                                <div class="text-xs text-gray-500 font-medium">
                                    Metode Pembayaran: <span class="font-bold text-gray-700 uppercase bg-gray-100 px-2 py-1 rounded-lg">{{ $order->payment_method ?? 'Tunai' }}</span>
                                </div>
                                <div>
                                    <span class="inline-flex items-center px-3 py-1 bg-green-50 text-green-600 border border-green-100 rounded-full text-[10px] font-bold uppercase tracking-wider">
                                        {{ $order->status }}
                                    </span>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest block">Total Akhir Pembayaran</span>
                                <span class="text-2xl font-black text-gray-800 mt-1 block">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

</body>
</html>