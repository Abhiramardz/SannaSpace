<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Sanna Space</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">

<div class="max-w-5xl mx-auto min-h-screen bg-white flex flex-col shadow-xl">


    <main class="flex-1 px-4 pt-8 pb-32">

        @auth
            <!-- TAMPILAN JIKA SUDAH LOGIN -->
            <div class="flex flex-col items-center mb-8">
                <div class="relative">
                    <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center text-red-500 mb-4 border-4 border-white shadow-sm overflow-hidden">
                        @if(Auth::user()->avatar)
                            <img src="{{ Auth::user()->avatar }}" alt="Profile" class="w-full h-full object-cover">
                        @else
                            <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                        @endif
                    </div>
                    <div class="absolute bottom-4 right-0 w-6 h-6 bg-green-500 border-2 border-white rounded-full"></div>
                </div>
                <h2 class="text-xl font-bold text-gray-800">{{ Auth::user()->name }}</h2>
                <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                <div class="mt-2 flex gap-2">
                    <div class="px-3 py-1 bg-green-100 text-green-700 text-[10px] font-bold uppercase rounded-full tracking-wider">
                        {{ Auth::user()->role }}
                    </div>
                    <div class="px-3 py-1 bg-red-100 text-red-700 text-[10px] font-bold uppercase rounded-full tracking-wider">
                        Member Gold
                    </div>
                </div>
            </div>

            <!-- Stats/Summary -->
            <div class="grid grid-cols-2 gap-4 mb-8">
                <div class="bg-gray-50 p-4 rounded-3xl border border-gray-100">
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">Total Pesanan</p>
                    <p class="text-xl font-black text-gray-800">{{ $orders->count() }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-3xl border border-gray-100">
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">Total Spent</p>
                    <p class="text-xl font-black text-gray-800">Rp {{ number_format($orders->sum('total_amount'), 0, ',', '.') }}</p>
                </div>
            </div>

            <!-- Menu Options -->
            <div class="space-y-3 mb-10">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest px-1">Menu Akun</h3>
                <a href="{{ route('profile.edit') }}" class="flex items-center justify-between p-4 bg-white border border-gray-100 rounded-2xl hover:shadow-md transition">
                    <div class="flex items-center gap-3 text-gray-700">
                        <div class="w-8 h-8 bg-blue-50 text-blue-500 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-semibold">Edit Profil</span>
                    </div>
                    <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                
                @if(Auth::user()->role === 'superadmin')
                <a href="/dashboardsuperadmin" class="flex items-center justify-between p-4 bg-red-50 border border-red-100 rounded-2xl hover:bg-red-100 transition">
                    <div class="flex items-center gap-3 text-red-700">
                        <div class="w-8 h-8 bg-red-100 text-red-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-bold">Panel Superadmin</span>
                    </div>
                    <svg class="w-4 h-4 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                @endif
            </div>

            <!-- Purchase History -->
            <div class="space-y-4">
                <div class="flex items-center justify-between px-1">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Riwayat Pesanan</h3>
                    <a href="#" class="text-[10px] font-bold text-red-500 uppercase hover:underline">Lihat Semua</a>
                </div>

                @if($orders->count() > 0)
                    <div class="space-y-3">
                        @foreach($orders as $order)
                            <a href="{{ route('customer.order.show', $order->id) }}" class="block bg-white border border-gray-100 rounded-3xl p-4 hover:shadow-lg hover:border-gray-200 transition cursor-pointer">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tight mb-0.5">{{ $order->order_number }}</p>
                                        <p class="text-xs font-medium text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
                                    </div>
                                    @php
                                        $statusConfig = [
                                            'pending' => ['label' => 'Menunggu', 'class' => 'bg-yellow-100 text-yellow-700'],
                                            'diproses' => ['label' => 'Diproses', 'class' => 'bg-blue-100 text-blue-700'],
                                            'siap_saji' => ['label' => 'Siap Saji', 'class' => 'bg-purple-100 text-purple-700'],
                                            'completed' => ['label' => 'Selesai', 'class' => 'bg-green-100 text-green-700'],
                                            'cancelled' => ['label' => 'Dibatalkan', 'class' => 'bg-red-100 text-red-700']
                                        ];
                                        $currentStatus = $statusConfig[$order->status] ?? ['label' => $order->status, 'class' => 'bg-gray-100 text-gray-700'];
                                    @endphp
                                    <span class="px-3 py-1 {{ $currentStatus['class'] }} text-[10px] font-extrabold rounded-full uppercase tracking-widest shadow-sm">
                                        {{ $currentStatus['label'] }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-end">
                                    <div class="text-gray-400 text-xs">
                                        {{ $order->items->count() }} item • {{ $order->payment_method }}
                                    </div>
                                    <div class="text-sm font-black text-gray-800 flex items-center gap-2">
                                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                        <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="bg-gray-50 rounded-3xl p-8 flex flex-col items-center text-center">
                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center text-gray-200 mb-4 shadow-sm">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                        <p class="text-sm font-bold text-gray-400">Belum ada riwayat pesanan</p>
                        <p class="text-[11px] text-gray-300 mt-1 max-w-[150px]">Pesan menu favoritmu dan lihat riwayatnya di sini.</p>
                    </div>
                @endif

                <form action="{{ route('logout') }}" method="POST" class="mt-12">
                    @csrf
                    <button type="submit" class="w-full py-4 border-2 border-red-500 text-red-500 font-bold rounded-2xl hover:bg-red-50 transition active:scale-95">
                        Keluar Akun
                    </button>
                </form>
            </div>

        @else
            <!-- TAMPILAN JIKA BELUM LOGIN -->
            <div class="flex flex-col items-center text-center mt-10">
                <div class="w-32 h-32 bg-gray-100 rounded-full flex items-center justify-center text-gray-300 mb-6">
                    <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-extrabold text-gray-800 mb-2">Halo, Sobat Sanna!</h2>
                <p class="text-sm text-gray-500 mb-10 max-w-xs">Masuk ke akunmu untuk mulai memesan kopi favoritmu dengan lebih mudah.</p>
                
                <div class="w-full space-y-4">
                    <a href="/login" class="block w-full py-4 bg-red-500 text-white font-bold rounded-2xl shadow-lg shadow-red-200 hover:bg-red-600 transition-all active:scale-95">
                        Masuk Sekarang
                    </a>
                    <a href="/daftar" class="block w-full py-4 border-2 border-gray-200 text-gray-700 font-bold rounded-2xl hover:bg-gray-50 transition-all">
                        Belum Punya Akun? Daftar
                    </a>
                </div>

                <div class="mt-10 flex items-center gap-3 w-full">
                    <div class="flex-1 h-[1px] bg-gray-100"></div>
                    <span class="text-xs text-gray-400">Atau masuk dengan</span>
                    <div class="flex-1 h-[1px] bg-gray-100"></div>
                </div>

                <a href="/auth/google" class="mt-6 flex items-center justify-center gap-3 w-full py-3 border border-gray-200 rounded-2xl hover:bg-gray-50 transition-colors">
                    <img src="https://www.gstatic.com/images/branding/product/1x/gsa_512dp.png" class="w-5 h-5" alt="Google">
                    <span class="text-sm font-semibold text-gray-600">Google</span>
                </a>
            </div>
        @endauth

    </main>

    <!-- Bottom Navigation -->
    @include('layout.bottomnav')

</div>

</body>
</html>
