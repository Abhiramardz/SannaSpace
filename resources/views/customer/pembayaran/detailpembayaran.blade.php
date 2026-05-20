<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Metode Pembayaran - Sanna Space</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">

<div class="max-w-5xl mx-auto min-h-screen bg-white flex flex-col shadow-xl pb-24">
    
    <div class="sticky top-0 z-30 bg-white px-4 py-4 border-b border-gray-100 flex items-center gap-4">
        <a href="/pesanan" class="p-2 hover:bg-gray-100 rounded-full transition">
            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h1 class="text-xl font-bold text-gray-800">Metode Pembayaran</h1>
    </div>

    <div class="flex-1 px-6 py-8">
        <h2 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-6">Ringkasan Pesanan</h2>
        
        {{-- List Singkat Item Belanjaan --}}
        <div class="space-y-3 mb-8">
            @foreach($cart as $id => $details)
                <div class="flex justify-between items-center bg-gray-50/50 p-4 rounded-xl border border-gray-100">
                    <div>
                        <p class="font-bold text-gray-800 text-sm">{{ $details['name'] }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">Jumlah: {{ $details['quantity'] }}x</p>
                    </div>
                    <p class="text-sm font-bold text-gray-700">Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</p>
                </div>
            @endforeach
        </div>

        {{-- Form Pemilihan Pembayaran Final --}}
        <form action="{{ route('checkout.proses') }}" method="POST" class="space-y-8">
            @csrf
            
            <div>
                <h2 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">Pilih Cara Pembayaran</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="border-2 border-gray-100 rounded-2xl p-5 flex items-center justify-between cursor-pointer hover:bg-gray-50/50 transition has-[:checked]:border-red-500 has-[:checked]:bg-red-50/20">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-green-50 text-green-600 rounded-xl flex items-center justify-center shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <span class="block text-sm font-bold text-gray-800">Bayar di Kasir (Cash)</span>
                                <span class="block text-xs text-gray-400 mt-0.5">Bayar langsung tunai di meja kasir</span>
                            </div>
                        </div>
                        <input type="radio" name="payment_method" value="Cash" class="w-4 h-4 text-red-500 focus:ring-red-500 border-gray-300" checked>
                    </label>

                    <label class="border-2 border-gray-100 rounded-2xl p-5 flex items-center justify-between cursor-pointer hover:bg-gray-50/50 transition has-[:checked]:border-red-500 has-[:checked]:bg-red-50/20">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <span class="block text-sm font-bold text-gray-800">QRIS Mandiri / Dana</span>
                                <span class="block text-xs text-gray-400 mt-0.5">Scan barcode otomatis instan</span>
                            </div>
                        </div>
                        <input type="radio" name="payment_method" value="QRIS" class="w-4 h-4 text-red-500 focus:ring-red-500 border-gray-300">
                    </label>
                </div>
            </div>

            <div class="border-t border-gray-100 pt-6 mt-10">
                <div class="flex justify-between items-center mb-6">
                    <span class="text-gray-500 font-medium">Total Tagihan</span>
                    <span class="text-2xl font-black text-gray-800">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                
                <button type="submit" class="w-full bg-red-500 text-white font-bold py-4 rounded-2xl shadow-lg shadow-red-200 hover:bg-red-600 transition active:scale-[0.98]">
                    Konfirmasi & Selesaikan Pesanan
                </button>
            </div>
        </form>
    </div>

    @include('layout.bottomnav')
</div>

</body>
</html>